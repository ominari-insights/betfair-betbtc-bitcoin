Betfair <=> BetBTC Arbitrage Bot
===


Requirements
===
- <a href="https://github.com/danieledangeli/betfair-php"><b>danieledangeli's betfair-php library</b></a>


Basic Concept
===


This Arbitrage <b>Betfair <=> BetBTC</b> bot allows you to automatically place bets on <b>BetBTC</b> based on the <b>Betfair</b> prices at each time.
You can apply a spread based on the odd if you want (in %) making a price gap. This is programmed to be constantly checking Betfair Marketbooks and only in case of odd variance it will update the <b>BetBTC</b> bets.

The basic working concept is the following:

- Starts by Interacting with the <b>BetBTC API</B> (read markets) to get a list of future markets and stores them on a local DB
- Reads the stored BetBTC markets and uses <b>Betfair API</b> (read markets with parameters) to find the correspondent Betfair Data such as MarketIds and Team Names, on the end it also records them on another DB fields, same row.</li>
- Reads each one of the stored Betfair's Marketbooks and uses <b>Betfair API</b> (read marketbook) to store the best prices / odds on the local database (another DB fields, same row).</li>
- If since the last database price writing there was a variance on the prices (odds) it uses <b>BetBTC API</b> to update previous bets on the selected market, if there was no change

This script is running on loop every 5 minutes the <b>Place/Remove Bets</b> and every 30 minutes the <b>Import Markets</b>.

The <a href="https://github.com/danieledangeli/betfair-php"><b>danieledangeli's betfair-php library</b></a> is used as base of this BetBTC Arbitrage bot, so it is required to setup it before using this script.

Import Database (First Step)
===

You need to import the database_schema to your local or remote database.
<b> <a href="https://github.com/acegilz/betfair-betbtc-bitcoin/blob/master/database_schema.sql">Database Example </b></a>

Import BetBTC Markets and Match with Betfair (Second Step)
===

File <b> <a href="https://github.com/acegilz/betfair-betbtc-bitcoin/blob/master/import.php">import.php </b></a>

After having the database setted up you should run the Importer. By default it is running on an endless loop, checking for new events every 30 minutes.

This importer will update the database with retrieved data.

Don't forget to update some preferences and fill the Database / Login Credentials.

``` php
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
```

Auto Removing and Placing Bets (Third Step)
===

File <b> <a href="https://github.com/acegilz/betfair-betbtc-bitcoin/blob/master/bet.php">bet.php </b></a>

After having the markets stored on database we are prepared to run the bettor. This will read all the markets we have on the database with status = 1 (active) and work on them, checking the betfair market of each one and update the correspondent database field. If there is any variance on the odds it will use again the BetBTC Api to remove the old Bets for that market and create new ones.

By default it is running on an endless loop, removing and placing new bets every 5 minutes.

Don't forget to Update the credentials and spread to apply on bets.php (beggining of file)

``` php
date_default_timezone_set("UTC");
####### CONFIG LOGIN CREDENTIALS
$betbtc_token = '';
$betfair_username = '';
$betfair_password = '';
$betfair_key = '';
#########################
$commission = 0.05;
$servername = "127.0.0.1";
$username   = "root";
$password   = "";
$dbname     = "api";
```
