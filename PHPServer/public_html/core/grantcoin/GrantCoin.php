<?php


/**
 * Class for interacting with GrantCoin servers and other needed functionality
 * Note: All functions are to throw exceptions in case of an error; caller is responsible for catching and handling them
 * 
 * @author jnorcross
 */
class GrantCoin {
    
    /*
     * Retrieves a unique GrantCoin address for a user to send coins to
     * @return - A unique GrantCoin address
     */
    public function GetUniqueAddress()
    {
        return 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    }
    
    /*
     * Retrieves the balance of an address
     * @param address - The GrantCoin address to retrieve the balance of
     * @return - The balance of the given address
     */
    public function CheckBalance($address)
    {
        return 450000;
    }
    
    /*
     * Moves GrantCoins between two Internal addresses (should not incur a transaction fee)
     * This is to be used to move coins from the address unique to a user to our main address so that the user's address acts like an account for them to store coins and we subtract from their account when they make a donation or for a monthly/yearly subscription
     * @param fromAddress - The internal address where coins will be subtracted
     * @param toAddress - The internal address where coins will be added
     * @param amount - The amount to transfer
     */
    public function TransferInternal($fromAddress, $toAddress, $amount)
    {
        $balance = $this->CheckBalance($fromAddress);
        if($balance < $amount)
        {
            throw new Exception ('Attempted to transfer ' . $amount . '. The address: ' . $fromAddress . ' does not have sufficient funds.  Current balance is ' . $balance);
        }
    }
    
    /*
     * Moves GrantCoins between an Internal address and an external address (will incur a transaction fee)
     * This is to be used to transfer coins to an external wallet so that excess coins don't build up in the web wallet on the GrantCoin server
     * @param fromAddress - The internal address where coins will be subtracted
     * @param toAddress - The external address where coins will be added
     * @param amount - The amount to transfer
     */
    public function TransferExternal($fromAddress, $toAddress, $amount)
    {
        $balance = $this->CheckBalance($fromAddress);
        if($balance < $amount)
        {
            throw new Exception ('Attempted to transfer ' . $amount . '. The address: ' . $fromAddress . ' does not have sufficient funds.  Current balance is ' . $balance);
        }
    }
    
    
    /*
     * How much GRT do I need to have $usdValue?
     * @param usdValue - The value in USD to convert to GRT using the Bid price
     * @return - The amount of GRT someone would need to give us to equal the USD value if I were to sell the GRT today
     */
    public function ConvertUSDToGRT($usdValue)
    {
        $grtToBTC = Bittrex::GetBidGRTToBTCValue();
        $btcToUSD = Bitstamp::GetBidBTCToUSD();
        return $usdValue / $btcToUSD / $grtToBTC;
    }
    
    /*
     * How much USD do I need to have $grtValue?
     * @param grtValue - The value in GRT to convert to USD using the Ask price
     * @return - The amount of USD someone would need to give us to equal the GRT value if I were to sell the USD today
     */
    public function ConvertGRTToUSD($grtValue)
    {
        $grtToBTC = Bittrex::GetAskGRTToBTCValue();
        $btcToUSD = Bitstamp::GetAskBTCToUSD();
        return $grtValue * $grtToBTC * $btcToUSD;
    }
}
