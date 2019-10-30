<?php
  require "objects/session_life.php";
  require "objects/db-connect.php";
  require "objects/lfadmin.php";
  require "objects/item.php";
  require "objects/founditem.php";

  session_start();
  session_life();

  if(!isset($_SESSION['lfadmin']))
  {
    header('Location:adminlogin.php?location=' . urlencode(basename(__FILE__)));
    exit();
  }

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" >
    <title>ElAf - Lost and Found Box</title>
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
                <a class="nav-link active" href="elafbox.php"> Lost and Found Box </a>
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
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-4">
        </div>
        <div class="col-md-4">
          <form method="POST" class='form-horizontal' action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" style="padding-bottom:10px;">
            <div class='row form-group'>
              <div class="col-8">
                <input type='text' class="form-control" name='search' placeholder="Search">
              </div>
              <div class="col-4">
                <input type='submit' class="form-control" value='Search'>
              </div>
            </div>
          </form>

          <fieldset class="the-fieldset">
            <legend class="the-legend" style="background-color:white;"> Lost and Found Box </legend>
            <?php
              try
              {
                $conn = dbconn();
                $stmt;
                if(isset($_POST['search']))
                {
                  echo "<b>Search results:</b><br/>";
                  $search = $_POST['search'];
                  $stmt = $conn->prepare("SELECT * FROM item, founditem WHERE item.ITEMID=founditem.ITEMID AND ITEMNAME LIKE :_search ORDER BY item.ITEMTIME DESC LIMIT 50");
                  $stmt->execute(array(":_search" => '%'.$search.'%'));
                }
                else
                {
                  $stmt = $conn->prepare("SELECT * FROM item, founditem WHERE item.ITEMID=founditem.ITEMID ORDER BY item.ITEMTIME DESC LIMIT 50");
                  $stmt->execute();
                }
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
                    $color = $row['ITEMCOLOR'];
                    $brand = $row['ITEMBRAND'];
                    $description = $row['ITEMDESCRIPTION'];
                    $time = $row['ITEMTIME'];
                    $foundat = $row['PLACEFOUND'];
                    echo "<div>
                            <p>
                              $name <br/>
                              <small style='color:lightgrey;'>
                                Color: $color | Brand: $brand <br/>
                                Description: $description <br/>
                                Time: $time | Found at: $foundat <br/>
                              </small>
                            </p>
                          </div>";
                  }
                }
              }
              catch (PDOException $e)
              {
                echo $e->getMessage();
              }

            ?><!--
            <div>
              <p>
                Socks <br/>
                <small style='color:lightgrey;'>
                  Color: Red | Brand: Nike <br/>
                  Description: blblblblblblblblblblb <br/>
                  Time: 12:31 | Found at: Library <br/>
                </small>
              </p>
            </div>-->
        </div>
        <div class="col-md-4">
        </div>
      </div>
    </div>
  </body>
</html>
