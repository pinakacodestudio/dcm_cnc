<?php
session_start();
require("inc/connection.php");
require("inc/funcstuffs.php");

$pageUrl = $sitepath . "managemachinebreakreport.php";
$pageChange = $sitepath . "exportmachinebreakreport.php";
$managePage = "Manage Machine Breakdown Report";
$tabname = "production_1";
$tabmachine = "machine";
$taboperator = "operator";
$tabpro = "production_machine_breakdown";
$title = "Manage Machine Breakdown Report";
$first_day = date('01/m/Y'); // hard-coded '01' for first day
$last_day = date('t/m/Y');
$msdate = "";
$medate = "";

if ($_SESSION["sadmin_username"] != "") {
	$rndstring = base64_encode(rand(11111111, 99999999) . "/deleteProduction");

	if (isset($_POST['submitdate'])) {


		$_SESSION["msdate"] = $_POST["msdate"];
		$_SESSION["medate"] = $_POST["medate"];

		if ($_SESSION["msdate"]) {
			$msdate = $_SESSION["msdate"];
		}

		if ($_SESSION["medate"]) {
			$medate = $_SESSION["medate"];
		}

		$search_result = "Search Result from : " . $msdate . " to " . $medate;
	}
	if ($msdate == "") {
		$msdate = $first_day;
	}
	if ($medate == "") {
		$medate = $last_day;
	}
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
						<a href="<?= $sitepath . 'home.php'; ?>">Home</a> <span class="divider">/</span>
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
						<form class="form-horizontal" id="frm1" name="frm1" action="<?php echo $pageUrl; ?>" method="post" enctype="multipart/form-data">
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

						<form id="login" name="login" method="post" action="<?php echo $sitepath; ?>delete_process.php" >
							<input type="hidden" name="deleteKey" value="<?= $rndstring; ?>" />
						<table class="table table-striped table-bordered bootstrap-datatable" id="datatable">
						  <thead>
							  <tr>
								  <th>Machine</th>
								  <th>Setting Min.</th>
								  <th>Machine Fault Min.</th>
								  <th>Recess Min.</th>
								  <th>Maintainance Min.</th>
								  <th>No Operator Min.</th>
								  <th>No Load Min.</th>
								  <th>Power Failure Min.</th>
								  <th>Rework Min.</th>
								  <th>Other Min.</th>
								  <th>Total Breakdown Min.</th>
							  </tr>
						  </thead>   
						  <tbody>
                          <?php

							if ($msdate != "" && $medate != "") {
								$date = DateTime::createFromFormat('d/m/Y', $msdate);
								$msdate = $date->format('Y-m-d');
								$date = DateTime::createFromFormat('d/m/Y', $medate);
								$medate = $date->format('Y-m-d');

								$sql = " where $tabname.productiondate >= '$msdate' and $tabname.productiondate <= '$medate'";

							}

							$sql = "SELECT $tabname.id,$tabname.productiondate,$tabmachine.machine, sum($tabpro.setting_hour) as setting_hour, sum($tabpro.machine_fault_hour) as machine_fault_hour,sum($tabpro.recess_hour) as recess_hour,sum($tabpro.maintanance_hour) as maintanance_hour,sum($tabpro.no_operator_hour) as no_operator_hour,sum($tabpro.no_load_hour) as no_load_hour,sum($tabpro.power_fail_hour) as power_fail_hour,sum($tabpro.other) as other,sum($tabpro.rework) as rework,sum($tabpro.total_breakdown_hours) as total_breakdown_hours FROM $tabname LEFT JOIN $tabmachine ON $tabmachine.id=$tabname.machine LEFT JOIN $tabpro ON $tabpro.production_1=$tabname.id " . $sql . " group by $tabname.machine";
							$rs = $db->query($sql) or die("cannot Select data " . $db->error);
							while ($row = $rs->fetch_assoc()) {

								?>
							
							<tr>
								<td><?= $row["machine"]; ?></td>
								<td><?= $row["setting_hour"]; ?></td>
								<td><?= $row["machine_fault_hour"]; ?></td>
								<td><?= $row["recess_hour"]; ?></td>
								<td><?= $row["maintanance_hour"] ?></td>
								<td><?= $row["no_operator_hour"] ?></td>
								<td><?= $row["no_load_hour"] ?></td>
								<td><?= $row["power_fail_hour"] ?></td>
								<td><?= $row["rework"] ?></td>
								<td><?= $row["other"] ?></td>
								<td><?= $row["total_breakdown_hours"] ?></td>

							</tr>
                            
                            <?php 
																										} ?>

						  </tbody>
						  <tfoot>
							  <tr>
								  <th>Total</th>
								  <th>0</th>
								  <th>0</th>
								  <th>0</th>
								  <th>0</th>
								  <th>0</th>
								  <th>0</th>
								  <th>0</th>
								  <th>0</th>
								  <th>0</th>
								  <th>0</th>
								 
							  </tr>
						  </tfoot>   
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
		
<script type="text/javascript">
					
			//datatable
			$("#datatable").dataTable({
				sDom:
				"<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span12'i><'span12 center'p>>",
				sPaginationType: "bootstrap",
				order: [[0, "desc"]],
				oLanguage: {
				sLengthMenu: "_MENU_ records per page"
				},
				"footerCallback": function(row, data, start, end, display) {
				var api = this.api(),
				data;
				// Remove the formatting to get integer data for summation
				var intVal = function(i) {
				return typeof i === 'string' ?
					i.replace(/[\$,]/g, '') * 1 :
					typeof i === 'number' ?
					i : 0;
				};
				var col1 = api.column(1).data()
				.reduce(function(a, b) {
					return intVal(a) + intVal(b);
				}, 0);
				var col2 = api.column(2).data()
				.reduce(function(a, b) {
					return intVal(a) + intVal(b);
				}, 0);
				var col3 = api.column(3).data()
				.reduce(function(a, b) {
					return intVal(a) + intVal(b);
				}, 0);
				var col4 = api.column(4).data()
				.reduce(function(a, b) {
					return intVal(a) + intVal(b);
				}, 0);
				var col5 = api.column(5).data()
				.reduce(function(a, b) {
					return intVal(a) + intVal(b);
				}, 0);
				
				var col6 = api.column(6).data()
				.reduce(function(a, b) {
					return intVal(a) + intVal(b);
				}, 0);
				
				var col7 = api.column(7).data()
				.reduce(function(a, b) {
					return intVal(a) + intVal(b);
				}, 0);
				
				var col8 = api.column(8).data()
				.reduce(function(a, b) {
					return intVal(a) + intVal(b);
				}, 0);
				
				var col9 = api.column(9).data()
				.reduce(function(a, b) {
					return intVal(a) + intVal(b);
				}, 0);
				var col10 = api.column(10).data()
				.reduce(function(a, b) {
					return intVal(a) + intVal(b);
				}, 0);
				
				// Update footer
				$(api.column(1).footer()).html(col1);
				$(api.column(2).footer()).html(col2);
				$(api.column(3).footer()).html(col3);
				$(api.column(4).footer()).html(col4);
				$(api.column(5).footer()).html(col5);
				$(api.column(6).footer()).html(col6);
				$(api.column(7).footer()).html(col7);
				$(api.column(8).footer()).html(col8);
				$(api.column(9).footer()).html(col9);
				$(api.column(10).footer()).html(col10);
				

			}
			});
			
		</script>

</body>
</html>
	<?php

} else {
	print "<META http-equiv='refresh' content=0;URL=" . $sitepath . ">";
	exit;
}
?>
