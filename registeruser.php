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

// Check to see if password is correct
if (isset($_REQUEST["submit"]) && !empty($_GET['user'])
    && !empty($_GET['pass'])&& !empty($_GET['fname'])&& !empty($_GET['cpass']) && !empty($_GET['lname'])) {

    for ($i = 0; $i < count($username); $i++){
        if ($_GET['user'] == $username[$i]) {
            //echo '<script>alert("Username is in use")</script>';
        }
        else{
            if($_GET['pass'] == $_GET['cpass']){
                $sql = "INSERT INTO users (userid, username, name, lastname, password)
    VALUES ('".$_GET['user']."', '".$_GET['user']."', '".$_GET['fname']."', '".$_GET['lname']."', '".$_GET['pass']."')";
            $conn->query($sql);
            $sql = "INSERT INTO myportfolio (userid, tickerid, stockname, earningspershare, dividends)
    VALUES ('".$_GET['user']."', 'APPL', 'APPLE Computers', 9.0, 2.4)";
                $conn->query($sql);
                echo '<script>alert("Account Created")</script>';
                echo '<meta HTTP-EQUIV="REFRESH" content="0; url=CheckLogin.php">';
            }
            else{
                echo '<script>alert("Passwords do not match")</script>';
            }

        }
    }

}
elseif(isset($_REQUEST["submit"])){
    echo '<script>alert("Please Enter All Fields")</script>';
}

$conn->close();


?>


<!DOCTYPE HTML>
<html>
<head>
    <title>Sign-Up</title> </head>
<body id="body-color">
<div id="Sign-Up">
    <fieldset style="width:30%"><legend>Registration Form</legend>
        <table border="0">
            <tr>
                <form method="GET">
                    <td>First Name</td><td> <input type="text" name="fname"></td>
            </tr>
            <tr>
                <td>Last Name</td><td> <input type="text" name="lname"></td>
            </tr>
            <tr>
                <td>UserName</td><td> <input type="text" name="user"></td>
            </tr>
            <tr>
                <td>Password</td><td> <input type="password" name="pass"></td>
            </tr>
            <tr>
                <td>Confirm Password </td><td><input type="password" name="cpass"></td>
            </tr>
            <tr>
                <td><input type="submit" name="submit" value="Sign-Up"></td>
            </tr>
            </form>
        </table>
    </fieldset>
</div>
</body>
</html>




