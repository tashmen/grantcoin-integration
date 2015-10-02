<?php


/**
 * Class for interacting with GrantCoin servers and other needed functionality
 * 
 * @author jnorcross
 */
class GrantCoin {
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
