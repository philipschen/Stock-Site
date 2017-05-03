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

$sql ="SELECT stockname,closingprice, volume FROM stockprices WHERE tickerid = '".$cookiestock."'ORDER BY date ASC";
if ($conn->query($sql) === TRUE) {
    //echo "DATA OBTAINED successfully";
} else {
    //echo "Error: " . $sql . "<br>" . $conn->error;
}

$price = array();
$movingaverage = array();
$price1 = array();
$movingaverage1 = array();

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row

    while ($row = $result->fetch_assoc()) {
        array_push($price1,$row["closingprice"]);
        //echo "id: " . $row["id"] . "VALUE " . $row["price"] . "vol" . $row["volume"] . "<br>";
    }
}
$conn->close();

// MA
for ($i = 14; $i < count($price1); $i++) {
    $total = 0;
    for ($i1 = $i-14; $i1 < $i; $i1++) {
        $total = $total + $price1[$i1];
    }
    array_push($movingaverage1, $total/14 );
}



// Obtain This many data points for RSI calculation.
$timecount = 100;
for ($i = count($price1)-1 - $timecount; $i < count($price1); $i++) {
    array_push($price,$price1[$i]);
    array_push($movingaverage,$movingaverage1[$i-14]);

}

// MA Graph
$myDataset = $obv;
$myData = new pData();
$myData->addPoints($movingaverage, "Moving Average");
$myData->addPoints($price, "Price");
$myImage = new pImage(700, 400, $myData);
$myImage->setFontProperties(array(
    "FontName" => "pChart2.1.4/fonts/GeosansLight.ttf",
    "FontSize" => 10));
$myImage->drawText(300,40,"14 Day Moving Average Graph",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
$myImage->drawText(400,350,"Last 100 days",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
$myImage->drawText(40,200,"Price (Dollars)",array("FontSize"=>20, "Angle" => 90,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
$myImage->drawText(60,360,"",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
$myImage->setGraphArea(100,80, 600,300);

$labelskip = 9/1; // values = 1000, hours = 24
$scaleSettings = array("LabelSkip"=>$labelskip);
$myImage->drawScale($scaleSettings);
$myImage->drawLineChart(array("DisplayValues"=>FALSE,"DisplayColor"=>DISPLAY_AUTO));
header("Content-Type: image/png");
$myImage->Render(null);

?>