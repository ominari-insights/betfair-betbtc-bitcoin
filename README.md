Betfair <=> BetBTC Arbitrage Bot
===


Requirements
===
- <a href="https://github.com/danieledangeli/betfair-php"><b>danieledangeli's betfair-php library</b></a>


How it works?
===


This Arbitrage <b>Betfair <=> BetBTC</b> bot allows you to automatically place bets on <b>BetBTC</b> based on the <b>Betfair</b> prices.
You can apply a spread based on the odd (value in %) making a price gap. This is programmed to be constantly checking Betfair Marketbooks and only in case of odd variance it will update the <b>BetBTC</b> bets - removinng old ones and placing newer bets.

The basic working concept is the following:

- Starts by Interacting with the <b>BetBTC API</B> (read markets) to get a list of future markets, then it stores these markets on a local DB - This markets already come with the correspondent Betfair ID.
- Reads each one of the stored Betfair's Marketbooks using the <b>Betfair API</b> (read marketbook) to store the best prices / odds on the local database.</li>
- If there was a variance on the odd price since the last check, it uses <b>BetBTC API</b> to update previous bets on the selected market. If there was a variance on the odd price since the last check, it skips.

This script is running on loop. We are using crontab and a script to ensure it is always running.

The <a href="https://github.com/danieledangeli/betfair-php"><b>danieledangeli's betfair-php library</b></a> is the lib used by this BetBTC Arbitrage bot to communicate with betfair, it is required to setup it before using this script.


Instructions
===

- Make a clean Linux Instalation (Ubuntu is fine) - pick a 2GB VPS at least to avoid crashings.
- Install a fresh LAMP.
- Get the <a href="https://github.com/danieledangeli/betfair-php"><b>danieledangeli's betfair-php library</b></a>
- Clone this LIB.
- Adapt configurations on the once_new.php file
- Make /crons folder and contents executable and put both running on your system crontab.
- Congratulations, you are now one of the few people profiting with this 100% automatic bot, remember that <a href="https://betbtc.zendesk.com/hc/en-us/articles/201733242"><b>Market Makers don't pay commission at BetBTC.</b>
