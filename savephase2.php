<?php
session_start();
include("inc/connection.php");
require("inc/funcstuffs.php");


// Page Variable Declaration
$addpage = $sitepath . "addphase2.php";
$url = $_SESSION["phaseurl"];
$prourl = $sitepath . "phase3.php?id=";
$msgexist = "Phase2 Already Exists";
$msgadded = "Phase2 Added Successfully";
$msgupdate = "Phase2 Updated Successfully";
$id = $_POST['id'];
$tabname = "production_3";

// Check Login Session
if ($_SESSION["sadmin_username"] != "") {

    $today = date("Y-m-d H:i:s");
    $cname = StringRepair($_POST["id"]);
    if ($cname != "") {
        //recordexist(fieldname,tablename,value,id,addpage,manageurl);
        //recordexist($db,$_POST["opt"],$sname,$tabname,$cname,$id,$addpage,$url,$msgexist,'');

        $variation_nos = StringRepair($_POST["variation_nos"]);
        $turning_rejection_nos = StringRepair($_POST["turning_rejection_nos"]);
        $setting_rejection_nos = StringRepair($_POST["setting_rejection_nos"]);
        $pre_machining_rejection_nos = StringRepair($_POST["pre_machining_rejection_nos"]);
        $forging_rejection_nos = StringRepair($_POST["forging_rejection_nos"]);
        $rework_nos = StringRepair($_POST["rework_nos"]);
        $total_q_after_rejection = StringRepair($_POST["total_q_after_rejection"]);


        if ($_POST["opt"] == "Edit") {

            $form_data = array(
                'variation_nos' => $variation_nos,
                'turning_rejection_nos' => $turning_rejection_nos,
                'setting_rejection_nos' => $setting_rejection_nos,
                'pre_machining_rejection_nos' => $pre_machining_rejection_nos,
                'forging_rejection_nos' => $forging_rejection_nos,
                'rework_nos' => $rework_nos,
                'total_q_after_rejection' => $total_q_after_rejection,

            );

            // Updation of Data
            dbRowUpdate($db, $tabname, $form_data, "where production_1=" . $id);

            $_SESSION["sadmin_changeImage_Delete"] = $msgupdate;
        }
    }

    print "<META http-equiv='refresh' content=0;URL='" . $prourl . $id . "'>";
    exit;

} else {
    print "<META http-equiv='refresh' content=0;URL=" . $sitepath . ">";
}
?>