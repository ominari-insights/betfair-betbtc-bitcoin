<?php

    try{

        $nowtime = strtotime(date('Y-m-d H:i:s'));

        echo "Begin Importing Bot";
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }


        ## RETRIEVES FROM BETBTC API AND STORES THE PAIR OF MARKETS TO BE WORKED ON A TABLE


        $y = Lookup::httpGet("https://www.betbtc.co/api/event/");



        #$group_markets = array_merge($x);



        foreach ($y as $x) {
            
            $id  = $x[0];
            $name = $x[1];
            $league = $x[2];
            $date = $x[3];
            $sport = $x[4];
            $betfair_mkt = $x[5];
            $selections = $x[6];
            $mkt_name = $x[7];

            $sel = [];

            foreach($selections as $item)
                    {
                        $item = [$item['id'], $item['name'], 'back' => [], 'lay' => [] ];
                        array_push($sel, $item);
                    }

            $sel = json_encode($sel);

            print_r($sel);

            $resulta = $conn->query("SELECT * FROM Markets WHERE betbtc = ' $id' ");

            if ($resulta->num_rows > 0) {

                // $sql = "UPDATE Markets SET event_date= '$date' WHERE betbtc = '$id'";   
                // $conn->query($sql);

            }else{
            
                $sql = "INSERT INTO Markets (name, mkt_name, sport, betbtc, status, event_date, betfair, selections, created_at) VALUES ('$name', '$mkt_name', '$sport', '$id', 1, '$date','$betfair_mkt','$sel', '$nowtime')";    
                $conn->query($sql);

            } 
        }

        echo "BetBTC Markets Inserted";

        mysqli_close($conn);



    }

    catch( ConnectException $ex ) {
        break;
    }     

    catch( BetfairLoginException $ex ) {
        break;
    } 

    catch( RingException $ex ) {
        break;
    } 

    catch( GuzzleHttp\Ring\Exception\RingException $ex ) {
        break;
    } 

    catch( GuzzleHttp\Exception\ConnectException $ex ) {
        break;
    } 
