
<?php
require_once("phpChart_Lite/conf.php"); // this must be include in every page that uses phpChart.
?>

<?php
/*
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password, "testdb");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

/*
// Create database
$sql = "CREATE DATABASE testDB";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}


$sql = "Drop TABLE testtable";

if ($conn->query($sql) === TRUE) {
    echo "Table deleted successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

// sql to create table
$sql = "CREATE TABLE testtable (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
price DOUBLE NOT NULL,
reg_date TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$temp1 = 5;
for ($i = 1; $i <= 100; $i++) {
    $temp3 = rand(1,2);
    if ($temp3 == 1)
        $temp1 = $temp1 + rand(10,20);
    if ($temp3 == 2)
        $temp1 = $temp1 - rand(10,20);
    $sql = "INSERT INTO testtable (price)
    VALUES ($temp1)";
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
*/
/*
$sql = "SELECT id, price FROM testtable";
if ($conn->query($sql) === TRUE) {
    echo "DATA OBTAINED successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"] . "VALUE " . $row["price"] . "<br>";
    }
}
    $conn->close();
*/
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>phpChart - Basic Chart</title>
</head>
<body>

<?php

//Code for getting RSI data and displaying RSI graph.
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password, "testdb");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

$sql = "SELECT id, price FROM testtable";
if ($conn->query($sql) === TRUE) {
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
$temp = array();
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        array_push($temp,$row["price"]);
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
for ($i = 1; $i <= count($rs); $i++) {
    array_push($rsi, 100 - 100 / (1 + $rs[$i-1]));
}
for ($i = 1; $i <= count($rs); $i++) {
    array_push($r1, 30);
}
for ($i = 1; $i <= count($rs); $i++) {
    array_push($r2, 70);
}

$pc = new C_PhpChartX(array($rsi,$r1,$r2),'basic_chart');
$pc->set_title(array('text'=>'RSI Chart'));
$pc->set_xaxes(array(
    'xaxis' => array(
        'borderWidth'=>0,
        'borderColor'=>'#ffffff',
        'autoscale'=>true,
        'min'=>'0'
    )));
$pc->set_yaxes(array(
    'yaxis' => array(
        'borderWidth'=>0,
        'borderColor'=>'#ffffff',
        'autoscale'=>true,
        'min'=>'0',
        'max'=>100,
        'numberTicks'=>11
    )));

$pc->draw();
?>

</body>
</html>