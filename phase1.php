<?php
session_start();
include("inc/connection.php");
require("inc/funcstuffs.php");

$_SESSION["phaseurl"] = full_url();

$pageUrl = $sitepath . "manageproduction.php";
$pageChange = $sitepath . "phase1.php";
$savePage = $sitepath . "savephase1.php";
$managePage = " Production Phase 1";
$tabname = "production_2";
$tabmain = "production_1";
$tabcustomer = "customer";
$taboperator = "operator";
$tabmachine = "machine";
$tabjob = "joblist";

if ($_SESSION["sadmin_username"] != "") {
  $operation = "Add";
  $id = $_GET["id"];
  if ($id != "" and is_numeric($id)) {

    $qry = "SELECT * from $tabname where production_1=" . $id;
    $result = mysqli_query($db, $qry) or die("cannot select Production_2 " . mysqli_error($db));
    if ($row = mysqli_fetch_array($result)) {
      $operation = "Edit";
      $proid = $row["production_1"];
      $t1 = $row["time_1"];
      $qa1 = $row["q_after_1"];
      $t2 = $row["time_2"];
      $qa2 = $row["q_after_2"];
      $t3 = $row["time_3"];
      $qa3 = $row["q_after_3"];
      $t4 = $row["time_4"];
      $qa4 = $row["q_after_4"];
      $t5 = $row["time_5"];
      $qa5 = $row["q_after_5"];
      $t6 = $row["time_6"];
      $qa6 = $row["q_after_6"];
      $t7 = $row["time_7"];
      $qa7 = $row["q_after_7"];
      $t8 = $row["time_8"];
      $qa8 = $row["q_after_8"];
      $t9 = $row["time_9"];
		  $qa9 = $row["q_after_9"];
      $t10 = $row["time_10"];
      $qa10 = $row["q_after_10"];
      $t11 = $row["time_11"];
      $qa11 = $row["q_after_11"];
      $t12 = $row["time_12"];
      $qa12 = $row["q_after_12"];
      $total_q_before_rejection = $row["total_q_before_rejection"];

      $checked_1 = "";
      if($row["check_1"] == 1){
        $checked_1 = "checked";
      }
      $checked_2 = "";
      if($row["check_2"] == 1){
        $checked_2 = "checked";
      }
      $checked_3 = "";
      if($row["check_3"] == 1){
        $checked_3 = "checked";
      }
      $checked_4 = "";
      if($row["check_4"] == 1){
        $checked_4 = "checked";
      }
      $checked_5 = "";
      if($row["check_5"] == 1){
        $checked_5 = "checked";
      }
      $checked_6 = "";
      if($row["check_6"] == 1){
        $checked_6 = "checked";
      }
      $checked_7 = "";
      if($row["check_7"] == 1){
        $checked_7 = "checked";
      }
      $checked_8 = "";
      if($row["check_8"] == 1){
        $checked_8 = "checked";
      }
      $checked_9 = "";
      if($row["check_9"] == 1){
        $checked_9 = "checked";
      }
      $checked_10 = "";
      if($row["check_10"] == 1){
        $checked_10 = "checked";
      }
      $checked_11 = "";
      if($row["check_11"] == 1){
        $checked_11 = "checked";
      }
      $checked_12 = "";
      if($row["check_12"] == 1){
        $checked_12 = "checked";
      }
      

      $qry = "SELECT $tabmain.productiondate,$tabjob.jobno,$tabmain.part_count_start,$tabmain.shift,$tabmain.required_product_q_per_hr,$tabmachine.machine,$tabcustomer.customer,$taboperator.operator FROM $tabmain  LEFT JOIN $tabmachine ON $tabmachine.id=$tabmain.machine LEFT JOIN $tabcustomer ON $tabcustomer.id=$tabmain.customer LEFT JOIN $tabjob ON $tabjob.id=$tabmain.job_no LEFT JOIN $taboperator ON $taboperator.id=$tabmain.operator where $tabmain.id=" . $proid . "";
      $result = mysqli_query($db, $qry) or die("cannot select Production table " . mysqli_error());
      if ($row1 = mysqli_fetch_array($result)) {

        $customer = $row1["customer"];
        $machine = $row1["machine"];
        $start_count = $row1["part_count_start"];
        $operator = $row1["operator"];
        $jobno = $row1["jobno"];
        $required_product_q_per_hr = $row1["required_product_q_per_hr"];

        if ($row["shift"] == "0") {
			$shift = "Day";
		} else {
			$shift = "Night";
        }

		$date = new DateTime($row1["productiondate"]);
        $productiondate = $date->format('m/d/Y');
      }
    } else {
      print "<META http-equiv='refresh' content=0;URL='" . $pageUrl . "'>";
      exit;
    }
  }
  $title = " Production Phase 1 ( Before Rejection )";
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
									<li> <a href="<?= $sitepath . " home.php "; ?>">Home</a> <span class="divider">/</span> </li>
									<li> <a href="<?= $pageUrl; ?>">Manage Production</a> <span class="divider">/</span> </li>
									<li>
										<a href="#">
											<?= $title; ?>
										</a>
									</li>
								</ul>
							</div>
							<?php alertBox(); ?>
								<div class="row-fluid sortable">
									<div class="box span12">
										<div class="box-header well" data-original-title>
											<h2><i class="icon-edit"></i> <?= $title; ?></h2> </div>
										<div class="box-content">
											<form class="form-horizontal" id="frm1" name="frm1" action="<?php echo $savePage; ?>" method="post" enctype="multipart/form-data">
												<input type="hidden" name="id" value="<?php echo $id; ?>" />
												<input type="hidden" name="opt" value="<?php echo $operation; ?>" />
												<input type="hidden" name="starting_count" id="starting_count" value="<?php echo $start_count; ?>" />
												<fieldset>
													<div class="well detbox">
														<div class="row-fluid">
															<div class="span4"><b>Production Date :</b>
																<?= $productiondate; ?>
															</div>
															<div class="span4"><b>Machine :</b>
																<?= $machine; ?>
															</div>
															<div class="span4"><b>Customer :</b>
																<?= $customer; ?>
															</div>
														</div>
														<div class="row-fluid">
															<div class="span4"><b>Operator :</b>
																<?= $operator; ?>
															</div>
															<div class="span4"><b>Shift :</b>
																<?= $shift; ?>
															</div>
															<div class="span4"><b>Required Qty. / Hour :</b>
																<?= $required_product_q_per_hr; ?>
															</div>
														</div>
														<div class="row-fluid">
															<div class="span4"><b>Starting Count :</b>
																<?= $start_count; ?>
															</div>
															<div class="span4"><b>Job No. :</b>
																<?= $jobno; ?>
															</div>
														</div>
													</div>
													<div class="clearfix"></div>
													<div class="row-fluid">
														<div class="span6">
															<div class="control-group">
																<label class="control-label">8:00 to 9:00 am </label>
																<div class="controls">
																	<input type="number" class="input-xlarge callblur" id="time_1" name="time_1" value="<?= $t1; ?>" placeholder="0" required>
																	<input type="checkbox" class="mycheckbox" id="check_1" name="check_1" <?= $checked_1; ?>> 
                                </div>
															</div>
														</div>
														<div class="span6">
															<div class="control-group">
																<label class="control-label">After 1Hr Prod. Qty.</label>
																<div class="controls">
																	<input type="text" class="input-xlarge" id="dq_after_1" name="dq_after_1" value="<?= $qa1; ?>" placeholder="0" disabled required>
																	<input type="hidden" class="input-xlarge" id="q_after_1" name="q_after_1" value="<?= $qa1; ?>"> </div>
															</div>
														</div>
													</div>
													<div class="row-fluid">
														<div class="span6">
															<div class="control-group">
																<label class="control-label">9:00 to 10:00 am</label>
																<div class="controls">
																	<input type="number" class="input-xlarge callblur" id="time_2" name="time_2" value="<?= $t2; ?>" placeholder="0" required>
                                  <input type="checkbox" class="mycheckbox" id="check_2" name="check_2" <?= $checked_2; ?>> 
                                </div>
															</div>
														</div>
														<div class="span6">
															<div class="control-group">
																<label class="control-label">After 2Hr Prod. Qty.</label>
																<div class="controls">
																	<input type="text" class="input-xlarge" id="dq_after_2" name="dq_after_2" value="<?= $qa2; ?>" placeholder="0" disabled required>
																	<input type="hidden" class="input-xlarge" id="q_after_2" name="q_after_2" value="<?= $qa2; ?>"> </div>
															</div>
														</div>
													</div>
													<div class="row-fluid">
														<div class="span6">
															<div class="control-group">
																<label class="control-label">10:00 to 11:00 am </label>
																<div class="controls">
																	<input type="number" class="input-xlarge callblur" id="time_3" name="time_3" value="<?= $t3; ?>" placeholder="0" required>
                                  <input type="checkbox" class="mycheckbox" id="check_3" name="check_3" <?= $checked_3; ?>> 
                                </div>
															</div>
														</div>
														<div class="span6">
															<div class="control-group">
																<label class="control-label">After 3Hr Prod. Qty.</label>
																<div class="controls">
																	<input type="text" class="input-xlarge" id="dq_after_3" name="dq_after_3" value="<?= $qa3; ?>" placeholder="0" disabled required>
																	<input type="hidden" class="input-xlarge" id="q_after_3" name="q_after_3" value="<?= $qa3; ?>"> </div>
															</div>
														</div>
													</div>
													<div class="row-fluid">
														<div class="span6">
															<div class="control-group">
																<label class="control-label">11:00 to 12:00 pm</label>
																<div class="controls">
																	<input type="number" class="input-xlarge callblur" id="time_4" name="time_4" value="<?= $t4; ?>" placeholder="0" required> 
                                  <input type="checkbox" class="mycheckbox" id="check_4" name="check_4" <?= $checked_4; ?>>
                                </div>
															</div>
														</div>
														<div class="span6">
															<div class="control-group">
																<label class="control-label">After 4Hr Prod. Qty.</label>
																<div class="controls">
																	<input type="text" class="input-xlarge" id="dq_after_4" name="dq_after_4" value="<?= $qa4; ?>" placeholder="0" disabled required>
																	<input type="hidden" class="input-xlarge" id="q_after_4" name="q_after_4" value="<?= $qa4; ?>"> </div>
															</div>
														</div>
													</div>
													<div class="row-fluid">
														<div class="span6">
															<div class="control-group">
																<label class="control-label">12:00 to 1:00 pm </label>
																<div class="controls">
																	<input type="number" class="input-xlarge callblur" id="time_5" name="time_5" value="<?= $t5; ?>" placeholder="0" required> 
                                  <input type="checkbox" class="mycheckbox" id="check_5" name="check_5" <?= $checked_5; ?>>
                                </div>
															</div>
														</div>
														<div class="span6">
															<div class="control-group">
																<label class="control-label">After 5Hr Prod. Qty.</label>
																<div class="controls">
																	<input type="text" class="input-xlarge" id="dq_after_5" name="dq_after_5" value="<?= $qa5; ?>" placeholder="0" disabled required>
																	<input type="hidden" class="input-xlarge" id="q_after_5" name="q_after_5" value="<?= $qa5; ?>"> </div>
															</div>
														</div>
													</div>
													<div class="row-fluid">
														<div class="span6">
															<div class="control-group">
																<label class="control-label">1:00 to 2:00 pm</label>
																<div class="controls">
																	<input type="number" class="input-xlarge callblur" id="time_6" name="time_6" value="<?= $t6; ?>" placeholder="0" required> 
                                  <input type="checkbox" class="mycheckbox" id="check_6" name="check_6" <?= $checked_6; ?>>
                                </div>
															</div>
														</div>
														<div class="span6">
															<div class="control-group">
																<label class="control-label">After 6Hr Prod. Qty.</label>
																<div class="controls">
																	<input type="text" class="input-xlarge" id="dq_after_6" name="dq_after_6" value="<?= $qa6; ?>" placeholder="0" disabled required>
																	<input type="hidden" class="input-xlarge" id="q_after_6" name="q_after_6" value="<?= $qa6; ?>"> </div>
															</div>
														</div>
													</div>
													<div class="row-fluid">
														<div class="span6">
															<div class="control-group">
																<label class="control-label">2:00 to 3:00 pm </label>
																<div class="controls">
																	<input type="number" class="input-xlarge callblur" id="time_7" name="time_7" value="<?= $t7; ?>" placeholder="0" required> 
                                  <input type="checkbox" class="mycheckbox" id="check_7" name="check_7" <?= $checked_7; ?>>
                                </div>
															</div>
														</div>
														<div class="span6">
															<div class="control-group">
																<label class="control-label">After 7Hr Prod. Qty.</label>
																<div class="controls">
																	<input type="text" class="input-xlarge" id="dq_after_7" name="dq_after_7" value="<?= $qa7; ?>" placeholder="0" disabled required>
																	<input type="hidden" class="input-xlarge" id="q_after_7" name="q_after_7" value="<?= $qa7; ?>"> </div>
															</div>
														</div>
													</div>
													<div class="row-fluid">
														<div class="span6">
															<div class="control-group">
																<label class="control-label">3:00 to 4:00 pm</label>
																<div class="controls">
																	<input type="number" class="input-xlarge callblur" id="time_8" name="time_8" value="<?= $t8; ?>" placeholder="0" required> 
                                  <input type="checkbox" class="mycheckbox" id="check_8" name="check_8" <?= $checked_8; ?>>
                                </div>
															</div>
														</div>
														<div class="span6">
															<div class="control-group">
																<label class="control-label">After 8Hr Prod. Qty.</label>
																<div class="controls">
																	<input type="text" class="input-xlarge" id="dq_after_8" name="dq_after_8" value="<?= $qa8; ?>" placeholder="0" disabled required>
																	<input type="hidden" class="input-xlarge" id="q_after_8" name="q_after_8" value="<?= $qa8; ?>"> </div>
															</div>
														</div>
													</div>
													<div class="row-fluid">
														<div class="span6">
															<div class="control-group">
																<label class="control-label">4:00 to 5:00 pm </label>
																<div class="controls">
																	<input type="number" class="input-xlarge callblur" id="time_9" name="time_9" value="<?= $t9; ?>" placeholder="0" required> 
                                  <input type="checkbox" class="mycheckbox" id="check_9" name="check_9" <?= $checked_9; ?>>
                                </div>
															</div>
														</div>
														<div class="span6">
															<div class="control-group">
																<label class="control-label">After 9Hr Prod. Qty.</label>
																<div class="controls">
																	<input type="text" class="input-xlarge" id="dq_after_9" name="dq_after_9" value="<?= $qa9; ?>" placeholder="0" disabled required>
																	<input type="hidden" class="input-xlarge" id="q_after_9" name="q_after_9" value="<?= $qa9; ?>"> </div>
															</div>
														</div>
													</div>
													<div class="row-fluid">
														<div class="span6">
															<div class="control-group">
																<label class="control-label">5:00 to 6:00 pm</label>
																<div class="controls">
																	<input type="number" class="input-xlarge callblur" id="time_10" name="time_10" value="<?= $t10; ?>" placeholder="0" required> 
                                  <input type="checkbox" class="mycheckbox" id="check_10" name="check_10" <?= $checked_10; ?>>
                                </div>
															</div>
														</div>
														<div class="span6">
															<div class="control-group">
																<label class="control-label">After 10Hr Prod. Qty.</label>
																<div class="controls">
																	<input type="text" class="input-xlarge" id="dq_after_10" name="dq_after_10" value="<?= $qa10; ?>" placeholder="0" disabled required>
																	<input type="hidden" class="input-xlarge" id="q_after_10" name="q_after_10" value="<?= $qa10; ?>"> </div>
															</div>
														</div>
													</div>
													<div class="row-fluid">
														<div class="span6">
															<div class="control-group">
																<label class="control-label">6:00 to 7:00 pm </label>
																<div class="controls">
																	<input type="number" class="input-xlarge callblur" id="time_11" name="time_11" value="<?= $t11; ?>" placeholder="0" required> 
                                  <input type="checkbox" class="mycheckbox" id="check_11" name="check_11" <?= $checked_11; ?>>
                                </div>
															</div>
														</div>
														<div class="span6">
															<div class="control-group">
																<label class="control-label">After 11Hr Prod. Qty.</label>
																<div class="controls">
																	<input type="text" class="input-xlarge" id="dq_after_11" name="dq_after_11" value="<?= $qa11; ?>" placeholder="0" disabled required>
																	<input type="hidden" class="input-xlarge" id="q_after_11" name="q_after_11" value="<?= $qa11; ?>"> </div>
															</div>
														</div>
													</div>
													<div class="row-fluid">
														<div class="span6">
															<div class="control-group">
																<label class="control-label">7:00 to 8:00 am</label>
																<div class="controls">
																	<input type="number" class="input-xlarge callblur" id="time_12" name="time_12" value="<?= $t12; ?>" placeholder="0" required> 
                                  <input type="checkbox" class="mycheckbox" id="check_12" name="check_12" <?= $checked_12; ?>>
                                </div>
															</div>
														</div>
														<div class="span6">
															<div class="control-group">
																<label class="control-label">After 12Hr Prod. Qty.</label>
																<div class="controls">
																	<input type="text" class="input-xlarge" id="dq_after_12" name="dq_after_12" value="<?= $qa12; ?>" placeholder="0" disabled required>
																	<input type="hidden" class="input-xlarge" id="q_after_12" name="q_after_12" value="<?= $qa12; ?>"> </div>
															</div>
														</div>
													</div>
													<div class="row-fluid">
													<div class="span6">
															<div class="control-group">
																<label class="control-label">Starting Count</label>
																<div class="controls">
																	<input type="text" class="input-xlarge" id="scount" name="scount" value="<?= $start_count; ?>" placeholder="0" disabled required> </div>
															</div>
														</div>	
														<div class="span6">
															<div class="control-group">
																<label class="control-label">Total Qty. Before Rejection</label>
																<div class="controls">
																	<input type="text" class="input-xlarge" id="dtotal_q_before_rejection" name="dtotal_q_before_rejection" value="<?= $total_q_before_rejection; ?>" placeholder="0" disabled required>
																	<input type="hidden" class="input-xlarge" id="total_q_before_rejection" name="total_q_before_rejection" value="<?= $total_q_before_rejection; ?>"> </div>
															</div>
														</div>
														
													</div>
													<div class="form-actions">
														<button type="submit" class="btn btn-primary">Save changes</button> <a href="<?= $pageUrl; ?>" class="btn">Cancel</a> </div>
												</fieldset>
											</form>
										</div>
									</div>
									<!--/span-->
								</div>
								<!--/row-->
						</div>
						<!--/#content.span10-->
				</div>
				<!--/fluid-row-->
				<hr>
				<?php include("inc/footer.php"); ?>
			</div>
			<!--/.fluid-container-->
			<?php include("inc/footerscripts.php"); ?>
				<script>
				$(document).ready(function() {
					$(".callblur").each(function() {
						$(this).bind("blur", function() {
							checkData();
						})
					})
					$("input.mycheckbox").click(function () {
						// Loop all these checkboxes which are checked
						$("input.mycheckbox:checked").each(function(){
							checkData();
							// Use $(this).val() to get the Bike, Car etc.. value
						});
					})
				});

				function checkData(){
					var starting_count = parseInt($("#starting_count").val());
						
							var t1 = parseInt($("#time_1").val()); // T1 Field Value
							var t2 = parseInt($("#time_2").val()); // T2 Field Value
							var t3 = parseInt($("#time_3").val()); // T3 Field Value
							var t4 = parseInt($("#time_4").val()); // T4 Field Value
							var t5 = parseInt($("#time_5").val()); // T5 Field Value
							var t6 = parseInt($("#time_6").val()); // T6 Field Value
							var t7 = parseInt($("#time_7").val()); // T7 Field Value
							var t8 = parseInt($("#time_8").val()); // T8 Field Value
							var t9 = parseInt($("#time_9").val()); // T9 Field Value
							var t10 = parseInt($("#time_10").val()); // T10 Field Value
							var t11 = parseInt($("#time_11").val()); // T11 Field Value
							var t12 = parseInt($("#time_12").val()); // T12 Field Value
							var qa1 = parseInt($("#q_after_1").val()); // QA1 Field Value
							var qa2 = parseInt($("#q_after_2").val()); // QA2 Field Value
							var qa3 = parseInt($("#q_after_3").val()); // QA3 Field Value
							var qa4 = parseInt($("#q_after_4").val()); // QA4 Field Value
							var qa5 = parseInt($("#q_after_5").val()); // QA5 Field Value
							var qa6 = parseInt($("#q_after_6").val()); // QA6 Field Value
							var qa7 = parseInt($("#q_after_7").val()); // QA7 Field Value
							var qa8 = parseInt($("#q_after_8").val()); // QA8 Field Value
							var qa9 = parseInt($("#q_after_9").val()); // QA9 Field Value
							var qa10 = parseInt($("#q_after_10").val()); // QA10 Field Value
							var qa11 = parseInt($("#q_after_11").val()); // QA11 Field Value
							var qa12 = parseInt($("#q_after_12").val()); // QA12 Field Value

							var cb1 = document.querySelector('#check_1'); // Checkbox Value
							if(!cb1.checked){
								t1 = 0;
							}
							var cb2 = document.querySelector('#check_2'); // Checkbox Value
							if(!cb2.checked){
								t2 = 0;
							}
							var cb3 = document.querySelector('#check_3'); // Checkbox Value
							if(!cb3.checked){
								t3 = 0;
							}
							var cb4 = document.querySelector('#check_4'); // Checkbox Value
							if(!cb4.checked){
								t4 = 0;
							}
							var cb5 = document.querySelector('#check_5'); // Checkbox Value
							if(!cb5.checked){
								t5 = 0;
							}
							var cb6 = document.querySelector('#check_6'); // Checkbox Value
							if(!cb6.checked){
								t6 = 0;
							}
							var cb7 = document.querySelector('#check_7'); // Checkbox Value
							if(!cb7.checked){
								t7 = 0;
							}
							var cb8 = document.querySelector('#check_8'); // Checkbox Value
							if(!cb8.checked){
								t8 = 0;
							}
							var cb9 = document.querySelector('#check_9'); // Checkbox Value
							if(!cb9.checked){
								t9 = 0;
							}
							var cb10 = document.querySelector('#check_10'); // Checkbox Value
							if(!cb10.checked){
								t10 = 0;
							}
							var cb11 = document.querySelector('#check_11'); // Checkbox Value
							if(!cb11.checked){
								t11 = 0;
							}
							var cb12 = document.querySelector('#check_12'); // Checkbox Value
							if(!cb12.checked){
								t12 = 0;
							}

							$('#dq_after_1').val(starting_count + t1);
							$('#q_after_1').val(starting_count + t1);
							$('#dq_after_2').val(starting_count + t1 + t2);
							$('#q_after_2').val(starting_count + t1 + t2);
							$('#dq_after_3').val(starting_count + t1 + t2 + t3);
							$('#q_after_3').val(starting_count + t1 + t2 + t3);
							$('#dq_after_3').val(starting_count + t1 + t2 + t3);
							$('#q_after_3').val(starting_count + t1 + t2 + t3);
							$('#dq_after_4').val(starting_count + t1 + t2 + t3 + t4);
							$('#q_after_4').val(starting_count + t1 + t2 + t3 + t4);
							$('#dq_after_5').val(starting_count + t1 + t2 + t3 + t4 + t5);
							$('#q_after_5').val(starting_count + t1 + t2 + t3 + t4 + t5);
							$('#dq_after_6').val(starting_count + t1 + t2 + t3 + t4 + t5 + t6);
							$('#q_after_6').val(starting_count + t1 + t2 + t3 + t4 + t5 + t6);
							$('#dq_after_7').val(starting_count + t1 + t2 + t3 + t4 + t5 + t6 + t7);
							$('#q_after_7').val(starting_count + t1 + t2 + t3 + t4 + t5 + t6 + t7);
							$('#dq_after_8').val(starting_count + t1 + t2 + t3 + t4 + t5 + t6 + t7 + t8);
							$('#q_after_8').val(starting_count + t1 + t2 + t3 + t4 + t5 + t6 + t7 + t8);
							$('#dq_after_9').val(starting_count + t1 + t2 + t3 + t4 + t5 + t6 + t7 + t8 + t9);
							$('#q_after_9').val(starting_count + t1 + t2 + t3 + t4 + t5 + t6 + t7 + t8 + t9);
							$('#dq_after_10').val(starting_count + t1 + t2 + t3 + t4 + t5 + t6 + t7 + t8 + t9 + t10);
							$('#q_after_10').val(starting_count + t1 + t2 + t3 + t4 + t5 + t6 + t7 + t8 + t9 + t10);
							$('#dq_after_11').val(starting_count + t1 + t2 + t3 + t4 + t5 + t6 + t7 + t8 + t9 + t10 + t11);
							$('#q_after_11').val(starting_count + t1 + t2 + t3 + t4 + t5 + t6 + t7 + t8 + t9 + t10 + t11);
							$('#dq_after_12').val(starting_count + t1 + t2 + t3 + t4 + t5 + t6 + t7 + t8 + t9 + t10 + t11 + t12);
							$('#q_after_12').val(starting_count + t1 + t2 + t3 + t4 + t5 + t6 + t7 + t8 + t9 + t10 + t11 + t12);
							$('#dtotal_q_before_rejection').val(starting_count + t1 + t2 + t3 + t4 + t5 + t6 + t7 + t8 + t9 + t10 + t11 + t12);
							$('#total_q_before_rejection').val(starting_count + t1 + t2 + t3 + t4 + t5 + t6 + t7 + t8 + t9 + t10 + t11 + t12);
						
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