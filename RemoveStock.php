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

<br><a href="portfolio.php">Return to My Portfolio</a>

<h3>Remove Stocks</h3>

<?php
//Code for getting the list of tickers and company names.
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

// Cookie for UserID
$uid = "";
if (isset($_COOKIE["cookieid"]))
    $uid = $_COOKIE["cookieid"];
else
    $uid = "user1";
// Find Portfolio Information
$sql = "SELECT tickerid, stockname FROM myportfolio WHERE userid = '" . $uid . "'";
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
<th>Action</th>
</tr>";
for ($i = 0; $i < count($tickers); $i++) {
    echo "<tr>";
    echo "<td><input type='submit' name='". $tickers[$i] ."' value = '".$tickers[$i]."'></td>";
    //echo "<td><a href='predictionpage.php'>".$tickers[$i]."</a></td>";
    echo "<td>" . $tickname[$i] . "</td>";
    echo "<td><a href=\"remove.php?stock=$tickers[$i] \">Remove this stock to My Portfolio</a></td>";
    echo "</tr>";
}
echo "</table>";
?>
</form>
</body>
</html>