<!DOCTYPE html>
<html>
<body>

<h1>Write Test portfolio and Users</h1>

<?php
//This module will read from a CSV file and save the data into the Stock Table.
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "StockDBPDO";
$path = "Hist";
$id = array("AAPL", "AIG", "AMD", "AZO", "BP", "CI", "DB", "EA", "F", "FB", "FDX");
$stockname = array("Apple Computer, Inc.", "American International Group Inc", "Advanced Micro Devices, Inc.",
    "AutoZone, Inc.", "BP plc (ADR)", "CIGNA Corporation", "Deutsche Bank AG (USA)", "Electronic Arts Inc.",
    "Ford Motor Company", "Facebook Inc", "FedEx Corporation");


// Create Users
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO users (userid, username, name, lastname, password)
    VALUES ('user1', 'user1', 'Bill', 'Adams', 'test1')";
    // use exec() because no results are returned
    $conn->exec($sql);
    $sql = "INSERT INTO users (userid, username, name, lastname, password)
    VALUES ('user2', 'user2', 'John', 'Cena', 'test1')";
    // use exec() because no results are returned
    $conn->exec($sql);
    echo "Finished creating users";
} catch (PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}
$conn = null;


$row = 1;
for ($i = 0; $i < count($id); $i++) {
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO myportfolio (userid, tickerid, stockname, earningspershare, dividends)
    VALUES ('user1', '$id[$i]', '$stockname[$i]', 9.0, 2.4)";
        // use exec() because no results are returned
        $conn->exec($sql);
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }
    if ($i < 5){
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO myportfolio (userid, tickerid, stockname, earningspershare, dividends)
    VALUES ('user2', '$id[$i]', '$stockname[$i]', 9.0, 2.4)";
            // use exec() because no results are returned
            $conn->exec($sql);
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }

    $conn = null;
}

}
echo "<p> Stock Prices Update Completed<br /></p>\n";
?>

</body>
</html>