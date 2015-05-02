<?php

use Betfair\BetfairFactory;
use Betfair\Model\MarketFilter;
use Betfair\Model\PriceProjection;
use GuzzleHttp\Ring\Exception\ConnectException;
require 'vendor/autoload.php';

date_default_timezone_set("UTC");



####### CONFIG LOGIN CREDENTIALS

$betbtc_token = '';

$betfair_username = '';
$betfair_password = '';
$betfair_key = '';

##########################

$commission = 0.05;

$servername = "127.0.0.1";
$username   = "root";
$password   = "";
$dbname     = "api";





$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



#    ███╗   ███╗ █████╗ ██████╗ ██╗  ██╗███████╗████████╗██████╗  ██████╗  ██████╗ ██╗  ██╗        ██████╗ ███████╗████████╗███████╗ █████╗ ██╗██████╗ 
#    ████╗ ████║██╔══██╗██╔══██╗██║ ██╔╝██╔════╝╚══██╔══╝██╔══██╗██╔═══██╗██╔═══██╗██║ ██╔╝        ██╔══██╗██╔════╝╚══██╔══╝██╔════╝██╔══██╗██║██╔══██╗
#    ██╔████╔██║███████║██████╔╝█████╔╝ █████╗     ██║   ██████╔╝██║   ██║██║   ██║█████╔╝         ██████╔╝█████╗     ██║   █████╗  ███████║██║██████╔╝
#    ██║╚██╔╝██║██╔══██║██╔══██╗██╔═██╗ ██╔══╝     ██║   ██╔══██╗██║   ██║██║   ██║██╔═██╗         ██╔══██╗██╔══╝     ██║   ██╔══╝  ██╔══██║██║██╔══██╗
#    ██║ ╚═╝ ██║██║  ██║██║  ██║██║  ██╗███████╗   ██║   ██████╔╝╚██████╔╝╚██████╔╝██║  ██╗        ██████╔╝███████╗   ██║   ██║     ██║  ██║██║██║  ██║
#    ╚═╝     ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝╚══════╝   ╚═╝   ╚═════╝  ╚═════╝  ╚═════╝ ╚═╝  ╚═╝        ╚═════╝ ╚══════╝   ╚═╝   ╚═╝     ╚═╝  ╚═╝╚═╝╚═╝  ╚═╝


class Bets
{
    
    
    public function cancel($market, $meta)
    {
        $token = $betbtc_token;
        
        $data        = array(
            "selection" => $meta
        );

        $data_all        = array(
            "selection" => 'all'
        );

        $data_string = json_encode($data);
        $data_string_all = json_encode($data_all);
        
        $url = ("https://www.betbtc.co/api/bet/" . $market);
        $ch  = curl_init();
        
        $headers = array(
            "Content-Type: application/json",
            "Authorization: Token token=" . $token
        );
        #$post_data = array('selection' => $selection );
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // man-in-the-middle defense by verifying ssl cert.
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // man-in-the-middle defense by verifying ssl cert.
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

        if ((float)$meta > 0) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        } else {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string_all);
        }
        
        $result   = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return $httpCode;
    }
    
    
    public function httpGet($url)
    {
        $token   = $betbtc_token;
        $headers = array(
            "Content-Type: application/json",
            "Authorization: Token token=" . $token
        );
        $ch      = curl_init();
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // man-in-the-middle defense by verifying ssl cert.
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // man-in-the-middle defense by verifying ssl cert.
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        //  curl_setopt($ch,CURLOPT_HEADER, false); 
        
        $output = curl_exec($ch);
        $dec    = json_decode($output, true);
        
        curl_close($ch);
        return $dec;
    }
    
    
    
    
    public function betting($market, $selection, $odd, $stake, $type)
    {
        $token = $betbtc_token;
        $data  = array(
            "market_id" => $market,
            "selection" => $selection,
            "odd" => $odd,
            "stake" => $stake,
            "bet_type" => $type
        );
        
        $data_string = json_encode($data);
        
        $url = ("https://www.betbtc.co/api/bet/");
        $ch  = curl_init();
        
        $headers = array(
            "Content-Type: application/json",
            "Authorization: Token token=" . $token
        );
        #$post_data = array('selection' => $selection );
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // man-in-the-middle defense by verifying ssl cert.
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // man-in-the-middle defense by verifying ssl cert.
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        
        $result   = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return $httpCode;
    }
    
}

class OddsVolumes
{
    
    public function oddsback($back_temp)
    {
        
        if ($back_temp < 2) {
            $back_bet  = (round($back_temp / 0.01) * 0.01);
            $back_bet2 = (round($back_temp / 0.01) * 0.01 + 0.01 < 2) ? (round($back_temp / 0.01) * 0.01 + 0.01) : (round($back_temp / 0.02 + 0.02) * 0.02 + 0.02);
            $back_bet3 = (round($back_temp / 0.01) * 0.01 + 0.02 < 2) ? (round($back_temp / 0.01) * 0.01 + 0.02) : (round($back_temp / 0.02 + 0.02) * 0.02 + 0.04);
            
        } elseif ($back_temp < 3) {
            $back_bet  = (round($back_temp / 0.02) * 0.02);
            $back_bet2 = (round($back_temp / 0.02) * 0.02 + 0.02 < 3) ? (round($back_temp / 0.02) * 0.02 + 0.02) : (round($back_temp / 0.05 + 0.05) * 0.05 + 0.05);
            $back_bet3 = (round($back_temp / 0.02) * 0.02 + 0.04 < 3) ? (round($back_temp / 0.02) * 0.02 + 0.04) : (round($back_temp / 0.05 + 0.05) * 0.05 + 0.1);
        } elseif ($back_temp < 4) {
            $back_bet  = (round($back_temp / 0.05) * 0.05);
            $back_bet2 = (round($back_temp / 0.05) * 0.05 + 0.05 < 4) ? (round($back_temp / 0.05) * 0.05 + 0.05) : (round($back_temp / 0.1 + 0.1) * 0.1 + 0.1);
            $back_bet3 = (round($back_temp / 0.05) * 0.05 + 0.1 < 4) ? (round($back_temp / 0.05) * 0.05 + 0.1) : (round($back_temp / 0.1 + 0.1) * 0.1 + 0.2);
            
        } elseif ($back_temp < 6) {
            $back_bet  = (round($back_temp / 0.1) * 0.1);
            $back_bet2 = (round($back_temp / 0.1) * 0.1 + 0.1 < 6) ? (round($back_temp / 0.1) * 0.1 + 0.1) : (round($back_temp / 0.2 + 0.2) * 0.2 + 0.2);
            $back_bet3 = (round($back_temp / 0.1) * 0.1 + 0.2 < 6) ? (round($back_temp / 0.1) * 0.1 + 0.2) : (round($back_temp / 0.2 + 0.2) * 0.2 + 0.4);
            
        } elseif ($back_temp < 10) {
            $back_bet  = (round($back_temp / 0.2) * 0.2);
            $back_bet2 = (round($back_temp / 0.2) * 0.2 + 0.2 < 10) ? (round($back_temp / 0.2) * 0.2 + 0.2) : (round($back_temp / 0.5 + 0.5) * 0.5 + 0.5);
            $back_bet3 = (round($back_temp / 0.2) * 0.2 + 0.4 < 10) ? (round($back_temp / 0.2) * 0.2 + 0.4) : (round($back_temp / 0.5 + 0.5) * 0.5 + 1);
            
        } elseif ($back_temp < 20) {
            $back_bet  = (round($back_temp / 0.5) * 0.5);
            $back_bet2 = (round($back_temp / 0.5) * 0.5 + 0.5 < 20) ? (round($back_temp / 0.5) * 0.5 + 0.5) : (round($back_temp / 1 + 1) * 1 + 1);
            $back_bet3 = (round($back_temp / 0.5) * 0.5 + 1 < 20) ? (round($back_temp / 0.5) * 0.5 + 1) : (round($back_temp / 1 + 1) * 1 + 2);
            
        } elseif ($back_temp < 30) {
            $back_bet  = (round($back_temp / 1) * 1);
            $back_bet2 = (round($back_temp / 1) * 1 + 1 < 30) ? (round($back_temp / 1) * 1 + 1) : (round($back_temp / 2 + 2) * 2 + 2);
            $back_bet3 = (round($back_temp / 1) * 1 + 2 < 30) ? (round($back_temp / 1) * 1 + 2) : (round($back_temp / 2 + 2) * 2 + 4);
            
        } elseif ($back_temp < 50) {
            $back_bet  = (round($back_temp / 2) * 2);
            $back_bet2 = (round($back_temp / 2) * 2 + 2 < 50) ? (round($back_temp / 2) * 2 + 2) : (round($back_temp / 5 + 5) * 5 + 5);
            $back_bet3 = (round($back_temp / 2) * 2 + 4 < 50) ? (round($back_temp / 2) * 2 + 4) : (round($back_temp / 5 + 5) * 5 + 10);
        } elseif ($back_temp < 100) {
            $back_bet  = (round($back_temp / 5) * 5);
            $back_bet2 = (round($back_temp / 5) * 5 + 5 < 100) ? (round($back_temp / 5) * 5 + 5) : (round($back_temp / 10 + 10) * 10 + 10);
            $back_bet3 = (round($back_temp / 5) * 5 + 10 < 100) ? (round($back_temp / 5) * 5 + 10) : (round($back_temp / 10 + 10) * 10 + 20);
        } elseif ($back_temp < 1000) {
            $back_bet  = (round($back_temp / 10) * 10);
            $back_bet2 = (round($back_temp / 10) * 10 + 10);
            $back_bet3 = (round($back_temp / 10) * 10 + 20);
        }
        
        return array(
            $back_bet,
            $back_bet2,
            $back_bet3
        );
        
    }
    
    public function oddslay($lay_temp)
    {
        
        if (($lay_temp > 0.98 && $lay_temp < 1.1)) {
            $lay_bet  = 1.03;
            $lay_bet2 = 1.02;
            $lay_bet3 = 1.01;
            
        } elseif ($lay_temp < 2) {
            $lay_bet  = (round($lay_temp / 0.01) * 0.01);
            $lay_bet2 = (round($lay_temp / 0.01) * 0.01 - 0.01);
            $lay_bet3 = (round($lay_temp / 0.01) * 0.01 - 0.02);
            
        } elseif ($lay_temp < 3) {
            $lay_bet  = (round($lay_temp / 0.02) * 0.02);
            $lay_bet2 = (round($lay_temp / 0.02) * 0.02 - 0.02 > 2) ? (round($lay_temp / 0.02) * 0.02 - 0.02) : (round($lay_temp / 0.01) * 0.01 - 0.01);
            $lay_bet3 = (round($lay_temp / 0.02) * 0.02 - 0.04 > 2) ? (round($lay_temp / 0.02) * 0.02 - 0.04) : (round($lay_temp / 0.01) * 0.01 - 0.02);
        } elseif ($lay_temp < 4) {
            $lay_bet  = (round($lay_temp / 0.05) * 0.05);
            $lay_bet2 = (round($lay_temp / 0.05) * 0.05 - 0.05 > 3) ? (round($lay_temp / 0.05) * 0.05 - 0.05) : (round($lay_temp / 0.02) * 0.02 - 0.02);
            $lay_bet3 = (round($lay_temp / 0.05) * 0.05 - 0.1 > 3) ? (round($lay_temp / 0.05) * 0.05 - 0.1) : (round($lay_temp / 0.02) * 0.02 - 0.04);
            
        } elseif ($lay_temp < 6) {
            $lay_bet  = (round($lay_temp / 0.1) * 0.1);
            $lay_bet2 = (round($lay_temp / 0.1) * 0.1 - 0.1 > 4) ? (round($lay_temp / 0.1) * 0.1 - 0.1) : (round($lay_temp / 0.05) * 0.05 - 0.05);
            $lay_bet3 = (round($lay_temp / 0.1) * 0.1 - 0.2 > 4) ? (round($lay_temp / 0.1) * 0.1 - 0.2) : (round($lay_temp / 0.05) * 0.05 - 0.1);
            
        } elseif ($lay_temp < 10) {
            $lay_bet  = (round($lay_temp / 0.2) * 0.2);
            $lay_bet2 = (round($lay_temp / 0.2) * 0.2 - 0.2 > 6) ? (round($lay_temp / 0.2) * 0.2 - 0.2) : (round($lay_temp / 0.1) * 0.1 - 0.1);
            $lay_bet3 = (round($lay_temp / 0.2) * 0.2 - 0.4 > 6) ? (round($lay_temp / 0.2) * 0.2 - 0.4) : (round($lay_temp / 0.1) * 0.1 - 0.2);
            
        } elseif ($lay_temp < 20) {
            $lay_bet  = (round($lay_temp / 0.5) * 0.5);
            $lay_bet2 = (round($lay_temp / 0.5) * 0.5 - 0.5 > 10) ? (round($lay_temp / 0.5) * 0.5 - 0.5) : (round($lay_temp / 0.2) * 0.2 - 0.2);
            $lay_bet3 = (round($lay_temp / 0.5) * 0.5 - 1 > 10) ? (round($lay_temp / 0.5) * 0.5 - 1) : (round($lay_temp / 0.2) * 0.2 - 0.4);
            
        } elseif ($lay_temp < 30) {
            $lay_bet  = (round($lay_temp / 1) * 1);
            $lay_bet2 = (round($lay_temp / 1) * 1 - 1 > 20) ? (round($lay_temp / 1) * 1 - 1) : (round($lay_temp / 0.5) * 0.5 - 0.5);
            $lay_bet3 = (round($lay_temp / 1) * 1 - 2 > 20) ? (round($lay_temp / 1) * 1 - 2) : (round($lay_temp / 0.5) * 0.5 - 1);
            
        } elseif ($lay_temp < 50) {
            $lay_bet  = (round($lay_temp / 2) * 2);
            $lay_bet2 = (round($lay_temp / 2) * 2 - 2 > 30) ? (round($lay_temp / 2) * 2 - 2) : (round($lay_temp / 1) * 1 - 1);
            $lay_bet3 = (round($lay_temp / 2) * 2 - 4 > 30) ? (round($lay_temp / 2) * 2 - 4) : (round($lay_temp / 1) * 1 - 2);
        } elseif ($lay_temp < 100) {
            $lay_bet  = (round($lay_temp / 5) * 5);
            $lay_bet2 = (round($lay_temp / 5) * 5 - 5 > 50) ? (round($lay_temp / 5) * 5 - 5) : (round($lay_temp / 2) * 2 - 2);
            $lay_bet3 = (round($lay_temp / 5) * 5 - 10 > 50) ? (round($lay_temp / 5) * 5 - 10) : (round($lay_temp / 2) * 2 - 4);
        } elseif ($lay_temp < 1000) {
            $lay_bet  = (round($lay_temp / 10) * 10);
            $lay_bet2 = (round($lay_temp / 10) * 10 - 10 > 100) ? (round($lay_temp / 10) * 10 - 10) : (round($lay_temp / 5) * 5 - 5);
            $lay_bet3 = (round($lay_temp / 10) * 10 - 20 > 100) ? (round($lay_temp / 10) * 10 - 20) : (round($lay_temp / 5) * 5 - 10);
        }
        
        return array(
            $lay_bet,
            $lay_bet2,
            $lay_bet3
        );
    }
    
    
    public function volumes($odd_back, $volume_back, $volume_lay, $featured, $selection_betbtc)
    {
        #  $volume_back = $volume_back * 0.003;
        #  $volume_lay = $volume_lay * 0.003;
        if ($featured == 1) {

            if ($selection_betbtc == 'DRAW') {

                $stake_back  = mt_rand(1 * 100, 3 * 100) / 100000;
                $stake_back2 = mt_rand(1 * 100, 3 * 100) / 100000;
                $stake_back3 = mt_rand(1 * 100, 3 * 100) / 100000;

                $stake_lay  = mt_rand(1 * 100, 3 * 100) / 100000;
                $stake_lay2 = mt_rand(1 * 100, 3 * 100) / 100000;
                $stake_lay3 = mt_rand(1 * 100, 3 * 100) / 100000;             


            } else {

                if ($volume_back >= 300 && $odd_back <= 2) {

                    $stake_back  = mt_rand(30 * 100, 175 * 100) / 100000;
                    $stake_back2 = mt_rand(10 * 100, 45 * 100) / 100000;
                    $stake_back3 = mt_rand(10 * 100, 45 * 100) / 100000;

                } else {

                    $stake_back  = mt_rand(1 * 100, 30 * 100) / 100000;
                    $stake_back2 = mt_rand(1 * 100, 30 * 100) / 100000;
                    $stake_back3 = mt_rand(1 * 100, 30 * 100) / 100000;
                }
                
                if ($volume_lay >= 300 && $odd_back <= 2) {


                    $stake_lay  = mt_rand(30 * 100, 175 * 100) / 100000;
                    $stake_lay2 = mt_rand(10 * 100, 45 * 100) / 100000;
                    $stake_lay3 = mt_rand(10 * 100, 45 * 100) / 100000;

                } else {

                    $stake_lay  = mt_rand(1 * 100, 30 * 100) / 100000;
                    $stake_lay2 = mt_rand(1 * 100, 30 * 100) / 100000;
                    $stake_lay3 = mt_rand(1 * 100, 30 * 100) / 100000;
                }
            }



        } else {

            if ($selection_betbtc == 'DRAW') {

                $stake_back  = mt_rand(1 * 100, 3 * 100) / 100000;
                $stake_back2 = mt_rand(1 * 100, 3 * 100) / 100000;
                $stake_back3 = mt_rand(1 * 100, 3 * 100) / 100000;

                $stake_lay  = mt_rand(1 * 100, 3 * 100) / 100000;
                $stake_lay2 = mt_rand(1 * 100, 3 * 100) / 100000;
                $stake_lay3 = mt_rand(1 * 100, 3 * 100) / 100000;             


            } else {

                if ($volume_back < 300) {
                    $stake_back  = mt_rand(1 * 100, 4 * 100) / 100000;
                    $stake_back2 = mt_rand(1 * 100, 4 * 100) / 100000;
                    $stake_back3 = mt_rand(1 * 100, 4 * 100) / 100000;
                } else {
                    $stake_back  = mt_rand(4 * 100, 8 * 100) / 100000;
                    $stake_back2 = mt_rand(4 * 100, 8 * 100) / 100000;
                    $stake_back3 = mt_rand(4 * 100, 8 * 100) / 100000;
                }
                
                if ($volume_lay < 300) {
                    $stake_lay  = mt_rand(1 * 100, 4 * 100) / 100000;
                    $stake_lay2 = mt_rand(1 * 100, 4 * 100) / 100000;
                    $stake_lay3 = mt_rand(1 * 100, 4 * 100) / 100000;
                } else {
                    $stake_lay  = mt_rand(4 * 100, 8 * 100) / 100000;
                    $stake_lay2 = mt_rand(4 * 100, 8 * 100) / 100000;
                    $stake_lay3 = mt_rand(4 * 100, 8 * 100) / 100000;
                }
            }
        }
        
        
        return array(
            $stake_back,
            $stake_back2,
            $stake_back,
            $stake_lay,
            $stake_lay2,
            $stake_lay3
        );
        
    }
}


class SearchMarketBookExample
{
    
    
    public function searchWithExBestOffer($appKey, $username, $pwd, $array)
    {
        $betfair           = BetfairFactory::createBetfair($appKey, $username, $pwd);
        $marketBookBetfair = $betfair->getBetfairMarketBook();
        
        
        $priceProjection = new PriceProjection(array(
            \Betfair\Model\PriceData::EX_BEST_OFFERS
        ));
        $marketBookBetfair->withPriceProjection($priceProjection)->withMarketIds($array); //, 1.117519052));
        $results = $marketBookBetfair->getResults();
        
        return $results;
    }
}

while (1) {

    try {
    
        $events = "SELECT betfair, betbtc FROM Markets WHERE status = 1";
        
        $result = $conn->query($events);
        
        $GLOBALS = array();
        
        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $GLOBALS[] = array(
                    'betfair' => $row['betfair'],
                    'betbtc' => $row['betbtc']
                );
            }
        } else {
            echo "0 results";
        }
        
        $block = (array_chunk($GLOBALS, 35));
        
        foreach ($block as $single) {
            $ar = array();
            
            foreach ($single as $key => $v) {
                # code...
                array_push($ar, $v['betfair']);
            }
         

            $marketbooks = SearchMarketBookExample::searchWithExBestOffer($betfair_key, $betfair_username, $betfair_password, $ar);
              
       
            foreach ($marketbooks as $row) {
                $status    = $row['inplay'];
                $closed    = $row['status'];
                $market_id = $row['marketId'];

                $sql    = "SELECT home_betfair, away_betfair, event_date, event_date_betfair FROM Markets WHERE betfair = '$market_id' ";
                $result = $conn->query($sql);
                
                                   
                while ($datas = $result->fetch_assoc()) {                          
                    $event_date   = $datas['event_date'];
                    $event_date_betfair   = $datas['event_date_betfair']; 
                    $home   = $datas['home_betfair']; 
                    $away   = $datas['away_betfair'];                           
                }

                $timenow = strtotime(date('Y-m-d H:i:s'));

                $plus20now = strtotime('+20 minutes', $timenow);

                $timeevent = strtotime($event_date);
                $timeevent_betfair = strtotime($event_date_betfair);
                

                echo ('Betting on' . " " . $home . " vs " . $away);
            

                ###IF TIME_NOW -3600 is higher than Event_Time
                if ((float)$status == 1 || $closed == 'CLOSED' || $timeevent <= $plus20now || $timeevent_betfair <= $plus20now) {

                    echo("OLD MARKET DELETED");

                    $sql    = "SELECT betbtc FROM Markets WHERE betfair = '$market_id' ";
                    $result = $conn->query($sql);
                    
                                       
                    while ($row = $result->fetch_assoc()) {                          
                        $market_betbtc   = $row['betbtc'];                           
                    }
                    
                    sleep(0.5);
                    Bets::cancel($market_betbtc, 0);
                    sleep(0.5);

                    $conn->query("DELETE FROM Markets WHERE betfair='$market_id'");


                ## IF TIME_NOW is higher than Event_Time - 30 Minutes 
                    
                } else {
                    
                    if ($row['numberOfRunners'] == 3) {
                        
                        
                        
                        
                        ##HOME-TEAM
                        $lay_price_home   = isset($row['runners'][0]['ex']['availableToLay'][0]['price']) ? $row['runners'][0]['ex']['availableToLay'][0]['price'] : null;
                        $back_price_home  = isset($row['runners'][0]['ex']['availableToBack'][0]['price']) ? $row['runners'][0]['ex']['availableToBack'][0]['price'] : null;
                        $lay_volume_home  = isset($row['runners'][0]['ex']['availableToLay'][0]['size']) ? $row['runners'][0]['ex']['availableToLay'][0]['size'] : null;
                        $back_volume_home = isset($row['runners'][0]['ex']['availableToBack'][0]['size']) ? $row['runners'][0]['ex']['availableToBack'][0]['size'] : null;
                        
                        ##AWAY-TEAM
                        $lay_price_away   = isset($row['runners'][1]['ex']['availableToLay'][0]['price']) ? $row['runners'][1]['ex']['availableToLay'][0]['price'] : null;
                        $back_price_away  = isset($row['runners'][1]['ex']['availableToBack'][0]['price']) ? $row['runners'][1]['ex']['availableToBack'][0]['price'] : null;
                        $lay_volume_away  = isset($row['runners'][1]['ex']['availableToLay'][0]['size']) ? $row['runners'][1]['ex']['availableToLay'][0]['size'] : null;
                        $back_volume_away = isset($row['runners'][1]['ex']['availableToBack'][0]['size']) ? $row['runners'][1]['ex']['availableToBack'][0]['size'] : null;
                        
                        #draw
                        $lay_price_draw   = isset($row['runners'][2]['ex']['availableToLay'][0]['price']) ? $row['runners'][2]['ex']['availableToLay'][0]['price'] : null;
                        $back_price_draw  = isset($row['runners'][2]['ex']['availableToBack'][0]['price']) ? $row['runners'][2]['ex']['availableToBack'][0]['price'] : null;
                        $lay_volume_draw  = isset($row['runners'][2]['ex']['availableToLay'][0]['size']) ? $row['runners'][2]['ex']['availableToLay'][0]['size'] : null;
                        $back_volume_draw = isset($row['runners'][2]['ex']['availableToBack'][0]['size']) ? $row['runners'][2]['ex']['availableToBack'][0]['size'] : null;
                        
                        
                        
                        
                        
                        
                        // #EDIT TIME AND VOLUMES DB
                        $update_time = date("Y-m-d H:i:s");
                        
                        $conn->query("UPDATE Markets SET lay_volume_home='" . $lay_volume_home . "', lay_volume_away='" . $lay_volume_away . "', lay_volume_draw='" . $lay_volume_draw . "', back_volume_home='" . $back_volume_home . "', back_volume_away='" . $back_volume_away . "', back_volume_draw='" . $back_volume_draw . "',updated_at='" . $update_time . "' WHERE betfair='" . $market_id . "'");
                        
                        
                        
                        
                        // #EDITAR PRICES AND REMOVE / PLACE BETS
                        


                        ###### TEAM HOME
                        
                        $conn->query("UPDATE Markets SET back_price_home='" . $back_price_home . "', lay_price_home='" . $lay_price_home . "' WHERE betfair='" . $market_id . "'");
                        $affected = $conn->affected_rows;
                        
                        
                        if ($affected > 0) {
                            $sql    = "SELECT betfair, betbtc, home_betbtc, back_price_home, lay_price_home, back_volume_home, lay_volume_home, featured FROM Markets WHERE betfair = '$market_id' ";
                            $result = $conn->query($sql);
                            
                            
                            
                            while ($row = $result->fetch_assoc()) {
                                
                                $market_betbtc   = $row['betbtc'];
                                $selection_betbtc = $row['home_betbtc'];
                                $odd_back         = $row['back_price_home'];
                                $odd_lay          = $row['lay_price_home'];
                                $volume_back      = $row['back_volume_home'];
                                $volume_lay       = $row['lay_volume_home'];
                                $featured         = $row['featured'];
                                
                            }
                            
                            
                            
                            ######cancel
                            Bets::cancel($market_betbtc, 1);
                            sleep(1);
              
              
                            if ($lay_price_home != null and $back_price_home != null) {
                                $back_temp = ($odd_lay * (1 + $commission));
                                $back      = OddsVolumes::oddsback($back_temp);
                                $lay_temp  = ($odd_back * (1 - $commission));
                                $lay       = OddsVolumes::oddslay($lay_temp);
                                $volume    = OddsVolumes::volumes($odd_back, $volume_back, $volume_lay, $featured, $selection_betbtc);
                                
                                print_r($lay);
                                
                                
                                
                                Bets::betting($market_betbtc, $selection_betbtc, $back[0], $volume[0], 'back');
                                sleep(0.2);
                                
                                Bets::betting($market_betbtc, $selection_betbtc, $back[1], $volume[1], 'back');
                                sleep(0.2);

                                Bets::betting($market_betbtc, $selection_betbtc, $back[2], $volume[2], 'back');
                                sleep(0.2);
                                
                                
                                if ($back_price_home > 5) {
                                    Bets::betting($market_betbtc, $selection_betbtc, ((round(mt_rand(3, 5) / 0.1)) * 0.1), $volume[3], 'lay');
                                    sleep(0.2);
                                    
                                    Bets::betting($market_betbtc, $selection_betbtc, ((round(mt_rand(3, 5) / 0.1)) * 0.1), $volume[4], 'lay');
                                    sleep(0.2);
                                    
                                    Bets::betting($market_betbtc, $selection_betbtc, ((round(mt_rand(3, 5) / 0.1)) * 0.1), $volume[5], 'lay');
                                    sleep(0.2);
                                    
                                    
                                } else {
                                    Bets::betting($market_betbtc, $selection_betbtc, $lay[0], $volume[3], 'lay');
                                    sleep(0.2);

                                    Bets::betting($market_betbtc, $selection_betbtc, $lay[1], $volume[4], 'lay');
                                    sleep(0.2);

                                    Bets::betting($market_betbtc, $selection_betbtc, $lay[2], $volume[5], 'lay');
                                    sleep(0.2);
                                    
                                    
                                }
                            }
                        }
                        
                        
                        
                        
                        
                        
                        ###### TEAM AWAY
                        
                        $conn->query("UPDATE Markets SET back_price_away='" . $back_price_away . "', lay_price_away='" . $lay_price_away . "' WHERE betfair='" . $market_id . "'");
                        $affected = $conn->affected_rows;
                        
                        
                        if ($affected > 0) {
                            $sql    = "SELECT betfair, betbtc, away_betbtc, back_price_away, lay_price_away, back_volume_away, lay_volume_away, featured FROM Markets WHERE betfair = '$market_id' ";
                            $result = $conn->query($sql);
                            
                            
                            
                            while ($row = $result->fetch_assoc()) {
                                
                                $market_betbtc   = $row['betbtc'];
                                $selection_betbtc = $row['away_betbtc'];
                                $odd_back         = $row['back_price_away'];
                                $odd_lay          = $row['lay_price_away'];
                                $volume_back      = $row['back_volume_away'];
                                $volume_lay       = $row['lay_volume_away'];
                                $featured         = $row['featured'];
                                
                            }
                            
                            ######cancel
                            Bets::cancel($market_betbtc, 2);
                            sleep(1);
                            
                            
                            if ($lay_price_away != null and $back_price_away != null) {
                                $back_temp = ($odd_lay * (1 + $commission));
                                $back      = OddsVolumes::oddsback($back_temp);
                                $lay_temp  = ($odd_back * (1 - $commission));
                                $lay       = OddsVolumes::oddslay($lay_temp);
                                $volume    = OddsVolumes::volumes($odd_back, $volume_back, $volume_lay, $featured, $selection_betbtc);
                                
                                Bets::betting($market_betbtc, $selection_betbtc, $back[0], $volume[0], 'back');
                                sleep(0.2);
                                
                                Bets::betting($market_betbtc, $selection_betbtc, $back[1], $volume[1], 'back');
                                sleep(0.2);

                                Bets::betting($market_betbtc, $selection_betbtc, $back[2], $volume[2], 'back');
                                sleep(0.2);
                                
                                
                                if ($back_price_away > 5) {
                                    Bets::betting($market_betbtc, $selection_betbtc, ((round(mt_rand(3, 5) / 0.1)) * 0.1), $volume[3], 'lay');
                                    sleep(0.2);
                                    
                                    Bets::betting($market_betbtc, $selection_betbtc, ((round(mt_rand(3, 5) / 0.1)) * 0.1), $volume[4], 'lay');
                                    sleep(0.2);
                                    
                                    Bets::betting($market_betbtc, $selection_betbtc, ((round(mt_rand(3, 5) / 0.1)) * 0.1), $volume[5], 'lay');
                                    sleep(0.2);
                                    
                                    
                                } else {
                                    Bets::betting($market_betbtc, $selection_betbtc, $lay[0], $volume[3], 'lay');
                                    sleep(0.2);

                                    Bets::betting($market_betbtc, $selection_betbtc, $lay[1], $volume[4], 'lay');
                                    sleep(0.2);

                                    Bets::betting($market_betbtc, $selection_betbtc, $lay[2], $volume[5], 'lay');
                                    sleep(0.2);
                                    
                                    
                                }
                            }
                        }
                        
               
                        
                        ##### TEAM DRAW
                        
                        $conn->query("UPDATE Markets SET back_price_draw='" . $back_price_draw . "', lay_price_draw='" . $lay_price_draw . "' WHERE betfair='" . $market_id . "'");
                        $affected = $conn->affected_rows;
                        
                        
                        
                        if ($affected > 0) {
                            $sql    = "SELECT betfair, betbtc, back_price_draw, lay_price_draw, back_volume_draw, lay_volume_draw, featured FROM Markets WHERE betfair = '$market_id' ";
                            $result = $conn->query($sql);
                            
                            
                            
                            while ($row = $result->fetch_assoc()) {
                                
                                $market_betbtc   = $row['betbtc'];
                                $selection_betbtc = 'DRAW';
                                $odd_back         = $row['back_price_draw'];
                                $odd_lay          = $row['lay_price_draw'];
                                $volume_back      = $row['back_volume_draw'];
                                $volume_lay       = $row['lay_volume_draw'];
                                $featured         = $row['featured'];
                            }
                            
                            ######cancel
                            Bets::cancel($market_betbtc, 3);
                            sleep(1);
                            
                          
      
                            if ($lay_price_draw != null and $back_price_draw != null) {
                                $back_temp = ($odd_lay * (1 + $commission));
                                $back      = OddsVolumes::oddsback($back_temp);
                                $lay_temp  = ($odd_back * (1 - $commission));
                                $lay       = OddsVolumes::oddslay($lay_temp);
                                $volume    = OddsVolumes::volumes($odd_back, $volume_back, $volume_lay, $featured, $selection_betbtc);
                                
                                Bets::betting($market_betbtc, $selection_betbtc, $back[0], $volume[0], 'back');
                                sleep(0.2);
                                
                                Bets::betting($market_betbtc, $selection_betbtc, $back[1], $volume[1], 'back');
                                sleep(0.2);

                                Bets::betting($market_betbtc, $selection_betbtc, $back[2], $volume[2], 'back');
                                sleep(0.2);
                                
                                
                                if ($back_price_draw > 5) {
                                    Bets::betting($market_betbtc, $selection_betbtc, ((round(mt_rand(3, 5) / 0.1)) * 0.1), $volume[3], 'lay');
                                    sleep(0.2);
                                    
                                    Bets::betting($market_betbtc, $selection_betbtc, ((round(mt_rand(3, 5) / 0.1)) * 0.1), $volume[4], 'lay');
                                    sleep(0.2);
                                    
                                    Bets::betting($market_betbtc, $selection_betbtc, ((round(mt_rand(3, 5) / 0.1)) * 0.1), $volume[5], 'lay');
                                    sleep(0.2);
                                    
                                    
                                } else {
                                    Bets::betting($market_betbtc, $selection_betbtc, $lay[0], $volume[3], 'lay');
                                    sleep(0.2);

                                    Bets::betting($market_betbtc, $selection_betbtc, $lay[1], $volume[4], 'lay');
                                    sleep(0.2);

                                    Bets::betting($market_betbtc, $selection_betbtc, $lay[2], $volume[5], 'lay');
                                    sleep(0.2);
                                    
                                    
                                }
                            }
                        }
                        
                    } elseif ($row['numberOfRunners'] == 2) {

                        $sql    = "SELECT sport FROM Markets WHERE betfair = '$market_id' ";
                        $result = $conn->query($sql);
                        
                                           
                        while ($sport = $result->fetch_assoc()) {                          
                            $sports   = $sport['sport'];                           
                        }

                        if ($sports == 4) {


                        ##HOME-TEAM
                        $lay_price_home   = isset($row['runners'][0]['ex']['availableToLay'][0]['price']) ? $row['runners'][0]['ex']['availableToLay'][0]['price'] : null;
                        $back_price_home  = isset($row['runners'][0]['ex']['availableToBack'][0]['price']) ? $row['runners'][0]['ex']['availableToBack'][0]['price'] : null;
                        $lay_volume_home  = isset($row['runners'][0]['ex']['availableToLay'][0]['size']) ? $row['runners'][0]['ex']['availableToLay'][0]['size'] : null;
                        $back_volume_home = isset($row['runners'][0]['ex']['availableToBack'][0]['size']) ? $row['runners'][0]['ex']['availableToBack'][0]['size'] : null;
                        
                        ##AWAY-TEAM
                        $lay_price_away   = isset($row['runners'][1]['ex']['availableToLay'][0]['price']) ? $row['runners'][1]['ex']['availableToLay'][0]['price'] : null;
                        $back_price_away  = isset($row['runners'][1]['ex']['availableToBack'][0]['price']) ? $row['runners'][1]['ex']['availableToBack'][0]['price'] : null;
                        $lay_volume_away  = isset($row['runners'][1]['ex']['availableToLay'][0]['size']) ? $row['runners'][1]['ex']['availableToLay'][0]['size'] : null;
                        $back_volume_away = isset($row['runners'][1]['ex']['availableToBack'][0]['size']) ? $row['runners'][1]['ex']['availableToBack'][0]['size'] : null;

                        } else {                  
                        
                        
                        
                        
                        ##HOME-TEAM
                        $lay_price_home   = isset($row['runners'][1]['ex']['availableToLay'][0]['price']) ? $row['runners'][1]['ex']['availableToLay'][0]['price'] : null;
                        $back_price_home  = isset($row['runners'][1]['ex']['availableToBack'][0]['price']) ? $row['runners'][1]['ex']['availableToBack'][0]['price'] : null;
                        $lay_volume_home  = isset($row['runners'][1]['ex']['availableToLay'][0]['size']) ? $row['runners'][1]['ex']['availableToLay'][0]['size'] : null;
                        $back_volume_home = isset($row['runners'][1]['ex']['availableToBack'][0]['size']) ? $row['runners'][1]['ex']['availableToBack'][0]['size'] : null;
                        
                        ##AWAY-TEAM
                        $lay_price_away   = isset($row['runners'][0]['ex']['availableToLay'][0]['price']) ? $row['runners'][0]['ex']['availableToLay'][0]['price'] : null;
                        $back_price_away  = isset($row['runners'][0]['ex']['availableToBack'][0]['price']) ? $row['runners'][0]['ex']['availableToBack'][0]['price'] : null;
                        $lay_volume_away  = isset($row['runners'][0]['ex']['availableToLay'][0]['size']) ? $row['runners'][0]['ex']['availableToLay'][0]['size'] : null;
                        $back_volume_away = isset($row['runners'][0]['ex']['availableToBack'][0]['size']) ? $row['runners'][0]['ex']['availableToBack'][0]['size'] : null;

                        }
                        
                        
                        
                        
                        // #EDIT TIME AND VOLUMES DB
                        
                        $update_time = date("Y-m-d H:i:s");
                        
                        
                        $conn->query("UPDATE Markets SET lay_volume_home='" . $lay_volume_home . "', lay_volume_away='" . $lay_volume_away . "', back_volume_home='" . $back_volume_home . "', back_volume_away='" . $back_volume_away . "',updated_at='" . $update_time . "' WHERE betfair='" . $market_id . "'");
                        
                        
                        
                        
                        // #EDITAR PRICES AND REMOVE / PLACE BETS
                        
                        
                        ###### TEAM HOME
                        
                        $conn->query("UPDATE Markets SET back_price_home='" . $back_price_home . "', lay_price_home='" . $lay_price_home . "' WHERE betfair='" . $market_id . "'");
                        $affected = $conn->affected_rows;
                        
                        
                        if ($affected > 0) {
                            $sql    = "SELECT betfair, betbtc, home_betbtc, back_price_home, lay_price_home, back_volume_home, lay_volume_home, featured FROM Markets WHERE betfair = '$market_id' ";
                            $result = $conn->query($sql);
                            
                            
                            
                            while ($row = $result->fetch_assoc()) {
                                
                                $market_betbtc   = $row['betbtc'];
                                $selection_betbtc = $row['home_betbtc'];
                                $odd_back         = $row['back_price_home'];
                                $odd_lay          = $row['lay_price_home'];
                                $volume_back      = $row['back_volume_home'];
                                $volume_lay       = $row['lay_volume_home'];
                                $featured         = $row['featured'];
                                
                            }
                            
                            
                            ######cancel
                            Bets::cancel($market_betbtc, 1);
                            sleep(1);
                        
                            
                            if ($lay_price_home != null and $back_price_home != null) {
                                $back_temp = ($odd_lay * (1 + $commission));
                                $back      = OddsVolumes::oddsback($back_temp);
                                $lay_temp  = ($odd_back * (1 - $commission));
                                $lay       = OddsVolumes::oddslay($lay_temp);
                                $volume    = OddsVolumes::volumes($odd_back, $volume_back, $volume_lay, $featured, $selection_betbtc);
                                
                                Bets::betting($market_betbtc, $selection_betbtc, $back[0], $volume[0], 'back');
                                sleep(0.2);
                                
                                Bets::betting($market_betbtc, $selection_betbtc, $back[1], $volume[1], 'back');
                                sleep(0.2);

                                Bets::betting($market_betbtc, $selection_betbtc, $back[2], $volume[2], 'back');
                                sleep(0.2);
                                
                                
                                if ($back_price_home > 5) {
                                    Bets::betting($market_betbtc, $selection_betbtc, ((round(mt_rand(3, 5) / 0.1)) * 0.1), $volume[3], 'lay');
                                    sleep(0.2);
                                    
                                    Bets::betting($market_betbtc, $selection_betbtc, ((round(mt_rand(3, 5) / 0.1)) * 0.1), $volume[4], 'lay');
                                    sleep(0.2);
                                    
                                    Bets::betting($market_betbtc, $selection_betbtc, ((round(mt_rand(3, 5) / 0.1)) * 0.1), $volume[5], 'lay');
                                    sleep(0.2);
                                    
                                    
                                } else {
                                    Bets::betting($market_betbtc, $selection_betbtc, $lay[0], $volume[3], 'lay');
                                    sleep(0.2);

                                    Bets::betting($market_betbtc, $selection_betbtc, $lay[1], $volume[4], 'lay');
                                    sleep(0.2);

                                    Bets::betting($market_betbtc, $selection_betbtc, $lay[2], $volume[5], 'lay');
                                    sleep(0.2);
                                    
                                    
                                }
                            }
                            
                        }
                        
                        
                        
                        
                        
                        
                        ###### TEAM AWAY
                        
                        $conn->query("UPDATE Markets SET back_price_away='" . $back_price_away . "', lay_price_away='" . $lay_price_away . "' WHERE betfair='" . $market_id . "'");
                        $affected = $conn->affected_rows;
                        
                        
                        if ($affected > 0) {
                            $sql    = "SELECT betfair, betbtc, away_betbtc, back_price_away, lay_price_away, back_volume_away, lay_volume_away, featured FROM Markets WHERE betfair = '$market_id' ";
                            $result = $conn->query($sql);
                            
                            
                            
                            while ($row = $result->fetch_assoc()) {
                                
                                $market_betbtc   = $row['betbtc'];
                                $selection_betbtc = $row['away_betbtc'];
                                $odd_back         = $row['back_price_away'];
                                $odd_lay          = $row['lay_price_away'];
                                $volume_back      = $row['back_volume_away'];
                                $volume_lay       = $row['lay_volume_away'];
                                $featured         = $row['featured'];
                                
                            }
                            
                            ######cancel
                            Bets::cancel($market_betbtc, 2);
                            sleep(1);
                          
                            
                            if ($lay_price_away != null and $back_price_away != null) {
                                $back_temp = ($odd_lay * (1 + $commission));
                                $back      = OddsVolumes::oddsback($back_temp);
                                $lay_temp  = ($odd_back * (1 - $commission));
                                $lay       = OddsVolumes::oddslay($lay_temp);
                                $volume    = OddsVolumes::volumes($odd_back, $volume_back, $volume_lay, $featured, $selection_betbtc);
                                
                                Bets::betting($market_betbtc, $selection_betbtc, $back[0], $volume[0], 'back');
                                sleep(0.2);
                                
                                Bets::betting($market_betbtc, $selection_betbtc, $back[1], $volume[1], 'back');
                                sleep(0.2);

                                Bets::betting($market_betbtc, $selection_betbtc, $back[2], $volume[2], 'back');
                                sleep(0.2);
                                
                                
                                if ($back_price_away > 5) {
                                    Bets::betting($market_betbtc, $selection_betbtc, ((round(mt_rand(3, 5) / 0.1)) * 0.1), $volume[3], 'lay');
                                    sleep(0.2);
                                    
                                    Bets::betting($market_betbtc, $selection_betbtc, ((round(mt_rand(3, 5) / 0.1)) * 0.1), $volume[4], 'lay');
                                    sleep(0.2);
                                    
                                    Bets::betting($market_betbtc, $selection_betbtc, ((round(mt_rand(3, 5) / 0.1)) * 0.1), $volume[5], 'lay');
                                    sleep(0.2);
                                    
                                    
                                } else {
                                    Bets::betting($market_betbtc, $selection_betbtc, $lay[0], $volume[3], 'lay');
                                    sleep(0.2);

                                    Bets::betting($market_betbtc, $selection_betbtc, $lay[1], $volume[4], 'lay');
                                    sleep(0.2);

                                    Bets::betting($market_betbtc, $selection_betbtc, $lay[2], $volume[5], 'lay');
                                    sleep(0.2);
                                    
                                    
                                }
                            }
                        }
                    }
                    
                }
            }
        }
            print_r('SLEEPING FOR 5 MINUTES - ');
            print_r(date('H:i:s'));
            sleep(300);

    } catch( ConnectException $ex ) {
        switch ( $ex->getMessage() ) {
            case '7': break;
            case '56': break;// to be verified
                // handle your exception in the way you want,
                // maybe with a graceful fallback         
        }
    }     
}
