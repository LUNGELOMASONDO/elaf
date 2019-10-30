<?php
  /*
    capture lost item details
	*/
  require "db-connect.php";
  require "session_life.php";
  require "item.php";
  require "lostitem.php";
  require "lfuser.php";

  session_start();
  session_life();

  $lfuser;

  if(!isset($_SESSION['lfuser']))
  {
    header('Location:../lostsomething.php');
    exit();
  }
  else
  {
    $lfuser = $_SESSION['lfuser'];
  }

  if(!isset($_POST))
  {
	   header("Location:../lostsomething.php");
	   exit();
  }

  if(isset($_GET['cancel']))
  {
    if(isset($_SESSION['lfuser'], $_SESSION['lost']))
    {
      unset($_SESSION['lost']);
    }
    header("Location:../lostsomething.php");
    exit();
  }

  if(isset($_POST['timelost']))
  {
     /*
      **  regex and valid time checks here
     */
     $timelost = trim($_POST['timelost']) . ":00";
     $regexp = "/[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) (2[0-3]|[01][0-9]):[0-5][0-9]:00/"; // datetime regex pattern

	   if(preg_match($regexp, $timelost)) // check that it is correct format for mysql datetime type
	   {
       $timelost = date("Y-m-d H:i:s", strtotime($timelost));  //  convert from string to datetime type
       $lost_time_limit = date("Y-m-d", mktime(0, 0, 0, date("m")-6, date("d"), date("Y"))); // 6 months less than current date
       $current_datetime = date("Y-m-d H:i:s", mktime(date("H"), date("i"), 0, date("m"), date("d"), date("Y")));

       if($timelost > $lost_time_limit && $timelost <= $current_datetime){
          $_SESSION['lost']['timelost'] = $timelost;
          header('Location:../lostsomething.php?lost=itemname');
          exit();
       }
       else{
         header('Location:../lostsomething.php?lost=timelost&err=true');
         exit();
       }
	   }
     else
     {
        header('Location:../lostsomething.php?lost=timelost&err=true');
        exit();
     }
  }

  if(isset($_POST['itemname']))
  {
    $itemname = trim($_POST['itemname']);
    if(!is_numeric($itemname) && strlen($itemname) > 1)
    {
        $_SESSION['lost']['name'] = $itemname;
        // go to next input
        header('Location:../lostsomething.php?lost=brand');
        exit();
    }
	  else
	  {
		    header('Location:../lostsomething.php?lost=itemname&err=true');
		    exit();
	  }
  }

  if(isset($_POST['itembrand'])) // nullable
  {
     $brand = trim($_POST['itembrand']);
     if(strlen($brand) == 0)
     {
       // go to next page, no input from user
       header('Location:../lostsomething.php?lost=itemcolor');
       exit();
     }

	   if(!is_numeric($brand) && strlen($brand) > 1) // input, check if correct
	   {
       $_SESSION['lost']['brand'] = $brand;
       // go to next page
       header('Location:../lostsomething.php?lost=itemcolor');
       exit();
	   }
     else
     {
       header('Location:../lostsomething.php?lost=brand&err=true');
       exit();
     }
  }

  if(isset($_POST['itemcolor']))
  {
    $color = trim($_POST['itemcolor']);
    if(!is_numeric($itemcolor) && strlen($color) > 2)
    {
        $_SESSION['lost']['color'] = $color;
        // go to next input
        header('Location:../lostsomething.php?lost=identifier');
        exit();
    }
    else
    {
        header('Location:../lostsomething.php?lost=itemcolor&err=true');
        exit();
    }
  }

  if(isset($_POST['identifier']))
  {
    $identifier = trim($_POST['identifier']);
    if(strlen($identifier) != 0)
    {
      $_SESSION['lost']['identifier'] = $identifier;
    }
    else
    {
      $identifier = NULL;
    }
    //  go to next page
    header('Location:../lostsomething.php?lost=description');
    exit();
  }

  if(isset($_POST['description']))
  {
    $description = trim($_POST['description']);
    if(!is_numeric($description) && strlen($description) > 2)
    {
        $_SESSION['lost']['description'] = $description;
        // go to next input
        header('Location:../lostsomething.php?lost=placelost');
        exit();
    }
    else
    {
        header('Location:../lostsomething.php?lost=description&err=true');
        exit();
    }
  }

  if(isset($_POST['placelost']))
  {
    $placelost = trim($_POST['placelost']);
    if(!is_numeric($placelost) && strlen($placelost) > 2)
    {
        $_SESSION['lost']['place'] = $placelost;
        //  go to next page
        header('Location:../lostsomething.php?lost=review');
        exit();
    }
	  else
	  {
	     header('Location:../lostsomething.php?lost=place&err=true');
	     exit();
	  }
  }

  if(isset($_POST['submitlostitem']))
  {
    if(!isset($_SESSION['lost']['timelost']))
    {
      header('Location:../lostsomething.php?lost=timelost&err=true');
      exit();
    }
    elseif(!isset($_SESSION['lost']['name']))
    {
	     header('Location:../lostsomething.php?lost=itemname&err=true');
	     exit();
    }
    elseif(!isset($_SESSION['lost']['description']))
    {
	     header('Location:../lostsomething.php?lost=description&err=true');
	     exit();
    }
    elseif(!isset($_SESSION['lost']['place']))
    {
	     header('Location:../lostsomething.php?lost=placelost&err=true');
	     exit();
    }
    elseif(!isset($_SESSION['lost']['color']))
    {
       header('Location:../lostsomething.php?lost=itemcolor&err=true');
       exit();
    }
    else
    {
      $time = $_SESSION['lost']['timelost'];
      $item = $_SESSION['lost']['name'];
      $brand = NULL;
      if(isset($_SESSION['lost']['brand']))
        $brand = $_SESSION['lost']['brand'];
      $color = $_SESSION['lost']['color'];
      $identifier = NULL;
      if(isset($_SESSION['lost']['identifier']))
        $identifier = $_SESSION['lost']['identifier'];
      $description = $_SESSION['lost']['description'];
      $place = $_SESSION['lost']['place'];
      // make new lost item object to write to db
      $lostitem = new LostItem;
      $lostitem->set_name($item);
      $lostitem->set_timelost($time);
      $lostitem->set_brand($brand);
      $lostitem->set_color($color);
      $lostitem->set_identifier($identifier);
      $lostitem->set_description($description);
      $lostitem->set_placelost($place);
      $lostitem->set_lfuser($lfuser);
      $lostitem->set_isItem(true);
      // connect to database
      if($lostitem->store_in_db())
      {
        $_SESSION['lost']['complete'] = true;
        header("Location:../useracc.php");
        exit();
      }
      else
      {
        $_SESSION['lost']['complete'] = false;
        header("Location:../lostsomething.php");
        exit();
      }
    }
  }

?>
