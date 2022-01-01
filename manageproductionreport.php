<?php
session_start();
require("inc/connection.php");
require("inc/funcstuffs.php");

$pageUrl = $sitepath."manageproductionreport.php";
$pageChange = $sitepath."exportproductionreport.php";
$managePage = "Manage Production Report";
$tabname = "production_1";
$tabpro1 = "production_2";
$tabpro2 = "production_3";
$tabpro3 = "production_machine_breakdown";
$tabmachine = "machine";
$taboperator = "operator";
$tabcustomer = "customer";
$title = "Manage Production Report";
$first_day = date('01/m/Y'); // hard-coded '01' for first day
$last_day  = date('t/m/Y');
$msdate = "";
$medate = "";

if($_SESSION["sadmin_username"]!="")
{
	$rndstring=base64_encode(rand(11111111,99999999)."/deleteProduction");
	if(isset($_POST['submitdate'])) {


		$_SESSION["msdate"]=$_POST["msdate"];
		$_SESSION["medate"]=$_POST["medate"];

		if($_SESSION["msdate"]){
			$msdate = $_SESSION["msdate"];
		}

		if($_SESSION["medate"]){
			$medate = $_SESSION["medate"];
		}

		$search_result = "Search Result from : ".$msdate." to ".$medate;
	}
	if($msdate == ""){ $msdate = $first_day;}
	if($medate == ""){ $medate = $last_day;}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php include("inc/headscripts.php"); ?>
	<link rel="stylesheet" type="text/css" href="css/sweetalert.css">

</head>

<body>
		<?php include("inc/topbar.php"); ?>
		<div class="container-fluid">
		<div class="row-fluid">
				
			<?php include("inc/leftbar.php"); ?>
			<div id="content" class="span10">
			<!-- content starts -->
			

			<div>
				<ul class="breadcrumb">
					<li>
						<a href="<?= $sitepath.'home.php'; ?>">Home</a> <span class="divider">/</span>
					</li>
					<li>
						<a href="#">Manage Production Report</a>
					</li>
				</ul>
			</div>
				<?php alertBox(); ?>

			<div class="row-fluid sortable">		
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-th"></i> Production</h2>

						<a href="<?= $pageChange; ?>" class="btn btn-warning" style="float:right">Export Report</a>
					</div>
					<div class="box-content">
						<form class="form-horizontal" id="frm1" name="frm1" action="<?php echo $pageUrl;?>" method="post" enctype="multipart/form-data">
							<fieldset>
								<div class="row-fluid">

									<div class="span5">
										<div class="control-group">
											<label class="control-label">Start Date *</label>
											<div class="controls">
												<input type="text" class="input-xlarge datepicker" id="msdate" name="msdate" value="<?= $msdate; ?>"  required>
											</div>
										</div>
									</div>
									<div class="span5">
										<div class="control-group">
											<label class="control-label">End Date *</label>
											<div class="controls">
												<input type="text" class="input-xlarge datepicker" id="medate" name="medate" value="<?= $medate; ?>" required>
											</div>
										</div>
									</div>
									<div class="span2">
										<button type="submit" name="submitdate" class="btn btn-primary">Submit</button>
									</div>
								</div>
							</fieldset>
						</form>

						<form id="login" name="login" method="post" action="<?php echo $sitepath;?>delete_process.php" >
							<input type="hidden" name="deleteKey" value="<?=$rndstring;?>" />
						<table class="table table-striped table-bordered bootstrap-datatable datatable">
						  <thead>
							  <tr>
								  <th>Production Date</th>
								  <th>Machine</th>
								  <th>Operator</th>
								  <th>Shift</th>
								  <th>Expected<br/>Product<br/>Qty.</th>
								  <th>Production<br/>Before<br/>Rejection</th>
								  <th>Final Production<br/>After<br/>Rejection</th>
								  <th>Product Loss /<br/> Increment Qty.</th>
								  <th>Production<br/>Percentage<br/>%</th>
								  <th>Total<br/>Breakdown<br/>Hour</th>
								  <th>Actions</th>
							  </tr>
						  </thead>   
						  <tbody>
                          <?php

						  if($msdate != "" && $medate != ""){
							  $date = DateTime::createFromFormat('d/m/Y', $msdate);
							  $msdate = $date->format('Y-m-d');
							  $date = DateTime::createFromFormat('d/m/Y', $medate);
							  $medate = $date->format('Y-m-d');

							  $sql = " where $tabname.productiondate >= '$msdate' and $tabname.productiondate <= '$medate'";

						  }

						  $sql = "SELECT $tabname.id,$tabname.productiondate,$tabmachine.machine,$taboperator.operator,$tabname.shift,$tabpro2.production_per,$tabname.required_product_q_per_hr,$tabpro1.total_q_before_rejection,$tabpro2.total_q_after_rejection,$tabpro2.production_loss_increase_q,$tabpro3.total_breakdown_hours FROM $tabname LEFT JOIN $tabmachine ON $tabmachine.id=$tabname.machine LEFT JOIN $tabpro1 ON $tabpro1.production_1=$tabname.id LEFT JOIN $tabpro2 ON $tabpro2.production_1=$tabname.id LEFT JOIN $tabpro3 ON $tabpro3.production_1=$tabname.id LEFT JOIN $taboperator ON $taboperator.id=$tabname.operator ".$sql;
						  $rs=$db->query($sql) or die("cannot Select Customers".$db->error);
						  while($row=$rs->fetch_assoc())
						  {
							  $date = new DateTime($row["productiondate"]);
							  $productiondate = $date->format('d-m-Y');

							  if($row["shift"]=='0'){
								  $shift="Day";
							  }else{
								  $shift="Night";
							  }

							  $production_loss_increase_q = $row["production_loss_increase_q"]; 
							  $production_per = $row["production_per"];
							  
							  ?>
							
							<tr>
								<td class="center"><?= $productiondate; ?></td>
								<td><?= $row["machine"]; ?></td>
								<td><?= $row["operator"]; ?></td>
								<td class="center"><?= $shift; ?></td>
								<td class="center"><?= $row["required_product_q_per_hr"]; ?></td>
								<td class="center"><?= $row["total_q_before_rejection"]; ?></td>
								<td class="center"><?= $row["total_q_after_rejection"]; ?></td>
								<td class="center"><?= $production_loss_increase_q ?></td>
								<td class="center"><?= round($production_per,2) ?></td>
								<td class="center"><?= $row["total_breakdown_hours"]; ?></td>
								<td class="center">
									<a class="btn btn-info" href="exportproductionsingle.php?id=<?= $row["id"]; ?>" target="_blank">
										<i class="icon-edit icon-white"></i>
									</a>
								</td>
							</tr>
                            
                            <?php } ?>

						  </tbody>
					  </table>
							<input type="hidden" name="delid" id="delid" value="<?= $c; ?>" />
						</form>
					</div>
				</div><!--/span-->
			
			</div><!--/row-->

			</div><!--/#content.span10-->
				</div><!--/fluid-row-->
				
		<hr>

		

		<?php include("inc/footer.php"); ?>
		
	</div><!--/.fluid-container-->
<?php include("inc/footerscripts.php"); ?>
		<script src="js/sweetalert.min.js"></script>

		<script type="text/javascript">
			function deleteRecord(val){

				document.login.delid.value = val;
				swal({
						title: "Are you sure?",
						text: "You will not be able to recover this Production Details!",
						type: "warning",
						showCancelButton: true,
						confirmButtonClass: "btn-danger",
						confirmButtonText: "Yes, delete it!",
						cancelButtonText: "No, cancel plx!",
						closeOnConfirm: false,
						closeOnCancel: false
					},
					function(isConfirm) {
						if (isConfirm) {

							document.login.submit();

						} else {
							swal({
								title: "Cancelled",
								text: "Your Records are safe :)",
								type: "error",
								confirmButtonClass: "btn-danger"
							});
						}
					});
			}
		</script>

</body>
</html>
	<?php
}
else
{
	print "<META http-equiv='refresh' content=0;URL=".$sitepath.">";
	exit;
}
?>
