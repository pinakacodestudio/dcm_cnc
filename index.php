<?php
session_start();
require("inc/connection.php");
require("inc/funcstuffs.php");

if (isset($_SESSION["sadmin_username"]) && $_SESSION["sadmin_username"]  != "") {
	print "<META http-equiv='refresh' content=0;URL=" . $sitepath . "home.php>";
	exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php include("inc/headscripts.php"); ?>
		
</head>

<body>
		<div class="container-fluid">
		<div class="row-fluid">
		
			<div class="row-fluid">
				<div class="span12 center login-header">
					<h2>Welcome to DCM Industries</h2>
				</div><!--/span-->
			</div><!--/row-->
			
			<div class="row-fluid">
				<div class="well span5 center login-box">
					<div class="alert alert-info">
						Please login with your Username and Password.
					</div>
					<?php alertBox(); ?>
					<form class="form-horizontal" action="validatelogin.php" method="post">
						<fieldset>
							<div class="input-prepend" title="Username" data-rel="tooltip">
								<span class="add-on"><i class="icon-user"></i></span><input autofocus class="input-large span10" name="username" id="username" type="text" value="" required/>
							</div>
							<div class="clearfix"></div>

							<div class="input-prepend" title="Password" data-rel="tooltip">
								<span class="add-on"><i class="icon-lock"></i></span><input class="input-large span10" name="password" id="password" type="password" value="" required/>
							</div>
							<div class="clearfix"></div>

							<div class="input-prepend">
							<a href="#">Forgot Password</a>
                            </div>
							<div class="clearfix"></div>

							<p class="center span5">
							<button type="submit" class="btn btn-primary">Login</button>
							</p>
						</fieldset>
					</form>
				</div><!--/span-->
			</div><!--/row-->
				</div><!--/fluid-row-->
		
	</div><!--/.fluid-container-->

<?php include("inc/footerscripts.php"); ?>
		
</body>
</html>
