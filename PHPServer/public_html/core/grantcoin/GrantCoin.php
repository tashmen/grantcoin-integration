<?php
/*
    Copyright 2015 Jon Norcross 

    This file is part of GrantCoin Integration.

    GrantCoin Integration is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    GrantCoin Integration is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with GrantCoin Integration.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
 * Class for interacting with GrantCoin servers and other needed functionality
 * Note: All functions are to throw exceptions in case of an error; caller is responsible for catching and handling them
 * 
 * @author jnorcross
 */
class GrantCoin {
    //GrantCoin address settings:  
    //The internal address is the one to use for the grantcoin server web wallet account
    private static $grantCoinInternalAddress = '';
    //The external address is the one used for cold storage
    private static $grantCoinExternalAddress = '';
    
    public static function GetInternalAddress(){
        return self::$grantCoinInternalAddress;
    }
    
    public static function GetExternalAddress(){
        return self::$grantCoinExternalAddress;
    }
    
    /*
     * Retrieves a unique GrantCoin address for a user to send coins to
     * @return - A unique GrantCoin address
     */
    public function GetUniqueAddress()
    {
        //TODO: Implement the webservice request to GrantCoin server
        return 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    }
    
    /*
     * Retrieves the balance of an address
     * @param address - The GrantCoin address to retrieve the balance of
     * @return - The balance of the given address
     */
    public function CheckBalance($address)
    {
        //TODO: Implement the webservice request to GrantCoin server
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
        //TODO: Implement the webservice request to GrantCoin server
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
        //TODO: Implement the webservice request to GrantCoin server
    }
    
    
    /*
     * How much GRT do I need to have $usdValue?
     * @param usdValue - The value in USD to convert to GRT using the Bid price
     * @return - The amount of GRT someone would need to give us to equal the USD value if I were to sell the GRT today
     */
    public function ConvertBuyUSDToGRT($usdValue)
    {
        $grtToBTC = Bittrex::GetBidGRTToBTCValue();
        $btcToUSD = Bitstamp::GetBidBTCToUSD();
        return $usdValue / $btcToUSD / $grtToBTC;
    }
    
    /*
     * How much is $usdValue worth in GRT?
     * @param usdValue - The value in USD to convert to GRT using the Ask price
     * @return - The amount of GRT the usdValue is worth today
     */
    public function ConvertSellUSDToGRT($usdValue)
    {
        $grtToBTC = Bittrex::GetAskGRTToBTCValue();
        $btcToUSD = Bitstamp::GetAskBTCToUSD();
        return $usdValue / $btcToUSD / $grtToBTC;
    }
    
    /*
     * How much USD do I need to have $grtValue?
     * @param grtValue - The value in GRT to convert to USD using the Ask price
     * @return - The amount of USD someone would need to give us to equal the GRT value if I were to sell the USD today
     */
    public function ConvertBuyGRTToUSD($grtValue)
    {
        $grtToBTC = Bittrex::GetAskGRTToBTCValue();
        $btcToUSD = Bitstamp::GetAskBTCToUSD();
        return $grtValue * $grtToBTC * $btcToUSD;
    }
    
    /*
     * How much is $grtValue worth in USD?
     * @param grtValue - The value in GRT to convert to USD using the Bid price
     * @return - The amount of USD the grtValue is worth today
     */
    public function ConvertSellGRTToUSD($grtValue)
    {
        $grtToBTC = Bittrex::GetBidGRTToBTCValue();
        $btcToUSD = Bitstamp::GetBidBTCToUSD();
        return $grtValue * $grtToBTC * $btcToUSD;
    }
}
