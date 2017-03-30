<?php
session_start();

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
$sql = "SELECT username, password FROM users";
if ($conn->query($sql) === TRUE) {
} else {
    //echo "Error: " . $sql . "<br>" . $conn->error;
}
$username = array();
$password = array();
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        array_push($username,$row["username"]);
        array_push($password,$row["password"]);
    }
}
if (!empty($_GET['username'])
    && !empty($_GET['password'])) {
    $temp1 = 0;
    for ($i = 0; $i < count($username); $i++){
        if ($_GET['username'] == $username[$i] &&
            $_GET['password'] == $password[$i]) {
            $temp1 = 1;
            setcookie("cookieid",$_GET['username'], time()+3600);
            echo '<meta HTTP-EQUIV="REFRESH" content="0; url=portfolio.php">';
        }
    }
    // UNIT TEST
    // $checklogin=fopen("checklogin.txt", or die("checklogin file unaccessible");
    // $txt="Login class accessible\n";
    // fwrite($checkloginFile, $txt);  //This test will check userman and password to see if they match th
    to see if they match the ones in our data base and then let the user in.

    if ($temp1 == 0)
        echo '<script>alert("Wrong Username of Password")</script>';
}
$conn->close();

//fclose($checkloginFile);

?>

<html>
<body>
<div align="center">
    <div align="center" style="float: left; width:auto;">
        <font size=+10">StockWatch</font><img src="stopwatch.jpg" "alt=https://www.wpclipart.com/dl.php?img=/time/stopwatch/running_stopwatch.jpg" width="60" height="60">
        <br><div align="left"><font size=+2">Time is Money!</font>
            <hr style="width: 110%; margin-left: -5%;"/>

        </div>
        <form>
            Username:<br>
            <input type="text" name="username"><br>
            Password:<br>
            <input type="password" name="password"><br>
            <br>
            <input type="submit" value="Sign in!" name = "submit">
        </form>
    </div>

</body>
</html>
