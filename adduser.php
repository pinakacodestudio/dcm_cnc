	<?php
session_start();
include("inc/connection.php");
require("inc/funcstuffs.php");

$pageUrl = $sitepath . "manageuser.php";
$pageChange = $sitepath . "adduser.php";
$savePage = $sitepath . "saveuser.php";
$managePage = " User";
$tabname = "company";

if ($_SESSION["sadmin_username"] != "") {
	$operation = "Add";
	$id = $_GET["id"];
	$today = date("d/m/Y");
	if ($id != "" and is_numeric($id)) {
		$username = "";
		$fullname = "";
		$mobile = "";
		$password = "";
		$status = 1;
		$usertype = 2;
		$qry = "select * from $tabname where id=" . $id;
		$result = mysqli_query($db, $qry) or die("cannot select users " . mysqli_error());
		if ($row = mysqli_fetch_array($result)) {
			$operation = "Edit";
			$username = $row["username"];
			$fullname = $row["fullname"];
			$mobile = $row["mobile"];
			$password = $row["password"];
			$usertype = $row["usertype"];
			$status = $row["status"];
		} else {
			print "<META http-equiv='refresh' content=0;URL='" . $pageUrl . "'>";
			exit;
		}
	} else {
		$operation = "Add";
		$username = "";
		$fullname = "";
		$mobile = "";
		$password = "";
		$usertype = 2;
		$status = 1;
	}

	$checked = "";
	if ($status == 1) {
		$checked = "checked";
	}

	$title = $operation . " User";
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
						<a href="<?= $pageUrl; ?>">Manage User</a> <span class="divider">/</span>
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
							  <label class="control-label">User Fullname *</label>
							  <div class="controls">
								<input type="text" class="input-xlarge" id="fullname" name="fullname" value="<?= $fullname; ?>" required>
							  </div>
							</div> 
							
							<div class="control-group">
							  <label class="control-label">Mobile No. *</label>
							  <div class="controls">
								<input type="number" class="input-xlarge" id="mobile" name="mobile"  pattern="[789][0-9]{9}" value="<?= $mobile; ?>" required>
							  </div>
							</div>
							<div class="control-group">
							  <label class="control-label">Username *</label>
							  <div class="controls">
								<input type="text" class="input-xlarge" id="username" name="username" value="<?= $username; ?>" required>
							  </div>
							</div>
							<div class="control-group">
							  <label class="control-label">Password *</label>
							  <div class="controls">
								<input type="text" class="input-xlarge" id="password" name="password" value="<?= $password; ?>" required>
							  </div>
							</div>

								<div class="control-group">
							  <label class="control-label">User Type *</label>
							  <div class="controls">
								<select name="usertype" id="usertype" class="input-xlarge">
									<option value="1" <?php if ($usertype == 1) {
																											echo "selected";
																										} ?>>Admin</option>
									<option value="2" <?php if ($usertype == 2) {
																											echo "selected";
																										} ?>>Sub User</option>
								</select>
							  </div>
							</div>
							
              
              <div class="control-group">
							  <label class="control-label">Status</label>
							  <div class="controls">
								 <input data-no-uniform="true" type="checkbox" id="status" name="status" <?= $checked; ?> class="iphone-toggle">
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
   
		
</body>
</html>
	<?php

} else {
	print "<META http-equiv='refresh' content=0;URL=" . $sitepath . ">";
	exit;
}
?>
