<?php
//include "admin\checksession.php";
//checkUser('AC_MANAGER');
//loginStatus();

include "elements\header.php";
include "elements\menu.php";
echo '<div id="site_content">';
include "elements\sidebar.php";

echo '<div id="content">';
include "elements\privacystatement.php";

echo '</div></div>';
echo '<div id="footer">';
include "elements\footer.php";
?>