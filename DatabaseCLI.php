<?php

    /**
     * ABOUT: This script is created to manage the database StocksStore through Command Line Interface
     * USE: Run this script to insert, update, delete and view data in the StocksStore database
     */



    system("clear");
    require_once "connection.php";



    # UTILITY FUNCTIONS 

    function cls(){system("clear");}
    function br() {echo "\n";}
    function line(){echo "|—————|————————————————————————————————————————|———————————————|——————————|——————————|——————————|——————————|\n";}

    function center($text, $pad_string = ' ') {
        $window_size = (int) `tput cols`;
        return str_pad($text, $window_size, $pad_string, STR_PAD_BOTH)."\n";
    }

    // color-ref: https://joshtronic.com/2013/09/02/how-to-use-colors-in-command-line-output/
    function color($text, $color = "white", $bgcolor="default"){
        $color_arr = ["black"=>30, "red"=>31, "green"=>32, "yellow"=>33, "blue"=>34, "magenta"=>35, "cyan"=>36, "white"=>37];
        $bgcolor_arr = ["black"=>40, "red"=>41, "green"=>42, "yellow"=>43, "blue"=>44, "magenta"=>45, "cyan"=>46, "default"=>0];

        $color = $color_arr[$color];
        $bgcolor = $bgcolor_arr[$bgcolor];
        $bgcolor == 0 ? $str = "\e[1;$color"."m$text\e[0m"
                             : "\e[1;$color;$bgcolor"."m$text\e[0m";
        return $str;
    }
    
    function appName(){
        br();
        echo center(color("

        ███████╗████████╗ ██████╗  ██████╗██╗  ██╗███████╗    ███████╗████████╗ ██████╗ ██████╗ ███████╗
        ██╔════╝╚══██╔══╝██╔═══██╗██╔════╝██║ ██╔╝██╔════╝    ██╔════╝╚══██╔══╝██╔═══██╗██╔══██╗██╔════╝
        ███████╗   ██║   ██║   ██║██║     █████╔╝ ███████╗    ███████╗   ██║   ██║   ██║██████╔╝█████╗  
        ╚════██║   ██║   ██║   ██║██║     ██╔═██╗ ╚════██║    ╚════██║   ██║   ██║   ██║██╔══██╗██╔══╝  
        ███████║   ██║   ╚██████╔╝╚██████╗██║  ██╗███████║    ███████║   ██║   ╚██████╔╝██║  ██║███████╗
        ╚══════╝   ╚═╝    ╚═════╝  ╚═════╝╚═╝  ╚═╝╚══════╝    ╚══════╝   ╚═╝    ╚═════╝ ╚═╝  ╚═╝╚══════╝
                                                                                                        
        ", "green"));
    }

    function menu(){
        echo color(center("MENU", "-"), "yellow");
        br();
        echo color("1. Insert new data\n", "blue");
        echo color("2. Update data\n", "yellow");
        echo color("3. Delete data\n", "red");
        echo color("4. Display data\n", "green");
        br();
        echo "Press 0 to exit\n";
        br();
    }



    # OPERATIONAL FUNCTIONS 

    function insertData(){
        cls();
        appName();

        echo "\e[1;34m"; // make the text blue
        $name           = readline("Enter stock name*: ");
        $symbol         = readline("Enter stock symbol*: ");
        $quantity       = (int)readline("Enter stock quantity*: ");
        $avgPrice       = (float)readline("Enter stock average price*: ");
        $targetPrice    = readline("Enter stock target price: ") ?: NULL;
        $stopLoss       = readline("Enter stock stoploss: ") ?: NULL;
        echo "\e[0m"; // reset color

        $GLOBALS['conn']->query("INSERT INTO `Stocks` (`name`, `symbol`, `quantity`, `avgPrice`, `targetPrice`, `stopLoss`) VALUES ('$name', '$symbol', $quantity, $avgPrice, $targetPrice, $stopLoss)") or die(color("Error inserting data.\n", "yellow"));
        echo color("New record inserted successfully.\n", "green");

        echo "\e[1;35m"; // make the text magenta
        $more = readline("Have more stocks to insert?(y/n): ");
        echo "\e[0m"; // reset color

        $more == 'y' ? insertData() : "";
    }

    function displayData(){
        cls();
        appName();

        $result = $GLOBALS['conn']->query("SELECT * FROM `Stocks`");
        if($result->num_rows > 0){
            echo "\e[1;32m"; // make the text green
            line();
            echo "|".str_pad("ID", 5)."|".
                     str_pad("Name", 40)."|".
                     str_pad("Symbol", 15)."|".
                     str_pad("Quantity", 10)."|".
                     str_pad("Average", 10)."|".
                     str_pad("Target", 10)."|".
                     str_pad("Stop Loss", 10)."|";
            br();
            line();

            while($row = $result->fetch_assoc()){
                echo "|".str_pad($row["ID"], 5, " ", STR_PAD_LEFT)."|".
                     str_pad($row["name"], 40, " ", STR_PAD_RIGHT)."|".
                     str_pad($row["symbol"], 15, " ", STR_PAD_RIGHT)."|".
                     str_pad($row["quantity"], 10, " ", STR_PAD_LEFT)."|".
                     str_pad($row["avgPrice"], 10, " ", STR_PAD_LEFT)."|".
                     str_pad($row["targetPrice"], 10, " ", STR_PAD_LEFT)."|".
                     str_pad($row["stopLoss"], 10, " ", STR_PAD_LEFT)."|";
                br();
                line();
            }
            echo "\e[0m"; // reset color
        } else {
            echo color("No results to show.\n", "yellow");
        }
    }

    function updateData(){
        cls();
        appName();

        displayData();

        echo "\e[1;33m"; // make the texts yellow
        $item = (int)readline("Enter ID of the stock to be updated: ");
        echo "\e[0m"; // reset color

        $result = $GLOBALS['conn']->query("SELECT * FROM `Stocks` WHERE `ID`=$item") or die(color("Error getting data.", "yellow"));
        $row = $result->fetch_assoc();
        
        // assigns old data
        $name           = $row['name'];
        $symbol         = $row['symbol'];
        $quantity       = $row['quantity'];
        $avgPrice       = $row['avgPrice'];
        $targetPrice    = $row['targetPrice'];
        $stopLoss       = $row['stopLoss'];

        // take new data
        echo "\e[1;33m"; // make the texts yellow
        echo "Press enter for the fields that don't need changes.\n";
        $name           = readline("Enter stock name: ") ?: $name;
        $symbol         = readline("Enter stock symbol: ") ?: $symbol;
        $quantity       = (int)readline("Enter stock quantity: ") ?: $quantity;
        $avgPrice       = (float)readline("Enter stock average price: ") ?: $avgPrice;
        $targetPrice    = readline("Enter stock target price: ") ?: $targetPrice;
        $stopLoss       = readline("Enter stock stoploss: ") ?: $stopLoss;
        echo "\e[0m"; // reset color

        $GLOBALS['conn']->query("UPDATE`Stocks` SET `name`='$name', `symbol`='$symbol', `quantity`=$quantity, `avgPrice`=$avgPrice, `targetPrice`=$targetPrice, `stopLoss`=$stopLoss WHERE `ID`=$item") or die(color("Erro updating data.\n", "yellow"));
        echo color("Stock with ID $item updated successfully.\n", "green");

        echo "\e[1;35m"; // make the text magenta
        $more = readline("Have more stocks to update?(y/n): ");
        echo "\e[0m"; // reset the color

        $more == 'y' ? updateData() : "";
    }

    function deleteData(){
        cls();
        appName();

        displayData();

        echo "\e[1;31m"; // make the text red
        $item = (int)readline("Enter ID of the stock to be deleted: ");
        echo "\e[0m"; // resets the color

        $GLOBALS['conn']->query("DELETE FROM `Stocks` WHERE `ID`=$item") or die(color("Error deleting stock!\n", "yellow"));
        echo color("Stock with ID $item deleted successfully.\n", "green");

        echo "\e[1;35m"; // make the text magenta
        $more = readline("Have more stocks to delete?(y/n): ");
        echo "\e[0m"; // reset the color

        $more == 'y' ? deleteData() : "";
    }

    function main(){
        cls();
        appName();
        br();
        menu();

        echo "\e[1;35m"; // make the text magenta
        $option = (int)readline("Choose option: ");
        echo "\e[0m"; // reset the color

        switch ($option) {
            case 0:
                die(color("Thank you for using Stocks Store. Bye.\n", "yellow"));
                break;

            case 1:
                insertData();
                break;
            
            case 2:
                updateData();
                break;
            
            case 3:
                deleteData();
                break;
            
            case 4:
                displayData();
                break;
            
            default:
                echo color("Please enter a valid option next time.\n", "yellow");
                break;
        }

        br();
        echo "\e[1;35m"; // make the text magenta
        $more = readline("Continue using StockStore?(y/n): ");
        echo "\e[0m"; // reset the color

        $more == 'y' ? "" : die(color("\nThank you for using Stocks Store 😊. Bye.\n", "yellow"));
    }
    while(1) main();

?>