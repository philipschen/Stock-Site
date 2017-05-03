<!DOCTYPE html>
<html>

<?php
// Get Requirements
require_once("vendor/scheb/yahoo-finance-api/ApiClient.php");
require_once("vendor/scheb/yahoo-finance-api/Exception/ApiException.php");
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

?>


<head>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, td, th {
            border: 1px solid black;
            padding: 5px;
        }

        th {
            text-align: left;
        }
    </style>
</head>
<body>

<h1>My Portfolio</h1>

<!--
<div align="right"><a href="">Stock List</a> <a href="">Advanced Search</a>
    <form><input type="text" name="Search Stocks"><br><input type="submit" value="Search">
        <input type="submit" value="Log out">
</div>
-->
<div align="right">
    <form>
        <input type='submit' name='logout' value="Log Out">
    </form>
</div>

<img src="person.jpg" width="40" height="40"> Username:
<?php
echo " " . $uid;
?>

<!--
<br><a href="">Portfolio Summary</a> <a href="">TransactionHistory</a> <a href="">Watchlist</a>
<br><a href="">Import Existing</a> <a href="">Friends List</a> <a href="">Chat Forums</a>
-->

<hr>
<form method='GET'>

    <?php
    // Get Requirements
    require_once("vendor/scheb/yahoo-finance-api/ApiClient.php");
    $client = new \Scheb\YahooFinanceApi\ApiClient();
    // Get Initial Tables
    if (true) {
        //Code for getting OBV data and displaying OBV graph.
        $servername = "localhost";
        $username = "root";
        $password = "";

        // Create connection
        $conn = new mysqli($servername, $username, $password, "stockdbpdo");

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        //echo "Connected successfully";

        $tickers = array();
        $tickname = array();
        $tickearnings = array();
        $tickdiv = array();

        // Cookie for UserID
        $uid = "";
        if (isset($_COOKIE["cookieid"]))
            $uid = $_COOKIE["cookieid"];
        else
            $uid = "user1";

        // Find Portfolio Information
        $sql = "SELECT tickerid, stockname, earningspershare, dividends FROM myportfolio WHERE userid = '" . $uid . "' ORDER BY tickerid ASC ";
        if ($conn->query($sql) === TRUE) {
            //echo "DATA OBTAINED successfully";
        } else {
            //echo "Error: " . $sql . "<br>" . $conn->error;
        }
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                array_push($tickers, $row["tickerid"]);
                array_push($tickname, $row["stockname"]);
                array_push($tickearnings, $row["earningspershare"]);
                array_push($tickdiv, $row["dividends"]);

            }
        }

        $tickersall = array();
        $ticknameall = array();
        $sql = "SELECT DISTINCT tickerid, stockname FROM stockprices ";
        if ($conn->query($sql) === TRUE) {
            //echo "DATA OBTAINED successfully";
        } else {
            //echo "Error: " . $sql . "<br>" . $conn->error;
        }
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                array_push($tickersall, $row["tickerid"]);
                array_push($ticknameall, $row["stockname"]);
            }
        }
    }

    // Server Side Options
    if ($_SERVER["REQUEST_METHOD"] == "GET") {

        // Go to predictions
        for ($i = 0; $i < count($tickers); $i++) {
            if (isset($_REQUEST[$tickers[$i]])) {
                setcookie("cookiestock", $_GET[$tickers[$i]], time() + 3600);
                echo '<meta HTTP-EQUIV="REFRESH" content="0; url=predictionpage.php">';
            }
        }

        // Logout
        if (isset($_REQUEST['logout'])) {
            setcookie("cookiestock", "", time() - 3600);
            setcookie("cookieid", "", time() - 3600);
            echo '<meta HTTP-EQUIV="REFRESH" content="0; url=CheckLogin.php">';
        }

        // Add to portfolio
        if (isset($_REQUEST['AddStock'])) {
            // Check valid
            if (!ctype_alpha($_REQUEST['AddStock']))
                echo '<script>alert("Ticker ID Is Invalid, Tickers are Letters example: GOOG")</script>';
            else {
                // To Upper
                $tickerstring = strtoupper($_GET['enterstock']);
                $presentportfolio = false;
                $presenttable = false;

                // check in tables
                for ($i = 0; $i < count($tickers); $i++) {
                    if ($tickerstring == $tickers[$i])
                        $presentportfolio = true;
                    if ($tickerstring == $tickersall[$i])
                        $presenttable = true;
                }
                if ($presentportfolio)
                    echo '<script>alert("Stock Is Already in Portfolio")</script>';
                elseif (!$presentportfolio && $presenttable) {
                    $key = 0;
                    for ($i = 0; $i < count($tickersall); $i++) {
                        if ($tickerstring == $tickersall[$i])
                            $key = $i;
                    }
                    $sql = "INSERT INTO myportfolio (userid, tickerid, stockname, earningspershare, dividends)
    VALUES ('" . $uid . "', '" . $tickerstring . "', '" . $ticknameall[$key] . "', 9.0, 2.4)";
                    $conn->query($sql);

                    echo '<meta http-equiv="Refresh" content="0;' . $_SERVER['PHP_SELF'] . '">';
                } elseif (!$presentportfolio && !$presenttable) {
                    $endDate = date_create("2017-04-20");
                    $startDate = date_create("2016-04-20");
                    try{
                        $data = $client->getHistoricalData($tickerstring, $startDate, $endDate);
                        $data2 = $client->getQuotes(array($tickerstring, "AAPL"));
                        $name1 = $data2["query"]["results"]["quote"][0]["Name"];

                        if ($tickerstring == $data["query"]["results"]["quote"][0]["Symbol"]) {

                            for ($i = 0; $i < count($data["query"]["results"]["quote"]); $i++) {

                                $symbol1 = $data["query"]["results"]["quote"][$i]["Symbol"];
                                $date1 = $data["query"]["results"]["quote"][$i]["Date"];
                                $price1 = $data["query"]["results"]["quote"][$i]["Close"];
                                $volume1 = $data["query"]["results"]["quote"][$i]["Volume"];
                                $sql = "INSERT INTO StockPrices (tickerid, stockname, date, closingprice, volume)
    VALUES ('$symbol1', '$name1', '$date1', $price1, $volume1)";
                                $conn->query($sql);
                            }
                            $sql = "INSERT INTO myportfolio (userid, tickerid, stockname, earningspershare, dividends)
    VALUES ('" . $uid . "', '" . $tickerstring . "', '" . $name1 . "', 9.0, 2.4)";
                            $conn->query($sql);
                            echo '<meta http-equiv="Refresh" content="0;' . $_SERVER['PHP_SELF'] . '">';;
                        }
                    }
                    catch(\Scheb\YahooFinanceApi\Exception\ApiException $e){
                        echo '<script>alert("ERROR: Invalid Stock Ticker, Stock Information was not found")</script>';
                    }

                } else
                    echo '<script>alert("ERROR: Invalid Stock Ticker, Stock Information was not found")</script>';
            }

        }

        // Remove from portfolio
        if (isset($_REQUEST['RemoveStock'])) {
            $sql = "DELETE FROM myportfolio WHERE tickerid = '" . $_GET['selectstock2'] . "'";
            if ($conn->query($sql) === TRUE) {
                //echo "DATA OBTAINED successfully";
            } else {
                //echo "Error: " . $sql . "<br>" . $conn->error;
            }
            $result = $conn->query($sql);
            echo '<meta http-equiv="Refresh" content="0;' . $_SERVER['PHP_SELF'] . '">';
        }
    }

    ?>


    Enter Ticker ID to Add:<br>
    <input type="text" name="enterstock">

    <!--
    <select name='selectstock'>
        <?php
    /*for ($i = 0; $i < count($tickersall); $i++) {
        echo "<option value='" . $tickersall[$i] . "'>" . $tickersall[$i] . "</option>";
    }*/
    ?>
    </select>
    -->
    <input type='submit' name='AddStock' value='AddStock'>
    <br>
    <br>

    Select Stock to Remove: <br>
    <select name='selectstock2'>
        <?php

        for ($i = 0; $i < count($tickers); $i++) {
            echo "<option value='" . $tickers[$i] . "'>" . $tickers[$i] . "</option>";
        }
        ?>
    </select>
    <input type='submit' name='RemoveStock' value='RemoveStock'>
    <br>
    <br>
    <?php
    $currentprice = array();
    $lastupdate = array();
    /*try{

        }
    }
    catch(\Scheb\YahooFinanceApi\Exception\ApiException $e){

    }*/
$data = $client->getQuotes($tickers);

        for ($i = 0; $i < count($tickers); $i++) {
            if (Null != $data["query"]["results"]["quote"][$i]["EarningsShare"])
                $tickearnings[$i] = $data["query"]["results"]["quote"][$i]["EarningsShare"];
            else
                $tickearnings[$i] = 0;

            if (Null != $data["query"]["results"]["quote"][$i]["DividendYield"])
                $tickdiv[$i] = $data["query"]["results"]["quote"][$i]["DividendYield"];
            else
                $tickdiv[$i] = 0;
            if (Null != $data["query"]["results"]["quote"][$i]["LastTradePriceOnly"])
                $currentprice[$i] = "$" . $data["query"]["results"]["quote"][$i]["LastTradePriceOnly"];
            else
                $currentprice[$i] = 0;
            if (Null != $data["query"]["results"]["quote"][$i]["LastTradeDate"] && Null != $data["query"]["results"]["quote"][$i]["LastTradeTime"])
                $lastupdate[$i] = $data["query"]["results"]["quote"][$i]["LastTradeDate"] . " " . $data["query"]["results"]["quote"][$i]["LastTradeTime"];
            else
                $lastupdate[$i] = 0;
        }

    //$tickerdiv = $data["query"]["results"]["quote"];

    // Format Portfolio Table
    echo "<table>
<tr><th>Ticker</th><th>Company Name</th><th>Earnings Per Share</th><th>Dividend</th><th>Current Price</th><th>Last Update</th></tr>";
    for ($i = 0; $i < count($tickers); $i++) {
        echo "<tr>";
        echo "<td><input type='submit' name='" . $tickers[$i] . "' value = '" . $tickers[$i] . "'></td>";
        //echo "<td><a href='predictionpage.php'>".$tickers[$i]."</a></td>";
        echo "<td>" . $tickname[$i] . "</td>";
        echo "<td>" . $tickearnings[$i] . "</td>";
        echo "<td>" . $tickdiv[$i] . "</td>";
        echo "<td>" . $currentprice[$i] . "</td>";
        echo "<td>" . $lastupdate[$i] . "</td>";
        echo "</tr>";
    }
    echo "</table>";

    ?>
</form>

</body>
</html>

<?php
$conn->close();
}
?>