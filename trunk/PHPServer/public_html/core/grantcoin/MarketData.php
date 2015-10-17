<?php

/**
 * Common class for storing Market Data information
 *
 * @author jnorcross
 */
class MarketData {
    
    public static function GetMarketData(iDatabase $connection, $market){
        $select = "Select id, bid, ask, high, low, last, volume, lastupdated from marketdata where id = (?) and lastupdated > (?)";
        $parameters[] = $market;
        $dateTime = new DateTime();
        $dateTime->modify('-30 minutes');
        $parameters[] = $dateTime->format('Y-m-d H:i:s');
        $records = $connection->execute($select, $parameters);
        $records = $records[0];
        $response = null;
        if($records != null)
        {
            $response = new stdClass();
            $response->bid = floatval($records['bid']);
            $response->ask = floatval($records['ask']);
            $response->high = floatval($records['high']);
            $response->low = floatval($records['low']);
            $response->last = floatval($records['last']);
            $response->volume = floatval($records['volume']);
            $response->lastupdated = $records['lastupdated'];
        }
        return $response;
    }
    
    public static function CreateUpdateMarketData(iDatabase $connection, $market, $bid, $ask, $high, $low, $last, $volume){
        $insert = "Insert into marketdata (id, bid, ask, high, low, last, volume, lastupdated) values (?,?,?,?,?,?,?,?) on duplicate key update  bid = values(bid), ask = values(ask), high = values(high), low = values(low), last = values(last), volume = values(volume), lastupdated = values(lastupdated)";
        $parameters[] = $market;
        $parameters[] = $bid;
        $parameters[] = $ask;
        $parameters[] = $high;
        $parameters[] = $low;
        $parameters[] = $last;
        $parameters[] = $volume;
        $dateTime = new DateTime();
        $parameters[] = $dateTime->format('Y-m-d H:i:s');
        $connection->execute($insert, $parameters, false);
        return self::GetMarketData($connection, $market);
    }
}
