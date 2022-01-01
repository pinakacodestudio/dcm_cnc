<?php

session_start();

include("inc/connection.php");

require("inc/funcstuffs.php");



$pageUrl = $sitepath."managemachine.php";

$pageChange = $sitepath."addmachine.php";

$savePage = $sitepath."savemachine.php";

$managePage = " Machine";

$tabname = "machine";



if($_SESSION["sadmin_username"]!="")

{

$operation="Add";

$id=$_GET["id"];

if($id!="" and is_numeric($id))

{

	$machine="";

	$qry="select * from $tabname where id=".$id;

	$result=mysqli_query($db,$qry) or die("cannot select Machine".mysqli_error($db));

	if($row=mysqli_fetch_array($result))

	{

		$operation="Edit";

		$machine=$row["machine"];

	}

	else

	{

		print "<META http-equiv='refresh' content=0;URL='".$pageUrl."'>";

		exit;

	}

}

else

{

	$operation="Add";

	$machine="";

}



$title = $operation." Machine";

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

						<a href="<?= $sitepath."home.php"; ?>">Home</a> <span class="divider">/</span>

					</li>

					<li>

						<a href="<?= $pageUrl; ?>">Manage Machine</a> <span class="divider">/</span>

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

						<form class="form-horizontal" id="frm1" name="frm1" action="<?php echo $savePage;?>" method="post" enctype="multipart/form-data">

							<input type="hidden" name="id" value="<?php echo $id; ?>" />

							<input type="hidden" name="opt" value="<?php echo $operation; ?>" />

						  <fieldset>

							

							<div class="control-group">

							  <label class="control-label">Machine Name *</label>

							  <div class="controls">

								<input type="text" class="input-xlarge" id="machine" name="machine" value="<?= $machine; ?>" required>

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

}

else

{

	print "<META http-equiv='refresh' content=0;URL=".$sitepath.">";

	exit;

}

?>

