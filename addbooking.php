<?php
//include "admin\checksession.php";
//checkUser('AC_MANAGER');
//loginStatus();

include "elements\header.php";
include "elements\menu.php";
echo '<div id="site_content">';
include "elements\sidebar.php";

echo '<div id="content">';
?>
<script>

function searchResult(searchstr) {
  if (searchstr.length==0) {
    document.getElementById("memberlist").innerHTML="";
    return;
  }
  xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById("memberlist").innerHTML=this.responseText;
    }
  }
  xmlhttp.open("GET","roomSearch.php?sq="+searchstr,true);
  xmlhttp.send();
}
</script>
<?php

//this line is for debugging purposes so that we can see the actual POST data
echo "<pre>"; var_dump($_POST); echo "</pre>";

//function to clean input but not validate type and content
function cleanInput($data) {
  return htmlspecialchars(stripslashes(trim($data)));
}
//the data was sent using a form therefore we use the $_POST instead of $_GET
//check if we are saving data first by checking if the submit button exists in the array
if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Register')) {
//if ($_SERVER["REQUEST_METHOD"] == "POST") { //alternative simpler POST test
    include "admin\config.php"; //load in any variables
    $DBC = mysqli_connect("127.0.0.1", DBUSER, DBPASSWORD, DBDATABASE);

    if (mysqli_connect_errno()) {
        echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
        exit; //stop processing the page further
    };

//roomID
    $error = 0; //clear our error flag
    $msg = 'Error: ';
    if (isset($_POST['roomID']) and !empty($_POST['roomID'])) {
       $fn = cleanInput($_POST['roomID']);
 //check length and clip if too big
       $roomID = (strlen($fn) > 3)?substr($fn,1,3):$fn;
       //we would also do context checking here for contents, etc
    } else {
       $error++; //bump the error flag
       $msg .= 'Invalid roomID '; //append error message
       $roomID = '';
    }
//customerID
	if (isset($_POST['customerID']) and !empty($_POST['customerID'])) {
       $ln = cleanInput($_POST['customerID']);
 //check length and clip if too big
       $customerID = (strlen($ln) > 3)?substr($ln,1,3):$ln;
       //we would also do context checking here for contents, etc
    } else {
       $error++; //bump the error flag
       $msg .= 'Invalid customerID '; //append error message
       $customerID = '';
    }
	//contactNo
	if (isset($_POST['contactNo']) and !empty($_POST['contactNo'])) {
       $em = cleanInput($_POST['contactNo']);
 //check length and clip if too big
       $contactNo = (strlen($em) > 10)?substr($em,1,10):$em;
       //we would also do context checking here for contents, etc
    } else {
       $error++; //bump the error flag
       $msg .= 'Invalid contact Number '; //append error message
       $contactNo = '';
    }
	//checkindate
	if (isset($_POST['checkInDate']) and !empty($_POST['checkInDate'])) {
       $un = cleanInput($_POST['checkInDate']);
 //check length and clip if too big
       $checkInDate = (strlen($un) > 8)?substr($un,1,8):$un;
       //we would also do context checking here for contents, et
    } else {
       $error++; //bump the error flag
       $msg .= 'Invalid check in date '; //append error message
       $checkInDate = '';
    }
    //checkOutdate
	if (isset($_POST['checkOutDate']) and !empty($_POST['checkOutDate'])) {
   $pw = cleanInput($_POST['checkOutDate']);
//check length and clip if too big
   $checkOutDate = (strlen($pw) > 8)?substr($pw,1,8):$pw;
   //we would also do context checking here for contents, etc
} else {
   $error++; //bump the error flag
   $msg .= 'Invalid check out date '; //append error message
   $checkOutDate = '';
}
	//extras
	if (isset($_POST['extras']) and !empty($_POST['extras'])
        and is_string($_POST['extras'])) {
       $pw = cleanInput($_POST['extras']);
 //check length and clip if too big
       $extras = (strlen($pw) > 500)?substr($pw,1,500):$pw;
       //we would also do context checking here for contents, etc
    } else {
       $error++; //bump the error flag
       $msg .= 'Invalid extras '; //append error message
       $extras = '';
    }
//save the member data if the error flag is still clear
    if ($error == 0) {
        $query = "INSERT INTO booking (`roomID`,`customerID`,`contactNo`,`checkInDate`,`checkOutDate`,'extras') VALUES (?,?,?,?,?,?)";
        $stmt = mysqli_prepare($DBC,$query); //prepare the query
        mysqli_stmt_bind_param($stmt,'iiiiis', $roomID, $customerID, $contactNo,$checkInDate,$checkOutDate,$extras);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo "<h2>Booking saved</h2>";
    } else {
      echo "<h2>$msg</h2>".PHP_EOL;
    }
    mysqli_close($DBC); //close the connection once done
}
?>
<h1>Booking Registration</h1>
<h2><a href='listbookings.php'>[Return to the Bookings listing]</a></h2>

<form method="POST" action="newbooking.php">
  <p>
    <label for="roomID">Room ID: </label>
    <input type="int" id="roomID" name="roomID" minlength="1" maxlength="3" required>
  </p>
  <p>
    <label for="customerID">Customer ID: </label>
    <input type="int" id="customerID" name="customerID" minlength="1" maxlength="3" required>
  </p>
  <p>
    <label for="contactNo">Contact Number: </label>
    <input type="int" id="contactNo" name="contactNo" maxlength="10" required>
   </p>
  <p>
    <label for="checkInDate">Check In Date: </label>
    <input type="int" id="checkInDate" name="checkInDate" minlength="8" required onkeyup="searchResult(this.value)" onclick="javascript: this.value = ''">
  </p>
  <p>
    <label for="checkOutDate">Check Out Date: </label>
    <input type="int" id="checkOutDate" name="checkOutDate" minlength="8" required onkeyup="searchResult(this.value)" onclick="javascript: this.value = ''">
  </p>
  <p>
    <label for="extras">Extras: </label>
    <input type="text" id="extras" name="extras" minlength="0" maxlength="500" required>
  </p>
   <input type="submit" name="submit" value="Register">
</form>
<?php
echo '</div></div>';
echo '<div id="footer">';
include "elements\footer.php";
?>