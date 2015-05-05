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


$servername = "127.0.0.1";
$username   = "root";
$password   = "";
$dbname     = "api";



#      ██╗███╗   ███╗██████╗  ██████╗ ██████╗ ████████╗    ██████╗ ███████╗████████╗██████╗ ████████╗ ██████╗
#      ██║████╗ ████║██╔══██╗██╔═══██╗██╔══██╗╚══██╔══╝    ██╔══██╗██╔════╝╚══██╔══╝██╔══██╗╚══██╔══╝██╔════╝
#      ██║██╔████╔██║██████╔╝██║   ██║██████╔╝   ██║       ██████╔╝█████╗     ██║   ██████╔╝   ██║   ██║     
#      ██║██║╚██╔╝██║██╔═══╝ ██║   ██║██╔══██╗   ██║       ██╔══██╗██╔══╝     ██║   ██╔══██╗   ██║   ██║     
#      ██║██║ ╚═╝ ██║██║     ╚██████╔╝██║  ██║   ██║       ██████╔╝███████╗   ██║   ██████╔╝   ██║   ╚██████╗
#      ╚═╝╚═╝     ╚═╝╚═╝      ╚═════╝ ╚═╝  ╚═╝   ╚═╝       ╚═════╝ ╚══════╝   ╚═╝   ╚═════╝    ╚═╝    ╚═════╝
                                                                                                      



class Lookup
{
    
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
    
}




class Rename
{
    
    
    public function change($appKey, $username, $pwd, $torename)
    {
        
        $league_matches = array(
            //Betfair => BetBTC
            
            ##FUTEBOL
            "228" => "527", //Champions League
            "2005" => "741", //Europa League
            "59" => "643", //Bundesleague
            "31" => "597", //Premier League
            "30558" => "525", //FA CUP
            "117" => "614", //Spanish League
            "55" => "641", // French League
            "99" => "673", //Portugal
            "81" => "605", // Seria A
            "71" => "666", //Eredivise
            "101" => "613", //Russia
            "1874" => "766", //Copa Italia
            "4527196" => "733", //EURO 2016
            ##BASKET
            "5420499" => "503",
            #NHL
            "5447921" => "1436"
            
        );
        
        $team_matches = array(
            //Betfair => BetBTC
            
            #FOOTBALL
            "W Bremen" => "Werder Bremen",
            "Sp Lisbon" => "Sporting",
            "Dinamo Kiev" => "Dyn. Kiev",
            "Dinamo Moscow" => "Dynamo Moscow",
            "Roma" => "AS Roma",
            "Man City" => "Manchester City",
            "Hamburg" => "Hamburger SV",
            "Paris St-G" => "Paris SG",
            "Stuttgart" => "VfB Stuttgart",
            "Stoke" => "Stoke City",
            "Newcastle" => "Newcastle Utd",
            "Granada" => "Granada CF",
            "Deportivo" => "Dep. La Coruna",
            "Man Utd" => "Manchester United",
            "C Palace" => "Crystal Palace",
            "Porto" => "FC Porto",
            "B Munich" => "Bayern Munich",
            "Mgladbach" => "B. Monchengladbach",
            "Rostov" => "FK Rostov",
            "Torpedo Moscow" => "T. Moscow",
            "Mordovia Saransk" => "M. Saransk",
            "Leverkusen" => "Bayer Leverkusen",
            "Sociedad" => "Real Sociedad",
            "Hull" => "Hull City",

            
            #BASKET
            "Chicago" => "Chicago Bulls",
            "Memphis" => "Memphis Grizzlies",
            "Detroit" => "Detroit Pistons",
            "San Antonio" => "San Antonio Spurs",
            "New York" => "New York Knicks",
            "Milwaukee" => "Milwaukee Bucks",
            "New Orleans" => "New Orleans Pelicans",
            "Orlando" => "Orlando Magic",
            "Houston" => "Houston Rockets",
            "Charlotte" => "Charlotte Hornets",
            "LA Clippers" => "Los Angeles Clippers",
            "LA Lakers" => "Los Angeles Lakers",
            "Brooklyn" => "Brooklyn Nets",
            "Cleveland" => "Cleveland Cavaliers",
            "Philadelphia" => "Philadelphia 76ers",
            "Minnesota" => "Minnesota Timberwolves",
            "Toronto" => "Toronto Raptors",
            "Portland" => "Portland Trail Blazers",
            "Miami" => "Miami Heat",
            "Dallas" => "Dallas Mavericks",
            "Indiana" => "Indiana Pacers",
            "Utah" => "Utah Jazz",
            "Phoenix" => "Phoenix Suns",
            "Atlanta" => "Atlanta Hawks",
            "Golden State" => "Golden State Warriors",
            "Washington" => "Washington Wizards",
            "Denver" => "Denver Nuggets",
            "Sacramento" => "Sacramento Kings",
            "Oklahoma City" => "Oklahoma City Thunder",
            "Boston" => "Boston Celtics",        

            #NHL
            "Ottawa" => "Ottawa Senators",
            "Pittsburgh" => "Pittsburgh Penguins",
            "New Jersey" => "New Jersey Devils",
            "NY Rangers" => "New York Rangers",
            "Philadelphia" => "Philadelphia Flyers",
            "NY Islanders" => "New York Islanders",
            "Detroit" => "Detroit Red Wings",
            "Carolina" => "Carolina Hurricanes",
            "St Louis" => "St.Louis Blues",
            "Chicago" => "Chicago Blackhawks",
            "Winnipeg" => "Winnipeg Jets",
            "Minnesota" => "Minnesota Wild",
            "Calgary" => "Calgary Flames",
            "Arizona" => "Arizona Coyotes",
            "Colorado" => "Colorado Avalanche",
            "Nashville" => "Nashville Predators",
            "Edmonton" => "Edmonton Oilers",
            "Los Angeles" => "Los Angeles Kings",
            "Anaheim" => "Anaheim Ducks",
            "Dallas" => "Dallas Stars",
            "Washington" => "Washington Capitals",
            "Boston" => "Boston Bruins",
            "Columbus" => "Columbus Blue Jackets",
            "Toronto" => "Toronto Maple Leafs",
            "Vancouver" => "Vancouver Canucks",
            "Arizona" => "Arizona Coyotes",
            "Montreal" => "Montreal Canadiens",
            "Detroit" => "Detroit Red Wings",
            "Florida" => "Florida Panthers",
            "Boston" => "Boston Bruins",
            "Tampa Bay" => "Tampa Bay Lightning",
            "New Jersey" => "New Jersey Devils"

            ##TENIS
            // "" => "D. Istomin",
            // "" => "A. Ramos",
            // "" => "J. Janowicz",
            // "" => "F. Fognini",
            // "" => "N. Gombos",
            // "" => " D. Goffin",
            // "" => "P. Carreno-Busta",
            // "" => "S. Stakhovsky",
            // "" => "D. Kudla",
            // "" => "B. Paire",
            // "" => "B. Coric",
            // "" => "A. Dolgopolov",
            // "" => "T. Robredo",
            // "" => "A. Seppi",
            // "" => "P. Kohlschreiber",
            // "" => "M. Kukushkin",
            // "" => "S. Johnson",
            // "" => "J. Isner",
            // "" => "F. Verdasco",
            // "" => "G. Dimitrov",
            // "" => "J. Tsonga",
            // "" => "S. Querrey",
            // "" => "D. Thiem",
            // "" => "L. Pouille",
            // "" => "S. Querrey",

            
            
        );
        
        
        
        #CONVERT NAMES AND LEAGUES
        foreach ($torename as $key => $x) {
            
            
            $team = $x['team1'];
            $league = $x['league'];
            
            $result = array_search($team, $team_matches);
            if ($result !== false) {
                $team = $result;
            }
            
            $torename[$key]['team1'] = $team;
            
            $result2 = array_search($league, $league_matches);
            if ($result2 !== false) {
                $league = $result2;
            }
            
            $torename[$key]['league'] = $league;
            
        }
        
        
        
        
        $betfair      = BetfairFactory::createBetfair($appKey, $username, $pwd);
        $betfairEvent = $betfair->getBetfairMarketCatalogue();


        
        
        
        $events = array();
        
        foreach ($torename as $i => $row) {

            if ($row['sport'] == 6) {

                $from     = new DateTime("now + 1 hour");
                $to       = new DateTime("now + 4 day");
                $timeZone = new \Betfair\Model\TimeRange($from, $to);     

                $marketFilter = MarketFilter::create();
                $marketFilter
                //->setEventIds=$ids;
                    ->setTextQuery($row['team1'])->setMarketTypeCodes(["MATCH_ODDS"])->setCompetitionIds(array(
                    $row['league']))->setMarketStartTime($timeZone);
                #->setMarketSort( ["LAST_TO_START"] )
                    #->setMarketStartTime($timeZone);
                
                $betfairEvent->withMarketFilter($marketFilter);
                
                $betfair = $betfairEvent->getResults();
                
                $events[] = array(
                    'betbtc' => $row['marketid'],
                    'sport' => $row['sport'],
                    'betfair' => $betfair,
                    'visitor' => $row['team2']
                );

              } elseif ($row['sport'] == 5) {

                $from     = new DateTime("now + 1 hour");
                $to       = new DateTime("now + 2 day");
                $timeZone = new \Betfair\Model\TimeRange($from, $to);             
                
                $marketFilter = MarketFilter::create();
                $marketFilter
                //->setEventIds=$ids;
                    ->setTextQuery($row['team1'])->setMarketTypeCodes(["MATCH_ODDS"])->setCompetitionIds(array(
                    $row['league']))->setMarketStartTime($timeZone);
                #->setMarketSort( ["LAST_TO_START"] )
                
                $betfairEvent->withMarketFilter($marketFilter);
                
                $betfair = $betfairEvent->getResults();
                
                $events[] = array(
                    'betbtc' => $row['marketid'],
                    'sport' => $row['sport'],
                    'betfair' => $betfair,
                    'visitor' => $row['team2']
                );

            //TÉNIS
            } elseif ($row['sport'] == 4) {

                $from     = new DateTime("now + 1 hour");
                $to       = new DateTime("now + 1 day");
                $timeZone = new \Betfair\Model\TimeRange($from, $to);

                $teams = $row['team1'];
                $explode = explode(' ', $teams);

                $end = '';
                $begin = '';

                if(count($explode) > 0){
                    $end = array_pop($explode); // removes the last element, and returns it

                    if(count($explode) > 0){
                        $begin = implode(' ', $explode); // glue the remaining pieces back together
                    }
                }

                $marketFilter = MarketFilter::create();
                $marketFilter
                //->setEventIds=$ids;
                    ->setTextQuery($end)->setMarketTypeCodes(["MATCH_ODDS"])->setMarketStartTime($timeZone);#->setCompetitionIds(array($row['league']))
                #->setMarketSort( ["LAST_TO_START"] )
                    #->setMarketStartTime($timeZone);
                
                $betfairEvent->withMarketFilter($marketFilter);
                
                $betfair = $betfairEvent->getResults();
                
                $events[] = array(
                    'betbtc' => $row['marketid'],
                    'sport' => $row['sport'],
                    'betfair' => $betfair,
                    'visitor' => $row['team2']
                );
                
            //HOCKEY == MONEYLINE
            } elseif ($row['sport'] == 8) {

                $from     = new DateTime("now + 1 hour");
                $to       = new DateTime("now + 2 day");
                $timeZone = new \Betfair\Model\TimeRange($from, $to);

                $marketFilter = MarketFilter::create();
                $marketFilter
                //->setEventIds=$ids;
                    ->setTextQuery($row['team1'])->setMarketTypeCodes(["MONEY_LINE"])->setCompetitionIds(array(
                    $row['league']))->setMarketStartTime($timeZone);
                #->setMarketSort( ["LAST_TO_START"] )
                    #->setMarketStartTime($timeZone);
                
                $betfairEvent->withMarketFilter($marketFilter);
                
                $betfair = $betfairEvent->getResults();
                
                $events[] = array(
                    'betbtc' => $row['marketid'],
                    'sport' => $row['sport'],
                    'betfair' => $betfair,
                    'visitor' => $row['team2']
                );
                
            }
            
        }
        return $events;
    }

    public function checkdates($appKey, $username, $pwd, $marketid)
    {

        $betfair      = BetfairFactory::createBetfair($appKey, $username, $pwd);
        
        $betfairEvent = $betfair->getBetfairEvent();

        $marketFilter = MarketFilter::create()
            ->setMarketIds(array($marketid));

        $betfairEvent->withMarketFilter($marketFilter);

        $event = $betfairEvent->getResults();

        $time = strtotime($event[0]['event']['openDate']);

        return $time;
    }
}



while (1) {

    try{
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }


        ## RETRIEVES FROM BETBTC API AND STORES THE PAIR OF MARKETS TO BE WORKED ON A TABLE

        $x = Lookup::httpGet("www.betbtc.co/api/event/?league=featured");
        $b = Lookup::httpGet("www.betbtc.co/api/event/?league=597"); // Premier
        $c = Lookup::httpGet("www.betbtc.co/api/event/?league=527"); // Champions
        $d = Lookup::httpGet("www.betbtc.co/api/event/?league=741"); // Europa
        $e = Lookup::httpGet("www.betbtc.co/api/event/?league=643"); // Bundesleague
        $f = Lookup::httpGet("www.betbtc.co/api/event/?league=614"); // Spanish
        $g = Lookup::httpGet("www.betbtc.co/api/event/?league=641"); // French
        $h = Lookup::httpGet("www.betbtc.co/api/event/?league=673"); // Portugal
        $i = Lookup::httpGet("www.betbtc.co/api/event/?league=766"); // Copa Italia
        $j = Lookup::httpGet("www.betbtc.co/api/event/?league=666"); // Eredivise
        $k = Lookup::httpGet("www.betbtc.co/api/event/?league=613"); // Russia
        $l = Lookup::httpGet("www.betbtc.co/api/event/?league=605"); // SERIE A
        $m = Lookup::httpGet("www.betbtc.co/api/event/?league=525"); // FA Cup
        $y = Lookup::httpGet("www.betbtc.co/api/event/?sport=4"); // Tenis

        $group_markets = array_merge($x, $b, $c, $d, $e, $f, $g, $h, $i, $j, $k, $l, $m, $y);



        foreach ($group_markets as $x) {
            
            
            
            
            $w  = $x[0];
            $w1 = $x[1];
            $w2 = $x[2];
            $w3 = $x[3];
            $w4 = $x[4];
            $w5 = $x[5];


            $querys= "SELECT * FROM Markets WHERE betbtc = '" . $w . "'";
            $resulta = $conn->query($querys);

            if ($resulta->num_rows > 0) {

                $sql = "UPDATE Markets SET event_date='" . $w4 . "' WHERE betbtc = '" . $w . "'";   
                $conn->query($sql);
            }else{
            
                $sql = "INSERT INTO Markets (betbtc, home_betbtc, away_betbtc, league_betbtc, event_date, sport) VALUES ('$w','$w1','$w2','$w3','$w4','$w5')";    
                $conn->query($sql);

            } 
        }
        echo "BetBTC Markets Inserted";






        $sqla2    = "SELECT sport, betbtc, home_betbtc, away_betbtc, league_betbtc, event_date FROM Markets";
        $resulta2 = $conn->query($sqla2);

        $torename = array();

        if ($resulta2->num_rows > 0) {
            // output data of each row
            while ($row = $resulta2->fetch_assoc()) {
                $torename[] = array(
                    'sport' => $row['sport'],
                    'marketid' => $row['betbtc'],
                    'team1' => $row['home_betbtc'],
                    'team2' => $row['away_betbtc'],
                    'league' => $row['league_betbtc'],
                    'event_date' => $row['event_date']
                );
            }
        } else {
            echo "0 results";
        }


        $callback_rename       = Rename::change($betfair_key, $betfair_username, password, $torename);



        foreach ($callback_rename as $row) {

         if (isset($row['betfair'][0])) {

            print_r($row);
            
            if ($row['betfair'][0]['eventType']['name'] == 'Basketball' || $row['betfair'][0]['eventType']['name'] == 'Basket' || $row['betfair'][0]['eventType']['name'] == 'Hockey' || $row['betfair'][0]['eventType']['name'] == 'Ice-Hockey' || $row['betfair'][0]['eventType']['name'] == 'Ice Hockey') {
                
                $market_betfair = $row['betfair'][0]['marketId'];
                $home_betfair    = $row['betfair'][0]['runners'][1]['runnerName'];
                $home_id = $row['betfair'][0]['runners'][1]['selectionId'];
                $away_betfair    = $row['betfair'][0]['runners'][0]['runnerName'];
                $away_id =  $row['betfair'][0]['runners'][0]['selectionId'];
                $league_betfair    = $row['betfair'][0]['competition']['id'];

                
            } else {
                
                $market_betfair = $row['betfair'][0]['marketId'];
                $home_betfair    = $row['betfair'][0]['runners'][0]['runnerName'];
                $home_id = $row['betfair'][0]['runners'][0]['selectionId'];
                $away_betfair    = $row['betfair'][0]['runners'][1]['runnerName'];
                $away_id = $row['betfair'][0]['runners'][1]['selectionId'];
                $league_betfair    = $row['betfair'][0]['competition']['id'];
                
                
                
            }

            $sport = $row['sport'];
            $visitor_betbtc = $row['visitor'];


            $explode = explode(' ', $visitor_betbtc);
            $end = '';

            if(count($explode) > 0){
                $end = array_pop($explode); // removes the last element, and returns it
            }

            #CHECK FOR BAD TENIS MATCHES (DOUBLES and WRONG VISITORS)
            if ($sport != 4 || (strpos($home_betfair, '/') === false && strpos($away_betfair, '/') === false && strpos($away_betfair, $end) !== false)) {

                $query3= "SELECT * FROM Markets WHERE betfair = '" . $market_betfair . "'";
                $result3 = $conn->query($query3);

                if ($result3->num_rows > 0) {
                    echo "Duplicated Markets";
                }else{


                    $betfair_date = Rename::checkdates($betfair_key, $betfair_username, $betfair_password, $market_betfair);

          
                    $date = date('Y-m-d H:i:s', $betfair_date);


                    #UPDATE BETFAIR EVENT DATES
                    $sql_data = "UPDATE Markets SET event_date_betfair='" . $date . "' WHERE betbtc='" . $row['betbtc'] . "'";
                    $conn->query($sql_data);

                    #DELETE EVENTS WHOSE BETFAIR <=> BETBTC DIFERS FOR MORE THAN 5 HOURS (BAD MATCH)
                    $delete_1 = "DELETE from Markets WHERE TIMESTAMPDIFF(HOUR,event_date,event_date_betfair) > 5 OR TIMESTAMPDIFF(HOUR,event_date,event_date_betfair) < -5";
                    $conn->query($delete_1);          

                    #UPDATE GENUINE MATCHES WITH RETRIEVED INFORMATION (FROM BETFAIR API) AND CHANGE STATUS TO 1
                    $sql = "UPDATE Markets SET event_date_betfair='" . $date . "', betfair='" . $market_betfair . "', home_betfair='" . $home_betfair . "', away_betfair='" . $away_betfair . "', league_betfair='" . $league_betfair . "', home_id='" . $home_id . "', away_id='" . $away_id . "', status=1 WHERE betbtc='" . $row['betbtc'] . "'";
                    $conn->query($sql);

                    #UPDATE FEATURED MATCHES
                    $featured = "UPDATE Markets SET featured = '1' WHERE sport = 6 or sport = 5";
                    $conn->query($featured);                    
                }

            #BAD TENIS MATCHES (DOUBLES) 
            } else {
                echo "ERROR: HAD / ";


            }
            
         }   
        }

                $delete_2 = "DELETE from Markets WHERE status = '0'";
                $conn->query($delete_2);

        mysqli_close($conn);

        print_r('SLEPPING FOR 30 MINUTES - ');
        print_r(date('H:i:s'));

        sleep(1800);

    } catch( ConnectException $ex ) {
        switch ( $ex->getMessage() ) {
            case '7': break;
            case '56': break;
                // handle your exception in the way you want,
                // maybe with a graceful fallback         
        }
    }
}
