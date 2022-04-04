<?php

//$db_username = "jjasanic_user";
$db_username = "root";
//$db_password = "t]=;M1A]zb%C";
$db_password = "";
$db_name = "jjasani_dcmindustries";
$db_host = "localhost";

//$sitepath = "http://cnc.dcmindustries.co.in/";
$sitepath = "http://localhost/cnc/";

set_time_limit(0);
ini_set('display_errors', 0);
//Create Connection ...
$db = new mysqli($db_host, $db_username, $db_password, $db_name);
// Check Connection...
if ($db->connect_error) {
	echo "Error: Unable to connect to MySQL." . $db->connect_error;
	echo "Debugging errno: " . $db->connect_errno;
	exit;
}

if ($_SESSION["sadmin_username"] != "") {
	$user_type_admin = base64_decode($_SESSION["sadmin_site_rights"]);
	if ($_SESSION["sadmin_loginfor"] == "") {
		$_SESSION["sadmin_username"] = "";
		$_SESSION["sadmin_id"] = "";
		$_SESSION["sadmin_subid"] = "";
		$_SESSION["sadmin_city"] = "";
		$_SESSION["sadmin_email"] = "";
		$_SESSION["sadmin_login"] = "";
		$_SESSION["sadmin_loginfor"] = "";
		$_SESSION["sadmin_site_rights"] = "";
		print "<META http-equiv='refresh' content=0;URL=" . $sitepath . ">";
		exit;
	} else {
		if ($_SESSION["sadmin_loginfor"] != "corporate_uniquedemo") {
			$_SESSION["sadmin_username"] = "";
			$_SESSION["sadmin_id"] = "";
			$_SESSION["sadmin_subid"] = "";
			$_SESSION["sadmin_city"] = "";
			$_SESSION["sadmin_email"] = "";
			$_SESSION["sadmin_login"] = "";
			$_SESSION["sadmin_loginfor"] = "";
			$_SESSION["sadmin_site_rights"] = "";
			$_SESSION["slogin_Error"] = "Your account had been Logged Out. <br> Please contact administrator for the detail.";
			print "<META http-equiv='refresh' content=0;URL=" . $sitepath . ">";
			exit;
		}
	}
}
