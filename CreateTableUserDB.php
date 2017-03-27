<!DOCTYPE html>
<html>
<body>

<h1>My first PHP page</h1>

<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "UserDBPDO";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // sql to create table
    $sql = "CREATE TABLE Users (
    userid VARCHAR(16) NOT NULL, 
    username VARCHAR(16) NOT NULL,
    name VARCHAR(24) NOT NULL,
    lastname VARCHAR(24) NOT NULL,
    password VARCHAR(24) NOT NULL
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