<?php
  require "objects/session_life.php";
  require "objects/db-connect.php";
  require "objects/lfadmin.php";

  session_start();
  session_life();

  if(!isset($_SESSION['lfadmin']))
  {
    header('Location:adminlogin.php?location=' . urlencode(basename(__FILE__)));
    exit();
  }
?>
