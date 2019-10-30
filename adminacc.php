<?php
  require "objects/session_life.php";
  require "objects/db-connect.php";
  require "objects/lfadmin.php";
  require "objects/lfuser.php";
  require "objects/item.php";
  require "objects/lostitem.php";
  require "objects/founditem.php";
  require "objects/timeago.php";

  session_start();
  session_life();

  if(!isset($_SESSION['lfadmin']))
  {
    header('Location:adminlogin.php?location=' . urlencode(basename(__FILE__)));
    exit();
  }

  $lfadmin = $_SESSION['lfadmin'];


?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" >
    <title>ElAf - Admin</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="css/styleform.css">
    <link rel="stylesheet" href="css/notificationicon.css">
    <script src="js/post_redirect.js" ></script>
    <script src="js/sweetalert.min.js"></script>
    <script>
      $(document).on('click', '#admininfo', function(){
        <?php
          $admininfo = "";
          $admininfo .= "Admin ID: " . $lfadmin->get_adminid() . "\\n";
          $admininfo .= "Admin name: " . $lfadmin->get_name() . "\\n";
          $admininfo .= "Phone: " . $lfadmin->get_phone() . "\\n";
          $admininfo .= "Admin type: " . $lfadmin->get_type() . "\\n";

          echo "swal('Admin', '$admininfo');";
        ?>
      });
    </script>
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
                <a class="nav-link" href="elafbox.php"> Lost and Found Box </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="recordfound.php"> Record found item </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="claims.php"> Process a claim </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="logoutadmin.php"> Logout </a>
              </li>
          </ul>
        </div>
        <a href="#" id="admininfo" class="nav-link float-right"><i class="material-icons">person_outline</i></a>
    </nav>
    <!-- end nav -->
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-6">
          <fieldset class="the-fieldset" style="background-color:lightgrey;">
            <legend class="the-legend" style="background-color:white;">Recent items</legend>
            <?php
              try
              {
                $conn = dbconn();
                $stmt = $conn->prepare("SELECT * FROM item, founditem WHERE item.ITEMID=founditem.ITEMID ORDER BY item.ITEMTIME DESC LIMIT 8");
                $stmt->execute();
                $rows = $stmt->fetchAll();

                if($rows == null)
                {
                  echo "None available";
                }
                else
                {
                  foreach($rows as $row)
                  {
                    $name = $row['ITEMNAME'];
                    $timestamp = get_timeago(strtotime($row['ITEMTIME']));
                    echo "<div class='row lostitems'>
                            <div class='col-6 lostitem' >" . $name . "</div> <div class='col-6'>" . $timestamp . "</div>
                          </div>";
                  }
                }
              }
              catch (PDOException $e)
              {
                echo $e->getMessage();
              }
            ?>
          </fieldset>
        </div>
        <div class="col-md-6">
          <fieldset class="the-fieldset" style="background-color:lightgrey;">
            <legend class="the-legend" style="background-color:white;">Recent claims</legend>
              <?php
                try
                {
                  $conn = dbconn();
                  $stmt = $conn->prepare("SELECT * FROM claim_item, founditem, item WHERE claim_item.FOUNDITEMID=founditem.FOUNDITEMID AND item.ITEMID=founditem.ITEMID ORDER BY claim_item.CLAIMTIME DESC LIMIT 8");
                  $stmt->execute();
                  $rows = $stmt->fetchAll();

                  if($rows == null)
                  {
                    echo "None available";
                  }
                  else
                  {
                    foreach($rows as $row)
                    {
                      $name = $row['ITEMNAME'];
                      $timestamp = get_timeago(strtotime($row['CLAIMTIME']));
                      echo "<div class='row lostitems'>
                              <div class='col-6 lostitem' >" . $name . "</div> <div class='col-6'>" . $timestamp . "</div>
                            </div>";
                    }
                  }
                }
                catch (PDOException $e)
                {
                  echo $e->getMessage();
                }

              ?>
          </fieldset>
        </div>
      </div>
    </div>
  </body>
</html>
