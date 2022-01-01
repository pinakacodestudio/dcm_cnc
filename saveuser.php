<?php
session_start();
include("inc/connection.php");
require("inc/funcstuffs.php");

// Page Variable Declaration
$addpage = $sitepath . "adduser.php";
$url = $sitepath . "manageuser.php";
$msgexist = "User Already Exists";
$msgadded = "User Added Successfully";
$msgupdate = "User Updated Successfully";
$id = $_POST['id'];
$tabname = "company";
$sname = "username";

// Check Login Session
if ($_SESSION["sadmin_username"] != "") {

    $today = date("Y-m-d H:i:s");
    $cname = StringRepair($_POST["username"]);
    if ($cname != "") {
        //recordexist(fieldname,tablename,value,id,addpage,manageurl);
        recordexist($db, $_POST["opt"], $sname, $tabname, $cname, $id, $addpage, $url, $msgexist, '');

        $username = StringRepair($_POST["username"]);
        $fullname = StringRepair($_POST["fullname"]);
        $mobile = StringRepair($_POST["mobile"]);
        $password = StringRepair($_POST["password"]);
        $usertype = StringRepair($_POST["usertype"]);

        $status = 0;
        if ($_POST["status"] != "") {
            $status = 1;
        }

        if ($_POST["opt"] == "Add") {

            $form_data = array(
                'username' => $username,
                'fullname' => $fullname,
                'mobile' => $mobile,
                'password' => $password,
                'usertype' => $usertype,
                'status' => $status
            );
            // Insertion of Data
            dbRowInsert($db, $tabname, $form_data);

            $_SESSION["sadmin_changeImage_Delete"] = $msgadded;
        } elseif ($_POST["opt"] == "Edit") {


            $form_data = array(
                'username' => $username,
                'fullname' => $fullname,
                'mobile' => $mobile,
                'password' => $password,
                'usertype' => $usertype,
                'status' => $status
            );

            // Updation of Data
            dbRowUpdate($db, $tabname, $form_data, "where id=" . $id);

            $_SESSION["sadmin_changeImage_Delete"] = $msgupdate;
        }
    }

    print "<META http-equiv='refresh' content=0;URL='" . $url . "'>";
    exit;

} else {
    print "<META http-equiv='refresh' content=0;URL=" . $sitepath . ">";
}
?>