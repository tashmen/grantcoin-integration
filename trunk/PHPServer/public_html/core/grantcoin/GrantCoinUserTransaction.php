<?php

/**
 * Class for tracking, storing and applying GrantCoinUser Transactions
 *
 * @author jnorcross
 */
class GrantCoinUserTransaction {
    private $connection = null;
    private $grantCoinUser = null;
    private $grantCoin = null;
    private $transactionRecord = null;
    
    public $types = array(
        DONATE => 1,
        MEMBERSHIP => 2,
        WITHDRAW => 3
    );
    
    /*
     * Creates a new GrantCoinUser utilizing a database connection, the GrantCoin servers and a user's id
     * @param connection - The database connection 
     * @param userid - The GrantCoinUser to perform transactions for
     * @return a new GrantCoinUserTransaction
     */
    public function __construct(iDatabase $connection, GrantCoinUser $user, GrantCoin $grantCoin)
    {
        $this->connection = $connection;
        $this->grantCoinUser = $user;
        $this->grantCoin = $grantCoin;
    }
    
    /*
     * Retrieves the membership expiration date/time for this transaction record
     */
    public function GetMembershipExpiration(){
        return $this->transactionRecord['membershipexpiration'];
    }
    
    /*
     * Retrieves the type of transaction for this transaction record
     */
    public function GetType(){
        return $this->transactionRecord['type'];
    }
    
    
    
    /*
     * Creates a new pending transaction
     * @param type - The type of transaction; see $this->types
     * @param amount - The amount of GRT the user wants to send in the transaction
     * @param membershipExpiration - The date/time of when the membership will expire; defaults to null
     * @param withdrawAddress - The GrantCoin address to which a withdraw action will send the coins
     * @return - The transaction id
     */
    public function CreatePendingTransaction($type, $amount, $membershipExpiration = null, $withdrawAddress = null)
    {
        $insert = "Insert into grantcoinusertransaction (userid,type,amountgrt,isapproved,approvaldate,membershipexpiration,withdrawaddress) values (?,?,?,?,?,?,?)";
        $parameters[] = $this->grantCoinUser->GetUserId();
        $parameters[] = $type;
        $parameters[] = $amount;
        $parameters[] = false;
        $parameters[] = null;
        $parameters[] = $membershipExpiration;
        $parameters[] = $withdrawAddress;
        switch($type)
        {
            case $this->types['MEMBERSHIP']:
                if ($membershipExpiration == '' || $membershipExpiration == null) {
                    throw new Exception("Membership expiration is required to create a membership type transaction.");
                }
                break;
            case $this->types['WITHDRAW']:
                if($withdrawAddress == '' || $withdrawAddress == null){
                    throw new Exception("Withdraw address is required to create a withdraw type transaction.");
                }
                break;
        }
        $this->connection->execute($insert, $parameters, false);
        return $this->connection->lastInsertId();
    }
    
    /*
     * Applies/Commits a pending transaction
     * @param transactionID - The ID of the transaction to apply
     * @throws - Error if the transaction could not be found or if the transaction has already been applied
     */
    public function ApplyTransaction($transactionID)
    {
        $select = "Select userid,type,amountgrt,isapproved,approvaldate,membershipexpiration,withdrawaddress from grantcoinusertransaction ";
        $where = "where id = (?)";
        $parameters[] = $transactionID;
        
        $statement = $select . $where;
        $records = $this->connection->execute($statement, $parameters);
        $record = $records[0];
        if($record == null)
        {
            throw new Exception("Transaction could not be found.");
        }
        $this->transactionRecord = $record;
        $approved = $record['isapproved'];
        if ($approved) {
            throw new Exception("The transaction has already been applied.  The same transaction cannot be applied more than once.");
        }
        $type = $record['type'];
        $amountgrt = $record['amountgrt'];
        $address = $record['withdrawaddress'];
        
        switch($type){
            case $this->types['DONATE']:
            case $this->types['MEMBERSHIP']:
                $this->grantCoin->TransferInternal($this->grantCoinUser->GetAddress(), $this->grantCoin->GetInternalAddress(), $amountgrt);
                break;
            case $this->types['WITHDRAW']:
                $this->grantCoin->TransferExternal($this->grantCoinUser->GetAddress(), $address, $amountgrt);
                break;
        }
        
        $update = "update grantcoinusertransaction set isapproved = (?), approvaldate = (?)";
        $where = " where id = (?)";
        $parameters = null;
        $parameters[] = 1;
        $dateTime = new DateTime();
        $parameters[] = $dateTime->format('Y-m-d H:i:s');
        $parameters[] = $transactionID;
        
        $statement = $update . $where;
        $this->connection->execute($statement, $parameters, false);
        
    }
}
