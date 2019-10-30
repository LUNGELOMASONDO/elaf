<?php
	require "objects/db-connect.php";
	require "objects/session_life.php";
	require "objects/lfuser.php";
	require "objects/item.php";
	require "objects/lostitem.php";
	require "objects/founditem.php";
	require "objects/timeago.php";

	session_start();
	session_life();

	$lfuser;

	if(!isset($_SESSION['lfuser'])) {
		  header('Location:userlogin.php?location=' . urlencode(basename(__FILE__)));
		exit();
	}
	else {
		$lfuser = $_SESSION['lfuser'];
	}

	$lfuserid = $lfuser->get_lfuserid(); //	auto incremented id
	$userid = $lfuser->get_userid();
	$seconduserid = $lfuser->get_secondID();
	$username = $lfuser->get_name();
	$email = $lfuser->get_email();
	$phone = $lfuser->get_phone();
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" >
		<title>ElAf - Account</title>
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
		<link rel="stylesheet" href="css/styleform.css">
		<link rel="stylesheet" href="css/notificationicon.css">
		<script src="js/post_redirect.js" ></script>
		<script src="js/sweetalert.min.js"></script>
		<script>
			function updateID(){
				swal({
					title: 'Personal Info',
					text: 'Identification',
					content: {
						element: "input",
						attributes: {
							id: "id1",
							placeholder: "Student number/ ID number/ passport",
							<?php
								if(isset($userid))
									echo "value:" . json_encode($userid) . ",";
							?>
						}
					},
					buttons: {
						cancel: "Cancel",
						catch: {
							text: "Update",
							value: "update",
						},
					},
				})
				.then(value => {
					switch (value) {
						case "update":
								redirectPost("objects/update_userinfo.php", {id: $('#id1').val()});
								break;
					}
				});
			}

			function updateID2(){
				swal({
					title: 'Personal Info',
					text: 'Secondary Identification',
					content: {
						element: "input",
						attributes: {
							id: "id2",
							placeholder: "Student number/ ID number/ passport",
							<?php
								if(isset($seconduserid))
									echo "value:" . json_encode($seconduserid) . ",";
							?>
						}
					},
					buttons: {
						cancel: "Cancel",
						catch: {
							text: "Update",
							value: "update",
						},
					},
				})
				.then(value => {
					switch (value) {
						case "update":
								redirectPost("objects/update_userinfo.php", {id2: $('#id2').val()});
								break;
					}
				});
			}

			function updatename(){
				swal({
					title: 'Personal Info',
					text: 'Name & surname:',
					content: {
						element: "input",
						attributes: {
							id: "username",
							placeholder: "User name",
							<?php
								if(isset($username))
									echo "value:" . json_encode($username) . ",";
							?>
						}
					},
					buttons: {
						cancel: "Cancel",
						catch: {
							text: "Update",
							value: "update",
						},
					},
				})
				.then(value => {
					switch (value) {
						case "update":
								redirectPost("objects/update_userinfo.php", {username: $('#username').val()});
								break;
					}
				});
			}

			function updatephone(){
				swal({
					title: 'Personal Info',
					text: 'Cellphone number',
					content: {
						element: "input",
						attributes: {
							id: "phonenumber",
							placeholder: "",
							<?php
								if(isset($phone))
									echo "value:" . json_encode($phone) . ",";
							?>
						}
					},
					buttons: {
						cancel: "Cancel",
						catch: {
							text: "Update",
							value: "update",
						},
					},
				})
				.then(value => {
					switch (value) {
						case "update":
								redirectPost("objects/update_userinfo.php", {phonenumber: $('#phonenumber').val()});
								break;
					}
				});
			}

			function updatemail(){
				swal({
					title: 'Personal Info',
					text: 'Email:',
					content: {
						element: "input",
						attributes: {
							id: "email",
							placeholder: "e.g name@server.com",
							<?php
								if(isset($email))
									echo "value:" . json_encode($email) . ",";
							?>
						}
					},
					buttons: {
						cancel: "Cancel",
						catch: {
							text: "Update",
							value: "update",
						},
					},
				})
				.then(value => {
					switch (value) {
						case "update":
								redirectPost("objects/update_userinfo.php", {useremail: $('#email').val()});
								break;
					}
				});
			}

			$(document).on('click', '#editid', function(){
				updateID();
			});

			$(document).on('click', '#editid2', function(){
				updateID2();
			});

			$(document).on('click', '#editname', function(){
				updatename();
			});

			$(document).on('click', '#editphone', function(){
				updatephone();
			});

			$(document).on('click', '#editemail', function(){
				updatemail();
			});

			$(document).on('click', '.lostitem', function(event){
				let itemid = event.target.id;
				$.getJSON("objects/get_itemjson.php?itemid="+itemid, function(result){
						let review_info = "";
						review_info += "Item ID:\t\t" + result.itemID + "\n";
						review_info += "Item:\t\t" + result.itemname + "\n";
						review_info += "Brand:\t\t" + result.timebrand + "\n";
						review_info += "Color:\t\t" + result.itemcolor + "\n";
						review_info += "Identifier:\t\t" + result.identifier + "\n";
						review_info += "Description:\t\t" + result.itemdesc + "\n";
						review_info += "Place lost:\t\t" + result.placelost + "\n";
						review_info += "Time lost:\t\t" + result.timelost + "\n";

						swal("Lost Item", review_info);
				});
			});
		</script>
		<style>
			.lostitems:hover {
				background-color: white;
			}
			.lostitems {
				cursor: pointer;
			}
		</style>
	</head>

	<body>
		<!-- nav -->
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
								<a class="nav-link" href="logoutuser.php">Logout</a>
							</li>
					</ul>
				</div>
				<a href="#" class="nav-link float-right notification"><i class="material-icons">notifications_none</i><span class="badge">0</span></a>
		</nav>
		<!-- end nav -->
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-6">
					<fieldset class="the-fieldset" style="background-color:lightgrey;">
						<legend class="the-legend" style="background-color:white;"><b><i class="material-icons">account_circle</i></b></legend>
						<div class="row">
							<div class="col-md-3">
								Identification:
							</div>
							<div class="col-md-9">
								<input type='text' class='form-control' style='width:80%; float:left;' id='idtxt' disabled value=<?php if(isset($userid)){echo json_encode($userid);}else{ echo "''";} ?> />
								<button class="btn" style='width:10%;float:right;' id='editid' ><i class="material-icons">edit</i></button>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								Secondary ID:
							</div>
							<div class="col-md-9">
								<input type='text' class='form-control' style='width:80%; float:left;' id='id2txt' disabled value=<?php if(isset($seconduserid)){echo json_encode($seconduserid);}else{ echo "''";} ?> />
								<button class="btn" style='width:10%;float:right;' id='editid2' ><i class="material-icons">edit</i></button>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								Name:
							</div>
							<div class="col-md-9">
								<input type='text' class='form-control' style='width:80%; float:left;' id='nametxt' disabled value=<?php if(isset($username)){echo json_encode($username);}else{ echo "''";} ?> />
								<button class="btn" style='width:10%;float:right;' id='editname' ><i class="material-icons">edit</i></button>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								Phone:
							</div>
							<div class="col-md-9">
								<input type='text' class='form-control' style='width:80%; float:left;' id='phonetxt' disabled value=<?php if(isset($phone)){echo json_encode($phone);}else{ echo "''";} ?> />
								<button class="btn" style='width:10%;float:right;' id='editphone' ><i class="material-icons">edit</i></button>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								Email:
							</div>
							<div class="col-md-9">
								<input type='text' class='form-control' style='width:80%; float:left;' id='emailtxt' disabled value=<?php if(isset($email)){echo json_encode($email);}else{ echo "''";} ?> />
								<button class="btn" style='width:10%;float:right;' id='editemail' ><i class="material-icons">edit</i></button>
							</div>
						</div>
					</fieldset>
				</div>
				<div class="col-md-6">
					<fieldset class="the-fieldset" style="background-color:lightgrey;">
						<legend class="the-legend" style="background-color:white;"><b>Item History</b></legend>
						<div class='row'>
							<div class='col-6'><b>Item</b></div> <div class='col-6'><b>Reported</b></div>
						</div>
							<?php
								$items = $lfuser->get_associated_lostitems();
								if(isset($items))
								{
									foreach($items as $item)
									{
										$itemid = $item->get_id();
										$name = $item->get_name();
										$time_str = "" . $item->get_timestamp();
										$timestamp = get_timeago(strtotime($time_str));
										echo "<div class='row lostitems'>
														<div class='col-6 lostitem' id='$itemid' >" . $name . "</div> <div class='col-6'>" . $timestamp . "</div>
													</div>";
									}
								}
							?>
					</fieldset>
				</div>
			</div>
		</div>

		<script>
			<?php
				if(isset($_SESSION['lost']['complete']))
				{
					if($_SESSION['lost']['complete'])
					{
						echo "swal({
									text: 'Your item has been recorded',
									icon: 'success',
									buttons: {
										Back: true,
										catch: {
											text: 'OK',
											value: 'ok',
										},
									},
								})
								.then(value => {
									switch (value) {
										case 'Back':
											window.location.href = 'lostsomething.php';
											break;
										}
									});";
						unset($_SESSION['lost']);
					}
				}

				if(isset($_SESSION['update']))
				{
					echo "swal({
									text: 'Update successful',
									icon: 'success',
									buttons: {
										OK: true,
									},
								});";
					unset($_SESSION['update']);
				}

				if(isset($_GET['error']))
				{
					echo "swal({
									text: 'Update unsuccessful. Provide valid input',
									icon: 'error',
									buttons: {
										OK: true,
									},
								});";
				}
		?>
		</script>
	</body>
</html>
