<!DOCTYPE html>
<html>
<body>

<h1>My first PHP page</h1>

<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "MyPortfolioDBPDO";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // sql to create table
    $sql = "CREATE TABLE MyPortfolio (
    userid VARCHAR(16) NOT NULL, 
    tickerid VARCHAR(5) NOT NULL,
    earningspershare DECIMAL(11,6),
    dividends DECIMAL(11,6)
    )";

    // use exec() because no results are returned
    $conn->exec($sql);
    echo "Table Myportfolio created successfully";
    }
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

$conn = null;
?>

</body>
</html>