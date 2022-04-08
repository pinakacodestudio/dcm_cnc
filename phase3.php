<?php
session_start();
include("inc/connection.php");
require("inc/funcstuffs.php");

$_SESSION["phaseurl"] = full_url();

$pageUrl = $sitepath . "manageproduction.php";
$pageChange = $sitepath . "phase3.php";
$savePage = $sitepath . "savephase3.php";
$managePage = " Production Phase 3";
$tabname = "production_machine_breakdown";
$tabmain = "production_1";
$tab2 = "production_3";
$tab1 = "production_2";
$tabcustomer = "customer";
$taboperator = "operator";
$tabmachine = "machine";
$tabjob = "joblist";

if ($_SESSION["sadmin_username"] != "") {
	$operation = "Add";
	$id = $_GET["id"];
	if ($id != "" and is_numeric($id)) {

		$qry = "SELECT * from $tabname where production_1=" . $id;
		$result = mysqli_query($db, $qry) or die("cannot select production_machine_breakdown" . mysqli_error($db));
		if ($row = mysqli_fetch_array($result)) {
			$operation = "Edit";
			$proid = $row["production_1"];
			$setting_hour = $row["setting_hour"];
			$machine_fault_hour = $row["machine_fault_hour"];
			$recess_hour = $row["recess_hour"];
			$maintanance_hour = $row["maintanance_hour"];
			$no_operator_hour = $row["no_operator_hour"];
			$no_load_hour = $row["no_load_hour"];
			$power_fail_hour = $row["power_fail_hour"];
			$rework = $row["rework"];
			$other = $row["other"];
			$total_breakdown_hours = $row["total_breakdown_hours"];
			
			$qry = "SELECT $tabmain.productiondate,$tabmain.part_count_start,$tabjob.jobno,$tabmain.shift,$tabmain.required_product_q_per_hr,$tabmachine.machine,$tabcustomer.customer,$taboperator.operator,$tab2.total_q_after_rejection,$tab2.expected_q,$tab2.setting_hr,$tab2.production_loss_increase_q,$tab1.totalhour FROM $tabmain LEFT JOIN $tabmachine ON $tabmachine.id=$tabmain.machine  LEFT JOIN $tab2 ON $tab2.production_1=$tabmain.id LEFT JOIN $tab1 ON $tab1.production_1=$tabmain.id LEFT JOIN $tabcustomer ON $tabcustomer.id=$tabmain.customer LEFT JOIN $tabjob ON $tabjob.id=$tabmain.job_no LEFT JOIN $taboperator ON $taboperator.id=$tabmain.operator where $tabmain.id=" . $proid . "";
			$result = mysqli_query($db, $qry) or die("cannot select Production table " . mysqli_error($db));
			if ($row1 = mysqli_fetch_array($result)) {

				$customer = $row1["customer"];
				$machine = $row1["machine"];
				$start_count = $row1["part_count_start"];
				$operator = $row1["operator"];
				$jobno = $row1["jobno"];

				$required_product_q_per_hr = $row1["required_product_q_per_hr"];
				$total_q_after_rejection = $row1["total_q_after_rejection"];
				$expected_q = $row1["expected_q"];
				$setting_hr = $row1["setting_hr"];
				$totalhour = $row1["totalhour"];
				$production_loss_increase_q = $row1["production_loss_increase_q"];

				if ($row["shift"] == "0") {
					$shift = "Day";
				} else {
					$shift = "Night";
				}

				$expected_time = ($totalhour * 60) -  $setting_hr;
				$totalworktime = intval(($expected_time / $expected_q) * $total_q_after_rejection);

				$date = new DateTime($row1["productiondate"]);
				$productiondate = $date->format('m/d/Y');
			}
		} else {
			print "<META http-equiv='refresh' content=0;URL='" . $pageUrl . "'>";
			exit;
		}
	}
	$title = " Production Phase 3 ";
	?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php include("inc/headscripts.php"); ?>
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
						<a href="<?= $sitepath . "home.php"; ?>">Home</a> <span class="divider">/</span>
					</li>
					<li>
						<a href="<?= $pageUrl; ?>">Manage Production</a> <span class="divider">/</span>
					</li>
					<li>
						<a href="#"><?= $title; ?></a>
					</li>
				</ul>
			</div>

				<?php alertBox(); ?>

				<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-edit"></i> <?= $title; ?></h2>
					</div>
					<div class="box-content">
						<form class="form-horizontal" id="frm1" name="frm1" action="<?php echo $savePage; ?>" method="post" enctype="multipart/form-data">
							<input type="hidden" name="id" value="<?php echo $id; ?>" />
							<input type="hidden" name="opt" value="<?php echo $operation; ?>" />
							<input type="hidden" name="total_q_after_rejection" id="total_q_after_rejection" value="<?php echo $total_q_after_rejection; ?>" />
							<input type="hidden" name="required_product_q_per_hr" id="required_product_q_per_hr" value="<?php echo $required_product_q_per_hr; ?>" />
							<input type="hidden" name="totalhour" id="totalhour" value="<?php echo $totalhour; ?>" />
								<fieldset>
                          <div class="well detbox">
                          <div class="row-fluid">
                          	<div class="span4"><b>Production Date :</b> <?= $productiondate; ?> </div>
                            <div class="span4"><b>Machine :</b> <?= $machine; ?></div>
                            <div class="span4"><b>Customer :</b> <?= $customer; ?> </div>
                          </div>
                          <div class="row-fluid">
                            <div class="span4"><b>Operator :</b> <?= $operator; ?> </div>
                            <div class="span4"><b>Shift :</b> <?= $shift; ?> </div>
                            <div class="span4"><b>Required Qty. / Hour :</b> <?= $required_product_q_per_hr ?></div>
                           </div>
							  <div class="row-fluid">
								  <div class="span4"><b>Starting Count :</b> <?= $start_count; ?> </div>
                                  <div class="span4"><b>Job No. :</b> <?= $jobno; ?> </div>
							  </div>

						  </div>
                            <div class="clearfix"></div>
                             
                              <div class="control-group">
							  <label class="control-label">Setting Minutes</label>
							  <div class="controls">
								<input type="number" class="input-xlarge callblur" id="setting_hour" name="setting_hour" value="<?= $setting_hour ?>" placeholder="0" required>
							  </div>
							</div>
                            
                            <div class="control-group">
							  <label class="control-label">Machine Fault Minutes</label>
							  <div class="controls">
								<input type="number" class="input-xlarge callblur" id="machine_fault_hour" name="machine_fault_hour" value="<?= $machine_fault_hour; ?>" placeholder="0" required>
							  </div>
							</div>
					        
                             <div class="control-group">
							  <label class="control-label">Recess Minutes</label>
							  <div class="controls">
								<input type="number" class="input-xlarge callblur" id="recess_hour" name="recess_hour" value="<?= $recess_hour; ?>" placeholder="0" required>
							  </div>
							</div>
					        
                             <div class="control-group">
							  <label class="control-label">Maintainance Minutes</label>
							  <div class="controls">
								<input type="number" class="input-xlarge callblur" id="maintanance_hour" name="maintanance_hour" value="<?= $maintanance_hour; ?>" placeholder="0" required>
							  </div>
							</div>
					        
                             <div class="control-group">
							  <label class="control-label">No Operator Minutes</label>
							  <div class="controls">
								<input type="number" class="input-xlarge callblur" id="no_operator_hour" name="no_operator_hour" value="<?= $no_operator_hour; ?>" placeholder="0" required>
							  </div>
							</div>
					        
                             <div class="control-group">
							  <label class="control-label">No Load Minutes</label>
							  <div class="controls">
								<input type="number" class="input-xlarge callblur" id="no_load_hour" name="no_load_hour" value="<?= $no_load_hour; ?>" placeholder="0" required>
							  </div>
							</div>
					        
                             <div class="control-group">
							  <label class="control-label">Power Failure Minutes</label>
							  <div class="controls">
								<input type="number" class="input-xlarge callblur" id="power_fail_hour" name="power_fail_hour" value="<?= $power_fail_hour; ?>" placeholder="0"  required>
							  </div>
							</div>
							<div class="control-group">
							  <label class="control-label">Rework Minutes</label>
							  <div class="controls">
								<input type="number" class="input-xlarge callblur" id="rework" name="rework" value="<?= $rework; ?>"  placeholder="0"  required>
							  </div>
							</div> 
                             <div class="control-group">
							  <label class="control-label">Other Minutes</label>
							  <div class="controls">
								<input type="number" class="input-xlarge callblur" id="other" name="other" value="<?= $other; ?>"  placeholder="0"  required>
							  </div>
							</div>
					        
                            
                             <div class="control-group">
							  <label class="control-label">Total Breakdown Minutes</label>
							  <div class="controls">
								<input type="text" class="input-xlarge" id="dtotal_breakdown_hours" name="dtotal_breakdown_hours" value="<?= $total_breakdown_hours; ?>" disabled required>
								  <input type="hidden" class="input-xlarge" id="total_breakdown_hours" name="total_breakdown_hours" value="<?= $total_breakdown_hours; ?>">
							  </div>
							</div>
					        
									
							<div class="control-group">
								<label class="control-label">Setting Hours</label>
								<div class="controls">
									<input type="number" disabled class="input-xlarge" id="dsetting_hr" name="dsetting_hr" value="<?= $setting_hr; ?>"  placeholder="0" required>
									<input type="hidden" class="input-xlarge" id="setting_hr" name="setting_hr" value="<?= $setting_hr; ?>"  placeholder="0" required>
								</div>
							</div>

							

                             <div class="control-group">
							  <label class="control-label">Expected Qty.</label>
							  <div class="controls">
								<input type="text" class="input-xlarge" id="dexpected_q" name="dexpected_q" value="<?= $expected_q; ?>"  placeholder="0" disabled required>
								  <input type="hidden" class="input-xlarge" id="expected_q" name="expected_q" value="<?= $expected_q; ?>"  placeholder="0" required>
							  </div>
							</div>
                           
							<div class="control-group">
							  <label class="control-label">Actual Production Qty</label>
							  <div class="controls">
								<input type="text" class="input-xlarge" id="dactualproduction" name="dactualproduction" value="<?= $total_q_after_rejection; ?>"  placeholder="0" disabled required>
								  <input type="hidden" class="input-xlarge" id="actualproduction" name="actualproduction" value="<?= $total_q_after_rejection; ?>"  placeholder="0" required>
							  </div>
							</div>

                             <div class="control-group">
							  <label class="control-label">Production Loss / Increase Queue</label>
							  <div class="controls">
								  <?php
									if ($production_loss_increase_q >= 0) {
										$clvar = "pvcls";
									} else {
										$clvar = "ngcls";
									}
									?>
								<input type="text" class="input-xlarge <?= $clvar; ?>" id="dproduction_loss_increase_q" name="dproduction_loss_increase_q" value="<?= $production_loss_increase_q; ?>"  placeholder="0" disabled required>
								  <input type="hidden" class="input-xlarge" id="production_loss_increase_q" name="production_loss_increase_q" value="<?= $production_loss_increase_q; ?>"  placeholder="0" required>
							  </div>
							</div>

							
							<div class="control-group">
								<label class="control-label">Expected Time</label>
								<div class="controls">
									<input type="text" disabled class="input-xlarge" id="dexpected_time" name="dexpected_time" value="<?= $expected_time; ?> mins"  placeholder="0" required>
									<input type="hidden" class="input-xlarge" id="expected_time" name="expected_time" value="<?= $expected_time; ?>"  placeholder="0" required>
								</div>
							</div>

							<div class="control-group">
							  <label class="control-label">Total Work Time</label>
							  <div class="controls">
								<input type="text" class="input-xlarge" id="dtotalwork_time" name="dtotalwork_time" value="<?= $totalworktime; ?> mins"  placeholder="0" disabled required>
								  <input type="hidden" class="input-xlarge" id="totalwork_time" name="totalwork_time" value="<?= $totalworktime; ?>"  placeholder="0" required>
							  </div>
							</div>
							
							<div class="form-actions">
							  <button type="submit" class="btn btn-primary">Save changes</button>
								<a href="<?= $pageUrl; ?>" class="btn">Cancel</a>
							</div>
						  </fieldset>
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

		<script>
			$(document).ready(function () {
				$(".callblur").each(function () {
					$(this).bind("blur",function() {

						var sh = parseInt($("#setting_hour").val()); // Field Value
						var mfh = parseInt($("#machine_fault_hour").val()); // Field Value
						var rh = parseInt($("#recess_hour").val()); // Field Value
						var mh = parseInt($("#maintanance_hour").val()); // Field Value
						var noh = parseInt($("#no_operator_hour").val()); // Field Value
						var nlh = parseInt($("#no_load_hour").val()); // Field Value
						var pfh = parseInt($("#power_fail_hour").val()); // Field Value
						var rwh = parseInt($("#rework").val()); // Field Value
						var oh = parseInt($("#other").val()); // Field Value
						var th = parseInt($("#totalhour").val()); // Field Value
						var tqar = parseInt($("#total_q_after_rejection").val()); // Field Value
					

						if(isNaN(sh)){ sh = 0;}
						if(isNaN(mfh)){ mfh = 0;}
						if(isNaN(rh)){ rh = 0;}
						if(isNaN(mh)){ mh = 0;}
						if(isNaN(noh)){ noh = 0;}
						if(isNaN(nlh)){ nlh = 0;}
						if(isNaN(pfh)){ pfh = 0;}
						if(isNaN(oh)){ oh = 0;}
						if(isNaN(rwh)){ rwh = 0;}
						if(isNaN(th)){ th = 0;}


						var tot = sh + mfh + mh + noh + nlh + pfh + rwh ;

						$('#total_breakdown_hours').val(tot);
						$('#dtotal_breakdown_hours').val(tot);

						tot1 = tot + oh + rh;
						$('#setting_hr').val(tot1);
						$('#dsetting_hr').val(tot1);


						var hours = tot1 / 60;   
						var jct = 12; // Field Value
						var rqh = parseInt($("#required_product_q_per_hr").val()); // Field Value
						
						var tot2 = rqh * (jct - hours);

						$('#expected_q').val(tot2);
						$('#dexpected_q').val(tot2);

						// Calculating Total Work Minutes

						let totaltime = th*60 - tot1;
						$('#expected_time').val(totaltime);
						$('#dexpected_time').val(totaltime+" mins");

						let worktime = parseInt((totaltime / tot2) * tqar);
						$('#totalwork_time').val(worktime);
						$('#dtotalwork_time').val(worktime+" mins");


						showProfit();

					})
				})
				})

				function  showProfit() {

							var tqar = parseInt($('#total_q_after_rejection').val());
							var eq = parseInt($('#expected_q').val());

							var profit = tqar - eq;
							var sign = "";
							if(profit >= 0){
							sign = '+';
							$("#dproduction_loss_increase_q").removeClass();
							$("#dproduction_loss_increase_q").addClass("input-xlarge pvcls");
							}else if(profit < 0){
							sign = '-';
							$("#dproduction_loss_increase_q").removeClass();
							$("#dproduction_loss_increase_q").addClass("input-xlarge ngcls");
							}
							profit = Math.abs(profit);
							$('#dproduction_loss_increase_q').val(sign+" "+profit);
							$('#production_loss_increase_q').val(sign+" "+profit);

							}
		</script>

</body>
</html>
<?php

} else {
	print "<META http-equiv='refresh' content=0;URL=" . $sitepath . ">";
	exit;
}
?>
