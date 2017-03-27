<!DOCTYPE html>
<html>
<body>

<h1>My first PHP page</h1>

//This module will read from a CSV file and save the data into the Stock Table.

<?php
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
if (($handle = fopen("$id[$i].csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        $num = count($data);
        echo "<p> $num fields in line $row: <br /></p>\n";
        $row++;
        for ($c=0; $c < $num; $c++) {
            echo $data[$c] . "<br />\n";
        }
    $s = $data[0];
    $dt = new DateTime($s);

    $dat = $dt->format('Y-m-d');
    
    
    
    try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO Historic (tickerid, stockname, date, closingprice, volume)
    VALUES ('$id[$i]', '$stockname[$i]', '$dat', $data[1], $data[5])";
    // use exec() because no results are returned
    $conn->exec($sql);
    echo "New record created successfully";
    }
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

$conn = null;

    }
    fclose($handle);
}
}
?>

</body>
</html>