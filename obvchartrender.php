<?php
require_once("pChart2.1.4/class/pData.class.php");
require_once("pChart2.1.4/class/pDraw.class.php");
require_once("pChart2.1.4/class/pImage.class.php");

//Code for getting OBV data and displaying OBV graph.
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password, "testdb");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";

$sql = "SELECT id, price, volume FROM testtable";
if ($conn->query($sql) === TRUE) {
    //echo "DATA OBTAINED successfully";
} else {
    //echo "Error: " . $sql . "<br>" . $conn->error;
}

$volume = array();
$price = array();

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        array_push($price,$row["price"]);
        array_push($volume,$row["volume"]);
        //echo "id: " . $row["id"] . "VALUE " . $row["price"] . "vol" . $row["volume"] . "<br>";
    }
}
$conn->close();



// OBV ALGO
$obv = array();
array_push($obv,0.0);
for ($i = 1; $i < count($volume)-1; $i++) {
    if ($price[$i] > $price[$i-1])
        array_push($obv, $obv[$i-1] + $volume[$i]);
    elseif ($price[$i] < $price[$i-1])
        array_push($obv, $obv[$i-1] - $volume[$i]);
    else
        array_push($obv, $obv[$i-1]);
}


// OBV Graph
$myDataset = $obv;
$myData = new pData();
$myData->addPoints($myDataset);
$myImage = new pImage(500, 300, $myData);
$myImage->setFontProperties(array(
    "FontName" => "pChart2.1.4/fonts/GeosansLight.ttf",
    "FontSize" => 12));
$myImage->setGraphArea(25,25, 475,275);

$labelskip = 10/1; // values = 1000, hours = 24
$scaleSettings = array("LabelSkip"=>$labelskip);
$myImage->drawScale($scaleSettings);
$myImage->drawLineChart(array("DisplayValues"=>FALSE,"DisplayColor"=>DISPLAY_AUTO));
header("Content-Type: image/png");
$myImage->Render(null);
?>