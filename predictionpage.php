<!DOCTYPE html>
<html>
<body>
<h1>Predictive Analysis</h1>
<div align="right"><a href="">Stock List</a>   <a href="">Advanced Search</a>
    <form><input type="text" name="Search Stocks"><br><input type="submit" value="Search">
        <input type="submit" value="Log out"></div></form>

<!--<form><input type="button" value="My Portfolio" href="http://www.w3schools.com"></form>-->
<br>
<hr>
<!--<br><a href="">1M</a>	<a href="">3M</a>	<a href="">6M</a>	<a href="">1Y</a>	<a href="">5Y</a>-->

</body>
</html>

<?php
//RSI
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

    $sql = "SELECT stockname,closingprice FROM stockprices WHERE tickerid = 'AAPL'";
    if ($conn->query($sql) === TRUE) {
    } else {
        //echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $name = '';
    $temp = array();
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            array_push($temp,$row["closingprice"]);
            $name = $row["stockname"];
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
    echo '<h2>Stock Name: '. $name.'</h2>';
    if ($rsi[count($rsi)-1] >= 70)
        echo '<div>RSI Forecast: Sell</div>';
    elseif ($rsi[count($rsi)-1] <= 30)
        echo '<div>RSI Forecast: Buy</div>';
    else
        echo '<div>RSI Forecast: hold</div>';
}
//Pattern Matching
if (true){
    echo '<h3>Pattern Matching</h3>';


    $arr;
    $t;
    $array;


    $m = 2;

    function iscup($switch){
        global $m;
        global $t;
        if($switch == 1){
            $arr = array(50,50,50,50,50,50,50,50,50,50,55,53,50,49,48,47,40,41,42,43,44,45,53,50,49,48,47,46);
            $t = sizeof($arr);
        }
        if($switch == 2){
            $arr = array(20,30,40,50,60,70,80,90,95,94,92,82,80,74,69,62,68,69,76,79,83,89,84,83,81,80,78);
            $t = sizeof($arr);
        }
        if($switch == 3){
            $arr = array(1);
            $t = sizeof($arr);
        }
        if($switch == 4){
            $arr = array(50,50,50,50,50,50,50,50,50,50,55,34,31,48,51,51,51,51,51,51,51,51,51,51,51,51,51,51,51);
            $t = sizeof($arr);
        }
        if($t<26){
            echo"Pattern not Found! Array is too small to judge for patterns.";
            return false;
        }

        $a = 0.10; /*fluctuating toleration*/
        $b = 0.33; /*cup depth criteria*/
        $x = 0.10; /*RH,LH*/
        $d = 0.15; /*handle depth*/
        $obsPoint = $t;
        $high = $t; /* t > 26, suggest 52 weeks or longer*/

        /*start from time t, trace back to find Righ High Point */
        while ($arr[$t-2] >= $arr[$t-1] || $arr[$t-2] < $arr[$t-1]&& $arr[$t-2]>=(1-$a)*$high){
            if($arr[$t-2] >= $arr[$t-1]){
                $high = $arr[$t-2];
            }
            $t--;
        }

        $Right_High = $t; /*found Right High*/

        /*start from Right High, trace back to find Dip Point */
        $low = $t;
        while ($arr[$t-2] <= $arr[$t-1] || $arr[$t-2] > $arr[$t-1]&& $arr[$t-2]<=(1+$a)*$low){
            if($arr[$t-2] <= $arr[$t-1]){
                $low = $arr[$t-2];
            }
            $t--;
        }


        $Dip_Point = $t; /*found Dip Point*/

        /*start from Dip, trace back to find Left High Point*/

        while (($arr[$t-1] >= $arr[$t-0]) || ($arr[$t-1] < $arr[$t-0]) && ($arr[$t-1]>=(1-$a)*$high)){
            if($t-1 == 0){
                break;
            }
            if($arr[$t-1] >= $arr[$t-0]){
                $high = $arr[$t-1];
            }
            $t--;
        }

        $Left_High = $t; /*found Left High*/
        if($obsPoint!=$Right_High){
            if(abs( $arr[$Right_High-1] - $arr[$obsPoint-1])< $x*$arr[$Right_High-1]){

                echo '<div>Pattern not Found1! Dip too large compared to right peak for pattern to form</div>';
                return false; /*rule 3*/
            }
        }
        if($Right_High-$Dip_Point < 2 || $Right_High-$Dip_Point > 8){
            echo '<div>Pattern not Found!2 Handle fails to meet pattern specs.</div>';
            return false; /*shakeout handle is too long or too short (rule 5)*/
        }

        if($arr[$Right_High]-$arr[$Dip_Point]> $b * $arr[$Right_High]){
            echo '<div>Pattern not Found3! Dip too large.</div>';
            return false; /*because too dip (rule 2)*/
        }

        if($Right_High-$Left_High < 12 && $Right_High-$Left_High >26){
            echo '<div>Pattern not Found!4 Cup too narrow or wide for pattern.</div>';
            return false; /*because cup is too narrow or too wide (rule 4)*/
        }

        if(abs($arr[$Right_High]-$arr[$Left_High])> $d*$arr[$Right_High]){
            echo '<div>Pattern not Found!5 Change too drastic for pattern to form.</div>';
            return false; /*rule 1*/
        }

        echo "Pattern found!";
    }

    iscup($m);
}

echo '<img src="rsichartrender.php" alt="Failed RSI">';
echo '<img src="obvchartrender.php" alt="Failed OBV">';



?>

</body>
</html>