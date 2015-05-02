Betfair <=> BetBTC Arbitrage Bot
===


Requirements
===
- <a href="https://github.com/danieledangeli/betfair-php"><b>danieledangeli's betfair-php library</b></a>


How to Use it
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
