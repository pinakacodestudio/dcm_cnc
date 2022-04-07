<?php
session_start();
include("inc/connection.php");
require("inc/funcstuffs.php");


// Page Variable Declaration
$addpage= $sitepath."addphase1.php";
$url = $_SESSION["phaseurl"];
$prourl = $sitepath."phase2.php?id=";
$msgexist = "Phase1 Already Exists";
$msgadded = "Phase1 Added Successfully";
$msgupdate = "Phase1 Updated Successfully";
$id = $_POST['id'];
$tabname ="production_2";
$tab2 ="production_1";

// Check Login Session
if($_SESSION["sadmin_username"]!="")
{

    $today=date("Y-m-d H:i:s");
    $cname=StringRepair($_POST["id"]);
    if($cname!="")
    {
        //recordexist(fieldname,tablename,value,id,addpage,manageurl);
        //recordexist($db,$_POST["opt"],$sname,$tabname,$cname,$id,$addpage,$url,$msgexist,'');

        $t1 = StringRepair($_POST["time_1"]);
        $t2 = StringRepair($_POST["time_2"]);
        $t3 = StringRepair($_POST["time_3"]);
        $t4 = StringRepair($_POST["time_4"]);
        $t5 = StringRepair($_POST["time_5"]);
        $t6 = StringRepair($_POST["time_6"]);
        $t7 = StringRepair($_POST["time_7"]);
        $t8 = StringRepair($_POST["time_8"]);
        $t9 = StringRepair($_POST["time_9"]);
        $t10 = StringRepair($_POST["time_10"]);
        $t11 = StringRepair($_POST["time_11"]);
        $t12 = StringRepair($_POST["time_12"]);
        $qa1 = StringRepair($_POST["q_after_1"]);
        $qa2 = StringRepair($_POST["q_after_2"]);
        $qa3 = StringRepair($_POST["q_after_3"]);
        $qa4 = StringRepair($_POST["q_after_4"]);
        $qa5 = StringRepair($_POST["q_after_5"]);
        $qa6 = StringRepair($_POST["q_after_6"]);
        $qa7 = StringRepair($_POST["q_after_7"]);
        $qa8 = StringRepair($_POST["q_after_8"]);
        $qa9 = StringRepair($_POST["q_after_9"]);
        $qa10 = StringRepair($_POST["q_after_10"]);
        $qa11 = StringRepair($_POST["q_after_11"]);
        $qa12 = StringRepair($_POST["q_after_12"]);

        $totalhour= 0;
        $check_1 = 0;
        if ($_POST["check_1"] != "") {
            $check_1 = 1;
            $totalhour++;
        }
        $check_2 = 0;
        if ($_POST["check_2"] != "") {
            $check_2 = 1;
            $totalhour++;
        }
        $check_3 = 0;
        if ($_POST["check_3"] != "") {
            $check_3 = 1;
            $totalhour++;
        }
        $check_4 = 0;
        if ($_POST["check_4"] != "") {
            $check_4 = 1;
            $totalhour++;
        }
        $check_5 = 0;
        if ($_POST["check_5"] != "") {
            $check_5 = 1;
            $totalhour++;
        }
        $check_6 = 0;
        if ($_POST["check_6"] != "") {
            $check_6 = 1;
            $totalhour++;
        }
        $check_7 = 0;
        if ($_POST["check_7"] != "") {
            $check_7 = 1;
            $totalhour++;
        }
        $check_8 = 0;
        if ($_POST["check_8"] != "") {
            $check_8 = 1;
            $totalhour++;
        }
        $check_9 = 0;
        if ($_POST["check_9"] != "") {
            $check_9 = 1;
            $totalhour++;
        }
        $check_10 = 0;
        if ($_POST["check_10"] != "") {
            $check_10 = 1;
            $totalhour++;
        }
        $check_11 = 0;
        if ($_POST["check_11"] != "") {
            $check_11 = 1;
            $totalhour++;
        }
        $check_12 = 0;
        if ($_POST["check_12"] != "") {
            $check_12 = 1;
            $totalhour++;
        }
        

        $qar = StringRepair($_POST["total_q_before_rejection"]);

        if($_POST["opt"]=="Edit")
        {


            $form_data = array(
                'time_1'=>$t1,
                'time_2'=>$t2,
                'time_3'=>$t3,
                'time_4'=>$t4,
                'time_5'=>$t5,
                'time_6'=>$t6,
                'time_7'=>$t7,
                'time_8'=>$t8,
                'time_9'=>$t9,
                'time_10'=>$t10,
                'time_11'=>$t11,
                'time_12'=>$t12,
                'q_after_1'=>$qa1,
                'q_after_2'=>$qa2,
                'q_after_3'=>$qa3,
                'q_after_4'=>$qa4,
                'q_after_5'=>$qa5,
                'q_after_6'=>$qa6,
                'q_after_7'=>$qa7,
                'q_after_8'=>$qa8,
                'q_after_9'=>$qa9,
                'q_after_10'=>$qa10,
                'q_after_11'=>$qa11,
                'q_after_12'=>$qa12,
                'check_1'=>$check_1,
                'check_2'=>$check_2,
                'check_3'=>$check_3,
                'check_4'=>$check_4,
                'check_5'=>$check_5,
                'check_6'=>$check_6,
                'check_7'=>$check_7,
                'check_8'=>$check_8,
                'check_9'=>$check_9,
                'check_10'=>$check_10,
                'check_11'=>$check_11,
                'check_12'=>$check_12,
                'totalhour'=>$totalhour,
                'total_q_before_rejection'=>$qar
            );

            // Updation of Data
            dbRowUpdate($db,$tabname,$form_data,"where production_1=".$id);
			
			$form_data = array(
				'part_count_end' => $qar
			);

			// Updation of Data
            dbRowUpdate($db,$tab2,$form_data,"where id=".$id);
			
            $_SESSION["sadmin_changeImage_Delete"]=$msgupdate;
        }
    }

    print "<META http-equiv='refresh' content=0;URL='".$prourl.$id."'>";
    exit;

}
else
{
    print "<META http-equiv='refresh' content=0;URL=".$sitepath.">";
}
?>