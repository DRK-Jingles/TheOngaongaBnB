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

//retrieve the memberid from the URL
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = $_GET['id'];
    if (empty($id) or !is_numeric($id)) {
        echo "<h2>Invalid Booking ID</h2>"; //simple error feedback
        exit;
    }
}
/* the data was sent using a form therefore we use the $_POST instead of $_GET
   check if we are saving data first by checking if the submit button exists in
   the array */
if (isset($_POST['submit']) and !empty($_POST['submit'])
    and ($_POST['submit'] == 'Update')) {
/* validate incoming data - only the first field is done for
   you in this example - rest is up to you do*/
    $error = 0; //clear our error flag
    $msg = 'Error: ';
    if (isset($_POST['id']) and !empty($_POST['id'])
        and is_integer(intval($_POST['id']))) {
       $id = cleanInput($_POST['id']);
    } else {
       $error++; //bump the error flag
       $msg .= 'Invalid Booking ID '; //append error message
       $id = 0;
    }
       $roomID = cleanInput($_POST['roomID']);
       $customerID = cleanInput($_POST['customerID']);
       $contactNo = cleanInput($_POST['contactNo']);
       $checkInDate = cleanInput($_POST['checkInDate']);
       $checkOutDate = cleanInput($_POST['checkOutDate']);
       $extras = cleanInput($_POST['extras']);
       $review = cleanInput($_POST['review']);
    if ($error == 0 and $id > 0) {
        $query = "UPDATE booking SET roomID=?,customerID=?,contactNo=?,checkInDate=?,checkOutDate=?,extras=?,review=? WHERE bookingID=?";
        $stmt = mysqli_prepare($DBC,$query); //prepare the query
        mysqli_stmt_bind_param($stmt,'iiiiiss', $roomID, $customerID, $contactNo, $checkInDate,$checkOutDate, $extras, $review);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo "<h2>Booking details updated.</h2>";
    } else {
      echo "<h2>$msg</h2>".PHP_EOL;
    }
}
$query = 'SELECT bookingID,roomID,customerID,contactNo,checkInDate,checkOutDate,extras,review FROM booking WHERE bookingID='.$id;
$result = mysqli_query($DBC,$query);
$rowcount = mysqli_num_rows($result);
if ($rowcount > 0) {
  $row = mysqli_fetch_assoc($result);
?>
<h1>Booking update</h1>
<h2><a href='listbookings.php'>[Return to the bookings listing]</a></h2>

<form method="POST" action="editbooking.php">
  <input type="hidden" name="id" value="<?php echo $id;?>">
  <p>
    <label for="roomID">Room ID: </label>
    <input type="int" id="roomID" name="roomID" minlength="1"
           maxlength="3" required value="<?php echo $row['roomID']; ?>">
  </p>
  <p>
    <label for="customerID">Customer ID: </label>
    <input type="int" id="customerID" name="customerID" minlength="1"
           maxlength="3" required value="<?php echo $row['customerID']; ?>">
  </p>
  <p>
    <label for="contactNo">Contact No: </label>
    <input type="int" id="contactNo" name="contactNo" maxlength="10">
   </p>
   <!--needs calender inplemented in next part!-->
  <p>
    <label for="checkInDate">Check In Date: </label>
    <input type="int" id="checkInDate" name="checkInDate" minlength="8"
     required  value="<?php echo $row['checkInDate']; ?>">
  </p>
  <p>
    <label for="checkOutDate">Check Out Date: </label>
    <input type="int" id="checkOutDate" name="checkOutDay" minlength="8"
     required  value="<?php echo $row['checkOutDate']; ?>">
  </p>
  <p>
    <label for="extras">Extras: </label>
    <input type="text" id="extras" name="extras" minlength="0"
           maxlength="500">
  </p>
  <p>
    <label for="review">Review: </label>
    <input type="text" id="review" name="review" minlength="0"
           maxlength="500" required  value="<?php echo $row['review']; ?>">
  </p>
   <input type="submit" name="submit" value="Update">
   <a href="listbookings.php">[Cancel]</a>
 </form>
<?php
} else {
  echo "<h2>Booking not found with that ID</h2>"; //simple error feedback
}
mysqli_close($DBC); //close the connection once done

echo '</div></div>';
echo '<div id="footer">';
include "elements\footer.php";
?>