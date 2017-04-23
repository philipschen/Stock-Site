<!DOCTYPE html>
<html>
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
<div align="right"><a href="">Stock List</a> <a href="">Advanced Search</a>
    <form><input type="text" name="Search Stocks"><br><input type="submit" value="Search">
        <input type="submit" value="Log out">
</div>
</form>
<img src="person.jpg" width="40" height="40">Username:
<br><a href="">Portfolio Summary</a> <a href="">TransactionHistory</a> <a href="">Watchlist</a>
<br><a href="">Import Existing</a> <a href="">Friends List</a> <a href="">Chat Forums</a>
<br><a href="RemoveStock.php">Remove Stock from My Portfolio</a>
<br><a href="AddStock.php">Add Stock to My Portfolio</a>
<hr>
<form method='GET'>

<?php
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
$sql = "SELECT tickerid, stockname, earningspershare, dividends FROM myportfolio WHERE userid = '" . $uid . "'";
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
$conn->close();
// Goes to prediction page
if($_SERVER["REQUEST_METHOD"] == "GET"){
    for ($i = 0; $i < count($tickers); $i++) {
        if(isset($_REQUEST[$tickers[$i]])){
            setcookie("cookiestock",$_GET[$tickers[$i]], time()+3600);
            echo '<meta HTTP-EQUIV="REFRESH" content="0; url=predictionpage.php">';
        }
    }
}
// Format Portfolio Table
echo "<table>
<tr>
<th>Ticker</th>
<th>Company Name</th>
<th>Earnings Per Share</th>
<th>Dividend</th>
</tr>";
for ($i = 0; $i < count($tickers); $i++) {
    echo "<tr>";
    echo "<td><input type='submit' name='". $tickers[$i] ."' value = '".$tickers[$i]."'></td>";
    //echo "<td><a href='predictionpage.php'>".$tickers[$i]."</a></td>";
    echo "<td>" . $tickname[$i] . "</td>";
    echo "<td>" . $tickearnings[$i] . "</td>";
    echo "<td>" . $tickdiv[$i] . "</td>";
    echo "</tr>";
}
echo "</table>";
?>
</form>

</body>
</html>