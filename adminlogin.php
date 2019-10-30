<?php
	require "objects/session_life.php";
	require "objects/db-connect.php";
	require "objects/lfadmin.php";

	session_start();
	session_life();

  $redirect;
  $msg;
  $adminid = '';
  $password = '';

  if(isset($_GET['location']))
  {
	// login page was called by a page wanting to verify user
    $redirect = htmlspecialchars($_GET['location']); // store calling page
		if(isset($_SESSION['lfadmin']))
		{
			header('Location: ' . $redirect);
			exit();
		}
  }
  elseif(isset($_POST['location']))
  {
     /* login to request page */
	   $request_page = $_POST['location'];
	   if(isset($_POST['adminid']) && isset($_POST['pass']) && isset($_POST['login']))
	   {
  	    $lfadmin = new LFAdmin;
				$lfadmin->get_admin_from_db(htmlspecialchars($_POST['adminid']), htmlspecialchars($_POST['pass']));

		    if($lfadmin->isAdmin())
		    {
			       /*
				         valid admin credentials

								 	no session created for admin's of type POST
			       */
						 if($lfadmin->get_type() != "POST")
						 {
						 		$_SESSION['lfadmin'] = $lfadmin;
								header('Location:' . $request_page);
							 	exit();
						 }
		    }
		    else
		    {
			       /*
                invalid admin credentials
			       */
             header('Location:adminlogin.php?location=' . $request_page . "&err=true");
		    }
	   }
  }
  else
  {
	   if(isset($_SESSION['lfadmin']))
	   {
		     header('Location: adminacc.php');
		     exit();
	   }
	   elseif(isset($_POST['adminid']) && isset($_POST['pass']) && isset($_POST['login']))
	   {
			 	 $lfadmin = new LFAdmin;
				 $lfadmin->get_admin_from_db(htmlspecialchars($_POST['adminid']), htmlspecialchars($_POST['pass']));

		     if($lfadmin->isAdmin())
		     {
			        /*
				          valid admin credentials

									no session created for admin's of type POST
			        */
							if($lfadmin->get_type() != "POST")
							{
			        	$_SESSION['lfadmin'] = $lfadmin;
			        	header('Location: adminacc.php');
			        	exit();
							}
		     }
		     else
		     {
			        /*
				          invalid user credentials
			        */
			        header('Location:adminlogin.php?location=' . $request_page . "&err=true");
							// no exit cause $msg needs to display the error
		     }
	   }
  }
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" >
		<title> ELaF - Admin Login </title>
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/userlogin.js"></script>
		<script src="js/createaccount.js"></script>
		<script src="js/sweetalert.min.js"></script>
	</head>

	<body>
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
  			<a class="navbar-brand" href="index.php">ElAf</a>
  			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    			<span class="navbar-toggler-icon"></span>
  			</button>
  			<div class="collapse navbar-collapse" id="navbarNavDropdown">
			    <ul class="navbar-nav">
						<li class="nav-item">
							<a class="nav-link" href="lostsomething.php">Lost something?</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="foundsomething.php">Found something?</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="adminacc.php">Lost & Found Box</a>
						</li>
    			</ul>
  			</div>
		</nav>
		<div class="container-fluid">
		<div class="row" style="padding-top:10px;">
			<div class="col-md-4">

			</div>
			<div class="col-md-4">
				<h2>Login</h2>
				<form name="loginfrm" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
					<?php
						if(isset($redirect))
							echo "<input type='hidden' id='location' name='location' value='$redirect' />";
				  ?>
					<div class="form-group">
						<label for="studentnumber">Staff number</label>
						<input type="text" name="adminid" id="adminid" class="form-control"/>
					</div>
					<div class="form-group">
						<label for="password">Password</label>
						<input type="password" name="pass" id="pass" class="form-control" />
					</div>
					<input type="submit" id="login" name="login" class="btn btn-success" value="Login" />
					<a class="btn btn-link" href>
							Forgot password
					</a>
				</form>
				<br/>
				<div style="text-align:center;">
					<button class="btn" style="border-color:blue;">
						<a href="signup.php"> Create new admin </a>
					</button>
				</div>
			</div>
			<div class="col-md-4">

			</div>
		</div>
		</div>

		<script>

			<?php
					if(isset($_GET['err']))
					{
						$msg = "Invalid Staff number and/or password";
					}
					/*
						no session created for admin's of type POST
					*/
					if(isset($lfadmin))
					{
						if($lfadmin->get_type() == "POST")
						{
							$admininfo = "";
							$admininfo .= "Admin ID: " . $lfadmin->get_adminid() . "\\n";
							$admininfo .= "Admin name: " . $lfadmin->get_name() . "\\n";
							$admininfo .= "Phone: " . $lfadmin->get_phone() . "\\n";
							$admininfo .= "Admin type: " . $lfadmin->get_type() . "\\n";

							echo "swal('Admin', '$admininfo');";
						}
					}
					elseif(isset($msg))
					{
						echo "swal({text: '" . $msg . "',});";
					}
			?>
		</script>
	</body>
</html>
