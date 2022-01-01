<?php
session_start();
include("inc/connection.php");
require("inc/funcstuffs.php");

// Page Variable Declaration
$addpage = $sitepath . "addoperator.php";
$url = $sitepath . "manageoperator.php";
$msgexist = "Operator Already Exists";
$msgadded = "Operator Added Successfully";
$msgupdate = "Operator Updated Successfully";
$id = $_POST['id'];
$tabname = "operator";
$sname = "operator";

// Check Login Session
if ($_SESSION["sadmin_username"] != "") {

    $today = date("Y-m-d H:i:s");
    $cname = StringRepair($_POST["operator"]);
    if ($cname != "") {
        //recordexist(fieldname,tablename,value,id,addpage,manageurl);
        recordexist($db, $_POST["opt"], $sname, $tabname, $cname, $id, $addpage, $url, $msgexist, '');

        $operator = StringRepair($_POST["operator"]);
        $mobile = StringRepair($_POST["mobile"]);
        $status = 0;
        if ($_POST["status"] != "") {
            $status = 1;
        }
  /*      $email = StringRepair($_POST["email"]);
        $address = StringRepair($_POST["address"]);
        $salary= StringRepair($_POST["salary"]);
        $joining = StringRepair($_POST["joining"]);

        $date = DateTime::createFromFormat('d/m/Y', $joining);
        $joining = $date->format('Y-m-d');

         */
        if ($_POST["opt"] == "Add") {

            $form_data = array(
                'operator' => $operator,
                'mobile' => $mobile,
                'status' => $status
            );
            // Insertion of Data
            dbRowInsert($db, $tabname, $form_data);

            $_SESSION["sadmin_changeImage_Delete"] = $msgadded;
        } elseif ($_POST["opt"] == "Edit") {


            $form_data = array(
                'operator' => $operator,
                'mobile' => $mobile,
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