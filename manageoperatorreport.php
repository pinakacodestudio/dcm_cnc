<?php
session_start();
require("inc/connection.php");
require("inc/funcstuffs.php");

$pageUrl = $sitepath."manageoperatorreport.php";
$pageChange = $sitepath."exportoperatorreport.php";
$managePage = "Manage Operator Report";
$tabname = "production_1";
$tabmachine = "machine";
$taboperator = "operator";
$tabpro3 = "production_3";
$title = "Manage Operator Report";
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
						<table class="table table-striped table-bordered bootstrap-datatable" id="datatable">
						  <thead>
							  <tr>
								  <th>Operator Name</th>
								  <th>Req. Production Qty</th>
								  <th>Actual Production Qty</th>
								  <th>Turning Rejection</th>
								  <th>Variations</th>
								  <th>Rework</th>
								  <th>Diff. %</th>
							  </tr>
						  </thead>   
						  <tbody>
                          <?php

							if($msdate != "" && $medate != ""){
								$date = DateTime::createFromFormat('d/m/Y', $msdate);
								$msdate = $date->format('Y-m-d');
								$date = DateTime::createFromFormat('d/m/Y', $medate);
								$medate = $date->format('Y-m-d');
								$wheresql = " where $tabname.productiondate >= '$msdate' and $tabname.productiondate <= '$medate'";
							}

							$sql = "SELECT $taboperator.operator, round(avg(production_3.production_per),2) as avgproduction,sum($tabpro3.expected_q) as req_qty,sum($tabpro3.total_q_after_rejection) as prod_qty,sum($tabpro3.turning_rejection_nos) as turning_rejection_nos,sum($tabpro3.variation_nos) as variation_nos,sum($tabpro3.rework_nos) as rework_nos FROM $tabname LEFT JOIN $taboperator ON $taboperator.id=$tabname.operator LEFT JOIN $tabpro3 ON $tabpro3.production_1=$tabname.id ".$wheresql." group by $tabname.operator";
														
							$rs=$db->query($sql) or die("cannot Select data ".$db->error);
							while($row=$rs->fetch_assoc())
							{
								$operator = $row["operator"];
								$percentage = $row["avgproduction"];
								$req_qty = $row["req_qty"];
								$prod_qty = $row["prod_qty"];
								$turning_rejection_nos = $row["turning_rejection_nos"]; 
								$variation_nos = $row["variation_nos"];
								$rework_nos = $row["rework_nos"];
							?>
								<tr>
									<td><?= $operator; ?></td>
									<td><?= $req_qty; ?></td>
									<td><?= $prod_qty; ?></td>
									<td><?= $turning_rejection_nos; ?></td>
									<td><?= $variation_nos; ?></td>
									<td><?= $rework_nos; ?></td>
									<td><?= $percentage; ?></td>
								</tr>
                            <?php 
							} 
							?>

						  </tbody>
						  <tfoot>
                                 <tr>
                                    <th>Total : </th>
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
				
				// Update footer
				$(api.column(1).footer()).html(col1);
				$(api.column(2).footer()).html(col2);
				$(api.column(3).footer()).html(col3);
				$(api.column(4).footer()).html(col4);
				$(api.column(5).footer()).html(col5);
				
				var columnDataTotal = api
				.column(6)
				.data();
				var theColumnTotal = columnDataTotal
					.reduce(function(a, b) {
						if (isNaN(a)) {
							return '';
						} else {
							a = parseFloat(a);
						}
						if (isNaN(b)) {
							return '';
						} else {
							b = parseFloat(b);
						}
						return a + b;
					}, 0);
				// view page column
				var columnData = api
					.column(6, {
						page: 'current'
					})
					.data();
				var theColumnPage = columnData
					.reduce(function(a, b) {
						if (isNaN(a)) {
							return '';
						} else {
							a = parseFloat(a);
						}
						if (isNaN(b)) {
							return '';
						} else {
							b = parseFloat(b);
						}
						return a + b;
					}, 0);

				if(theColumnTotal == 0 || columnDataTotal.count() == 0){
					totalvalue = 0
				}else{
					totalvalue = theColumnTotal / columnDataTotal.count();
				}

				//$(api.column(6).footer()).html(parseFloat(theColumnPage / columnData.count()).toFixed(2) + ' (' + parseFloat(theColumnTotal / columnDataTotal.count()).toFixed(2) + ' Total)');
				$(api.column(6).footer()).html(parseFloat(totalvalue).toFixed(2));

			}
			});
			
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
