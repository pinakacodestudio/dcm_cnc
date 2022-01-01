<?php
session_start();
include("inc/connection.php");
require("inc/funcstuffs.php");

$pageUrl = $sitepath . "managejob.php";
$pageChange = $sitepath . "addjob.php";
$savePage = $sitepath . "savejob.php";
$managePage = " Job No.";
$tabname = "joblist";

if ($_SESSION["sadmin_username"] != "") {
    $operation = "Add";
    $id = $_GET["id"];
    $today = date("d/m/Y");
    if ($id != "" and is_numeric($id)) {
        $jobno = "";
        $status = 1;
        $qry = "select * from $tabname where id=" . $id;
        $result = mysqli_query($db, $qry) or die("cannot select Joblist " . mysqli_error());
        if ($row = mysqli_fetch_array($result)) {
            $operation = "Edit";
            $jobno = $row["jobno"];
            $status = $row["status"];
        } else {
            print "<META http-equiv='refresh' content=0;URL='" . $pageUrl . "'>";
            exit;
        }
    } else {
        $operation = "Add";
        $jobno = "";
        $status = 1;
    }

    $checked = "";
    if ($status == 1) {
        $checked = "checked";
    }

    $title = $operation . " Job No.";
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
						<a href="<?= $pageUrl; ?>">Manage Job No.</a> <span class="divider">/</span>
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
							  <label class="control-label">Job No. *</label>
							  <div class="controls">
								<input type="text" class="input-xlarge" id="jobno" name="jobno" value="<?= $jobno; ?>" required>
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
