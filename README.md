# StocksStore
A simple stock portfolio tracker webapp to track all the stocks from different apps in one place.

> The API used in this project has some limitations. So, the project is created keeping in mind those limitations. The API can fetch only 5 request per minute. I, therefore, changed the code to call only 5 requests per minute. For 12 stocks in your portfolio, it takes minimum 2 minutes to load.

## Features
1. Colorful CLI script for managing database.
2. Great UI.
3. Show alerts when stock price crashes/raises and when target/stoploss price reaches.
4. Show overall returns, total invested, current value etc.
5. Can be added in mobile homescreen.
6. Not fully responsive but looks good. Just the fonts are small a bit.

## Requirements
1. PHP
2. Git
3. MySql

## How to use?
1. Clone this repository.
2. Run `database.sql` script in MySql.
3. Change the database information in `connection.php` file if necessary.
4. Use `DatabaseCLI.php` script to insert, update, delete and display data. Run `php DatabaseCLI.php` in terminal.
5. Run `php updateDataFile.php` to fetch the updated data from database to `data.json` file.
6. You need a free API key of Alpha Vantage API. Change it in `main.js` file.
7. Start server and enjoy.

> Pro tip: If npm is installed, use `npx http-server -c5184000`, (this will cache the files for 2 months) and open the homepage in your phone and then add the app to homescreen. Now the app can be used even without server for 2 months in your phone.

## Screenshots
![Loading](/screenshots/stocksstore_loading.png?raw=true "Loading")
![Loading](/screenshots/stocksstore.png?raw=true "Fully loaded")
![Loading](/screenshots/stocksstore_cli.png?raw=true "CLI")

Thank you 😎
