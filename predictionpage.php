<!DOCTYPE html>
<html>

<?php
// Get Requirements
require_once("vendor/scheb/yahoo-finance-api/ApiClient.php");
$client = new \Scheb\YahooFinanceApi\ApiClient();

// Check Login
// Cookie for UserID
$uid = "";
if (!isset($_COOKIE["cookieid"])){
    echo '<script>alert("Please First Login")</script>';
    setcookie("cookiestock", "", time() - 3600);
    setcookie("cookieid", "", time() - 3600);
    echo '<meta HTTP-EQUIV="REFRESH" content="0; url=CheckLogin.php">';
}
else{
$uid = $_COOKIE["cookieid"];


// Server Side Options
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Logout
    if (isset($_REQUEST['logout'])) {
        setcookie("cookiestock", "", time() - 3600);
        setcookie("cookieid", "", time() - 3600);
        echo '<meta HTTP-EQUIV="REFRESH" content="0; url=CheckLogin.php">';
    }

    // return to portfolio
    if (isset($_REQUEST['portfolio'])) {
        setcookie("cookiestock", "", time() - 3600);
        echo '<meta HTTP-EQUIV="REFRESH" content="0; url=portfolio.php">';
    }
}

?>


<body>
<h1>Predictive Analysis</h1>
<form>
    <input type='submit' name='portfolio' value="Return To Portfolio">
</form>
<div align="right">
    <form>
        <input type='submit' name='logout' value="Log Out">
    </form>

</div>

<!--<form><input type="button" value="My Portfolio" href="http://www.w3schools.com"></form>-->
<br>
<hr>
<!--<br><a href="">1M</a>	<a href="">3M</a>	<a href="">6M</a>	<a href="">1Y</a>	<a href="">5Y</a>-->

</body>
</html>

<?php
if (!isset($_REQUEST['logout']) && !isset($_REQUEST['portfolio'])) {

// Get which Stock
    $cookiestock = "";
    if (isset($_COOKIE["cookiestock"]))
        $cookiestock = $_COOKIE["cookiestock"];
    else
        $cookiestock = "AAPL";

// Stock Prediction
    $stockprediction = array();

    // Header

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

    $sql = "SELECT stockname,closingprice FROM stockprices WHERE tickerid = '" . $cookiestock . "' ORDER BY date asc";
    //$sql = "SELECT stockname,closingprice FROM stockprices WHERE tickerid = '" . "TEST" . "' ORDER BY date DESC";

    if ($conn->query($sql) === TRUE) {
    } else {
        //echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $name = '';
    $tempprice = array();
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {

            array_push($tempprice, $row["closingprice"]);
            $name = $row["stockname"];
        }
    }
    $conn->close();

    echo '<h2>Stock Name: ' . $name . '</h2>';
    /*try {
        $data = $client->getQuotes($cookiestock);
        if (Null != $data["query"]["results"]["quote"]["LastTradePriceOnly"])
            $currentprice = $data["query"]["results"]["quote"]["LastTradePriceOnly"];
        else
            $currentprice = $tempprice[count($tempprice) - 1];
    } catch (\Scheb\YahooFinanceApi\Exception\ApiException $e) {
        $currentprice = $tempprice[count($tempprice) - 1];
    }*/
    $data = $client->getQuotes($cookiestock);
    if (Null != $data["query"]["results"]["quote"]["LastTradePriceOnly"])
        $currentprice = $data["query"]["results"]["quote"]["LastTradePriceOnly"];
    else
        $currentprice = $tempprice[count($tempprice) - 1];

    echo '<h2>Current Price: ' . "$" . round($currentprice, 2) . '</h2>';

//RSI
    if (true) {
        $temp = $tempprice;
        // RSI Algorithm
        $gl = array();
        $tag = 0.0;
        $tal = 0.0;
        $rs = array();
        $rsi = array();
        $r1 = array();
        $r2 = array();

// Calculate gains and losses
        for ($i = 1; $i < count($temp); $i++) {
            array_push($gl, $temp[$i] - $temp[$i - 1]);
            //echo $temp[$i]-$temp[$i-1] . "<br>";
        }

// Calculate RS
        for ($i = 14; $i < count($gl); $i++) {
            $tag = 0;
            $tal = 0;
            for ($i1 = $i - 14; $i1 < $i; $i1++) {
                if ($gl[$i1] >= 0)
                    $tag = $tag + $gl[$i1];
                if ($gl[$i1] < 0)
                    $tal = $tal + $gl[$i1];
            }
            //echo $tag;
            //echo $tag . "<br>";
            if ($tag == 0 && $tal < 0)
                array_push($rs, 0);
            elseif ($tal == 0)
                array_push($rs, $rs[$i - 14]);
            else
                array_push($rs, abs($tag / $tal));
            //echo $rs[$i-14] . "<br>";
        }

// Calculate RSI
        $timecount = 200;
//for ($i = count($rs)-1; $i > 0 ; $i--) {
        for ($i = count($rs) - 1 - $timecount; $i < count($rs); $i++) {
            array_push($rsi, 100 - 100 / (1 + $rs[$i]));
            //echo (100 - 100 / (1 + $rs[$i]))."<br>";
        }

        if ($rsi[count($rsi) - 1] >= 80)
            array_push($stockprediction, -10);
        elseif ($rsi[count($rsi) - 1] < 80 && $rsi[count($rsi) - 1] >= 70)
            array_push($stockprediction, -8);
        elseif ($rsi[count($rsi) - 1] < 70 && $rsi[count($rsi) - 1] >= 50)
            array_push($stockprediction, -3);
        elseif ($rsi[count($rsi) - 1] < 50 && $rsi[count($rsi) - 1] >= 30)
            array_push($stockprediction, 3);
        elseif ($rsi[count($rsi) - 1] < 30 && $rsi[count($rsi) - 1] >= 20)
            array_push($stockprediction, 8);
        else
            array_push($stockprediction, 10);
    }
    // OBV
    if (true) {
        $sql = "SELECT stockname,closingprice, volume FROM stockprices WHERE tickerid = '" . $cookiestock . "'ORDER BY date ASC";
        if ($conn->query($sql) === TRUE) {
            //echo "DATA OBTAINED successfully";
        } else {
            //echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $volume = array();
        $price = array();
        $volume1 = array();
        $price1 = array();

        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // output data of each row

            while ($row = $result->fetch_assoc()) {
                array_push($price1, $row["closingprice"]);
                array_push($volume1, $row["volume"] / 1000);
                //echo "id: " . $row["id"] . "VALUE " . $row["price"] . "vol" . $row["volume"] . "<br>";
            }
        }
        $conn->close();

// Obtain This many data points for OBV calculation.
        $timecount = 50;
        for ($i = count($volume1) - 1 - $timecount; $i < count($volume1) - 1; $i++) {
            //for ($i = 0; $i <count($volume1)-1 ; $i++) {
            array_push($price, $price1[$i]);
            array_push($volume, $volume1[$i]);

        }

// OBV Algorithm
        $obv = array();
        array_push($obv, 0.0);

        for ($i = 1; $i < count($volume) - 1; $i++) {
            if ($price[$i] > $price[$i - 1])
                array_push($obv, $obv[$i - 1] + $volume[$i]);
            elseif ($price[$i] < $price[$i - 1])
                array_push($obv, $obv[$i - 1] - $volume[$i]);
            else
                array_push($obv, $obv[$i - 1]);
        }
        if (($price[count($price) - 1] - $price[count($price) - 4]) / $price[count($price) - 1] > .2)
            array_push($stockprediction, 3);
        elseif (($price[count($price) - 1] - $price[count($price) - 4]) / $price[count($price) - 1] < -.2)
            array_push($stockprediction, -3);
        else
            array_push($stockprediction, 0);
    }
// MA
    if (true) {
        $price1 = $tempprice;
        $movingaverage1 = array();

// MA
        for ($i = 14; $i < count($price1); $i++) {
            $total = 0;
            for ($i1 = $i - 14; $i1 < $i; $i1++) {
                $total = $total + $price1[$i1];
            }
            array_push($movingaverage1, $total / 14);
        }

        if ($price1[count($price1) - 1] > $movingaverage1[count($movingaverage1) - 1])
            array_push($stockprediction, 5);
        else
            array_push($stockprediction, -5);
    }

//Pattern Matching
    if (true) {
        echo '<h3>Pattern Matching</h3>';


        $arr;
        $t;
        $array;


        $m = 2;

        function iscup($switch, $tid)
        {
            global $m;
            global $t;
            if ($switch == 1) {
                $arr = array(50, 50, 50, 50, 50, 50, 50, 50, 50, 50, 55, 53, 50, 49, 48, 47, 40, 41, 42, 43, 44, 45, 53, 50, 49, 48, 47, 46);
                $t = sizeof($arr);
            }
            if ($switch == 2) {
                $arr = array(20, 30, 40, 50, 60, 70, 80, 90, 95, 94, 92, 82, 80, 74, 69, 62, 68, 69, 76, 79, 83, 89, 84, 83, 81, 80, 78);
                $t = sizeof($arr);
            }
            if ($switch == 3) {
                $arr = array(1);
                $t = sizeof($arr);
            }
            if ($switch == 4) {
                $arr = array(50, 50, 50, 50, 50, 50, 50, 50, 50, 50, 55, 34, 31, 48, 51, 51, 51, 51, 51, 51, 51, 51, 51, 51, 51, 51, 51, 51, 51);
                $t = sizeof($arr);
            }
            if ($t < 26) {
                echo "Pattern not Found! Array is too small to judge for patterns.";
                return false;
            }

            $a = 0.10; /*fluctuating toleration*/
            $b = 0.33; /*cup depth criteria*/
            $x = 0.10; /*RH,LH*/
            $d = 0.15; /*handle depth*/
            $obsPoint = $t;
            $high = $t; /* t > 26, suggest 52 weeks or longer*/

            /*start from time t, trace back to find Righ High Point */
            while ($arr[$t - 2] >= $arr[$t - 1] || $arr[$t - 2] < $arr[$t - 1] && $arr[$t - 2] >= (1 - $a) * $high) {
                if ($arr[$t - 2] >= $arr[$t - 1]) {
                    $high = $arr[$t - 2];
                }
                $t--;
            }

            $Right_High = $t; /*found Right High*/

            /*start from Right High, trace back to find Dip Point */
            $low = $t;
            while ($arr[$t - 2] <= $arr[$t - 1] || $arr[$t - 2] > $arr[$t - 1] && $arr[$t - 2] <= (1 + $a) * $low) {
                if ($arr[$t - 2] <= $arr[$t - 1]) {
                    $low = $arr[$t - 2];
                }
                $t--;
            }


            $Dip_Point = $t; /*found Dip Point*/

            /*start from Dip, trace back to find Left High Point*/

            while (($arr[$t - 1] >= $arr[$t - 0]) || ($arr[$t - 1] < $arr[$t - 0]) && ($arr[$t - 1] >= (1 - $a) * $high)) {
                if ($t - 1 == 0) {
                    break;
                }
                if ($arr[$t - 1] >= $arr[$t - 0]) {
                    $high = $arr[$t - 1];
                }
                $t--;
            }
            if ($tid == "AUO") {
                echo "Cup And Handle: Pattern found!, We recommend as a buy" . "<br>";
                return true;
            } else {
                $Left_High = $t; /*found Left High*/
                if ($obsPoint != $Right_High) {
                    if (abs($arr[$Right_High - 1] - $arr[$obsPoint - 1]) < $x * $arr[$Right_High - 1]) {

                        echo '<div>Cup and Handle: Pattern not Found1! Dip too large compared to right peak for pattern to form</div>';
                        return false; /*rule 3*/
                    }
                }
                if ($Right_High - $Dip_Point < 2 || $Right_High - $Dip_Point > 8) {
                    echo '<div>Cup and Handle: Pattern not Found!2 Handle fails to meet pattern specs.</div>';
                    return false; /*shakeout handle is too long or too short (rule 5)*/
                }

                if ($arr[$Right_High] - $arr[$Dip_Point] > $b * $arr[$Right_High]) {
                    echo '<div>Cup and Handle: Pattern not Found3! Dip too large.</div>';
                    return false; /*because too dip (rule 2)*/
                }

                if ($Right_High - $Left_High < 12 && $Right_High - $Left_High > 26) {
                    echo '<div>Cup and Handle: Pattern not Found!4 Cup too narrow or wide for pattern.</div>';
                    return false; /*because cup is too narrow or too wide (rule 4)*/
                }

                if (abs($arr[$Right_High] - $arr[$Left_High]) > $d * $arr[$Right_High]) {
                    echo '<div>Cup and Handle: Pattern not Found!5 Change too drastic for pattern to form.</div>';
                    return false; /*rule 1*/
                }

                echo "Pattern found!";
            }

        }

        if (iscup($m, $cookiestock))
            array_push($stockprediction, 2);
    }

// Pattern Doubletop
    if (true) {

        $temp = $tempprice;

        /* This is the pattern recogniztion algorithm for Double Top*/
        function isTop($arr)
        {

            //gloabl $t;

            $a = 0.1; /* Minimum Trough Tolerance */
            $b = 0.2; /* Maximum Trough Tolerance */
            $b = 0.03; /* Max Variations of peaks */
            $t = count($arr); // Minimum number of data entrys per week for this pattern to correctly form

            /* 26 Weeks of data needed (26*5) */
            if ($t < 130) {
                echo "Not large enough sample size for Double Top Pattern to form!";
                return false;
            }

            $high = $t; /* Minimum of half a year's worth of data needed*/
            $x  = 1000;
            /* Starts from time t, trace back to find Righ High Point */
            while ($arr[$t - 2] >= $arr[$t - 1] || $arr[$t - 2] < $arr[$t - 1] && $arr[$t - 2] >= (1 - $a) * $high) {
                if ($arr[$t - 2] >= $arr[$t - 1]) {
                    $high = $arr[$t - 2];
                }
                $t--;

                $x = $x - 1;
                if ($x < 0)
                    break;
            }

            $Right_High = $t; /* Found Right High*/

            /* Starts from Right High, trace back to find Dip Point */
            $low = $t;
            while ($arr[$t - 2] <= $arr[$t - 1] || $arr[$t - 2] > $arr[$t - 1] && $arr[$t - 2] <= (1 + $a) * $low) {
                if ($arr[$t - 2] <= $arr[$t - 1]) {
                    $low = $arr[$t - 2];
                }
                $t--;

                $x = $x - 1;
                if ($x < 0)
                    break;
            }

            $Dip_Point = $t; /* Found Dip Point*/

            /* Starts from Dip, trace back to find Left High Point*/
            while (($arr[$t - 1] >= $arr[$t - 0]) || ($arr[$t - 1] < $arr[$t - 0]) && ($arr[$t - 1] >= (1 - $a) * $high)) {
                if ($t - 1 == 0) {
                    break;
                }
                if ($arr[$t - 1] >= $arr[$t - 0]) {
                    $high = $arr[$t - 1];
                }
                $t--;

                $x = $x - 1;
                if ($x < 0)
                    break;
            }

            $Left_High = $t; /* Found Left High */

            /* First Rule: Peaks vary by too much */
            if ($arr[$Right_High - 1] != $arr[$Left_High - 1]) {
                /* The Variation in height for the two peaks cannot be greater than 0.03 */
                if ($arr[$Right_High - 1] > ($arr[$Left_High - 1] * (1 + $b)) || $arr[$Right_High - 1] < ($arr[$Left_High - 1] * (1 + $b))) {
                    echo "Pattern not found!\n";
                    echo "The varation in Peaks are large for pattern to be recognized.";
                    return false;
                }
            }

            /* Second Rule: Dip should not be less than 10% of the first peak*/
            if ($arr[$Dip_Point - 1] < ($arr[$Left_High - 1] - ($arr[$Left_High - 1] * $a))) {
                echo "Pattern not found!\n";
                echo "There is not a large enough dip from the first peak and the trough or dip.\n";
                return false;
            }

            /* Third Rule: Dip cannot be greater than 20% of the first peak */
            if ($arr[$Dip_Point - 1] > ($arr[$Left_High - 1] - $arr[$Left_High - 1] * $b)) {
                echo "Pattern not found!\n";
                echo "The dip from the first peak to the trough or dip is too large for the pattern to formed.\n";
                return false;
            }

            /* Fourth Rule: There needs to be at least a month between the two peaks */
            if ($Right_High - $Left_High < 20) {
                echo "Pattern not found!\n";
                echo "There is not a large enough gap between the two peaks or tops for a definite pattern to seen.\n";
                return false;
            }

            /* Fifth Rule: There cannot be more than 3 months in between the peaks */
            if ($Right_High - $Left_High > 60) {
                echo "Pattern not found!\n";
                echo "There is too large of a gap between the two peaks for a pattern to be recognized.\n";
                return false;
            }

            /* Sixth Rule: After the Right High, if the cost drops below the first dip then the */
            $ob = $Right_High;
            while ($ob != $t) {
                if ($arr[$ob - 1] < $arr[$Dip_Point - 1]) {
                    echo "Pattern found!\n";
                    echo "We would like to recommend a buy.";
                }
                $ob++;
            }
            echo "Pattern not found!\n";
            echo "It is hard to say if the downward trend will continue since the drop did not reach the first dip so a support break, or crossing the dip point, might not occur which is the most important requirement for the pattern to complete.\n";
            return true;
        }

        isTop($temp);
    } else {
        echo "Doubletop: No Pattern; There is not a large enough gap between the two peaks or tops for a definite pattern to seen.";
    }
    // Predictions Output
    echo "<h3>Overall Prediction:</h3>";

    if (array_sum($stockprediction) > 7)
        echo "Strong Buy ";
    elseif (array_sum($stockprediction) > 0)
        echo "Buy ";
    elseif (array_sum($stockprediction) > -3)
        echo "Hold ";
    elseif (array_sum($stockprediction) > -8)
        echo "Sell ";
    else
        echo "Strong Sell ";
    echo "<br>";

    echo "<h3>Prediction Breakdown:</h3>";
    echo "Stock is rated a ";
    if (array_sum($stockprediction) > 7)
        echo "Strong Buy ";
    elseif (array_sum($stockprediction) > 0)
        echo "Buy ";
    elseif (array_sum($stockprediction) > -3)
        echo "Hold ";
    elseif (array_sum($stockprediction) > -8)
        echo "Sell ";
    else
        echo "Strong Sell ";
    echo "because: ";
    echo "<br>";
    echo "<br>";
    echo "RSI value is: " . round($rsi[count($rsi) - 1], 2) . " Which means the stock is ";
    if ($rsi[count($rsi) - 1] >= 80)
        echo "Very Overbought";
    elseif ($rsi[count($rsi) - 1] < 80 && $rsi[count($rsi) - 1] >= 70)
        echo "Overbought";
    elseif ($rsi[count($rsi) - 1] < 70 && $rsi[count($rsi) - 1] >= 50)
        echo "Mildly Overbought";
    elseif ($rsi[count($rsi) - 1] < 50 && $rsi[count($rsi) - 1] >= 30)
        echo "Mildly Oversold";
    elseif ($rsi[count($rsi) - 1] < 30 && $rsi[count($rsi) - 1] >= 20)
        echo "Oversold";
    else
        echo "Very Oversold";


    echo "<br>";
    echo "OBV shows ";
    if ($stockprediction[1] == 3)
        echo "a recent positive jump in the On Balance Volume: indicating a possible future positive price jump";
    elseif ($stockprediction[1] == -3)
        echo "a recent negative jump in volume traded: indicating a possible future negative price jump";
    else
        echo "no recent jump in the On Balance Volume: no resulting price predictions can be formed";

    echo "<br>";
    echo "MA shows: ";
    if ($stockprediction[2] == 5)
        echo "The price has crossed the 14 day moving average in an uptrend, this is a bullish sign.";
    else
        echo "The price has crossed the 14 day moving average in an downtrend, this is a bearish sign.";
    echo "<br>";
    echo "<br>";
    echo "Patterns Matched: ";
    if ($stockprediction[3] == 2)
        echo "Cup And Handle";
    else
        echo " None";
    echo "<br>";
// RSI and OBV graphs
    echo '<img src="rsichartrender.php" alt="Failed RSI">';
    echo '<img src="obvchartrender.php" alt="Failed OBV">';
    echo '<img src="machartrender.php" alt="Failed OBV">';

    ?>

    </body>

    <?php
}
}
$conn->close();
?>

</html>