<?php
session_start();
include("inc/connection.php");
require("inc/funcstuffs.php");

// Page Variable Declaration
$addpage = $sitepath . "addjob.php";
$url = $sitepath . "managejob.php";
$msgexist = "Job No. Already Exists";
$msgadded = "Job No. Added Successfully";
$msgupdate = "Job No. Updated Successfully";
$id = $_POST['id'];
$tabname = "joblist";
$sname = "jobno";

// Check Login Session
if ($_SESSION["sadmin_username"] != "") {

    $today = date("Y-m-d H:i:s");
    $cname = StringRepair($_POST["jobno"]);
    if ($cname != "") {
        //recordexist(fieldname,tablename,value,id,addpage,manageurl);
        recordexist($db, $_POST["opt"], $sname, $tabname, $cname, $id, $addpage, $url, $msgexist, '');

        $jobno = StringRepair($_POST["jobno"]);
        $status = 0;
        if ($_POST["status"] != "") {
            $status = 1;
        }

        if ($_POST["opt"] == "Add") {

            $form_data = array(
                'jobno' => $jobno,
                'status' => $status
            );
            // Insertion of Data
            dbRowInsert($db, $tabname, $form_data);

            $_SESSION["sadmin_changeImage_Delete"] = $msgadded;
        } elseif ($_POST["opt"] == "Edit") {


            $form_data = array(
                'jobno' => $jobno,
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