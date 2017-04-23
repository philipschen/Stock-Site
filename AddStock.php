<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, td, th {
            border: 1px solid black;
            padding: 5px;
        }
        th {
            text-align: left;
        }
    </style>
</head>
<body>

<h1>My Portfolio</h1>

<br><a href="portfolio.php">Return to My Portfolio</a>

<h3>Add Stocks</h3>

<p>Enter all or part of the Ticker ID or Company name and then click Search Stocks</p>
<form><input type="text" name="Search"><br><input type="submit" value="Search Stocks">

<form method='GET'>

<?php
//Code for getting the list of tickers and company names.
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
$tickers = array();
$tickname = array();
if (isset($_GET['Search'])){

$search = htmlspecialchars($_GET['Search']);

$sql = "SELECT DISTINCT * FROM Tickers WHERE tickerid LIKE '%$search%' OR stockname LIKE '%$search%'";
if ($conn->query($sql) === TRUE) {
    //echo "DATA OBTAINED successfully";
} else {
    //echo "Error: " . $sql . "<br>" . $conn->error;
}
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        array_push($tickers, $row["tickerid"]);
        array_push($tickname, $row["stockname"]);
    }
}
$conn->close();

// Format Results Table
echo "<table>
<tr>
<th>Ticker</th>
<th>Company Name</th>
<th>Action</th>
</tr>";
for ($i = 0; $i < count($tickers); $i++) {
    echo "<tr>";
    echo "<td><input type='submit' name='Search' value = '".$tickers[$i]."'></td>";
    echo "<td>" . $tickname[$i] . "</td>";
    echo "<td><a href=\"add.php?stock=$tickers[$i] \">Add this stock to My Portfolio</a></td>";
    // echo "<td> Add " . $tickers[$i] . " to your Portfolio </td>";
    echo "</tr>";
}
echo "</table>";
}


 
?>
</form>
</body>
</html>