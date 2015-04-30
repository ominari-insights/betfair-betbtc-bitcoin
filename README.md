Betfair <=> BetBTC Arbitrage Bot
===
Auto Place Bets
===


This Arbitrage Betfair <=> BetBTC bot allows you to place "safe" bets on BetBTC based on current Betfair prices.
You can select the odd spread you want to apply (in %) and this bot will constantly check Betfair Markets. 
In case of odd variance it will update the BetBTC bets.

The basic working concept is the following:

1- Starts by Interacting with the BetBTC API (read markets) to get a list of future markets and stores them on a local DB.

2- Reads the stored BetBTC markets and uses Betfair API (read markets with parameters) to find the correspondent Betfair Data such as MarketIds and Team Names, on the end it also records them on another DB fields, same row.

3- Reads each one of the stored Betfair's Marketbooks and uses Betfair API (read marketbook) to store the best prices / odds on the local database (another DB fields, same row).

4- If since the last database price writing there was a variance on the prices (odds) it uses BetBTC API to update previous bets on the selected market, if there was no change 

This script is running on loop every 5 minutes the Place/Remove Bets and every 30 minutes the Import Markets.
