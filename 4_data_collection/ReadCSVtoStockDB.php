<!DOCTYPE html>
<html>
<body>

<h1>Read Historic Stock Price Data</h1>

<?php
//This module will read from a CSV file and save the data into the Stock Table.
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "StockDBPDO";
$id = array("AAPL", "AIG", "AMD", "AZO", "BP", "CI", "DB", "EA", "F", "FB", "FDX");
$stockname = array("Apple Computer, Inc.","American International Group Inc", "Advanced Micro Devices, Inc.",
	"AutoZone, Inc.", "BP plc (ADR)", "CIGNA Corporation", "Deutsche Bank AG (USA)", "Electronic Arts Inc.",
	"Ford Motor Company", "Facebook Inc", "FedEx Corporation");

$row = 1;
for ($i = 0; $i < count($id); $i++){
if (($handle = fopen("/wamp64/www/Hist/$id[$i].csv", "r")) !== FALSE) {
    echo "<p> Reading data from $id[$i].csv<br /></p>\n";
    echo "<p> Updating Table StockPrices...<br /></p>\n";
    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        $num = count($data);
        $row++;
        $s = $data[0];
        $dt = new DateTime($s);
        $dat = $dt->format('Y-m-d');
    
    try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO StockPrices (tickerid, stockname, date, closingprice, volume)
    VALUES ('$id[$i]', '$stockname[$i]', '$dat', $data[1], $data[5])";
    // use exec() because no results are returned
    $conn->exec($sql);
    }
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

$conn = null;

    }
    echo "<p> Update Complete<br /></p>\n";
    fclose($handle);
}
}
echo "<p> Stock Prices Update Completed<br /></p>\n";
?>

</body>
</html>