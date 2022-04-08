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
						<table class="table table-striped table-bordered bootstrap-datatable" id="datatable">
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
								<td class="center"><?= str_replace(' ','',$production_loss_increase_q) ?></td>
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
						  <tfoot>
							  <tr>
								  
								  <th colspan="4">Total</th>
								  <th>0</th>
								  <th>0</th>
								  <th>0</th>
								  <th>0</th>
								  <th>0</th>
								  <th>0</th>
								  <th>&nbsp;</th>
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
				var col9 = api.column(9).data()
				.reduce(function(a, b) {
					return intVal(a) + intVal(b);
				}, 0);
				// Update footer
				
				$(api.column(4).footer()).html(col4);
				$(api.column(5).footer()).html(col5);
				$(api.column(6).footer()).html(col6);
				$(api.column(7).footer()).html(col7);
				$(api.column(9).footer()).html(col9);
				
				var columnDataTotal = api
				.column(8)
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
					.column(8, {
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
				$(api.column(8).footer()).html(parseFloat(totalvalue).toFixed(2));

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
