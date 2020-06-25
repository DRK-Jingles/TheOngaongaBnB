<?php
//include "admin\checksession.php";
//checkUser('AC_MANAGER');
//loginStatus();

include "elements\header.php";
include "elements\menu.php";
echo '<div id="site_content">';
include "elements\sidebar.php";

echo '<div id="content">';

include "admin\config.php"; //load in any variables
$DBC = mysqli_connect("127.0.0.1", DBUSER, DBPASSWORD, DBDATABASE);

//insert DB code from here onwards
//check if the connection was good
if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
    exit; //stop processing the page further
}
//this line is for debugging purposes so that we can see the actual POST/GET data
//echo "<pre>"; var_dump($_POST); var_dump($_GET);echo "</pre>";

//function to clean input but not validate type and content
function cleanInput($data) {
  return htmlspecialchars(stripslashes(trim($data)));
}
//retrieve the id from the URL
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = $_GET['id'];
    if (empty($id) or !is_numeric($id)) {
        echo "<h2>Invalid bookingID</h2>"; //simple error feedback
        exit;
    }
}
//check if we are saving data first by checking if the submit button exists in the array
if (isset($_POST['submit']) and !empty($_POST['submit'])
    and ($_POST['submit'] == 'Delete')) {
    $error = 0; //clear our error flag
    $msg = 'Error: ';
//ID (sent via a form it is a string not a number so we try a type conversion!)
    if (isset($_POST['id']) and !empty($_POST['id'])
        and is_integer(intval($_POST['id']))) {
       $id = cleanInput($_POST['id']);
    } else {
       $error++; //bump the error flag
       $msg .= 'Invalid booking ID '; //append error message
       $id = 0;
    }
//save the  data if the error flag is still clear and id is > 0
    if ($error == 0 and $id > 0) {
        $query = "DELETE FROM booking WHERE bookingID=?";
        $stmt = mysqli_prepare($DBC,$query); //prepare the query
        mysqli_stmt_bind_param($stmt,'i', $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo "<h2>Booking deleted.</h2>";
    } else {
      echo "<h2>$msg</h2>".PHP_EOL;
    }
}

//prepare a query and send it to the server
$query = "SELECT * FROM booking WHERE bookingid=".$id;
$result = mysqli_query($DBC,$query);
$rowcount = mysqli_num_rows($result);
?>
<h1>Booking details view</h1>
<h2><a href='listbookings.php'>[Return to the booking listing]</a></h2>
<?php

if ($rowcount > 0) {
   echo "<fieldset><legend>Booking detail #$id</legend><dl>";
   $row = mysqli_fetch_assoc($result);
   echo "<dt>Booking ID:</dt><dd>".$row['bookingID']."</dd>".PHP_EOL;
   echo "<dt>Room ID:</dt><dd>".$row['roomID']."</dd>".PHP_EOL;
   echo "<dt>Customer ID:</dt><dd>".$row['customerID']."</dd>".PHP_EOL;
   echo "<dt>Contact No:</dt><dd>".$row['contactNo']."</dd>".PHP_EOL;
   echo "<dt>Check In Date:</dt><dd>".$row['checkInDate']."</dd>".PHP_EOL;
   echo "<dt>Check Out Date:</dt><dd>".$row['checkOutDate']."</dd>".PHP_EOL;
   echo "<dt>Extras:</dt><dd>".$row['extras']."</dd>".PHP_EOL;
   echo "<dt>Review:</dt><dd>".$row['review']."</dd>".PHP_EOL;
   echo '</dl></fieldset>'.PHP_EOL;
   ?><form method="POST" action="deletebooking.php">
     <h2>Are you sure you want to delete this booking?(DONT CLICK DELETE!!)</h2>
     <input type="hidden" name="id" value="<?php echo $id; ?>">
     <input type="submit" name="submit" value="Delete">
     <a href="listbookings.php">[Cancel]</a>
     </form>
<?php
} else echo "<h2>No booking found, possibly deleted!</h2>"; //suitable feedback

mysqli_free_result($result); //free any memory used by the query
mysqli_close($DBC); //close the connection once done

echo '</div></div>';
echo '<div id="footer">';
include "elements\footer.php";
?>