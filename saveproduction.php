<?php
session_start();
include("inc/connection.php");
require("inc/funcstuffs.php");

// Page Variable Declaration
$addpage= $sitepath."addproduction.php";
$url = $sitepath."manageproduction.php";
$prourl = $sitepath."phase1.php?id=";
$msgexist = "Production Already Exists";
$msgadded = "Production Added Successfully";
$msgupdate = "Production Updated Successfully";
$id = $_POST['id'];
$tabname ="production_1";
$tab1 = "production_2";
$tab2 = "production_3";
$tab3 = "production_machine_breakdown";

// Check Login Session
if($_SESSION["sadmin_username"]!="")
{

    $today=date("Y-m-d H:i:s");
    $cname=StringRepair($_POST["productiondate"]);
    if($cname!="")
    {
        //recordexist(fieldname,tablename,value,id,addpage,manageurl);
        //recordexist($db,$_POST["opt"],$sname,$tabname,$cname,$id,$addpage,$url,$msgexist,'');

        $productiondate = StringRepair($_POST["productiondate"]);
        $machine = StringRepair($_POST["machine"]);
        $operator = StringRepair($_POST["operator"]);
        $customer = StringRepair($_POST["customer"]);
        $shift= StringRepair($_POST["shift"]);
        $part_count_start = StringRepair($_POST["part_count_start"]);
        $part_count_end = StringRepair($_POST["part_count_end"]);
        $job_no= StringRepair($_POST["job_no"]);
        $setup= StringRepair($_POST["setup"]);
        $batch_no= StringRepair($_POST["batch_no"]);
        $job_cycle_time= StringRepair($_POST["job_cycle_time"]);
        $required_product_q_per_hr= StringRepair($_POST["required_product_q_per_hr"]);

        $date = DateTime::createFromFormat('d/m/Y', $productiondate);
        $productiondate = $date->format('Y-m-d');


        if($_POST["opt"]=="Add")
        {

            $form_data = array(
                'productiondate' => $productiondate,
                'machine' => $machine,
                'operator' => $operator,
                'customer' => $customer,
                'shift' => $shift,
                'setup' => $setup,
                'part_count_start' => $part_count_start,
                'part_count_end' => $part_count_end,
                'job_no' => $job_no,
                'job_cycle_time' => $job_cycle_time,
                'batch_no' => $batch_no,
                'required_product_q_per_hr' => $required_product_q_per_hr,
            );
            // Insertion of Data
            dbRowInsert($db,$tabname,$form_data);
            $current_id = mysqli_insert_id($db);

            $form_data = array(
                'production_1'=> $current_id
            );

            dbRowInsert($db,$tab1,$form_data);
            dbRowInsert($db,$tab2,$form_data);
            dbRowInsert($db,$tab3,$form_data);


            $_SESSION["sadmin_changeImage_Delete"]=$msgadded;
        }
        elseif($_POST["opt"]=="Edit")
        {

            $form_data = array(
                'productiondate' => $productiondate,
                'machine' => $machine,
                'operator' => $operator,
                'customer' => $customer,
                'shift' => $shift,
                'setup' => $setup,
                'part_count_start' => $part_count_start,
                'part_count_end' => $part_count_end,
                'job_no' => $job_no,
                'job_cycle_time' => $job_cycle_time,
                'batch_no' => $batch_no,
                'required_product_q_per_hr' => $required_product_q_per_hr,
            );

            // Updation of Data
            dbRowUpdate($db,$tabname,$form_data,"where id=".$id);
			
			$current_id = $id;
            
			$_SESSION["sadmin_changeImage_Delete"]=$msgupdate;
        }
    }

    print "<META http-equiv='refresh' content=0;URL='".$prourl.$current_id."'>";
    exit;

}
else
{
    print "<META http-equiv='refresh' content=0;URL=".$sitepath.">";
}
?>