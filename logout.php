<?php
session_start();
include("inc/connection.php");
$_SESSION["sadmin_username"] = "";
$_SESSION["sadmin_id"] = "";
$_SESSION["sadmin_subid"] = "";
$_SESSION["sadmin_city"] = "";
$_SESSION["sadmin_email"] = "";
$_SESSION["sadmin_login"] = "";
$_SESSION["sadmin_loginfor"] = "";
$_SESSION["sadmin_site_rights"] = "";
print "<META http-equiv='refresh' content=0;URL=" . $sitepath . ">";
?>