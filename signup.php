<?php
	require "objects/session_life.php";
	require "objects/db-connect.php";
	require "objects/lfuser.php";

	session_start();
	session_life();

	if(isset($_SESSION['lfuser']))
	{
		header('Location:useracc.php');
		exit();
	}

	$id1;
	$id2;
	$username;
	$email;
	$phone;
	$password;
	$confirm;

	$alert;

	if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		/*
		*	check password
		*/
		if(isset($_POST['pass'], $_POST['confirm'])){
			$password = trim($_POST['pass']);
			$confirm = trim($_POST['confirm']);

			if(strlen($password) > 5){
				if($password != $confirm){
					$alert = "Please confirm your password";
				}
			}
			else{
				$alert = "Password must consist of 6 or more characters";
			}
		}
		else{
			$alert = "Please provide valid password";
		}
		/*
		*	check id 1 and 2
		*/
		if(isset($_POST['id1']) || isset($_POST['id2'])){
			$id1 = trim($_POST['id1']);
			$id2 = trim($_POST['id2']);

			if(strlen($id1) < 3 && strlen($id2) < 3){
				$alert = "Please provide atleast 1 valid form of identification";
			}
			else {
				if(strlen($id1) < 3){
					$id1 = !empty($id1) ? "$id1" : NULL;
				}
				if(strlen($id2) < 3){
					$id2 = !empty($id2) ? "$id2" : NULL;
				}
			}
		}
		else{
			$alert = "Please provide atleast 1 valid form of identification";
		}
		/*
		*	check name
		*/
		if(isset($_POST['username'])){
			$username = trim($_POST['username']);

			if(!preg_match("/^[a-zA-Z ]*$/", $username) || strlen($username) < 3)
			{
				$alert = "Please provide your name and surname";
			}
		}
		else {
			$alert = "Please provide your name and surname";
		}
		/*
		*	check email
		*/
		if(isset($_POST['email'])){
			$email = trim($_POST['email']);

			if(!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email)){
				$alert = "Please provide a valid email address";
			}
		}
		else{
			$alert = "Please provide a valid email address";
		}
		/*
		* check phone
		*/
		if(isset($_POST['cell'])){
			$reg_pattern =  "/\\d{10}/";
			$phone = trim($_POST['cell']);

			if(!preg_match("/\\d{10}/", $phone))
			{
				$alert = "Please provide a valid phone number";
			}
		}
		else{
			$alert = "Please provide a valid phone number";
		}
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" >
		<title>ElaF - Signup</title>
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
		<link rel="stylesheet" href="css/styleform.css">
		<link rel="stylesheet" href="css/notificationicon.css">
		<script src="js/post_redirect.js" ></script>
		<script src="js/sweetalert.min.js"></script>
	</head>

	<body>
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
				<a class="navbar-brand" href="index.php"> ELaF </a>
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

		<script>
			<?php
				if(isset($alert))
				{
					echo "swal({
									text: '$alert',
									icon: 'error',
									buttons: {
										OK: true,
									},
								});";
				}
				else
				{
					$primaryid = isset($id1) ? "$id1" : "$id2";
					$secondaryid = (isset($id2) && !isset($id1)) ? NULL : "$id2";

					try
					{
						$conn = dbconn();
						$stmt = $conn->prepare("SELECT * FROM verifieduser, lfuser WHERE verifieduser.LFUSERID=lfuser.LFUSERID AND lfuser.USERID=:_userid");
						$stmt->execute(array(":_userid" => $primaryid));
						$row = $stmt->fetch();

						if($row != null) // verified user exists with these account details
						{
							echo "swal({
											text: 'This user already exists, maybe you forgot your password. If not go to security (Building 1) with the appropriate documentation to prove these details belong to you',
											icon: 'error',
											buttons: {
												OK: true,
											},
										});";
						}
						else
						{
							/*
							*	if a student number is provided it will always be
							* be used as the primary means of identification. If it is not
							* then id2 is used as the primary id. USERID cannot be NULL
							* but SECONDARYID can.
							*/
							$stmt = $conn->prepare("INSERT INTO lfuser (USERID, SECONDARYID, USERNAME, phone, email, userpassword) VALUES (:_userid, :_secondaryid, :_username, :_phone, :_email, :_password)");
							$stmt->bindParam(":_userid", $primaryid);
							$stmt->bindParam(":_secondaryid", $secondaryid);
							$stmt->bindParam(":_username", $username);
							$stmt->bindParam(":_phone", $phone);
							$stmt->bindParam(":_email", $email);
							$stmt->bindParam(":_password", $password);
							$stmt->execute();

							/* get the user that was just created from database */
							$lfuser = new LFUser;
							$userid = $conn->lastInsertId();
							$lfuser->get_userinfo_from_db($userid);
							if($lfuser->isUser())
							{
								$_SESSION['lfuser'] = $lfuser;
								header("Location: useracc.php");
								exit();
							}
							else
							{
								echo "swal({
												text: 'Oops, Something went wrong.',
												icon: 'error',
												buttons: {
													OK: true,
												},
											});";
							}
						}
					}
					catch(PDOException $e)
					{
						$message = $e->getMessage();
						echo "swal({
										text: 'Oops, something went wrong',
										icon: 'error',
										buttons: {
											OK: true,
										},
									});";
					}
				}
			?>
		</script>

		<div class="container-fluid">
		<div class="row">
			<div class="col-md-4">
			</div>
			<div class="col-md-4">
				<fieldset class="the-fieldset">
					<legend class="the-legend"><b><i class="material-icons">person_add</i></b></legend>
					<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
						<div class="form-group">
							<label for="id1">NWU Sudent/staff number</label>
							<input type="text" class="form-control" name="id1" id="id1" <?php if(isset($id1)){ echo "value='$id1'"; } ?> >
						</div>
						<div class="form-group">
							<label for="id2">ID/Passport number</label>
							<input type="text" class="form-control" name="id2" id="id2" <?php if(isset($id2)){ echo "value='$id2'"; } ?> >
						</div>
						<div class="form-group">
							<label for="username">Name/initials and Surname</label>
							<input type="text" class="form-control" name="username" id="username" <?php if(isset($username)){ echo "value='$username'"; } ?> >
						</div>
						<div class="form-group">
							<label for="email">Email</label>
							<input type="email" class="form-control" name="email" aria-describedby="emailHelp" id="email" <?php if(isset($email)){ echo "value='$email'"; } ?> >
							<small id="emailHelp" class="form-text text-muted">We'll never share your email with any third party.</small>
						</div>
						<div class="form-group">
							<label for="cell">Cellphone number</label>
							<input type="number" class="form-control" name="cell" aria-describedby="cellHelp" id="cell" <?php if(isset($phone)){ echo "value='$phone'"; } ?> >
							<small id="cellHelp" class="form-text text-muted">We'll never share your number with any third party.</small>
						</div>
						<div class="form-group">
							<label for="pass">Password</label>
							<input type="password" class="form-control" name="pass" id="pass">
						</div>
						<div class="form-group">
							<label for="confirm">Confirm password</label>
							<input type="password" class="form-control" name="confirm" id="confirm">
						</div>
						<button type="submit" class="btn btn-primary">Submit</button>
					</form>
				</fieldset>
			</div>
			<div class="class-md-4">
			</div>
		</div>
		</div>
	</body>
</html>
