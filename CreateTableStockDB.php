<!DOCTYPE html>
<html>
<body>

<h1>My first PHP page</h1>

<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "StockDBPDO";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // sql to create table
    $sql = "CREATE TABLE Historic (
    tickerid VARCHAR(5) NOT NULL, 
    stockname VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    closingprice DECIMAL(11,6),
    volume BIGINT
    )";

    // use exec() because no results are returned
    $conn->exec($sql);
    echo "Table Historic created successfully";
    }
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

$conn = null;
?>

</body>
</html>