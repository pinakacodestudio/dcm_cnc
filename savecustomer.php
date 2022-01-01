<?php
session_start();
include("inc/connection.php");
require("inc/funcstuffs.php");

// Page Variable Declaration
$addpage= $sitepath."addcustomer.php";
$url = $sitepath."managecustomer.php";
$msgexist = "Customer Already Exists";
$msgadded = "Customer Added Successfully";
$msgupdate = "Customer Updated Successfully";
$id = $_POST['id'];
$tabname ="customer";
$sname = "customer";

// Check Login Session
if($_SESSION["sadmin_username"]!="")
{

    $today=date("Y-m-d H:i:s");
    $cname=StringRepair($_POST["customer"]);
    if($cname!="")
    {
        //recordexist(fieldname,tablename,value,id,addpage,manageurl);
        recordexist($db,$_POST["opt"],$sname,$tabname,$cname,$id,$addpage,$url,$msgexist,'');

        $customer = StringRepair($_POST["customer"]);
        /*
		$email = StringRepair($_POST["email"]);
        $mobile = StringRepair($_POST["mobile"]);
        $product = StringRepair($_POST["product"]);
        $quantity = StringRepair($_POST["quantity"]);
        $order_date = StringRepair($_POST["order_date"]);
        $dispatch_date = StringRepair($_POST["dispatch_date"]);

		$date = DateTime::createFromFormat('d/m/Y', $order_date);
		$order_date = $date->format('Y-m-d');

		$date = DateTime::createFromFormat('d/m/Y', $dispatch_date);
        $dispatch_date= $date->format('Y-m-d');
			*/
        $status=0;
        if($_POST["status"]!="")
        {
            $status = 1;
        }

        if($_POST["opt"]=="Add")
        {

            $form_data = array(
                'customer' => $customer,
                'status'=>$status
            );
            // Insertion of Data
            dbRowInsert($db,$tabname,$form_data);

            $_SESSION["sadmin_changeImage_Delete"]=$msgadded;
        }
        elseif($_POST["opt"]=="Edit")
        {


            $form_data = array(
                'customer' => $customer,
                'status'=>$status
            );

            // Updation of Data
            dbRowUpdate($db,$tabname,$form_data,"where id=".$id);

            $_SESSION["sadmin_changeImage_Delete"]=$msgupdate;
        }
    }

    print "<META http-equiv='refresh' content=0;URL='".$url."'>";
    exit;

}
else
{
    print "<META http-equiv='refresh' content=0;URL=".$sitepath.">";
}
?>