<!DOCTYPE html>
<html>
<body>

<h1>Prediction Page</h1>

<?php

if (true){
    //Code for getting RSI data and displaying RSI graph.
    $servername = "localhost";
    $username = "root";
    $password = "";

// Create connection
    $conn = new mysqli($servername, $username, $password, "StockDBPDO");

// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
//echo "Connected successfully";

    $sql = "SELECT closingprice FROM historic";
    if ($conn->query($sql) === TRUE) {
    } else {
        //echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $temp = array();
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            array_push($temp,$row["closingprice"]);
        }
    }
    $conn->close();

    // RSI ALGO
    $gl = array();
    $tag = 0.0;
    $tal = 0.0;
    $rs = array();
    $rsi = array();
    $r1 = array();
    $r2 = array();

//gl
    for ($i = 1; $i <= count($temp)-1; $i++) {
        array_push($gl, $temp[$i]-$temp[$i-1]);
    }
//rs
    for ($i = 14; $i < count($gl); $i++) {
        $tag = 0;
        $tal = 0;
        for ($i1 = $i-14; $i1 < $i; $i1++) {

            if($gl[$i1] > 0)
                $tag = $tag + $gl[$i];
            if($gl[$i1] < 0)
                $tal = $tal + $gl[$i];
        }
        if ($tag == 0)
            array_push($rs, 0);
        if ($tal == 0)
            array_push($rs, 10000);
        else
            array_push($rs, $tag/$tal);
    }
//rsi
    /*
    for ($i = 1; $i <= count($rs); $i++) {
        array_push($rsi, 100 - 100 / (1 + $rs[$i-1]));
    }
    */
    $timecount = 100;
    for ($i = count($rs)-1; $i > 0 ; $i--) {
        if ($timecount >= 0){
            array_push($rsi, 100 - 100 / (1 + $rs[$i]));
            $timecount -= 1;
        }
    }

    if ($rsi[count($rsi)-1] >= 70)
        echo '<div>RSI Says Sell</div>';
    elseif ($rsi[count($rsi)-1] <= 30)
        echo '<div>RSI Says Buy</div>';
    else
        echo '<div>RSI Says hold</div>';
}


echo '<img src="rsichartrender.php" alt="Failed RSI">';
echo '<img src="obvchartrender.php" alt="Failed OBV">';



?>

</body>
</html>