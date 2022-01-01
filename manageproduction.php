<?php
session_start();
require("inc/connection.php");
require("inc/funcstuffs.php");

$pageUrl = $sitepath . "manageproduction.php";
$pageChange = $sitepath . "addproduction.php";
$managePage = "Manage Production";
$tabname = "production_1";
$tabmachine = "machine";
$taboperator = "operator";
$tabcustomer = "customer";
$title = "Manage Production";
$lastmonth = date('Y-m-d', strtotime('first day of last month'));

if ($_SESSION["sadmin_username"] != "") {
	$rndstring = base64_encode(rand(11111111, 99999999) . "/deleteProduction");
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
						<a href="#">Manage Production</a>
					</li>
				</ul>
			</div>
				<?php alertBox(); ?>

			<div class="row-fluid sortable">		
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-th"></i> Production</h2>

						<a href="<?= $pageChange; ?>" class="btn btn-warning" style="float:right">Add New Entry</a>
					</div>
					<div class="box-content">
						<form id="login" name="login" method="post" action="<?php echo $sitepath; ?>delete_process.php" >
							<input type="hidden" name="deleteKey" value="<?= $rndstring; ?>" />
						<table class="table table-striped table-bordered bootstrap-datatable" id="datatable">
						  <thead>
							  <tr>
								  <th>Sr. No.</th>
								  <th>Production Date</th>
								  <th>Machine</th>
								  <th>Customer</th>
								  <th>Operator</th>
								  <th>Shift</th>
								  <th>Required<br/> Qty. / Hour</th>
								  <th>Phase 1</th>
								  <th>Phase 2</th>
								  <th>Phase 3</th>
								  <th>Actions</th>
							  </tr>
						  </thead>   
						  <tbody>
                          <?php
						  $i = 0;
							$sql = "SELECT $tabname.id,$tabname.productiondate,$tabname.shift,$tabname.required_product_q_per_hr,$tabmachine.machine,$tabcustomer.customer,$taboperator.operator FROM $tabname LEFT JOIN $tabmachine ON $tabmachine.id=$tabname.machine LEFT JOIN $tabcustomer ON $tabcustomer.id=$tabname.customer LEFT JOIN $taboperator ON $taboperator.id=$tabname.operator where date($tabname.productiondate) >= '$lastmonth'";
							$rs = $db->query($sql) or die("cannot Select Customers" . $db->error);
							while ($row = $rs->fetch_assoc()) {
								$date = new DateTime($row["productiondate"]);
								$productiondate = $date->format('d-m-Y');

								$i++;
								if ($row["shift"] == '0') {
									$shift = "Day";
								} else {
									$shift = "Night";
								}
								?>
							
							<tr>
								<td><?= $i; ?></td>
								<td class="center"><?= $productiondate; ?></td>
								<td><?= $row["machine"]; ?></td>
								<td><?= $row["customer"]; ?></td>
								<td><?= $row["operator"]; ?></td>
								<td class="center"><?= $shift; ?></td>
								<td class="center"><?= $row["required_product_q_per_hr"] ?></td>
                                <td class="center">
									<a class="btn btn-success" target="_blank" href="phase1.php?id=<?= $row["id"]; ?>">
										<i class="icon-zoom-in icon-white"></i>  P1
									</a>
								</td>
                                <td class="center">
									<a class="btn btn-success" target="_blank" href="phase2.php?id=<?= $row["id"]; ?>">
										<i class="icon-zoom-in icon-white"></i>  P2
									</a>
								</td>
                                <td class="center">
									<a class="btn btn-success" target="_blank" href="phase3.php?id=<?= $row["id"]; ?>">
										<i class="icon-zoom-in icon-white"></i>  P3
									</a>
								</td>
								<td class="center">
									<a class="btn btn-info" href="addproduction.php?id=<?= $row["id"]; ?>">
										<i class="icon-edit icon-white"></i>
									</a>
									<a class="btn btn-danger" href="#" onClick="javascript:deleteRecord(<?= $row["id"]; ?>);">
										<i class="icon-trash icon-white"></i>
									</a>
								</td>
							</tr>
                            
                            <?php 
																										} ?>

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
		//datatable
		$('#datatable').dataTable({
			"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span12'i><'span12 center'p>>",
			"sPaginationType": "bootstrap",
			"ordering": true,
			"order": [[ 0, 'desc' ]],
			"sLengthMenu": "_MENU_ records per page"
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
