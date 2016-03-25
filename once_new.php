<?php

use Betfair\BetfairFactory;
use Betfair\Model\MarketFilter;
use Betfair\Model\PriceProjection;

use GuzzleHttp\Ring\Exception\ConnectException;

date_default_timezone_set("UTC");


require '/root/beta/vendor/autoload.php';



###### CONFIGURATIONS ########

//BETFAIR CREDENTIALS
$betfair_username = '';
$betfair_password = '';
$betfair_appkey = '';

//BETBTC API TOKEN
$token   = "";


//LOCAL DB CONFIG
$servername = "localhost";
$username   = "";
$password   = "";
$dbname     = "";

// COMMISSIONS IN PERCENTAGE
$commission = 0.04;
$commission_live = 0.4;

// IMPORTING BETBTC EVENTS
$betbtc_events = "https://www.betbtc.co/api/event/?sport=2" ##SOCCER IN THIS CASE

##############################






#      ██╗███╗   ███╗██████╗  ██████╗ ██████╗ ████████╗    ██████╗ ███████╗████████╗██████╗ ████████╗ ██████╗
#      ██║████╗ ████║██╔══██╗██╔═══██╗██╔══██╗╚══██╔══╝    ██╔══██╗██╔════╝╚══██╔══╝██╔══██╗╚══██╔══╝██╔════╝
#      ██║██╔████╔██║██████╔╝██║   ██║██████╔╝   ██║       ██████╔╝█████╗     ██║   ██████╔╝   ██║   ██║     
#      ██║██║╚██╔╝██║██╔═══╝ ██║   ██║██╔══██╗   ██║       ██╔══██╗██╔══╝     ██║   ██╔══██╗   ██║   ██║     
#      ██║██║ ╚═╝ ██║██║     ╚██████╔╝██║  ██║   ██║       ██████╔╝███████╗   ██║   ██████╔╝   ██║   ╚██████╗
#      ╚═╝╚═╝     ╚═╝╚═╝      ╚═════╝ ╚═╝  ╚═╝   ╚═╝       ╚═════╝ ╚══════╝   ╚═╝   ╚═════╝    ╚═╝    ╚═════╝
                                                                                                      
/** 
 * Logging class:
 * - contains lfile, lwrite and lclose public methods
 * - lfile sets path and name of log file
 * - lwrite writes message to the log file (and implicitly opens log file)
 * - lclose closes log file
 * - first call of lwrite method will open log file implicitly
 * - message is written with the following format: [d/M/Y:H:i:s] (script name) message
 */

class Logging {
    // declare log file and file pointer as private properties
    private $log_file, $fp;
    // set log file (path and name)
    public function lfile($path) {
        $this->log_file = $path;
    }
    // write message to the log file
    public function lwrite($message) {
        // if file pointer doesn't exist, then open log file
        if (!is_resource($this->fp)) {
            $this->lopen();
        }
        // define script name
        $script_name = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
        // define current time and suppress E_WARNING if using the system TZ settings
        // (don't forget to set the INI setting date.timezone)
        $time = @date('[d/M/Y:H:i:s]');
        // write current time, script name and message to the log file
        fwrite($this->fp, "$time ($script_name) $message" . PHP_EOL);
    }
    // close log file (it's always a good idea to close a file when you're done with it)
    public function lclose() {
        fclose($this->fp);
    }
    // open log file (private method)
    private function lopen() {
        // in case of Windows set default log file
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $log_file_default = 'c:/php/logfile.txt';
        }
        // set default log file for Linux and other systems
        else {
            $log_file_default = '/tmp/logfile.txt';
        }
        // define log file from lfile method or use previously set default
        $lfile = $this->log_file ? $this->log_file : $log_file_default;
        // open log file for writing only and place file pointer at the end of the file
        // (if the file does not exist, try to create it)
        $this->fp = fopen($lfile, 'a') or exit("Can't open $lfile!");
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


class Lookup
{
    
    public function httpGet($url)
    {
        $headers = array(
            "Content-Type: application/json",
            "Authorization: Token token=" . $token
        );
        $ch      = curl_init();
        
        // curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // man-in-the-middle defense by verifying ssl cert.
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // man-in-the-middle defense by verifying ssl cert.;

        // curl_setopt($ch, CURLOPT_SSLVERSION, 3);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,2); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); //timeout in seconds
        //  curl_setopt($ch,CURLOPT_HEADER, false); 
        
        $output = curl_exec($ch);
        $dec    = json_decode($output, true);

        // print_r($dec);
        
        curl_close($ch);
        return $dec;
    }
    
}


class Bets
{
    
    
    public function cancel($market, $selection)
    {
        
        $data        = array(
            "selection" => $selection
        );

        $data_all        = array(
            "selection" => 'all'
        );

        if ($selection == null) {
            $data_string = json_encode($data_all);  
        } else {
            $data_string = json_encode($data);
        }
        
        
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
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,2); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); //timeout in seconds
        $result   = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return $httpCode;
    }
    
    

    public function httpGet($url)
    {
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
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,2); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); //timeout in seconds
        
        //  curl_setopt($ch,CURLOPT_HEADER, false); 
        
        $output = curl_exec($ch);
        $dec    = json_decode($output, true);
        
        curl_close($ch);
        return $dec;
    }
    
    
    
    
    public function betting($market, $selection, $odd, $stake, $type)
    {
        
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
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,2); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); //timeout in seconds
        
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
        } elseif ($back_temp <= 1000) {
            $back_bet  = (round($back_temp / 10) * 10);
            $back_bet2 = (round($back_temp / 10) * 10 + 10);
            $back_bet3 = (round($back_temp / 10) * 10 + 20);
        } elseif ($back_temp > 1000) { 
            $back_bet  = 1000;
            $back_bet2 = 1000;
            $back_bet3 = 1000;
        }
        
        return array(
            $back_bet,
            $back_bet2,
            $back_bet3
        );
        
    }
    

    public function oddslay($lay_temp)
    {
        
        // if (($lay_temp > 0.94 && $lay_temp < 1.1)) {
        //     $lay_bet  = 1.01;
        //     $lay_bet2 = 1.01;
        //     $lay_bet3 = 1.01;
            
        // } elseif ($lay_temp < 2) {
        if ($lay_temp < 2) {
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
        } elseif ($lay_temp <= 1000) {
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
    
    

    public function volumes($odd_lay, $volume_back, $volume_lay) {


        if ($odd_lay <= 3 && $volume_back > 250 && $volume_lay > 250) {

            $stake_back  = mt_rand(300 * 100, 1100 * 100) / 100000;
            $stake_back2 = mt_rand(10 * 100, 45 * 100) / 100000;
            $stake_back3 = mt_rand(10 * 100, 45 * 100) / 100000;

            $stake_lay  = mt_rand(300 * 100, 1100 * 100) / 100000;
            $stake_lay2 = mt_rand(10 * 100, 45 * 100) / 100000;
            $stake_lay3 = mt_rand(10 * 100, 45 * 100) / 100000;

        } elseif ($odd_lay <= 3 && $volume_back > 250 && $volume_lay <= 250) {

            $stake_back  = mt_rand(300 * 100, 1100 * 100) / 100000;
            $stake_back2 = mt_rand(10 * 100, 45 * 100) / 100000;
            $stake_back3 = mt_rand(10 * 100, 45 * 100) / 100000;

            $stake_lay  = mt_rand(20 * 100, 100 * 100) / 100000;
            $stake_lay2 = mt_rand(10 * 100, 45 * 100) / 100000;
            $stake_lay3 = mt_rand(10 * 100, 45 * 100) / 100000;

        } elseif ($odd_lay <= 3 && $volume_back <= 250 && $volume_lay > 250) {
                
            $stake_back  = mt_rand(20 * 100, 100 * 100) / 100000;
            $stake_back2 = mt_rand(10 * 100, 45 * 100) / 100000;
            $stake_back3 = mt_rand(10 * 100, 45 * 100) / 100000;

            $stake_lay  = mt_rand(300 * 100, 1100 * 100) / 100000;
            $stake_lay2 = mt_rand(10 * 100, 45 * 100) / 100000;
            $stake_lay3 = mt_rand(10 * 100, 45 * 100) / 100000;

        } else {

            $stake_back  = mt_rand(10 * 100, 100 * 100) / 100000;
            $stake_back2 = mt_rand(1 * 100, 30 * 100) / 100000;
            $stake_back3 = mt_rand(1 * 100, 30 * 100) / 100000;

            $stake_lay  = mt_rand(10 * 100, 100 * 100) / 100000;
            $stake_lay2 = mt_rand(1 * 100, 30 * 100) / 100000;
            $stake_lay3 = mt_rand(1 * 100, 30 * 100) / 100000;
        }
        
        
        return array(
            $stake_back,
            $stake_back2,
            $stake_back3,
            $stake_lay,
            $stake_lay2,
            $stake_lay3
        );
        
    }


    public function volumes_live($odd_lay, $volume_back, $volume_lay, $featured, $selection_betbtc)
    {
        #  $volume_back = $volume_back * 0.003;
        #  $volume_lay = $volume_lay * 0.003;
        if ($volume_back > 100 && $volume_lay > 100) {

            if ($selection_betbtc == 'DRAW') {

                $stake_back  = mt_rand(1 * 100, 2 * 100) / 100000;
                $stake_back2 = mt_rand(1 * 100, 3 * 100) / 100000;
                $stake_back3 = mt_rand(1 * 100, 3 * 100) / 100000;

                $stake_lay  = mt_rand(1 * 100, 2 * 100) / 100000;
                $stake_lay2 = mt_rand(1 * 100, 3 * 100) / 100000;
                $stake_lay3 = mt_rand(1 * 100, 3 * 100) / 100000;             


            } else {

                
                if ($odd_lay <= 4) {

                    // $stake_back  = mt_rand(100 * 100, 650 * 100) / 100000;
                    $stake_back  = mt_rand(1 * 100, 2 * 100) / 100000;
                    $stake_back2 = mt_rand(10 * 100, 45 * 100) / 100000;
                    $stake_back3 = mt_rand(10 * 100, 45 * 100) / 100000;

                } else {

                    $stake_back  = mt_rand(1 * 100, 2 * 100) / 100000;
                    $stake_back2 = mt_rand(1 * 100, 30 * 100) / 100000;
                    $stake_back3 = mt_rand(1 * 100, 30 * 100) / 100000;
                }
                
            
                if ($odd_lay <= 4) {


                    $stake_lay  = mt_rand(1 * 100, 2 * 100) / 100000;
                    $stake_lay2 = mt_rand(10 * 100, 45 * 100) / 100000;
                    $stake_lay3 = mt_rand(10 * 100, 45 * 100) / 100000;

                } else {

                    $stake_lay  = mt_rand(1 * 100, 2 * 100) / 100000;
                    $stake_lay2 = mt_rand(1 * 100, 30 * 100) / 100000;
                    $stake_lay3 = mt_rand(1 * 100, 30 * 100) / 100000;
                }
            }



        } else {

            if ($selection_betbtc == 'DRAW') {

                $stake_back  = mt_rand(1 * 100, 2 * 100) / 100000;
                $stake_back2 = mt_rand(1 * 100, 3 * 100) / 100000;
                $stake_back3 = mt_rand(1 * 100, 3 * 100) / 100000;

                $stake_lay  = mt_rand(1 * 100, 2 * 100) / 100000;
                $stake_lay2 = mt_rand(1 * 100, 3 * 100) / 100000;
                $stake_lay3 = mt_rand(1 * 100, 3 * 100) / 100000;             


            } else {


                $stake_back  = mt_rand(1 * 100, 2 * 100) / 100000;
                $stake_back2 = mt_rand(1 * 100, 4 * 100) / 100000;
                $stake_back3 = mt_rand(1 * 100, 4 * 100) / 100000;

                $stake_lay  = mt_rand(1 * 100, 2 * 100) / 100000;
                $stake_lay2 = mt_rand(1 * 100, 4 * 100) / 100000;
                $stake_lay3 = mt_rand(1 * 100, 4 * 100) / 100000;

            }
        }
        
        
        return array(
            $stake_back,
            $stake_back2,
            $stake_back3,
            $stake_lay,
            $stake_lay2,
            $stake_lay3
        );
        
    }    
}
