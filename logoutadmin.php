<?php
	require "objects/session_life.php";

	session_start();
	session_life();

	unset($_SESSION['lfadmin']);

	header("Location:index.php");
	exit();
?>
