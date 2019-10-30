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

  $item;
  $brand;
  $color;
  $identifier;
  $description;
  $place;

  if($_SERVER['REQUEST_METHOD'] == 'POST')
  {
    try
    {
      $conn = dbconn();
      $conn->beginTransaction();
        $stmt = $conn->prepare("INSERT INTO item (ITEMNAME, ITEMBRAND, ITEMCOLOR, IDENTIFIER, ITEMDESCRIPTION) VALUES (:name, :brand, :color, :identifier, :description)");
        $stmt->bindParam(":item", $_POST['name']);
        $stmt->bindParam(":brand", $_POST['brand']);
        $stmt->bindParam(":color", $_POST['color']);
        $stmt->bindParam(":identifier", $_POST['identifier']);
        $stmt->bindParam(":description", $_POST['description']);

        $stmt->execute();

        $last_item_id = $conn->lastInsertId();

        //$userid = NULL;
        $adminid = NULL;

        //$_lfuser = $this->get_lfuser(); //  user object associated with found item
        $_lfadmin = $this->get_lfadmin(); //admin object associated with found item

        //if($_lfuser->isUser())
          //$userid = $_lfuser->get_lfuserid();;

        if($_lfadmin->isAdmin())
          $adminid = $_lfadmin->get_lfadmin();

        //if(!isset($userid) && !isset($adminid)) //  NO user or admin associated to item
          //return false;

        $stmt = $conn->prepare("INSERT INTO founditem (ITEMID, ADMINID, PLACEFOUND) VALUES (:itemid, :adminid, :placefound)");
        $stmt->bindParam(":itemid", $last_item_id);
        $stmt->bindParam(":adminid", $adminid);
        $stmt->bindParam(":placefound", $_POST['place']);
        $stmt->execute();
      $conn->commit();
    }
    catch(PDOException $e)
    {
      echo $e->getMessage();
    }
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" >
    <title>ElAf - Record Found Item</title>
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
                <a class="nav-link active" href="recordfound.php"> Record found item </a>
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
      <div class="col-md-4">
      </div>
      <div class="col-md-4">
        <fieldset class="the-fieldset" style="background-color:lightgrey;">
          <legend class="the-legend" style="background-color:white;">Add an item</legend>
          <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <div class="form-group">
              <label for="item">*Item Lost</label>
              <input type="text" class="form-control" name="item" id="item" <?php if(isset($item)){ echo "value='$item'"; } ?> >
            </div>
            <div class="form-group">
              <label for="brand">Brand</label>
              <input type="text" class="form-control" name="brand" id="brand" <?php if(isset($brand)){ echo "value='$brand'"; } ?> >
            </div>
            <div class="form-group">
              <label for="item">*Item color</label>
              <input type="text" class="form-control" name="color" id="color" <?php if(isset($color)){ echo "value='$color'"; } ?> >
            </div>
            <div class="form-group">
              <label for="item">Identifier</label>
              <input type="text" class="form-control" name="identifier" id="identifier" <?php if(isset($identifier)){ echo "value='$identifier'"; } ?> >
            </div>
            <div class="form-group">
              <label for="item">*Description</label>
              <textarea id="descrption" name="description" class="form-control">
                <?php if(isset($description)){ echo "value='$description'"; } ?>
              </textarea>
            </div>
            <div class="form-group">
              <label for="item">Place found</label>
              <input type="text" class="form-control" name="place" id="place" <?php if(isset($place)){ echo "value='$place'"; } ?> >
            </div>
            <input type="submit" id="login" name="login" class="btn btn-success" value="Submit" />
        </fieldset>
      </div>
      <div class="col-md-4">
      </div>
    </div>
    </div>
  </body>
</html>
