<!DOCTYPE html>
<html>
<body>

<?php
//This module will a user selected stock to their portfolio.
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "StockDBPDO";
$tickers = array();
$tickname = array();
// Create connection
$conn = new mysqli($servername, $username, $password, "stockdbpdo");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";

if (isset($_GET['stock'])){

$search = htmlspecialchars($_GET['stock']);

$sql = "SELECT DISTINCT * FROM Tickers WHERE tickerid LIKE '%$search%' ";
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

// Cookie for UserID
$uid = "";
if (isset($_COOKIE["cookieid"]))
    $uid = $_COOKIE["cookieid"];
else
    $uid = "user1";

// Create portfolios

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO myportfolio (userid, tickerid, stockname, earningspershare, dividends)
    VALUES ('$uid', '$tickers[0]', '$tickname[0]', 9.0, 2.4)";
        // use exec() because no results are returned
        $conn->exec($sql);
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }
   
    $conn = null;
}

    // This will display a success alert box in case the transaction is succesful.
echo '<script language="javascript">';
echo 'alert("Stock added successfully")';
echo '</script>';
include 'portfolio.php';
?>

</body>
</html>