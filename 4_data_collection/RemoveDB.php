<!DOCTYPE html>
<html>
<body>

<h1>Database and Table Creation Module</h1>

<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "StockDBPDO";
	
// This statement will delete the entire database.
try {
    $conn = new PDO("mysql:host=$servername", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "DROP DATABASE $dbname";
    // use exec() because no results are returned
    $conn->exec($sql);
	// This will display a success message in case the transaction is succesful.
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
