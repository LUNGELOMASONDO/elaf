<?php
  require "objects/session_life.php";

  session_start();
  session_life();

  if(isset($_SESSION['lfadmin.php']))
  {
    header("Location:adminacc.php");
    exit();
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" >
    <title> ELaF - Lost and found at your fingertips </title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/styleform.css">
    <style>
      @media only screen and (min-width: 600px) {
        .row{
          padding-top:50px;
        }
      }
    </style>
  </head>

  <body>
    <!-- nav bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="index.php">ELaF</a>
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
                <a class="nav-link active" href="adminacc.php">Lost & Found Box</a>
              </li>
          </ul>
        </div>
    </nav>
    <!-- end nav -->
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-4">
        </div>
        <div class="col-md-4">
          <fieldset class="the-fieldset" style="background-color:lightgrey;">
            <legend class="the-legend" style="background-color:white;"><b>Quick Access</b></legend>
            <button class="btn" style="width:100%;padding-bottom:10px;"><a href="elafbox.php">Lost and Found Box</a></button>
            <div style="height:10px;"></div>
            <button class="btn" style="width:100%;padding-bottom:10px;"><a href="recordfound.php">Record a found item</a></button>
            <div style="height:10px;"></div>
            <button class="btn" style="width:100%;padding-bottom:10px;"><a href="claims.php">Process a claim</a></button>
            <div style="height:10px;"></div>
            <button class="btn" style="width:100%;padding-bottom:10px;"><a href="addadmin.php">Add an admin</a></button>
          </fieldset>
        </div>
        <div class="col-md-4">
        </div>
      </div>
    </div>
  </body>
</html>
