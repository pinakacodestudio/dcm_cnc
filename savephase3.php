<?php
session_start();
include("inc/connection.php");
require("inc/funcstuffs.php");


// Page Variable Declaration
$addpage = $sitepath . "addphase3.php";
$url = $sitepath . "manageproduction.php";
$msgexist = "Phase3 Already Exists";
$msgadded = "Phase3 Added Successfully";
$msgupdate = "Phase3 Updated Successfully";
$id = $_POST['id'];
$tabname = "production_machine_breakdown";
$tab2 = "production_3";
// Check Login Session
if ($_SESSION["sadmin_username"] != "") {

    $today = date("Y-m-d H:i:s");
    $cname = StringRepair($_POST["id"]);
    if ($cname != "") {
        //recordexist(fieldname,tablename,value,id,addpage,manageurl);
        //recordexist($db,$_POST["opt"],$sname,$tabname,$cname,$id,$addpage,$url,$msgexist,'');

        $setting_hour = StringRepair($_POST["setting_hour"]);
        $machine_fault_hour = StringRepair($_POST["machine_fault_hour"]);
        $recess_hour = StringRepair($_POST["recess_hour"]);
        $maintanance_hour = StringRepair($_POST["maintanance_hour"]);
        $no_operator_hour = StringRepair($_POST["no_operator_hour"]);
        $no_load_hour = StringRepair($_POST["no_load_hour"]);
        $power_fail_hour = StringRepair($_POST["power_fail_hour"]);
        $other = StringRepair($_POST["other"]);
        $rework = StringRepair($_POST["rework"]);
        $total_breakdown_hours = StringRepair($_POST["total_breakdown_hours"]);
        $total_q_after_rejection = StringRepair($_POST["total_q_after_rejection"]);

        $expected_q = StringRepair($_POST["expected_q"]);
        $setting_hr = StringRepair($_POST["setting_hr"]);
        $production_loss_increase_q = StringRepair($_POST["production_loss_increase_q"]);

        $production_per = ($total_q_after_rejection * 100) / $expected_q;

        if ($_POST["opt"] == "Edit") {


            $form_data = array(
                'setting_hour' => $setting_hour,
                'machine_fault_hour' => $machine_fault_hour,
                'recess_hour' => $recess_hour,
                'maintanance_hour' => $maintanance_hour,
                'no_operator_hour' => $no_operator_hour,
                'no_load_hour' => $no_load_hour,
                'power_fail_hour' => $power_fail_hour,
                'rework' => $rework,
                'other' => $other,
                'total_breakdown_hours' => $total_breakdown_hours
            );

            // Updation of Data
            dbRowUpdate($db, $tabname, $form_data, "where production_1=" . $id);


            $form_data = array(
                'setting_hr' => $setting_hr,
                'expected_q' => $expected_q,
                'production_loss_increase_q' => $production_loss_increase_q,
                'production_per' => $production_per
            );

            // Updation of Data
            dbRowUpdate($db, $tab2, $form_data, "where production_1=" . $id);


            $_SESSION["sadmin_changeImage_Delete"] = $msgupdate;
        }
    }

    print "<META http-equiv='refresh' content=0;URL='" . $url . "'>";
    exit;

} else {
    print "<META http-equiv='refresh' content=0;URL=" . $sitepath . ">";
}
?>