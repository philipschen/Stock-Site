<!DOCTYPE html>
<html>
<body>

<h1>Database and Table Creation Module</h1>

<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "StockDBPDO";

try {
    $conn = new PDO("mysql:host=$servername", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "DROP DATABASE $dbname";
    // use exec() because no results are returned
    $conn->exec($sql);
    echo "Database $dbname removed successfully<br>";
	
    }
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

$conn = null;

$dbname = "MyPortfolioDBPDO";

try {
    $conn = new PDO("mysql:host=$servername", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "DROP DATABASE $dbname";
    // use exec() because no results are returned
    $conn->exec($sql);
    echo "Database $dbname removed successfully<br>";
	
    }
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

$conn = null;

$dbname = "UserDBPDO";

try {
    $conn = new PDO("mysql:host=$servername", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "DROP DATABASE $dbname";
    // use exec() because no results are returned
    $conn->exec($sql);
    echo "Database $dbname removed successfully<br>";
	
    }
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

$conn = null;
?>

</body>
</html>