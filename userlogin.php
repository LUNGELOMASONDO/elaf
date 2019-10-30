<?php
	require "objects/session_life.php";
	require "objects/db-connect.php";
	require "objects/lfuser.php";

	session_start();
	session_life();

  $redirect;
  $msg;
  $studentnumber = '';
  $password = '';

  if(isset($_GET['location']))
  {
	// login page was called by a page wanting to verify user
    $redirect = htmlspecialchars($_GET['location']); // store calling page
		if(isset($_SESSION['lfuser']))
		{
			header('Location: ' . $redirect);
			exit();
		}
  }
  elseif(isset($_POST['location']))
  {
     /* login to request page */
	   $request_page = $_POST['location'];
	   if(isset($_POST['studentnumber']) && isset($_POST['pass']) && isset($_POST['login']))
	   {
  	    $lfuser = new LFUser;
				$lfuser->get_user_from_db(htmlspecialchars($_POST['studentnumber']), htmlspecialchars($_POST['pass']));

		    if($lfuser->isUser())
		    {
			       /*
				         valid user credentials
			       */
			       $_SESSION['lfuser'] = $lfuser;
			       header('Location:' . $request_page);
			       exit();
		    }
		    else
		    {
			       /*
                invalid user credentials
			       */

             header('Location:userlogin.php?location=' . $request_page . "&err=true");
		    }
	   }
  }
  else
  {
	   if(isset($_SESSION['lfuser']))
	   {
		     header('Location: useracc.php');
		     exit();
	   }
	   elseif(isset($_POST['studentnumber']) && isset($_POST['pass']) && isset($_POST['login']))
	   {
			 	 $lfuser = new LFUser;
				 $lfuser->get_user_from_db(htmlspecialchars($_POST['studentnumber']), htmlspecialchars($_POST['pass']));

		     if($lfuser->isUser())
		     {
			        /*
				          valid user credentials
			        */
			        $_SESSION['lfuser'] = $lfuser;
			        header('Location: useracc.php');
			        exit();
		     }
		     else
		     {
			        /*
				          invalid user credentials
			        */
			        header('Location:userlogin.php?location=' . $request_page . "&err=true");
		     }
	   }
  }
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" >
		<title> ELaF - Login </title>
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
							<a class="nav-link active" href="lostsomething.php">Lost something?</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="foundsomething.php">Found something?</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="adminquickaccess.php">Lost & Found Box</a>
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
						<label for="studentnumber">Student/ID number</label>
						<input type="text" name="studentnumber" id="studentnumber" class="form-control"/>
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
						<a href="signup.php"> Create account </a>
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
						$msg = "Invalid student/ID number and password";
					}

					if(isset($msg))
					{
						echo "swal({text: '" . $msg . "',});";
					}
			?>
		</script>
	</body>
</html>
