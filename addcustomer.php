<?php
	session_start();
	include("inc/connection.php");
	require("inc/funcstuffs.php");

	$pageUrl = $sitepath."managecustomer.php";
	$pageChange = $sitepath."addcustomer.php";
	$savePage = $sitepath."savecustomer.php";
	$managePage = " Customer";
	$tabname = "customer";

	if($_SESSION["sadmin_username"]!="")
	{
	$operation="Add";
	$id=$_GET["id"];
	$today=date("d/m/Y");
	if($id!="" and is_numeric($id))
	{
		$customer="";
		$email="";
		$mobile="";
		$product="";
		$quantity="";
		$order_date=$today;
		$dispatch_date=$today;
		$status=1;
		$qry="select * from $tabname where id=".$id;
		$result=mysqli_query($db,$qry) or die("cannot select users ".mysqli_error());
		if($row=mysqli_fetch_array($result))
		{
			$operation="Edit";
			$customer=$row["customer"];
			$email=$row["email"];
			$mobile=$row["mobile"];
			$product=$row["product"];
			$quantity=$row["quantity"];

			$date = new DateTime($row["order_date"]);
			$order_date = $date->format('d/m/Y');
			
			$date = new DateTime($row["dispatch_date"]);
			$dispatch_date= $date->format('d/m/Y');

			$status=$row["status"];
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
		$customer="";
		$email="";
		$mobile="";
		$product="";
		$quantity="";
		$order_date=$today;
		$dispatch_date=$today;
		$status=1;
	}

	$checked = "";
	if($status == 1){
		$checked = "checked";
	}

		$title = $operation." Customer";
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
						<a href="<?= $pageUrl; ?>">Manage Customer</a> <span class="divider">/</span>
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
							  <label class="control-label">Customer Name *</label>
							  <div class="controls">
								<input type="text" class="input-xlarge" id="customer" name="customer" value="<?= $customer; ?>" required>
							  </div>
							</div>
							<?php /* 
							<div class="control-group">
							  <label class="control-label">Email *</label>
							  <div class="controls">
								<input type="email" class="input-xlarge" id="email" name="email" value="<?= $email; ?>" required>
							  </div>
							</div>
							<div class="control-group">
							  <label class="control-label">Mobile No. *</label>
							  <div class="controls">
								<input type="number" class="input-xlarge" id="mobile" name="mobile"  min="10" max="10"  value="<?= $mobile; ?>" required>
							  </div>
							</div>
							<div class="control-group">
							  <label class="control-label">Product *</label>
							  <div class="controls">
								<input type="text" class="input-xlarge" id="product" name="product" value="<?= $product; ?>" required>
							  </div>
							</div>
							<div class="control-group">
							  <label class="control-label">Quantity *</label>
							  <div class="controls">
								<input type="number" class="input-xlarge" id="quantity" name="quantity" value="<?= $quantity; ?>" required>
							  </div>
							</div>
							
                            <div class="control-group">
							  <label class="control-label">Order Date *</label>
							  <div class="controls">
								<input type="text" class="input-xlarge datepicker" id="order_date" name="order_date" value="<?= $order_date; ?>"  required>
							  </div>
							</div>
                             <div class="control-group">
							  <label class="control-label">Dispatch Date *</label>
							  <div class="controls">
								<input type="text" class="input-xlarge datepicker" id="dispatch_date" name="dispatch_date" value="<?= $dispatch_date; ?>" required>
							  </div>
							</div>
                            */ ?>
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
}
else
{
	print "<META http-equiv='refresh' content=0;URL=".$sitepath.">";
	exit;
}
?>
