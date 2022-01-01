<?php
session_start();
include("inc/connection.php");
require("inc/funcstuffs.php");

$_SESSION["phaseurl"] = full_url();

$pageUrl = $sitepath . "manageproduction.php";
$pageChange = $sitepath . "phase2.php";
$savePage = $sitepath . "savephase2.php";
$managePage = " Production Phase 2";
$tabname = "production_3";
$tabmain = "production_1";
$tabcustomer = "customer";
$taboperator = "operator";
$tabmachine = "machine";
$tabjob = "joblist";
$tabphase1 = "production_2";

if ($_SESSION["sadmin_username"] != "") {
	$operation = "Add";
	$id = $_GET["id"];
	if ($id != "" and is_numeric($id)) {

		$qry = "SELECT * from $tabname where production_1=" . $id;
		$result = mysqli_query($db, $qry) or die("cannot select Production_3 " . mysqli_error($db));
		if ($row = mysqli_fetch_array($result)) {
			$operation = "Edit";
			$proid = $row["production_1"];
			$variation_nos = $row["variation_nos"];
			$turning_rejection_nos = $row["turning_rejection_nos"];
			$setting_rejection_nos = $row["setting_rejection_nos"];
			$pre_machining_rejection_nos = $row["pre_machining_rejection_nos"];
			$forging_rejection_nos = $row["forging_rejection_nos"];
			$total_q_after_rejection = $row["total_q_after_rejection"];
			$expected_q = $row["expected_q"];
			$setting_hr = $row["setting_hr"];
			$production_loss_increase_q = $row["production_loss_increase_q"];
 
			/*
			if ($variation_nos == 0) {
				$variation_nos = "";
			}
			if ($turning_rejection_nos == 0) {
				$turning_rejection_nos = "";
			}
			if ($setting_rejection_nos == 0) {
				$setting_rejection_nos = "";
			}
			if ($pre_machining_rejection_nos == 0) {
				$pre_machining_rejection_nos = "";
			}
			if ($forging_rejection_nos == 0) {
				$forging_rejection_nos = "";
			}
			if ($setting_hr == 0) {
				$setting_hr = "";
			}
			 */
			$qry = "SELECT $tabjob.jobno,$tabphase1.total_q_before_rejection,$tabmain.productiondate,$tabmain.job_cycle_time,$tabmain.part_count_start,$tabmain.shift,$tabmain.required_product_q_per_hr,$tabmachine.machine,$tabcustomer.customer,$taboperator.operator FROM $tabmain  LEFT JOIN $tabphase1 ON $tabphase1.production_1 =$tabmain.id  LEFT JOIN $tabmachine ON $tabmachine.id=$tabmain.machine LEFT JOIN $tabcustomer ON $tabcustomer.id=$tabmain.customer LEFT JOIN $tabjob ON $tabjob.id=$tabmain.job_no LEFT JOIN $taboperator ON $taboperator.id=$tabmain.operator where $tabmain.id=" . $proid . "";
			$result = mysqli_query($db, $qry) or die("cannot select Production table " . mysqli_error());
			if ($row1 = mysqli_fetch_array($result)) {

				$customer = $row1["customer"];
				$machine = $row1["machine"];
				$start_count = $row1["part_count_start"];
				$operator = $row1["operator"];
				$job_cycle_time = $row1["job_cycle_time"];
				$jobno = $row1["jobno"];
				$total_q_before_rejection = $row1["total_q_before_rejection"];
				$required_product_q_per_hr = $row1["required_product_q_per_hr"];
				$actual_expected_q = 11 * $required_product_q_per_hr;

				if ($row["shift"] == "0") {
					$shift = "Day";
				} else {
					$shift = "Night";
				}

				$date = new DateTime($row1["productiondate"]);
				$productiondate = $date->format('m/d/Y');
			}

			if ($expected_q == 0) {
				$expected_q = $actual_expected_q;
			}
		} else {
			print "<META http-equiv='refresh' content=0;URL='" . $pageUrl . "'>";
			exit;
		}
	}
	$title = " Production Phase 2";
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
						<h2><i class="icon-edit"></i><?= $title; ?></h2>
					</div>
					<div class="box-content">
						<form class="form-horizontal" id="frm1" name="frm1" action="<?php echo $savePage; ?>" method="post" enctype="multipart/form-data">
							<input type="hidden" name="id" value="<?php echo $id; ?>" />
							<input type="hidden" name="opt" value="<?php echo $operation; ?>" />
							<input type="hidden" name="start_count" id="start_count" value="<?php echo $start_count; ?>" />
							<input type="hidden" name="total_q_before_rejection" id="total_q_before_rejection" value="<?php echo $total_q_before_rejection; ?>" />
							<input type="hidden" name="job_cycle_time" id="job_cycle_time" value="<?php echo $job_cycle_time; ?>" />
							<input type="hidden" name="required_product_q_per_hr" id="required_product_q_per_hr" value="<?php echo $required_product_q_per_hr; ?>" />
							<fieldset>
                          <div class="well detbox">
                          <div class="row-fluid">
                          	<div class="span4"><b>Production Date :</b> <?= $productiondate ?> </div>
                            <div class="span4"><b>Machine :</b> <?= $machine; ?></div>
                            <div class="span4"><b>Customer :</b> <?= $customer; ?> </div>
                          </div>
                          <div class="row-fluid">
                            <div class="span4"><b>Operator :</b> <?= $operator; ?> </div>
                            <div class="span4"><b>Shift :</b> <?= $shift ?> </div>
                            <div class="span4"><b>Required Qty. / Hour :</b> <?= $required_product_q_per_hr; ?></div>
                           </div>
							  <div class="row-fluid">
								  <div class="span4"><b>Starting Count :</b> <?= $start_count; ?> </div>
                                  <div class="span4"><b>Job No. :</b> <?= $jobno; ?> </div>
								  <div class="span4"><b>Total Qty. Before Rejection :</b> <?= $total_q_before_rejection; ?> </div>
								</div>
                                <div class="row-fluid"
            					  <div class="span4"><b>Expected Qty. :</b> <?= $actual_expected_q; ?> </div>
							  	</div>

                            <div class="clearfix"></div>

                            <div class="control-group">
							  <label class="control-label">Variation </label>
							  <div class="controls">
								<input type="number" class="input-xlarge callblur" id="variation_nos" name="variation_nos" value="<?= $variation_nos; ?>" placeholder="0" required>
							  </div>
							</div>

                             <div class="control-group">
							  <label class="control-label">Turning Rejection</label>
							  <div class="controls">
								<input type="number" class="input-xlarge callblur" id="turning_rejection_nos" name="turning_rejection_nos" value="<?= $turning_rejection_nos; ?>"  placeholder="0" required>
							  </div>
							</div>

                             <div class="control-group">
							  <label class="control-label">Setting Rejection</label>
							  <div class="controls">
								<input type="number" class="input-xlarge callblur" id="setting_rejection_nos" name="setting_rejection_nos" value="<?= $setting_rejection_nos; ?>"  placeholder="0" required>
							  </div>
							</div>

                             <div class="control-group">
							  <label class="control-label">Pre Matching Rejection</label>
							  <div class="controls">
								<input type="number" class="input-xlarge callblur" id="pre_machining_rejection_nos" name="pre_machining_rejection_nos" value="<?= $pre_machining_rejection_nos; ?>"  placeholder="0" required>
							  </div>
							</div>

                             <div class="control-group">
							  <label class="control-label">Forging Rejection</label>
							  <div class="controls">
								<input type="number" class="input-xlarge callblur" id="forging_rejection_nos" name="forging_rejection_nos" value="<?= $forging_rejection_nos; ?>"  placeholder="0" required>
							  </div>
							</div>

                             <div class="control-group">
							  <label class="control-label">Total Qty. After Rejection</label>
							  <div class="controls">
								<input type="text" class="input-xlarge" id="dtotal_q_after_rejection" name="dtotal_q_after_rejection" value="<?= $total_q_after_rejection; ?>" disabled  placeholder="0" required>
								  <input type="hidden" class="input-xlarge" id="total_q_after_rejection" name="total_q_after_rejection" value="<?= $total_q_after_rejection; ?>"  placeholder="0" required>
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

						var vid = parseInt($("#variation_nos").val()); // Field Value
						var tjid = parseInt($("#turning_rejection_nos").val()); // Field Value
						var srid = parseInt($("#setting_rejection_nos").val()); // Field Value
						var pmrid = parseInt($("#pre_machining_rejection_nos").val()); // Field Value
						var frid = parseInt($("#forging_rejection_nos").val()); // Field Value

						if(isNaN(vid)){
							vid = 0;
						}
						if(isNaN(tjid)){
							tjid = 0;
						}
						if(isNaN(srid)){
							srid = 0;
						}
						if(isNaN(pmrid)){
							pmrid = 0;
						}
						if(isNaN(frid)){
							frid = 0;
						}
						var start_count = parseInt($("#start_count").val()); // Field Value
						var bfr = parseInt($("#total_q_before_rejection").val()); // Field Value

						var tot = (bfr - start_count ) - (vid + tjid + srid + pmrid + frid);

						$('#dtotal_q_after_rejection').val(tot);
						$('#total_q_after_rejection').val(tot);

						showProfit();
					})
				})

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
