const APIKEY = "PUT YOUR API KEY HERE"; // go to alpha vantage website for api key generation

let date              = new Date(); // today
let dateToday         = date.toJSON().slice(0, 10).replace(/-/g, '-');
                        date.setDate(date.getDate() - 1); // yesterday
let dateYesterday     = date.toJSON().slice(0, 10).replace(/-/g, '-');
                        date.setDate(date.getDate() - 1); // 2days ago
let dateTwoDaysAgo    = date.toJSON().slice(0, 10).replace(/-/g, '-');
                        date.setDate(date.getDate() - 1); // 3days ago
let dateThreeDaysAgo  = date.toJSON().slice(0, 10).replace(/-/g, '-');

date = new Date().getDay(); // day of the week (date have changed in the previous lines so new date() is used again)

const dayOfFetchData = date == 1 ? dateThreeDaysAgo // 1 --> monday
                                          : date == 0 ? dateTwoDaysAgo // 0 --> sunday
                                                               : dateYesterday;

// SELECT THE LOADER
const loader = document.querySelector(".loader");

// SELECT ELEMENTS IN PORTFOLIO OVERVIEW SECTION
const totalCurrentValue_elm   = document.getElementById("totalCurrentValue");
const totalReturns_elm        = document.getElementById("totalReturns");
const totalInvestedAmount_elm = document.getElementById("totalInvestedAmount");

// VARIABLES FOR PORTFOLIO OVERVIEW SECTION
let totalCurrentValue_var     = 0;
let totalReturns_var          = 0;
let totalReturns_var_percent  = 0;
let totalInvestedAmount_var   = 0;

// SELECT ELEMENTS IN DETAILED PORTFOLIO SECTION
const detailedPortfolioContainer_elm  = document.getElementById("detailedPortfolioContainer");

// VARIABLES FOR PORTFOLIO OVERVIEW SECTION
let currentValue_var    = 0;
let investedAmount_var  = 0;
let returns_var         = 0;
let returns_var_percent = 0;

// SELECT ELEMENTS IN ALERTS SECTION
const alertsContainer_elm   = document.getElementById("alertsContainer");
const noAlert               = document.getElementById("no-alert");

main();

// EVERYTHING STARTS HERE
function main(){
  // CREATE CARDS BASED ON AVAILABLE DATA
  fetchData().then((stocks) => {

    // DIVIDE stocks IN PARTS SO THAT EACH PART GET 5 ELEMENTS (reason: API being used only supports 5 calls per minute)
    // creating a two dimensional array
    let stocksNew = [...Array(Math.floor(((stocks.length-1)/5)+1))].map(() => Array(5));

    // populating the two dimensional array
    for(let i=0, k=0; i< stocksNew.length; i++){
      for(let j=0; j<5 && k!=stocks.length; j++){
        stocksNew[i][j] = stocks[k++];
      }
    }

    // FOR FETCHING THE DATA USING API ONLY 5 TIMES PER MINUTE
    for(let i=0; i<stocksNew.length; i++){
      setTimeout(()=>{
        stocksNew[i].forEach(async (stock) => {

          // FETCHING THE CURRENT PRICE OF EACH STOCK
          let todayClose = await getTodayClose(stock["symbol"]);

          // CALCULATE THE CURRENT AND INVESTED AMOUNT, AND RETURNS OF EACH STOCK
          currentValue_var    = +stock["quantity"] * +todayClose;
          investedAmount_var  = +stock["quantity"] * +stock["avgPrice"];
          returns_var         = currentValue_var - investedAmount_var;
          returns_var_percent = (returns_var / investedAmount_var) * 100;
          
          // CALCULATE THE TOTAL CURRENT AND INVESTED AMOUNT, AND RETURNS
          totalCurrentValue_var     += currentValue_var;
          totalInvestedAmount_var   += investedAmount_var;
          totalReturns_var           = totalCurrentValue_var - totalInvestedAmount_var
          totalReturns_var_percent   = (totalReturns_var / totalInvestedAmount_var) * 100;
      
          // ASSIGN VALUES TO PORTFOLIO OVERVIEW SECTION AT THE END OF EVERY PENDING REQUEST
          totalCurrentValue_elm.innerHTML    = `₹${totalCurrentValue_var.toFixed(2)}`;
          totalInvestedAmount_elm.innerHTML  = `₹${totalInvestedAmount_var.toFixed(2)}`;
          totalReturns_elm.innerHTML         = `${totalReturns_var.toFixed(2)} (${totalReturns_var_percent.toFixed(2)}%)`;
          if(totalReturns_var < 0) totalReturns_elm.style.color = "#ff5858"; // negative return --> red color text

          // DISPLAYING EACH STOCK IN DETAILED PORTFOLIO SECTION
          detailedPortfolioContainer_elm.innerHTML += `<div class="stock">
                                                          <!-- col-1 -->
                                                          <div class="stockNameAndQuantity">
                                                            <p class="stockName">${stock["name"]}</p>
                                                            <p class="stockQuantity">${stock["quantity"]} shares</p>
                                                          </div>
                                                          <!-- col-2 -->
                                                          <div class="targetAndStoploss">
                                                            <p class="targetPrice">₹${stock["targetPrice"]}</p>
                                                            <p class="stoplossPrice">₹${stock["stopLoss"]}</p>
                                                          </div>
                                                          <!-- col-3 -->
                                                          <div class="marketAndAvgPrice">
                                                            <p class="marketPrice">₹${(+todayClose).toFixed(2)}</p>
                                                            <p class="avgPrice">₹${stock["avgPrice"]}</p>
                                                          </div>
                                                          <!-- col-4 -->
                                                          <div class="returns">
                                                            <p class="returnsInRs">₹${returns_var.toFixed(2)}</p>
                                                            <p class="returnsInPercent" ${returns_var_percent < 0 ? "style='color: #FF5858;'>" : ">+"}${returns_var_percent.toFixed(2)}%</p>
                                                          </div>
                                                          <!-- col-5 -->
                                                          <div class="currentAndInvestedAmount">
                                                            <p class="currentValue">₹${currentValue_var.toFixed(2)}</p>
                                                            <p class="investedAmount">₹${investedAmount_var.toFixed(2)}</p>
                                                          </div>
                                                        </div>`;

          // DISPLAYING ALERT MESSAGES
          /**
           * 1. Target price reached
           * 2. Stoploss price reached
           * 3. Downed by 9%
           * 4. Upped by 15%
           */

          if((+todayClose >= +stock["targetPrice"]) && +stock["targetPrice"] != 0){
            noAlert.style.display = "none";
            alertsContainer_elm.innerHTML += `<p class="alert">Target price reached for ${stock["name"]}</p>`;
          }
          else if(+todayClose <= +stock["stopLoss"]){
            noAlert.style.display = "none";
            alertsContainer_elm.innerHTML += `<p class="alert">Stop Loss price reached for ${stock["name"]}</p>`;
          }

          if(Math.floor(returns_var_percent) < -9){
            noAlert.style.display = "none";
            alertsContainer_elm.innerHTML += `<p class="alert">${stock["name"]} is downed by ${returns_var_percent.toFixed(2)}%</p>`;
          }
          else if(Math.floor(returns_var_percent) >= 15){
            noAlert.style.display = "none";
            alertsContainer_elm.innerHTML += `<p class="alert">${stock["name"]} is upped by ${returns_var_percent.toFixed(2)}%</p>`;
          }

          // REMOVE THE LOADER WHEN FETCHING COMPLETED
          if(i == stocksNew.length - 1){
              loader.style.display = "none";
          } 

        });
      }, i*61000);
    }

  });
}

// FUNCTION TO CALL THE API AND TO GET TODAY'S CLOSE PRICE
async function getTodayClose(symbol) {
  const url       = `https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=${symbol}.BSE&apikey=${APIKEY}`;
  const response  = await fetch(url);
  const data      = await response.json();
  return            (data['Time Series (Daily)'][dayOfFetchData]['4. close']);
}

// FUNCTION TO FETCH THE LOCAL DATA JSON FILE AND TO GET THE OBJECTS AS AN ARRAY
async function fetchData() {
  const response  = await fetch('data.json');
  const data      = await response.json();
  return            data;
}