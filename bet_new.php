<?php



try {
    
        
    echo "Begin NORMAL Bot";
    
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    
    $result = $conn->query("SELECT betfair, betbtc FROM Markets WHERE status = 1 AND almost != 1 AND live != 1");

    
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
    
    $block = (array_chunk($GLOBALS, 3));
        
    ##EACH BLOCK
    foreach ($block as $keyz=>$single) {

        echo("STARTING BLOCK" . $keyz . "OF" . count($block) . ".");

        $ar = array();
        
        foreach ($single as $key => $v) {
            # code...
            array_push($ar, $v['betfair']);
        }
        
        
        
        sleep(0.4);
        $marketbooks = SearchMarketBookExample::searchWithExBestOffer($betfair_appkey, $betfair_username, $betfair_password, $ar);
        
        
        ####EACH EVENT
        foreach ($marketbooks as $marketbook) {

            $status    = $marketbook['inplay'];
            $closed    = $marketbook['status'];
            $market_id = $marketbook['marketId'];
            $betfair_runners = $marketbook['runners'];
            
            $sql    = "SELECT * FROM Markets WHERE betfair = '$market_id' ";
            $result = $conn->query($sql);
            
            
            while ($datas = $result->fetch_assoc()) {
                $selections = $datas['selections'];
                $betbtc_id = $datas['betbtc'];
                $event_date = $datas['event_date'];

            }


            $selections = json_decode($selections, true);

            $date = date("Y-m-d H:i:s");

            $timenow = strtotime(date('Y-m-d H:i:s'));
            
            $plus20now = strtotime('+20 minutes', $timenow);
            $plus60now = strtotime('+60 minutes', $timenow);
            $plus90now = strtotime('+90 minutes', $timenow);
            
            $timeevent         = strtotime($event_date);

            
            


            if ((float)$status == 1 || $timeevent <= $plus90now) {

                echo ("CHANGED TO ALMOST (live) MARKET BOT");                
                
                $conn->query("UPDATE Markets SET almost = 1 WHERE betfair = '$market_id' ");            
            
            ###IF TIME_NOW -3600 is higher than Event_Time

                
            } else {

                #EACH SELECTION
                foreach ($selections as $key => $selection) {


                    $sel_id = $selection[0];
                    $sel_name = $selection[1];
                    $back = $selection['back'];
                    $lay = $selection['lay'];

                    echo ($sel_name);


                    // if (isset($sel_id) && isset($betfair_runners)) {

                    $key_betfair = array_search($sel_id, array_column($betfair_runners, 'selectionId'), true);

                    $key_betfair = (int)$key_betfair;

                        if (isset($marketbook['runners'][$key_betfair]['ex']['availableToLay'][0]['price']) && isset($marketbook['runners'][$key_betfair]['ex']['availableToBack'][0]['price']) && isset($marketbook['runners'][$key_betfair]['ex']['availableToLay'][0]['size']) && isset($marketbook['runners'][$key_betfair]['ex']['availableToBack'][0]['size'])) {


                            $lay_price = $marketbook['runners'][$key_betfair]['ex']['availableToLay'][0]['price']; //+ alto
                            $back_price = $marketbook['runners'][$key_betfair]['ex']['availableToBack'][0]['price']; //+ baixo
                            $lay_volume = $marketbook['runners'][$key_betfair]['ex']['availableToLay'][0]['size'];
                            $back_volume = $marketbook['runners'][$key_betfair]['ex']['availableToBack'][0]['size'];

                            $selection_matched = $marketbook['runners'][$key_betfair]['totalMatched'];
                            $total_matched = $marketbook['totalMatched'];
                            $total_unmatched = $marketbook['totalAvailable'];


                            $result = $conn->query("SELECT selections FROM Markets WHERE betfair = '$market_id'");
                            
                            while ($db = $result->fetch_assoc()) {

                                $json = json_decode($db['selections'], true);


                                $back_database = $json[$key]['back'][0];
                                $lay_database  = $json[$key]['lay'][0];
                            }

                            if ($back_price != $lay_database || $lay_price != $back_database) {


                                $selections[$key]['back'][0] = $lay_price;
                                $selections[$key]['lay'][0] = $back_price;

                                $encode = json_encode($selections);

                                echo('date is');
                                print_r($date);

                                $conn->query("UPDATE Markets SET selections = '$encode' WHERE betfair = '$market_id' ");


                                 ####cancel
                                $response = [];
                                while ($response != '200') {
                                    $cancel = Bets::cancel($betbtc_id, $sel_name);
                                    $response = json_decode($cancel);
                                    // echo('**');
                                    // print_r($response);
                                    // echo('**');
                                    //sleep(0.2);
                                }

                                $conn->query("INSERT INTO log (action, market, selection, time) VALUES ('cancel', '$betbtc_id', '$sel_name', '$date')");





                                $back_temp = ($lay_price * (1 + $commission));
                                $back      = OddsVolumes::oddsback($back_temp);
                                $lay_temp  = ($back_price * (1 - $commission));
                                $lay       = OddsVolumes::oddslay($lay_temp);
                                $volume    = OddsVolumes::volumes($lay_temp, $back_volume, $lay_volume);
                                
                                
                                if ($total_matched > 10000 && $total_unmatched > 10000 && (float)$lay[0] > 1.3) {

                                    $response = [];
                                    while ($response != '200') {
                                        $bet = Bets::betting($betbtc_id, $sel_name, $back[0], $volume[0], 'back');
                                        $a = rand(0, 100);
                                        $b = rand(0, 100);
                                        if ($a < 75 && count($selections) <= 3) {
                                            Bets::betting($betbtc_id, $sel_name, $back[1], $volume[1], 'back');
                                        }
                                        if ($b < 75 && count($selections) <= 3) {
                                            Bets::betting($betbtc_id, $sel_name, $back[2], $volume[2], 'back');
                                        }

                                        $response = json_decode($bet);
                                    }

                                    $conn->query("INSERT INTO log (action, market, selection, type, time) VALUES ('placebet', '$betbtc_id', '$sel_name', 'back', '$date')");


                                }else {

                                    $response = [];
                                    while ($response != '200') {
                                        $bet = Bets::betting($betbtc_id, $sel_name, $back[0], ((round(mt_rand(1, 50) / 0.1)) * 0.0001), 'back');
                                        $response = json_decode($bet);
                                    }


                                    $conn->query("INSERT INTO log (action, market, selection, type, time) VALUES ('placebet', '$betbtc_id', '$sel_name', 'back', '$date')");

                                }
                                
                                
                                
                                if ($back_price > 5) {

                                    if ($back_price < 30) {
                                        $response = [];
                                        while ($response != '200') {                                          
                                            $bet = Bets::betting($betbtc_id, $sel_name, $lay[0], ((round(mt_rand(1, 10) / 0.1)) * 0.0001), 'lay');
                                            $response = json_decode($bet);
                                        }

                                        $conn->query("INSERT INTO log (action, market, selection, type, time) VALUES ('placebet', '$betbtc_id', '$sel_name', 'lay', '$date')");                                    
                                    }
                                    // if ($total_matched > 2000 && $total_unmatched > 2000) {

                                    //     $response = [];
                                    //     while ($response != '200') {                                        
                                    //         $bet = Bets::betting($betbtc_id, $sel_name, ((round(mt_rand(3, 5) / 0.1)) * 0.1), $volume[3], 'lay');
                                    //         $response = json_decode($bet);
                                    //     }

                                    //     $conn->query("INSERT INTO log (action, market, selection, type, time) VALUES ('placebet', '$betbtc_id', '$sel_name', 'lay', '$date')");
                                    
                                    // } else {

                                    //     $response = [];
                                    //     while ($response != '200') {                                          
                                    //         $bet = Bets::betting($betbtc_id, $sel_name, ((round(mt_rand(3, 5) / 0.1)) * 0.1), ((round(mt_rand(1, 10) / 0.1)) * 0.0001), 'lay');
                                    //         $response = json_decode($bet);
                                    //     }

                                    //     $conn->query("INSERT INTO log (action, market, selection, type, time) VALUES ('placebet', '$betbtc_id', '$sel_name', 'lay', '$date')");
                                    // }
                                    
                                    
                                } else {

                                    if ($total_matched > 2000 && $total_unmatched > 2000) {
                                        $response = [];
                                        while ($response != '200') {    
                                            $bet = Bets::betting($betbtc_id, $sel_name, $lay[0], $volume[3], 'lay');
                                            $a = rand(0, 100);
                                            $b = rand(0, 100);
                                            if ($a < 75 && count($selections) <= 3) {
                                                Bets::betting($betbtc_id, $sel_name, $lay[1], $volume[4], 'lay');
                                            }
                                            if ($b < 75 && count($selections) <= 3) {
                                                Bets::betting($betbtc_id, $sel_name, $lay[2], $volume[5], 'lay');
                                            }
                                            $response = json_decode($bet);
                                        }

                                        $conn->query("INSERT INTO log (action, market, selection, type, time) VALUES ('placebet', '$betbtc_id', '$sel_name', 'lay', '$date')");
                                    } else {

                                        $response = [];
                                        while ($response != '200') {                                          
                                            $bet = Bets::betting($betbtc_id, $sel_name, $lay[0], ((round(mt_rand(1, 50) / 0.1)) * 0.0001), 'lay');
                                            $response = json_decode($bet);
                                        }

                                        $conn->query("INSERT INTO log (action, market, selection, type, time) VALUES ('placebet', '$betbtc_id', '$sel_name', 'lay', '$date')");
                                    
                                    }                               
                                    
                                    
                                }
                         
                    
                                
                            } else {

                                echo('NO BETS TO PLACE');

                            }
                        }
                }
            }
        }
        
    }
    
    
    $conn->close();
        
    
}

catch (ConnectException $ex) {
    break;
}
    
catch (BetfairLoginException $ex) {
    break;
}
    
catch (RingException $ex) {
    break;
}
    
catch (GuzzleHttp\Ring\Exception\RingException $ex) {
    break;
}
    
catch (GuzzleHttp\Exception\ConnectException $ex) {
    break;
}
    
catch (Exception $ex) {

}
