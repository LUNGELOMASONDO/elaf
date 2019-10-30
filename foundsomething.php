<?php
  require "objects/session_life.php";

  session_start();
  session_life();

  if(!isset($_SESSION['lfuser'])) // checks for a valid session variable
  {
    header('Location:userlogin.php?location=' . urlencode(basename(__FILE__)));
    exit();
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" >
    <title> Found something? </title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </head>

  <body>
    <a href="logoutuser.php"> Logout </a>
  </body>
</html>
