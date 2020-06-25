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
$DBC = mysqli_connect("127.0.0.1", DBUSER, DBPASSWORD, DBDATABASE)or die();

if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
    exit;
}

$query = "SELECT bookingID,roomID,customerID FROM booking ORDER BY bookingID";
$result = mysqli_query($DBC,$query);

$rowcount = mysqli_num_rows($result);
?>
<h1>Booking list</h1>
<h2>Booking count <?php echo $rowcount; ?></h2>
<h2><a href='addbooking.php'>[Create new Booking]</a></h2>
<table border="1">
<thead><tr><th>Booking ID</th><th>Room ID</th><th>Customer ID</th><th>Actions</th></tr></thead>
<?php

//makes sure we have members
if ($rowcount > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
	  $id = $row['bookingID'];
	  echo '<tr><td>'.$row['bookingID'].'</td><td>'.$row['roomID'].'</td><td>'.$row['customerID'].'</td>';
      echo     '<td><a href="viewbooking.php?id='.$id.'">[view]</a>';
      echo     '<a href="editbooking.php?id='.$id.'">[edit]</a>';
      echo     '<a href="editbookingreview.php?id='.$id.'">[edit review]</a>';
	  echo     '<a href="deletebooking.php?id='.$id.'">[delete]</a></td>';
      echo '</tr>'.PHP_EOL;
   }
} else echo "<h2>No bookings found!</h2>"; //suitable feedback

mysqli_free_result($result);
mysqli_close($DBC);

echo '</div></div>';
echo '<div id="footer">';
include "footer.php";
?>