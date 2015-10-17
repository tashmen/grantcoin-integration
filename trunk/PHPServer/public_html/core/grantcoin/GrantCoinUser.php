<?php

/**
 * Class for interacting with a User's GrantCoin account
 *
 * @author jnorcross
 */
class GrantCoinUser {
    private $grantCoin = null;
    private $connection = null;
    private $userid = null;
    
    private $address = null;
    private $balancegrt = null;
    private $valueusd = null;
    
    /*
     * Creates a new GrantCoinUser utilizing a database connection, the GrantCoin servers and a user's id
     * @param connection - The database connection 
     * @param grantCoin - The grantCoin server object
     * @param userid - The id of the user to retrieve account information for
     * @return a new GrantCoinUser
     */
    public function __construct(iDatabase $connection, GrantCoin $grantCoin, $userid)
    {
        $this->connection = $connection;
        $this->grantCoin = $grantCoin;
        $this->userid = $userid;
        $this->GetAccountInfo();
    }
    
    public function GetUserId()
    {
        return $this->userid;
    }
    
    public function GetAddress()
    {
        return $this->address;
    }
    
    public function GetBalanceGRT()
    {
        return $this->balancegrt;
    }
    
    public function GetValueUSD()
    {
        return $this->valueusd;
    }
    
    
    /*
     * Retrieves the accounting information and stores it locally
     */
    private function GetAccountInfo()
    {
        $select = "Select count(*) from grantcoinuser ";
        $where = "where userid = (?)";
        $parameters[] = $this->userid;

        $statement = $select . $where;
        $count = $this->connection->rowCount($statement, $parameters);
        if ($count == 0){
            $this->CreateAccount();
        }
        else{
            $select = "Select address from grantcoinuser " . $where;
            $records = $this->connection->execute($select, $parameters);
            $this->address = $records[0]['address'];
        }
        
        if ($this->address == null) {
            throw new Exception("Could not retrieve address for user " . $this->userid);
        }
        
        $this->balancegrt = $this->grantCoin->CheckBalance($this->address);
        $this->valueusd = $this->grantCoin->ConvertSellGRTToUSD($this->balancegrt);
    }
    
    /*
     * Creates a new account for when the requested user does not have one yet
     */
    private function CreateAccount()
    {
        $insert = "Insert into grantcoinuser (userid,address) values (?,?)";
        $this->address = $this->grantCoin->GetUniqueAddress();
        $parameters[] = $this->userid;
        $parameters[] = $this->address;
        $this->connection->execute($insert, $parameters, false);
    }
}
