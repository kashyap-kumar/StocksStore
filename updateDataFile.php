<?php
    /**
     * USE: Run this script after updating StocksStore database using DatabaseCLI.php script
     */

    function get_data(){
        require_once "connection.php";
        
        $query = "SELECT * FROM `Stocks`";
        $result = $conn->query($query);
        $stock_data = array();
        
        while($row = $result->fetch_assoc()){
            $stock_data[] = array(
                'name'          => $row['name'],
                'symbol'        => $row['symbol'],
                'quantity'      => $row['quantity'],
                'avgPrice'      => $row['avgPrice'],
                'targetPrice'   => $row['targetPrice'],
                'stopLoss'      => $row['stopLoss']
            );
        }

        return json_encode($stock_data);
    }

    $file_name = 'data.json';

    if(file_put_contents($file_name, get_data())){
        echo $file_name." updated successfully\n";
    }
    else{
        echo "There is an error updating $file_name file.\n";
    }

?>