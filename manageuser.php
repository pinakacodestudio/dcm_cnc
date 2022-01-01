<?php

session_start();

require("inc/connection.php");

require("inc/funcstuffs.php");



$pageUrl = $sitepath . "manageuser.php";

$pageChange = $sitepath . "adduser.php";

$managePage = "Manage User";

$tabname = "company";

$title = "Manage Company";

if ($_SESSION["sadmin_username"] != "") {

	$rndstring = base64_encode(rand(11111111, 99999999) . "/deleteUser");

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

						<a href="<?= $sitepath . 'home.php'; ?>">Home</a> <span class="divider">/</span>

					</li>

					<li>

						<a href="#">Manager User</a>

					</li>

				</ul>

			</div>



				<?php alertBox(); ?>



				<div class="row-fluid sortable">

				<div class="box span12">

					<div class="box-header well" data-original-title>

						<h2><i class="icon-user"></i> User</h2>

						

							<a href="<?= $pageChange; ?>" class="btn btn-warning" style="float:right">Add New Entry</a>

					</div>

					<div class="box-content">

						<form id="login" name="login" method="post" action="<?php echo $sitepath; ?>delete_process.php" >

							<input type="hidden" name="deleteKey" value="<?= $rndstring; ?>" />



						<table class="table table-striped table-bordered bootstrap-datatable datatable">

						  <thead>

							  <tr>

								  <th>FullName</th>

								   <th>Mobile No.</th>

                                    <th>User Type</th>

                                  <th>Status</th>

								  <th>Actions</th>

							  </tr>

						  </thead>   

						  <tbody>

                          <?php

																									$sql = "select * from $tabname order by id";

																									$rs = $db->query($sql) or die("cannot Select User" . $db->error);

																									while ($row = $rs->fetch_assoc()) {

																										if ($row["status"] == 1) {

																											$status = '<span class="label label-success">Active</span>';

																										} else {

																											$status = '<span class="label label-danger">Deactive</span>';

																										}
																										$usertype = "";

																										if ($row["usertype"] == 1) {

																											$usertype = 'Admin';

																										} else if ($row["usertype"] == 2) {

																											$usertype = 'Sub User';

																										}

																										?>



							<tr>

								<td><?= $row["fullname"]; ?></td>

                                <td><a href="tel:<?= $row["mobile"]; ?>"><?= $row["mobile"]; ?></a></td>

                                <td class="center"><?= $usertype; ?></td>

								<td class="center"><?= $status; ?></td>

								<td class="center">

									<a class="btn btn-info" href="adduser.php?id=<?= $row["id"]; ?>">

										<i class="icon-edit icon-white"></i>  

									</a>

									<a class="btn btn-danger" href="#" onClick="javascript:deleteRecord(<?= $row["id"]; ?>);">

										<i class="icon-trash icon-white"></i> 

									</a>

								</td>

							</tr>

                            

                            <?php 
																										} ?>



						  </tbody>

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

		<script src="js/sweetalert.min.js"></script>



	<script type="text/javascript">

function deleteRecord(val){



			document.login.delid.value = val;

				swal({

						title: "Are you sure?",

						text: "You will not be able to recover this Customer!",

						type: "warning",

						showCancelButton: true,

						confirmButtonClass: "btn-danger",

						confirmButtonText: "Yes, delete it!",

						cancelButtonText: "No, cancel plx!",

						closeOnConfirm: false,

						closeOnCancel: false

					},

					function(isConfirm) {

						if (isConfirm) {



							document.login.submit();



						} else {

							swal({

								title: "Cancelled",

								text: "Your Records are safe :)",

								type: "error",

								confirmButtonClass: "btn-danger"

							});

						}

					});

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

