<?php
session_start();
require("inc/connection.php");
require("inc/funcstuffs.php");

$pageUrl = $sitepath."managerejectionreport.php";
$pageChange = $sitepath."exportrejectionreport.php";
$managePage = "Manage Rejection Report";
$tabname = "production_1";
$tabmachine = "machine";
$taboperator = "operator";
$tabpro3 = "production_3";
$title = "Manage Rejection Report";
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
						<a href="#"><?= $managePage; ?></a>
					</li>
				</ul>
			</div>
				<?php alertBox(); ?>

				<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-list-alt"></i> <?= $managePage; ?></h2>

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
								  <th>Operator Name</th>
								  <th>Turning Rejection Nos.</th>
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

						  $sql = "SELECT $tabname.id,$taboperator.operator, sum($tabpro3.turning_rejection_nos) as rejectionnos  FROM $tabname LEFT JOIN $taboperator ON $taboperator.id=$tabname.operator LEFT JOIN $tabpro3 ON $tabpro3.production_1=$tabname.id ".$sql." group by $tabname.operator";
						  $rs=$db->query($sql) or die("cannot Select data ".$db->error);
						  while($row=$rs->fetch_assoc())
						  {
							  
							  ?>
							
							<tr>
								<td><?= $row["operator"]; ?></td>
								<td><?= $row["rejectionnos"]; ?></td>
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
