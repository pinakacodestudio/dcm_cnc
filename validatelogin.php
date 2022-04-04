<?php
session_start();
include("inc/connection.php");
include("inc/functions.php");
$tab_user_info = "company";


$sql = "select * from $tab_user_info where status=1 and usertype=1 and username='" . mysqli_real_escape_string($db, StringRepair($_POST["username"])) . "' and password='" . mysqli_real_escape_string($db, StringRepair($_POST["password"])) . "'";
$rs = $db->query($sql) or die("cannot Select User" . $db->error);
if ($rs->num_rows > 0) {
    $today = date("Y-m-d H:i:s");
    $rsw = $rs->fetch_assoc();

    $_SESSION["sadmin_username"] = mysqli_real_escape_string($db, $_POST["username"]);
    $_SESSION["sadmin_id"] = $rsw["id"];
    $_SESSION["sadmin_loginfor"] = "corporate_uniquedemo";

    print "<META http-equiv='refresh' content=0;URL=" . $sitepath . "home.php>";
} else {

    $_SESSION["slogin_Error"] = "Invalid User name/Password";
    print "<META http-equiv='refresh' content=0;URL=" . $sitepath . ">";
}
?>