<!DOCTYPE html>
<html>
<body>

<h1>My first PHP page</h1>

<?php

//This module will calculate the Moving Average.

class TableRows extends RecursiveIteratorIterator { 
    function __construct($it) { 
        parent::__construct($it, self::LEAVES_ONLY); 
    }

    function current() {
        return "<td style='width:150px;border:1px solid black;'>" . parent::current(). "</td>";
    }

    function beginChildren() { 
        echo "<tr>"; 
    } 

    function endChildren() { 
        echo "</tr>" . "\n";
    } 
} 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "StockDBPDO";
$tid = "AAPL";
$i = 0;
$price = array();
$date = array();

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$sql = "SELECT date, closingprice FROM Historic WHERE tickerid = '$tid' ORDER BY date ASC ";


$date = array();
$price = array();
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        array_push($price,$row["closingprice"]);
        array_push($date,$row["date"]);
        //echo "id: " . $row["id"] . "VALUE " . $row["price"] . "vol" . $row["volume"] . "<br>";
    }
}
$conn->close();

// Moving Average Algorithm
  

$priceMA = array_slice($price, 0, 25);
$j = count($priceMA);
for ($t = 25; $t < count($price); $t++){
	$average = 0;
	$output = array_slice($price, ($t-25), $t);
	for ($u = 0; $u <count($output); $u++){
		$average = $output[$u] + $average;
	}
	$priceMA[] = $average/count($output);
	$j++;
}
for ($t = 0; $t < 25; $t++){
	$average = 0;
	$output1 = array_slice($price, ($j-25), count($price));
	$output2 = array_slice($priceMA, count($price), $j);
	$output = array_merge($output1, $output2);
	for ($u = 0; $u <count($output); $u++){
		$average = $output[$u] + $average;
	}
	$priceMA[] = $average/count($output);
	$j++;
}


// ARIMA Algorithm

$file = fopen("arimaprices.csv","w");

foreach ($price as $line)
  {
  fputcsv($file,explode(',',$line));
  }

fclose($file);

$file = fopen("maprices.csv","w");

foreach ($priceMA as $line)
  {
  fputcsv($file,explode(',',$line));
  }

fclose($file);

//exec("Rscript Rscript/Arima.R");
//header('Content-Type: image/jpg');
$im = imagecreatefromjpeg('output.jpg');
print_r($im);
header('Content-Type: image/jpg');
imagejpeg($im);
imagedestroy($im);

?>

</body>
</html>