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
} 
else {
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


$arr;
 $t;
$array;
php://stdin;

/*$m = 0;*/

function iscup($switch){
    global $m;
    global $t;


    // Very important DO NOT DELETE!!!!
    /*
    if($switch == 1){
        $arr = array(50,50,50,50,50,50,50,50,50,50,55,53,50,49,48,47,40,41,42,43,44,45,53,50,49,48,47,46);
        $t = sizeof($arr);
    }
    if($switch == 2){
        $arr = array(20,30,40,50,60,70,80,90,95,94,92,82,80,74,69,62,68,69,76,79,83,89,84,83,81,80,78);
        $t = sizeof($arr);
    }
    if($switch == 3){
        $arr = array(1);
        $t = sizeof($arr);
    }
    if($switch == 4){
        $arr = array(50,50,50,50,50,50,50,50,50,50,55,34,31,48,51,51,51,51,51,51,51,51,51,51,51,51,51,51,51);
        $t = sizeof($arr);
    }
    if($t<26){
        echo"Pattern not Found0!";
        return false;
    }
    */
    // Very important DO NOT DELETE!!!!
    
   $a = 0.10; /*fluctuating toleration*/
    $b = 0.33; /*cup depth criteria*/
    $x = 0.10; /*RH,LH*/
    $d = 0.15; /*handle depth*/
    $obsPoint = $t;
    $high = $t; /* t > 26, suggest 52 weeks or longer*/

  /*start from time t, trace back to find Righ High Point */
    while ($arr[$t-2] >= $arr[$t-1] || $arr[$t-2] < $arr[$t-1]&& $arr[$t-2]>=(1-$a)*$high){
        if($arr[$t-2] >= $arr[$t-1]){
          $high = $arr[$t-2];
        }
        $t--;
    }

  $Right_High = $t; /*found Right High*/

  /*start from Right High, trace back to find Dip Point */
    $low = $t;
    while ($arr[$t-2] <= $arr[$t-1] || $arr[$t-2] > $arr[$t-1]&& $arr[$t-2]<=(1+$a)*$low){
      if($arr[$t-2] <= $arr[$t-1]){
        $low = $arr[$t-2];
      }
      $t--;
    }
    

  $Dip_Point = $t; /*found Dip Point*/

  /*start from Dip, trace back to find Left High Point*/
    
    while (($arr[$t-1] >= $arr[$t-0]) || ($arr[$t-1] < $arr[$t-0]) && ($arr[$t-1]>=(1-$a)*$high)){
       if($t-1 == 0){
           break;
       }
       if($arr[$t-1] >= $arr[$t-0]){
        $high = $arr[$t-1];
       }
       $t--;
    }

  $Left_High = $t; /*found Left High*/
    if($obsPoint!=$Right_High){
        if(abs( $arr[$Right_High-1] - $arr[$obsPoint-1])< $x*$arr[$Right_High-1]){
            echo "Pattern not Found1!";
            echo "\n Breaks rule 3!"  
            return false; /*rule 3*/
        }
    }
    if($Right_High-$Dip_Point < 2 || $Right_High-$Dip_Point > 8){
        echo "Pattern not Found!2";
         echo "\n Breaks rule 5!"  
        return false; /*shakeout handle is too long or too short (rule 5)*/
    }
    
    if($arr[$Right_High]-$arr[$Dip_Point]> $b * $arr[$Right_High]){
        echo "Pattern not Found3!";
        echo "\n Breaks rule 2!"  
        return false; /*because too dip (rule 2)*/
      }
    
    if($Right_High-$Left_High < 12 && $Right_High-$Left_High >26){
        echo "Pattern not Found!4";
        echo "\n Breaks rule 4!"  
        return false; /*because cup is too narrow or too wide (rule 4)*/
      }
    
    if(abs($arr[$Right_High]-$arr[$Left_High])> $d*$arr[$Right_High]){
        echo "Pattern not Found!5";
        echo "\n Breaks rule 1!"  
        return false; /*rule 1*/
      }
      
    echo "Pattern found!";
    echo '<img src="CupTable.php" alt="Alt text" />';
}



    echo "Choose an number from 0-4";
    $line = trim(fgets(STDIN));
    fscanf(STDIN, "%d\n", $number);
    if($number > 4 || $number < 0)
        break;
    else
        iscup($number);

    ?>