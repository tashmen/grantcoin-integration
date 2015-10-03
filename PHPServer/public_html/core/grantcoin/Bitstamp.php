<?php

/**
 * Class for retrieving information from Bitstamp to for market information on BTC to USD
 * See https://www.bitstamp.net/api/
 * @author jnorcross
 */
class Bitstamp {
    private static $marketSummaryDictionary = array();
    private static $serverLocation = 'https://www.bitstamp.net/';
    private static $publicApiPath = 'api/';
    private static $usdBTCMarket = 'usd-btc';
    
    /*
     * Retrieves the asking price for the current exchange of USD to BTC
     * @return - The current asking price in USD for 1 BTC
     */
    public static function GetAskBTCToUSD()
    {
        return floatval(self::GetAskPrice(self::$usdBTCMarket));
    }
    
    /*
     * Retrieves the bid price for the current exchange of USD to BTC
     * @return - The current bid price in USD for 1 BTC
     */
    public static function GetBidBTCToUSD()
    {
        return floatval(self::GetBidPrice(self::$usdBTCMarket));
    }
    
    /*
     * Retrieves the market summary for the USD to BTC market
     * @return - The market summary in the following form:
     *  {
            "high" : "239.44",
            "last" : "238.04",
            "timestamp" : "1443745708",
            "bid" : "237.58",
            "vwap" : "237.12",
            "volume" : "20255.03135839",
            "low" : "235.01",
            "ask" : "238.04"
        }
     */
    public static function GetUSDBTCMarketSummary()
    {
        return self::GetMarketSummary(self::$usdBTCMarket);
    }
    
    /*
     * Retrieves the asking price from the marketsummary
     * This is how much USD people are offering to sell BTC
     */
    private static function GetAskPrice($market)
    {
        return self::GetMarketSummary($market)->ask;
    }
    
    /*
     * Retrieves the asking price from the marketsummary
     * This is how much USD people are offering to buy BTC
     */
    private static function GetBidPrice($market)
    {
        return self::GetMarketSummary($market)->bid;
    }
    
    /* obtains the markey summary of any market
     * @market - The type of market to retrieve from Bittrex
     * @return - true if the operation was successful
     * throws Exception if the request fails
     */
    
    private static function GetMarketSummary($market)
    {
        if(self::$marketSummaryDictionary[$market] == NULL)
        {
            //Request
            //https://www.bitstamp.net/api/ticker/

            //Response 
            /*
            {
                "high" : "239.44",
                "last" : "238.04",
                "timestamp" : "1443745708",
                "bid" : "237.58",
                "vwap" : "237.12",
                "volume" : "20255.03135839",
                "low" : "235.01",
                "ask" : "238.04"
            }

            */

            $ch = curl_init(self::$serverLocation . self::$publicApiPath . "ticker/");

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
            curl_setopt($ch, CURLOPT_TIMEOUT, '10');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-json", "Accept: application/x-json"));

            $json_response = curl_exec($ch);
            if ($curl_error = curl_error($ch)) {
                throw new Exception('Bitstamp::GetMarketSummary error: ' . $curl_error);
            }
            curl_close($ch);

            $response = json_decode($json_response);
            self::$marketSummaryDictionary[$market] = $response;
        }
        return self::$marketSummaryDictionary[$market];
    }
}
