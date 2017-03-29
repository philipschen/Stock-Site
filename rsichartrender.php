<?php
require_once("pChart2.1.4/class/pData.class.php");
require_once("pChart2.1.4/class/pDraw.class.php");
require_once("pChart2.1.4/class/pImage.class.php");

// Get which Stock
$cookiestock = "";
if (isset($_COOKIE["cookiestock"]))
    $cookiestock = $_COOKIE["cookiestock"];
else
    $cookiestock = "AAPL";

//Code for getting RSI data and displaying RSI graph.
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password, "StockDBPDO");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";

$sql = "SELECT stockname,closingprice FROM stockprices WHERE tickerid = '".$cookiestock."'";
if ($conn->query($sql) === TRUE) {
} else {
    //echo "Error: " . $sql . "<br>" . $conn->error;
}
$temp = array();
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        array_push($temp,$row["closingprice"]);
    }
}
$conn->close();



// RSI ALGO
$gl = array();
$tag = 0.0;
$tal = 0.0;
$rs = array();
$rsi = array();
$r1 = array();
$r2 = array();

//gl
for ($i = 1; $i <= count($temp)-1; $i++) {
    array_push($gl, $temp[$i]-$temp[$i-1]);
}
//rs
for ($i = 14; $i < count($gl); $i++) {
    $tag = 0;
    $tal = 0;
    for ($i1 = $i-14; $i1 < $i; $i1++) {

        if($gl[$i1] > 0)
            $tag = $tag + $gl[$i];
        if($gl[$i1] < 0)
            $tal = $tal + $gl[$i];
    }
    if ($tag == 0)
        array_push($rs, 0);
    if ($tal == 0)
        array_push($rs, 10000);
    else
        array_push($rs, $tag/$tal);
}
//rsi
/*
for ($i = 1; $i <= count($rs); $i++) {
    array_push($rsi, 100 - 100 / (1 + $rs[$i-1]));
}
*/
$timecount = 100;
for ($i = count($rs)-1; $i > 0 ; $i--) {
    if ($timecount >= 0){
        array_push($rsi, 100 - 100 / (1 + $rs[$i]));
        $timecount -= 1;
    }
}

for ($i = 1; $i <= count($rsi); $i++) {
    array_push($r1, 30);
}
for ($i = 1; $i <= count($rsi); $i++) {
    array_push($r2, 70);
}




// RSI Graph
$myData = new pData();
$myData->addPoints($rsi, "RSI");
$myData->addPoints($r1, "dat2");
$myData->addPoints($r2, "sat3");
$myImage = new pImage(700, 400, $myData);
$myImage->setFontProperties(array(
    "FontName" => "pChart2.1.4/fonts/GeosansLight.ttf",
    "FontSize" => 10));
$myImage->drawText(300,40,"RSI Graph",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
$myImage->drawText(350,350,"Last 100 days",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
$myImage->drawText(40,200,"RSI",array("FontSize"=>20, "Angle" => 90,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
$myImage->drawText(60,360,"",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
$myImage->setGraphArea(70,80, 600,300);

$AxisBoundaries = array(0=>array("Min"=>0,"Max"=>100));
$labelskip = 9/1; // values = 1000, hours = 24
$scaleSettings = array("LabelSkip"=>$labelskip,"Mode"=>SCALE_MODE_MANUAL,"ManualScale"=>$AxisBoundaries);
$myImage->drawScale($scaleSettings);
$myImage->drawLineChart(array("DisplayValues"=>FALSE,"DisplayColor"=>DISPLAY_AUTO));
header("Content-Type: image/png");
$myImage->Render(null);


?>
