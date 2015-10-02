<?php


/**
 * Class for interacting with Bittrex api to obtain BTC-GRT market information
 * See https://bittrex.com/Home/Api
 * @author Jon
 */
class Bittrex {
    private static $marketSummaryDictionary = array();
    private static $serverLocation = 'https://bittrex.com/';
    private static $publicApiPath = 'api/v1.1/public/';
    
    /*
     * Retrieves the asking price for the current exchange of BTC to GRT
     * @return - The current asking price in BTC for 1 GRT
     */
    public static function GetAskGRTToBTCValue()
    {
        return self::GetAskPrice('btc-grt');
    }
    
     /*
     * Retrieves the bid price for the current exchange of BTC to GRT
     * @return - The current bid price in BTC for 1 GRT
     */
    public static function GetBidGRTToBTCValue()
    {
        return self::GetBidPrice('btc-grt');
    }
    
    /*
     * Retrieves the asking price from the marketsummary
     * This is how much BTC people are offering to sell GRT
     */
    private static function GetAskPrice($market)
    {
        if(self::$marketSummaryDictionary[$market] == NULL)
        {
            self::GetMarketSummary($market);
        }
        return self::$marketSummaryDictionary[$market]->Ask;
    }
    
    /*
     * Retrieves the asking price from the marketsummary
     * This is how much BTC people are offering to buy GRT
     */
    private static function GetBidPrice($market)
    {
        if(self::$marketSummaryDictionary[$market] == NULL)
        {
            self::GetMarketSummary($market);
        }
        return self::$marketSummaryDictionary[$market]->Bid;
    }
    
    /* obtains the markey summary of any market
     * @market - The type of market to retrieve from Bittrex
     * throws Exception if the request fails
     */
    
    private static function GetMarketSummary($market)
    {
        //Request
        //https://bittrex.com/api/v1.1/public/getmarketsummary?market=btc-grt
        
        //Response 
        /*
        {
            "success" : true,
            "message" : "",
            "result" : [{
                "MarketName" : "BTC-GRT",
                "High" : 0.00000128,
                "Low" : 0.00000117,
                "Volume" : 88714.48403787,
                "Last" : 0.00000117,
                "BaseVolume" : 0.10871928,
                "TimeStamp" : "2015-10-01T22:05:26.62",
                "Bid" : 0.00000117,
                "Ask" : 0.00000133,
                "OpenBuyOrders" : 28,
                "OpenSellOrders" : 89,
                "PrevDay" : 0.00000128,
                "Created" : "2015-05-15T21:25:04.71"
            }]
        }

        */
        
        $ch = curl_init(self::$serverLocation . self::$publicApiPath . "getmarketsummary?market=" . $market);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_TIMEOUT, '10');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-json", "Accept: application/x-json"));

        $json_response = curl_exec($ch);
        if ($curl_error = curl_error($ch)) {
            throw new Exception('Bittrex::GetMarketSummary error: ' . $curl_error);
        }
        curl_close($ch);
        
        $response = json_decode($json_response);
        if($response->success)
        {
            self::$marketSummaryDictionary[$market] = $response->result[0];
        }
        else throw new Exception ('Bittrex::GetMarketSummary error: ' . $response->message);
    }
}
