<?php
session_start();
include("inc/connection.php");
include("inc/funcstuffs.php");

$id = $_GET["id"];
date_default_timezone_set('Asia/Kolkata');
$today = date("Y-m-d");
$tabname = "production_1";
$tabmachine = "machine";
$tabcustomer = "customer";
$taboperator = "operator";
$tabjob = "joblist";
$tabpro1 = "production_2";
$tabpro2 = "production_3";
$tabpro3 = "production_machine_breakdown";
$title = "Manage Production Report";

$pageUrl = $sitepath . "manageproductionreport.php";


//include("Excel/reader.php");
/// Check Login Session
if ($_SESSION["sadmin_username"] != "") {


	$k = 8;
	$sql = "SELECT $tabname.productiondate,$tabmachine.machine,$tabcustomer.customer,$taboperator.operator,$tabname.shift,$tabname.part_count_start,$tabname.part_count_end,$tabjob.jobno,$tabname.batch_no, $tabname.required_product_q_per_hr,$tabpro1.time_1,$tabpro1.time_2, $tabpro1.time_3,$tabpro1.time_4, $tabpro1.time_5,$tabpro1.time_6, $tabpro1.time_7,$tabpro1.time_8, $tabpro1.time_9,$tabpro1.time_10,$tabpro1.time_11,$tabpro1.time_12, $tabpro1.q_after_1,$tabpro1.q_after_2, $tabpro1.q_after_3, $tabpro1.q_after_4, $tabpro1.q_after_5, $tabpro1.q_after_6, $tabpro1.q_after_7, $tabpro1.q_after_8, $tabpro1.q_after_9, $tabpro1.q_after_10, $tabpro1.q_after_11, $tabpro1.q_after_12, $tabpro1.total_q_before_rejection, $tabpro2.variation_nos, $tabpro2.turning_rejection_nos, $tabpro2.setting_rejection_nos, $tabpro2.pre_machining_rejection_nos, $tabpro2.forging_rejection_nos, $tabpro2.total_q_after_rejection, $tabpro2.expected_q, $tabpro2.production_loss_increase_q, $tabpro3.setting_hour,$tabpro3.machine_fault_hour, $tabpro3.recess_hour,$tabpro3.maintanance_hour, $tabpro3.no_operator_hour, $tabpro3.no_load_hour,$tabpro3.power_fail_hour, $tabpro3.other,$tabpro3.total_breakdown_hours  FROM $tabname LEFT JOIN $tabmachine ON $tabmachine.id=$tabname.machine LEFT JOIN $taboperator ON $taboperator.id=$tabname.operator LEFT JOIN $tabcustomer ON $tabcustomer.id=$tabname.customer LEFT JOIN $tabpro1 ON $tabpro1.production_1=$tabname.id LEFT JOIN $tabpro2 ON $tabpro2.production_1=$tabname.id LEFT JOIN $tabjob ON $tabjob.id=$tabname.job_no LEFT JOIN $tabpro3 ON $tabpro3.production_1=$tabname.id where $tabname.id=" . $id;

	$rs = $db->query($sql) or die("cannot Select Customers" . $db->error);
	$row = $rs->fetch_assoc();

	$date = new DateTime($row["productiondate"]);
	$productiondate = $date->format('d-m-Y');

	if ($row["shift"] == '0') {
		$shift = "Day";
	} else {
		$shift = "Night";
	}

	?>
<style>
	table{
		width:100%;
		border:1px solid #6C6C6C;
		border-collapse:collapse;
	}
	th{
		font-size:20px;
		padding:5px;
	}
	tr{
		border:1px solid #6C6C6C;
	}
	td{
		padding:5px;
	}
	tr td:first-child{
		text-align:right;
		font-weight:bold;
	}
	tr td:nth-child(4){
		text-align:right;
		font-weight:bold;
	}
</style>
<div style="width:90%; margin:0 auto;">
<table style="padding:20px;">
<tr> 
<td> <img src="img/logo.png" alt="" style="float:left; width:60px;" /><h2 style="float:left; margin-left:10px;">Unique Industries</h2> </td>
<td><p style="text-align:right; font-weight:bold;">Report Name : Production Report</p></td>
</tr>
</table>

<table style="border:1px solid #6C6C6C;padding:5px;">
<tr> <th style="border-bottom:1px solid #6C6C6C;" colspan="5">Production Report</th> </tr>
<tr> <td>Date : </td> <td><?= $productiondate; ?></td> <td>&nbsp;</td> <td>Shift : </td> <td><?= $shift; ?></td> </tr>
<tr> <td>Machine No. : </td> <td><?= $row["machine"]; ?></td> <td>&nbsp;</td> <td>Operator : </td> <td><?= $row["operator"]; ?></td> </tr>
<tr> <td>Job No. : </td> <td><?= $row["jobno"]; ?></td> <td>&nbsp;</td> <td>Customer Name : </td> <td><?= $row["customer"]; ?></td> </tr>
<tr> <td>Part Count Start : </td> <td><?= $row["part_count_start"]; ?></td> <td>&nbsp;</td> <td>Part Count End : </td> <td><?= $row["part_count_end"]; ?></td> </tr>
<tr> <td>Batch No. / Qty. : </td> <td><?= $row["batch_no"]; ?></td> <td>&nbsp;</td> <td>Required Product Qty. / Hour : </td> <td><?= $row["required_product_q_per_hr"]; ?></td> </tr>
<tr> <th style="border-bottom:1px solid #6C6C6C;" colspan="5">Per Hour Production Status</th> </tr>
<tr> <td>TIME</td> <td colspan="2">Production Qty.</td> <td>&nbsp;</td> <td>&nbsp;</td> </tr>
<tr> <td>8:00 To 9:00 : </td> <td><?= $row["time_1"]; ?></td> <td><?= $row["q_after_1"]; ?></td> <td>Turning Ok Nos : </td> <td>&nbsp;</td> </tr>
<tr> <td>9:00 To 10:00 : </td> <td><?= $row["time_2"]; ?></td> <td><?= $row["q_after_2"]; ?></td> <td>Variation Nos : </td> <td><?= $row["variation_nos"]; ?></td> </tr>
<tr> <td>10:00 To 11:00 : </td> <td><?= $row["time_3"]; ?></td> <td><?= $row["q_after_3"]; ?></td> <td>Turning Rejection Nos : </td> <td><?= $row["turning_rejection_nos"]; ?></td> </tr>
<tr> <td>11:00 To 12:00 : </td> <td><?= $row["time_4"]; ?></td> <td><?= $row["q_after_4"]; ?></td> <td>Setting Rejection Nos : </td> <td><?= $row["setting_rejection_nos"]; ?></td> </tr>
<tr> <td>12:00 To 1:00 : </td> <td><?= $row["time_5"]; ?></td> <td><?= $row["q_after_5"]; ?></td> <td>Pre Matching Rejection Nos : </td> <td><?= $row["pre_machining_rejection_nos"]; ?></td> </tr>
<tr> <td>1:00 To 2:00 : </td> <td><?= $row["time_6"]; ?></td> <td><?= $row["q_after_6"]; ?></td> <td>Forging Rejection Nos : </td> <td><?= $row["forging_rejection_nos"]; ?></td> </tr>
<tr> <td>2:00 To 3:00 : </td> <td><?= $row["time_7"]; ?></td> <td><?= $row["q_after_7"]; ?></td> <td>Total Production Qty. ( Before Rejection ) : </td> <td><?= $row["total_q_before_rejection"]; ?></td> </tr>
<tr> <td>3:00 To 4:00 : </td> <td><?= $row["time_8"]; ?></td> <td><?= $row["q_after_8"]; ?></td> <td>Final Production Qty. ( After Rejection ) : </td> <td><?= $row["total_q_after_rejection"]; ?></td> </tr>
<tr> <td>4:00 To 5:00 : </td> <td><?= $row["time_9"]; ?></td> <td><?= $row["q_after_9"]; ?></td> <td>Expected Production Qty. : </td> <td><?= $row["expected_q"]; ?></td> </tr>
<tr> <td>5:00 To 6:00 : </td> <td><?= $row["time_10"]; ?></td> <td><?= $row["q_after_10"]; ?></td> <td>Production Loss / Increase Qty. : </td> <td><?= $row["production_loss_increase_q"]; ?></td> </tr>
<tr> <td>6:00 To 7:00 : </td> <td><?= $row["time_11"]; ?></td> <td><?= $row["q_after_11"]; ?></td> <td>&nbsp;</td> <td>&nbsp;</td> </tr>
<tr> <td>7:00 To 8:00 : </td> <td><?= $row["time_12"]; ?></td> <td><?= $row["q_after_12"]; ?></td> <td>&nbsp;</td> <td>&nbsp;</td> </tr>

<tr> <th style="border-bottom:1px solid #6C6C6C;" colspan="5">Machine Breakdown Minutes</th> </tr>

<tr> <td>Setting Minutes : </td> <td><?= $row["setting_hour"]; ?></td> <td>&nbsp;</td> <td>No Operator Minutes : </td> <td><?= $row["no_operator_hour"]; ?></td> </tr>
<tr> <td>Machine Fault Minutes : </td> <td><?= $row["machine_fault_hour"]; ?></td> <td>&nbsp;</td> <td>No Load Minutes : </td> <td><?= $row["no_load_hour"]; ?></td> </tr>
<tr> <td>Recess Minutes : </td> <td><?= $row["recess_hour"]; ?></td> <td>&nbsp;</td> <td>Power Fail Minutes : </td> <td><?= $row["power_fail_hour"]; ?></td> </tr>
<tr> <td>Maintainance Minutes : </td> <td><?= $row["maintanance_hour"]; ?></td> <td>&nbsp;</td> <td>Other Minutes : </td> <td><?= $row["other"]; ?></td> </tr>
<tr> <td>Total Breakdown Minutes : </td> <td><?= $row["total_breakdown_hours"]; ?></td> <td>&nbsp;</td> <td>Total Breakdown Hours : </td> <td><?= $row["total_breakdown_hours"]; ?></td> </tr>

</table>
</div>
<?php

} else {
	print "<META http-equiv='refresh' content=0;URL=index.php>";
}
?>