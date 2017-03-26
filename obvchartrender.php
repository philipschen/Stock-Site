<?php
require_once("pChart2.1.4/class/pData.class.php");
require_once("pChart2.1.4/class/pDraw.class.php");
require_once("pChart2.1.4/class/pImage.class.php");

//Code for getting OBV data and displaying OBV graph.
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password, "stockdbpdo");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";

$sql = "SELECT closingprice, volume FROM historic";
if ($conn->query($sql) === TRUE) {
    //echo "DATA OBTAINED successfully";
} else {
    //echo "Error: " . $sql . "<br>" . $conn->error;
}

$volume = array();
$price = array();
$volume1 = array();
$price1 = array();

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row

    while ($row = $result->fetch_assoc()) {
            array_push($price1,$row["closingprice"]);
            array_push($volume1,$row["volume"]/1000);
        //echo "id: " . $row["id"] . "VALUE " . $row["price"] . "vol" . $row["volume"] . "<br>";
    }
}
$conn->close();


$timecount = 100;
for ($i = count($volume1)-1; $i > 0 ; $i--) {
    if ($timecount >= 0){
        array_push($price,$price1[$i]);
        array_push($volume,$volume1[$i]);
        $timecount -= 1;
    }
}





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
    "FontSize" => 10));
$myImage->setGraphArea(50,50, 450,250);

$labelskip = 9/1; // values = 1000, hours = 24
$scaleSettings = array("LabelSkip"=>$labelskip);
$myImage->drawScale($scaleSettings);
$myImage->drawLineChart(array("DisplayValues"=>FALSE,"DisplayColor"=>DISPLAY_AUTO));
header("Content-Type: image/png");
$myImage->Render(null);
?>