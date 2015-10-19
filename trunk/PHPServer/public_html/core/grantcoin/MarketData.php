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
