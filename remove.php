<!DOCTYPE html>
<html>
<body>


<?php
//This module will remove a user selected stock to their portfolio.
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

// Cookie for UserID
$uid = "";
if (isset($_COOKIE["cookieid"]))
    $uid = $_COOKIE["cookieid"];
else
    $uid = "user1";

// Update portfolio

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "DELETE FROM myportfolio WHERE tickerid = '$search' AND userid = '$uid'";
        // use exec() because no results are returned
        $conn->exec($sql);
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }
   
    $conn = null;
}

    // This will display a success alert box in case the transaction is succesful.
echo '<script language="javascript">';
echo 'alert("Stock removed successfully")';
echo '</script>';
include 'portfolio.php';
?>
 

</body>
</html>