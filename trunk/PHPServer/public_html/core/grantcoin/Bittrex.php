<?php


/**
 * Class for interacting with Bittrex api to obtain BTC-GRT market information
 * See https://bittrex.com/Home/Api
 * @author jnorcross
 */
class Bittrex {
    private static $marketSummaryDictionary = array();
    private static $serverLocation = 'https://bittrex.com/';
    private static $publicApiPath = 'api/v1.1/public/';
    private static $btcGRTMarket = 'btc-grt';
    private static $connection = null;
    
    
    /*
     * Set a connection for the class to use for cachine data in the provided database
     * @param connection - The database connection object
     */
    public static function SetConnection(iDatabase $connection){
        self::$connection = $connection;
    }
            
    
    /*
     * Retrieves the asking price for the current exchange of BTC to GRT
     * @return - The current asking price in BTC for 1 GRT
     */
    public static function GetAskGRTToBTCValue()
    {
        return self::GetAskPrice(self::$btcGRTMarket);
    }
    
     /*
      * Retrieves the bid price for the current exchange of BTC to GRT
      * @return - The current bid price in BTC for 1 GRT
     */
    public static function GetBidGRTToBTCValue()
    {
        return self::GetBidPrice(self::$btcGRTMarket);
    }
    
    /*
     * Retrieves the BTC-GRT Market Summary
     * @return - The current market summary for BTC-GRT in the following form:
     *  {
            "high" : 0.00000128,
            "low" : 0.00000117,
            "volume" : 88714.48403787,
            "last" : 0.00000117,
            "bid" : 0.00000117,
            "ask" : 0.00000133,
            "lastupdated" : "1990-12-20 13:34:54"
        }
     */
    public static function GetBTCGRTMarketSummary()
    {
        return self::GetMarketSummary(self::$btcGRTMarket);
    }
    
    /*
     * Retrieves the asking price from the marketsummary
     * This is how much BTC people are offering to sell GRT
     */
    private static function GetAskPrice($market)
    {
        return self::GetMarketSummary($market)->ask;
    }
    
    /*
     * Retrieves the asking price from the marketsummary
     * This is how much BTC people are offering to buy GRT
     */
    private static function GetBidPrice($market)
    {
        return self::GetMarketSummary($market)->bid;
    }
    
    /* obtains the markey summary of any market
     * @param market - The type of market to retrieve from Bittrex
     * @param connection - The database connection object
     * throws Exception if the request fails
     */
    
    private static function GetMarketSummary($market)
    {
        if(self::$marketSummaryDictionary[$market] == NULL)
        {
            if(self::$connection != null)
            {
                $results = MarketData::GetMarketData(self::$connection, $market);
                if($results != null)
                {
                    self::$marketSummaryDictionary[$market] = $results;
                }
            }
            
            if(self::$marketSummaryDictionary[$market] == NULL)
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
                if ($response->success) {
                    $response = $response->result[0];
                    if(self::$connection != null)
                    {
                        self::$marketSummaryDictionary[$market] = MarketData::CreateUpdateMarketData(self::$connection, $market, $response->Bid, $response->Ask, $response->High, $response->Low, $response->Last, $response->Volume);
                    }
                    else {
                        self::$marketSummaryDictionary[$market] = self::NormalizeResponse($response);
                    }
                } else {
                    throw new Exception('Bittrex::GetMarketSummary error: ' . $response->message);
                }
            }
        }
        
        return self::$marketSummaryDictionary[$market];
    }
    
    private static function NormalizeResponse($response)
    {
        $object = new stdClass();
        $object->bid = $response->Bid;
        $object->ask = $response->Ask;
        $object->high = $response->High;
        $object->low = $response->Low;
        $object->last = $response->Last;
        $object->volume = $response->Volume;
        $dateTime = new DateTime();
        $object->lastupdated = $dateTime->format('Y-m-d H:i:s');
    }
}
