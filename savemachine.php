<?php
session_start();
include("inc/connection.php");
require("inc/funcstuffs.php");

// Page Variable Declaration
$addpage= $sitepath."addmachine.php";
$url = $sitepath."managemachine.php";
$msgexist = "Machine Already Exists";
$msgadded = "Machine Added Successfully";
$msgupdate = "Machine Updated Successfully";
$id = $_POST['id'];
$tabname ="machine";
$sname = "machine";

// Check Login Session
if($_SESSION["sadmin_username"]!="")
{

    $today=date("Y-m-d H:i:s");
    $cname=StringRepair($_POST["machine"]);
    if($cname!="")
    {
        //recordexist(fieldname,tablename,value,id,addpage,manageurl);
        recordexist($db,$_POST["opt"],$sname,$tabname,$cname,$id,$addpage,$url,$msgexist,'');

        $machine = StringRepair($_POST["machine"]);

        if($_POST["opt"]=="Add")
        {

            $form_data = array(
                'machine' => $machine,
            );
            // Insertion of Data
            dbRowInsert($db,$tabname,$form_data);

            $_SESSION["sadmin_changeImage_Delete"]=$msgadded;
        }
        elseif($_POST["opt"]=="Edit")
        {


            $form_data = array(
                'machine' => $machine,
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