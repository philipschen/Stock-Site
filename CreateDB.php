<!DOCTYPE html>
<html>
<body>

<h1>Database and Table Creation Module</h1>

<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "StockDBPDO";
$tablename = "StockPrices";

try {
    $conn = new PDO("mysql:host=$servername", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "CREATE DATABASE $dbname";
    // use exec() because no results are returned
    $conn->exec($sql);
    echo "Database $dbname created successfully<br>";

    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "CREATE TABLE $tablename (
    tickerid VARCHAR(5) NOT NULL, 
    stockname VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    closingprice DECIMAL(11,6),
    volume BIGINT
    )";

    // use exec() because no results are returned
    $conn->exec($sql);
    echo "Table $tablename created successfully<br>";	
    }
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

$conn = null;

$dbname = "MyPortfolioDBPDO";
$tablename = "MyPortfolio";

try {
    $conn = new PDO("mysql:host=$servername", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "CREATE DATABASE $dbname";
    // use exec() because no results are returned
    $conn->exec($sql);
    echo "Database $dbname created successfully<br>";

    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // sql to create table
    $sql = "CREATE TABLE $tablename (
    userid VARCHAR(16) NOT NULL, 
    tickerid VARCHAR(5) NOT NULL,
    earningspershare DECIMAL(11,6),
    dividends DECIMAL(11,6)
    )";

    // use exec() because no results are returned
    $conn->exec($sql);
    echo "Table $tablename created successfully<br>";	
    }
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

$conn = null;

$dbname = "UserDBPDO";
$tablename = "Users";

try {
    $conn = new PDO("mysql:host=$servername", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "CREATE DATABASE $dbname";
    // use exec() because no results are returned
    $conn->exec($sql);
    echo "Database $dbname created successfully<br>";

    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // sql to create table
    $sql = "CREATE TABLE $tablename (
    userid VARCHAR(16) NOT NULL, 
    username VARCHAR(16) NOT NULL,
    name VARCHAR(24) NOT NULL,
    lastname VARCHAR(24) NOT NULL,
    password VARCHAR(24) NOT NULL
    )";

    // use exec() because no results are returned
    $conn->exec($sql);
    echo "Table $tablename created successfully<br>";	
    }
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

$conn = null;

?>

</body>
</html>