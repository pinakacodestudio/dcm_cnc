<?php
session_start();
require("inc/connection.php");
require("inc/funcstuffs.php");

if($_SESSION["sadmin_username"]!="")
{
	$sql = "select id from production_1";
	$rs = mysqli_query($db,$sql)or die("Cannot Selection Production".mysqli_error($db));
	$production = mysqli_num_rows($rs);

	$sql = "select id from machine";
	$rs = mysqli_query($db,$sql)or die("Cannot Selection machine".mysqli_error($db));
	$machine = mysqli_num_rows($rs);

	$sql = "select id from operator";
	$rs = mysqli_query($db,$sql)or die("Cannot Selection Operator".mysqli_error($db));
	$operator = mysqli_num_rows($rs);

	$sql = "select id from customer";
	$rs = mysqli_query($db,$sql)or die("Cannot Selection Customer".mysqli_error($db));
	$customer = mysqli_num_rows($rs);

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
						<a href="home.php">Home</a> <span class="divider">/</span>
					</li>
					<li>
						<a href="#">Dashboard</a>
					</li>
				</ul>
			</div>

				<?php alertBox(); ?>

				<div class="sortable row-fluid">
				<a data-rel="tooltip" title="6 new members." class="well span3 top-block" href="manageproduction.php">
					<span class="icon-red"><i class="icon-th"></i></span>
					<div>Production</div>
					<div><?= $production; ?></div>
				</a>

				<a data-rel="tooltip" title="4 new pro members." class="well span3 top-block" href="managemachine.php">
					<span class="icon-green"><i class="icon-list-alt"></i></span>
					<div>Machine</div>
					<div><?= $machine; ?></div>
				</a>

				<a data-rel="tooltip" title="$34 new sales." class="well span3 top-block" href="manageoperator.php">
					<span class="icon-blue"><i class="icon-user"></i></span>
					<div>Operator</div>
					<div><?= $operator; ?></div>
				</a>
			
				<a data-rel="tooltip" title="$34 new sales." class="well span3 top-block" href="managecustomer.php">
					<span class="icon-blue"><i class="icon-user"></i></span>
					<div>Customer</div>
					<div><?= $customer; ?></div>
				</a>
				
			</div>

				<div class="sortable row-fluid">
				<a data-rel="tooltip" title="6 new members." class="well span3 top-block" href="manageproductionreport.php">
					<span class="icon-red"><i class="icon-th"></i></span>
					<div>Production Report</div>
					<div><?= $production; ?></div>
				</a>

				<a data-rel="tooltip" title="4 new pro members." class="well span3 top-block" href="managemachinereport.php">
					<span class="icon-green"><i class="icon-list-alt"></i></span>
					<div>Machine Report</div>
					<div><?= $machine; ?></div>
				</a>

				<a data-rel="tooltip" title="$34 new sales." class="well span3 top-block" href="managemachinebreakdownreport.php">
					<span class="icon-blue"><i class="icon-list-alt"></i></span>
					<div>Machine Breakdown Report</div>
					<div><?= $customer; ?></div>
				</a>

				<a data-rel="tooltip" title="$34 new sales." class="well span3 top-block" href="manageoperatorreport.php">
					<span class="icon-blue"><i class="icon-user"></i></span>
					<div>Operator Report</div>
					<div><?= $operator; ?></div>
				</a>
			
			</div>
			
            
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