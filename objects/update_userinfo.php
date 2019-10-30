<?php
  require "db-connect.php";
  require "session_life.php";
  require "lfuser.php";

  session_start();
  session_life();

  $lfuser;

  $column;
  $newvalue;

  if(!isset($_SESSION['lfuser']))
  {
    header('Location:../useracc.php');
    exit();
  }
  else
  {
    $lfuser = $_SESSION['lfuser'];
  }

  if(isset($_POST['id']))
  {
    $column = "USERID";
    $id = trim($_POST['id']);
    if(strlen($id) > 4)
    {
      $newvalue = $id;
    }
    else
    {
      header("Location:../useracc.php?error=true");
      exit();
    }
  }
  elseif(isset($_POST['id2']))
  {
    $column = "SECONDARYID";
    $id2 = trim($_POST['id2']);

    if(strlen($id2) == 0)
    {
      $newvalue = !empty($newvalue) ? "$newvalue" : NULL; // make variable null instead of empty string
    }
    elseif(strlen($id2) > 4)
    {
      $newvalue = $id2;
    }
    else
    {
      header("Location:../useracc.php?error=true");
      exit();
    }
  }
  elseif(isset($_POST['username']))
  {
    $column = "USERNAME";
    $name = trim($_POST['username']);

    if(strlen($name) > 3)
    {
      $newvalue = $name;
    }
    else
    {
      header("Location:../useracc.php?error=true");
      exit();
    }
  }
  elseif(isset($_POST['phonenumber']))
  {
    $column = "phone";
    $phone = trim($_POST['phonenumber']);

    if(strlen($phone) > 8)
    {
      $newvalue = $phone;
    }
    else
    {
      header("Location:../useracc.php?error=true");
      exit();
    }
  }
  elseif(isset($_POST['useremail']))
  {
    $column = "email";
    $email = trim($_POST['useremail']);

    if(strlen($email) > 4)
    {
      $newvalue = $email;
    }
    else
    {
      header("Location:../useracc.php?error=true");
      exit();
    }
  }

  try
  {
    $conn = dbconn();
    $stmt= $conn->prepare("UPDATE lfuser SET " . $column . "=? WHERE LFUSERID=?");
    $stmt->execute([$newvalue, $lfuser->get_lfuserid()]);

    if ($stmt->rowCount())
    {
      // some rows changed
      // do something
      $lfuser->get_userinfo_from_db($lfuser->get_lfuserid());
      $_SESSION['lfuser'] = $lfuser; // put updated info into session var
      $_SESSION['update'] = true; // variable used to alert user of successful update
      header("Location:../useracc.php");
      exit();
    }
    else
    {
      header("Location:../useracc.php?error=true2" . $newvalue);
      exit();
    }
  }
  catch (PDOException $e)
  {
    header("Location:../useracc.php?error=true1" . $e->getMessage());
    exit();
  }

?>
