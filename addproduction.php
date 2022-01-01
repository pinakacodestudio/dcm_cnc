<?php
session_start();
include("inc/connection.php");
require("inc/funcstuffs.php");

$pageUrl = $sitepath . "manageproduction.php";
$pageChange = $sitepath . "addproduction.php";
$savePage = $sitepath . "saveproduction.php";
$managePage = " Production";
$tabname = "production_1";

if ($_SESSION["sadmin_username"] != "") {
	$operation = "Add";
	$id = $_GET["id"];
	$mid = $_GET["mid"];
	$today = date("d/m/Y");
	if ($id != "" and is_numeric($id)) {
		$machine = "0";
		$customer = "0";
		$operator = "0";
		$shift = "0";
		$part_count_start = "";
		$part_count_end = "";
		$job_no = "";
		$batch_no = "";
		$job_cycle_time = "";
		$required_product_q_per_hr = "";
		$productiondate = $today;

		$qry = "select * from $tabname where id=" . $id;
		$result = mysqli_query($db, $qry) or die("cannot select users " . mysqli_error());
		if ($row = mysqli_fetch_array($result)) {
			$operation = "Edit";
			$machine = $row["machine"];
			$customer = $row["customer"];
			$operator = $row["operator"];
			$shift = $row["shift"];
			$part_count_end = $row["part_count_end"];
			$part_count_start = $row["part_count_start"];
			$job_no = $row["job_no"];
			$setup = $row["setup"];
			$batch_no = $row["batch_no"];
			$job_cycle_time = $row["job_cycle_time"];
			$required_product_q_per_hr = $row["required_product_q_per_hr"];

			$date = new DateTime($row["productiondate"]);
			$productiondate = $date->format('d/m/Y');
		} else {
			print "<META http-equiv='refresh' content=0;URL='" . $pageUrl . "'>";
			exit;
		}
	} else {
		$operation = "Add";
		$machine = $mid;
		$customer = "0";
		$operator = "0";
		$shift = "0";
		$part_count_start = "";
		$part_count_end = "";
		$job_no = "";
		$batch_no = "";
		$job_cycle_time = "";
		$required_product_q_per_hr = "";
		$productiondate = $today;

		if ($mid != "" and is_numeric($mid)) {
			$qry = "select * from $tabname where machine=" . $mid . " order by id desc limit 0,1";
			//echo $qry;
			$result = mysqli_query($db, $qry) or die("cannot select users " . mysqli_error());
			if ($row = mysqli_fetch_array($result)) {
				$machine = $row["machine"];
				$customer = $row["customer"];
				//$operator = $row["operator"];
				$shift = ($row["shift"]=="1")?"0":"1";
				$part_count_end = $row["part_count_end"];
				$part_count_start = $row["part_count_start"];
				$job_no = $row["job_no"];
				$setup = $row["setup"];
				$batch_no = $row["batch_no"];
				$job_cycle_time = $row["job_cycle_time"];
				$required_product_q_per_hr = $row["required_product_q_per_hr"];

				//$date = new DateTime($row["productiondate"]);
				//$productiondate = $date->format('d/m/Y');
			}
		}
	}

	$title = $operation . " Production";
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
						  <fieldset>

							 <div class="control-group">
							  <label class="control-label">Production Date *</label>
							  <div class="controls">
								<input type="text" class="input-xlarge datepicker" id="productdate" name="productiondate" value="<?= $productiondate; ?>" required>
							  </div>
							</div>
							<div class="control-group">
								<label class="control-label" >Machine *</label>
								<div class="controls">
								  <select id="machine" data-rel="chosen" name="machine" required <?php  ?>>
                                  <option value=""> - Select Machine - </option>
									<?php 
								$sql = "select id,machine from machine where status=1";
								$rs = mysqli_query($db, $sql) or die("cannot Select machine " . mysqli_error($db));
								while ($row = mysqli_fetch_array($rs)) {
									$sel = "";
									if ($row["id"] == $machine) {
										$sel = "selected";
									}
									echo '<option value="' . $row["id"] . '" ' . $sel . '>' . $row["machine"] . '</option>';
								}
								?>
                                        
								  </select>
								</div>
							  </div>
								<?php
									if($operation=="Add"){ 
									?>
										<div class="control-group">
											<label class="control-label" ></label>
											<div class="controls">
												<span id="reloadOldData" class="label label-warning" onClick="doReload()" style="cursor:help;padding:5px;">Click here to load previous process data</span>
											</div>
										</div>
									<?php
									}
								?>
                              <div class="control-group">
								<label class="control-label">Customer *</label>
								<div class="controls">
								  <select id="customer" name="customer" data-rel="chosen" required>
                                  <option value=""> - Select Customer - </option>
									<?php
								$sql = "select id,customer from customer where status=1";
								$rs = mysqli_query($db, $sql) or die("cannot Select customer " . mysqli_error($db));
								while ($row = mysqli_fetch_array($rs)) {
									$sel = "";
									if ($row["id"] == $customer) {
										$sel = "selected";
									}
									echo '<option value="' . $row["id"] . '" ' . $sel . '>' . $row["customer"] . '</option>';
								}
								?>
                                        
								  </select>
								</div>
							  </div>
                              
                              <div class="control-group">
								<label class="control-label">Operator *</label>
								<div class="controls">
								  <select id="operator" name="operator" data-rel="chosen" required>
                                  <option value=""> - Select Operator - </option>
									<?php
								$sql = "select id,operator from operator where status=1";
								$rs = mysqli_query($db, $sql) or die("cannot Select Operator " . mysqli_error($db));
								while ($row = mysqli_fetch_array($rs)) {
									$sel = "";
									if ($row["id"] == $operator) {
										$sel = "selected";
									}
									echo '<option value="' . $row["id"] . '" ' . $sel . '>' . $row["operator"] . '</option>';
								}
								?>
                                        
								  </select>
								</div>
							  </div>
                              
                              <div class="control-group">
								<label class="control-label">Shift *</label>
								<div class="controls">
								  <select id="shift" name="shift" data-rel="chosen" required>
									  <option value=""> - Select shift - </option>
									  <?php
										$day = "";
										$night = "";

										if ($shift == 0) {
											$day = "selected";
										} else {
											$night = "selected";
										}

										?>
									<option value="0" <?= $day ?>>Day</option>
									<option value="1" <?= $night; ?>>Night</option>
								  </select>
								</div>
							  </div>
                              
                              <div class="control-group">
							  <label class="control-label">Starting Count *</label>
							  <div class="controls">
								<input type="number" class="input-xlarge" id="part_count_start" name="part_count_start" value="<?= $part_count_start?>" placeholder="0" required>
							  </div>
							</div>
                            
                            <div class="control-group">
							  <label class="control-label">Ending Count *</label>
							  <div class="controls">
								<input type="text" class="input-xlarge" disabled id="part_count_end" name="part_count_end" value="<?= $part_count_end; ?>" required placeholder="0">
							  </div>
							</div>

							<div class="control-group">
								<label class="control-label" >Job No. *</label>
								<div class="controls">
								  <select id="job_no" data-rel="chosen" name="job_no" required>
                                  <option value=""> - Select Job No. - </option>
									<?php 
								$sql = "select id,jobno from joblist where status=1";
								$rs = mysqli_query($db, $sql) or die("cannot Select Jobs " . mysqli_error($db));
								while ($row = mysqli_fetch_array($rs)) {
									$sel = "";
									if ($row["id"] == $job_no) {
										$sel = "selected";
									}
									echo '<option value="' . $row["id"] . '" ' . $sel . '>' . $row["jobno"] . '</option>';
								}
								?>
                                        
								  </select>
								</div>
							  </div>
<!-- 								
											      <div class="control-group">
							  <label class="control-label">Job No. *</label>
							  <div class="controls">
								<input type="text" class="input-xlarge" id="job_no" name="job_no" value="<?= $job_no; ?>" required>
							  </div>
							</div> -->

							  <div class="control-group">
								  <label class="control-label">Setup *</label>
								  <div class="controls">
									<select id="setup" name="setup" data-rel="chosen" required>
										<option value=""> - Select Setup - </option>
										<?php
											$s1 = "";
											$s2 = "";
											$s3 = "";
											if ($setup == 1) {
												$s1 = "selected";
											} else if ($setup == 2) {
												$s2 = "selected";
											} else if ($setup == 3) {
												$s3 = "selected";
											}else if ($setup == 4) {
												$s3 = "selected";
											}
										?>
										<option value="1" <?= $s1 ?>> 1st </option>
										<option value="2" <?= $s2; ?>> 2nd </option>
										<option value="3" <?= $s3; ?>> 1st & 2nd </option>
										<option value="4" <?= $s4; ?>> Rework </option>
									  </select>								  
									</div>
							  </div>

                            <div class="control-group">
							  <label class="control-label">Batch No. / Qty. *</label>
							  <div class="controls">
								<input type="text" class="input-xlarge" id="batch_no" name="batch_no"  value="<?= $batch_no; ?>" required>
							  </div>
							</div>
                            <div class="control-group">
							  <label class="control-label">Job Cycle Time *</label>
							  <div class="controls">
								<input type="number" class="input-xlarge" id="job_cycle_time" name="job_cycle_time" placeholder="0" value="<?= $job_cycle_time; ?>" required>
							  </div>
							</div>
                            
							<div class="control-group">
							  <label class="control-label">Required Qty. / Hr. *</label>
							  <div class="controls">
								<input type="number" class="input-xlarge" id="required_product_q_per_hr" name="required_product_q_per_hr" value="<?= $required_product_q_per_hr; ?>" placeholder="0" required>
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
			function doReload(){
				var mid = $("#machine").val();
				if(mid==""){
					alert("Please select Machine.");
					return;
				}else{
					document.location = 'addproduction.php?mid=' + mid;
				}
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
