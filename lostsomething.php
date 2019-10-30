<?php
  require "objects/session_life.php";
  require "objects/db-connect.php";
  require "objects/lfuser.php";

  session_start();
  session_life();

  if(!isset($_SESSION['lfuser'])) // checks for a valid session variable
  {
    header('Location:userlogin.php?location=' . urlencode(basename(__FILE__)));
    exit();
  }

  $lfuser = $_SESSION['lfuser'];
  $userid = $lfuser->get_userid();
  $username = $lfuser->get_name();
  $phone = $lfuser->get_phone();
  $email = $lfuser->get_email();
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" >
    <title> ELaF - Lost something? </title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
	  <script src="js/jquery.min.js"></script>
	  <script src="js/popper.min.js"></script>
	  <script src="js/bootstrap.min.js"></script>
    <script src="js/sweetalert.min.js"></script>
    <link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" >
    <script src="js/bootstrap-datetimepicker.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	  <script src="js/post_redirect.js" ></script>
    <link rel="stylesheet" href="css/styleform.css">
    <style>
      @media only screen and (min-width: 600px) {
        .form_datetime{
          width:50%;
        }
      }
    </style>
    <script>
      function get_itemname(){
        swal({
          title: 'Your item',
          text: 'What did you lose?',
          content: {
            element: "input",
            attributes: {
              id: "itemname",
              placeholder: "e.g phone",
              <?php
                if(isset($_SESSION['lost']['name']))
                  echo "value:" . json_encode($_SESSION['lost']['name']) . ",";
              ?>
            }
          },
          buttons: {
            cancel: "Exit",
            catch: {
              text: "Next",
              value: "next",
            },
          },
        })
        .then(value => {
          switch (value) {
            case "next":
				        redirectPost("objects/get_lostitem.php", {itemname: $('#itemname').val()});
                break;
            default:
				        swal({
					             text: "You can continue from where you left off by clicking 'Find'",
					             icon: "info",
				        });
                break;
          }
        });
      }// end of function
      function get_itembrand(){
        swal({
          title: 'Brand',
          text: 'What brand was the item? (not compulsory)',
          content: {
            element: "input",
            attributes: {
              id: "brand",
              placeholder: "e.g Nike",
              <?php
                if(isset($_SESSION['lost']['brand']))
                  echo "value:" . json_encode($_SESSION['lost']['brand']) . ",";
              ?>
            }
          },
          buttons: {
            cancel: "Exit",
            Back: true,
            catch: {
              text: "Next",
              value: "next",
            },
          },
        })
        .then(value => {
          switch (value) {
            case "Back":
                window.location.href = "lostsomething.php?lost=itemname";
                break;

            case "next":
                redirectPost("objects/get_lostitem.php", {itembrand: $('#brand').val()});
                break;

            default:
                swal({
                       text: "You can continue from where you left off by clicking 'Find'",
                       icon: "info",
                });
                break;
          }
        });
      } // end of function
      function get_itemcolor(){
        swal({
          title: 'Color',
          text: 'What color is your item:',
          content: {
            element: "input",
            attributes: {
              id: "color",
              placeholder: "e.g black, red, yellow and white, grey",
              <?php
                if(isset($_SESSION['lost']['color']))
                  echo "value:" . json_encode($_SESSION['lost']['color']) . ",";
              ?>
            }
          },
          buttons: {
            cancel: "Exit",
            Back: true,
            catch: {
              text: "Next",
              value: "next",
            },
          },
        })
        .then(value => {
          switch (value) {
            case "Back":
                window.location.href = "lostsomething.php?lost=itembrand";
                break;

            case "next":
                redirectPost("objects/get_lostitem.php", {itemcolor: $('#color').val()});
                break;

            default:
                swal({
                       text: "You can continue from where you left off by clicking 'Find'",
                       icon: "info",
                });
                break;
          }
        });
      } // end of function
      function get_identifier(){
        swal({
          title: "Identifier",
          text: 'Provide your item\'s unique identifier (if you know it). e.g. a student number for a student card, the account number on a bank card or a tear or engraving etc.',
          content: {
            element: "input",
            attributes: {
              id: "identifier",
              placeholder: "e.g student number: 39231168 / engraving on the back written...",
              <?php
                if(isset($_SESSION['lost']['identifier']))
                  echo "value:" . json_encode($_SESSION['lost']['identifier']) . ",";
              ?>
            }
          },
          buttons: {
            cancel: "Exit",
            Back: true,
            catch: {
              text: "Next",
              value: "next",
            },
          },
        })
        .then(value => {
          switch (value) {
            case "Back":
                window.location.href = "lostsomething.php?lost=itemcolor";
                break;

            case "next":
                redirectPost("objects/get_lostitem.php", {identifier: $('#identifier').val()});
                break;

            default:
                swal({
                       text: "You can continue from where you left off by clicking 'Find'",
                       icon: "info",
                });
                break;
          }
        });
      } // end of function
      function get_description(){
            swal({
              title: 'Describe',
              text: 'Please provide further description that might aid in matching your item:',
              content: {
                element: "textarea",
                attributes: {
                  id: "desc",
                  placeholder: "e.g. cracked screen protector",
                  <?php
                    if(isset($_SESSION['lost']['description']))
                      echo "value:" . json_encode($_SESSION['lost']['description']) . ",";
                  ?>
                },
              },
              buttons: {
                cancel: "Exit",
                Back: true,
                catch: {
                  text: "Next",
                  value: "next",
                },
              },
            })
            .then(value => {
              switch (value) {

                   case "Back":
                       window.location.href = "lostsomething.php?lost=identifier";
                       break;

                   case "next":
                       redirectPost("objects/get_lostitem.php", {description: $('#desc').val()});
                       break;

                   default:
                       swal({
                              text: "You can continue from where you left off by clicking 'Find'",
                              icon: "info",
                       });
              }
            });
      }// end of function
      function get_placelost(){
        swal({
          title: "Place lost",
          text: 'Where did you lose your item',
          content: {
            element: "input",
            attributes: {
              id: "placelost",
              placeholder: "e.g. Library Basement or B25 Gym",
              <?php
                if(isset($_SESSION['lost']['place']))
                  echo "value:" . json_encode($_SESSION['lost']['place']) . ",";
              ?>
            }
          },
          buttons: {
            cancel: "Exit",
            Back: true,
            catch: {
              text: "Next",
              value: "next",
            },
          },
        })
        .then(value => {
          switch (value) {
            case "Back":
                window.location.href = "lostsomething.php?lost=description";
                break;

            case "next":
                redirectPost("objects/get_lostitem.php", {placelost: $('#placelost').val()});
                break;

            default:
                swal({
                       text: "You can continue from where you left off by clicking 'Find'",
                       icon: "info",
                });
                break;
          }
        });
      } //  end of function
      function review(){
        let timelost = <?php if(isset($_SESSION['lost']['timelost'])){ echo json_encode($_SESSION['lost']['timelost']);}else{ echo "''";} ?>;
        let item = <?php if(isset($_SESSION['lost']['name'])){ echo json_encode($_SESSION['lost']['name']);}else{ echo "''";} ?>;
        let brand = <?php if(isset($_SESSION['lost']['brand'])){ echo json_encode($_SESSION['lost']['brand']);}else{ echo "''";} ?>;
        let color = <?php if(isset($_SESSION['lost']['color'])){ echo json_encode($_SESSION['lost']['color']);}else{ echo "''";} ?>;
        let identifier = <?php if(isset($_SESSION['lost']['identifier'])){ echo json_encode($_SESSION['lost']['identifier']);}else{ echo "''";} ?>;
        let description = "\n" + <?php if(isset($_SESSION['lost']['description'])){ echo json_encode($_SESSION['lost']['description']);}else{ echo "''";} ?>;
        let placelost = <?php if(isset($_SESSION['lost']['place'])){ echo json_encode($_SESSION['lost']['place']);}else{ echo "''";} ?>;

        let review_info = "";
        review_info += "Time lost:\t\t" + timelost + "\n";
        review_info += "Item:\t\t" + item + "\n";
        review_info += "Brand:\t\t" + brand + "\n";
        review_info += "Color:\t\t" + color + "\n";
        review_info += "Identifier:\t\t" + identifier + "\n";
        review_info += "Description:\t\t" + description + "\n";
        review_info += "Place lost:\t\t" + placelost + "\n";

        swal({
          title: "Review",
          text: review_info,
          buttons: {
            cancel: "Exit",
            Back: true,
            catch: {
              text: "Find item",
              value: "find",
            },
          },
        })
        .then(value => {
          switch (value) {
            case "Back":
                window.location.href = "lostsomething.php?lost=placelost";
                break;

            case "find":
                redirectPost("objects/get_lostitem.php", {submitlostitem: "true"});
                break;

            default:
                swal({
                       text: "You can continue from where you left off by clicking 'Find'",
                       icon: "info",
                });
                break;
          }
        });
      } //  end of function
      function cancel(){
        swal({
          title: "Cancel",
          text: "Are you sure you want to cancel finding your lost item?",

          buttons: {
            cancel: "No",
            Yes: true,
          },
        })
        .then(value => {
          switch (value) {
            case "Yes":
                window.location.href = "objects/get_lostitem.php?cancel=true";
                break;

            default:
                swal({
                       text: "You can continue from where you left off by clicking 'Find'",
                       icon: "info",
                });
                break;
          }
        });
      }

      $(document).on('click', '#findbtn', function(){
        redirectPost("objects/get_lostitem.php", {timelost: $('#datetimepicker').val()});
      });

      $(document).on('click', '#cancelbtn', function(){
        cancel();
      });

      $(document).on('click', '#reviewbtn', function(){
        window.location.replace("lostsomething.php?lost=review");
      });

      $(document).on('change', '#datetimepicker', function(){
        redirectPost("objects/get_lostitem.php", {timelost: $('#datetimepicker').val()});
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
                <a class="nav-link active" href="lostsomething.php">Lost something?</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="foundsomething.php">Found something?</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="adminquickaccess.php">Lost & Found Box</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="logoutuser.php">Logout</a>
              </li>
          </ul>
        </div>
        <a class="nav-link float-right" href="useracc.php">
          <b>
            <i class="material-icons" style="color:grey;">
              person
            </i> <?php echo $username; ?>
          </b>
        </a>
    </nav>
    <!-- content -->
    <div class="container-fluid" style="padding-top:15px;">
      <div class="row">
        <div class="col-md-2">

        </div>
        <!-- content column -->
        <div class="col-md-8">
          <article>
            <fieldset class="the-fieldset" style="background-color:lightgrey;">
              <legend class="the-legend" style="background-color:white;"><b>Lets help you find your missing item</b></legend>
              <ol>
                <li> Provide an accurate description of the item you lost. </li>
                <li> Do not try to "window shop" for free items. Your lost item entries get logged and security notified of suspicious activity. </li>
                <li> If no matches are found for your item in our lost and found box. Fret not, we will notify you via email when a possible match occurs.  </li>
                <li> Click the Find button below to start :) </li>
              </ol>
            </fieldset>
          </article>

          <?php
            $on_page_load_losttime;

            if(isset($_SESSION['lost']["timelost"]))
              $on_page_load_losttime = date("Y-m-d H:i", strtotime("" . $_SESSION['lost']['timelost']));
            else
              $on_page_load_losttime = date('Y-m-d H:i');
          ?>

          <div class="date form_datetime" >
            <label for="datetimepicker" style="font-size:13px;"> Before you start select when you lost your item below. The current date and time will be recorded unless you state otherwise: </label>
            <input size="16" type="text" value="<?php echo $on_page_load_losttime; ?>" readonly class="form_datetime form-control" id="datetimepicker" name="datetimepicker" />
            <span class="add-on"><i class="icon-th"></i></span>
          </div>

          <script type="text/javascript">
            $(".form_datetime").datetimepicker({format: 'yyyy-mm-dd hh:ii'});
            $('#datetimepicker').datetimepicker('setStartDate', '<?php echo date("Y-m-d", mktime(0, 0, 0, date("m")-6, date("d"), date("Y"))); ?>');
            $('#datetimepicker').datetimepicker('setEndDate', '<?php echo date("Y-m-d H:i", mktime(date("H"), date("i"), 0, date("m"), date("d"), date("Y"))); ?>');
          </script>
          <div style="padding-top:5px;padding-bottom:8px;">
            <button class='btn btn-primary' id='findbtn' > Find </button>
            <?php
              if(isset($_SESSION['lost'])){	// if the user was interrupted while entering lost item details
                echo "<button class='btn btn-primary' id='reviewbtn' > Review </button>" .
                     "<button class='btn btn-warning' id='cancelbtn' > Cancel </button>";
              }
            ?>
          </div>
          <!--
          <div class="jumbotron border border-primary" id="your-lost-items">
            <?php

            ?>
          </div>-->
        </div>
        <!-- end of content column -->
        <div class="col-md-2">

        </div>
      </div>
      <!-- end of page content -->
      <script>
        <?php
          if(isset($_GET['lost']))
          {
            $pos = $_GET['lost'];

            if($pos == "itemname"){
              echo "get_itemname();";
            }
            elseif($pos == "itembrand"){
              echo "get_itembrand();";
            }
            elseif($pos == "itemcolor"){
              echo "get_itemcolor();";
            }
            elseif($pos == "identifier"){
              echo "get_identifier();";
            }
            elseif($pos == "description"){
              echo "get_description();";
            }
            elseif($pos == "placelost"){
              echo "get_placelost();";
            }
            elseif($pos == "brand"){
              echo "get_itembrand();";
            }
            elseif($pos == "review"){
              echo "review();";
            }
          }
        ?>
      </script>
      <?php
        /*
          invalid input
        */
        if(isset($_GET['err'], $_GET['lost']))
        {
          $pos = $_GET['lost'];

          if($pos == "itemname"){
            echo "<script>
                    swal({
                      text: 'Please provide a valid name for your lost item',
                      icon: 'error',
                    })
                    .then((value) => {
          			         get_itemname();
                     });
                  </script>";
          }
          elseif($pos == "itembrand"){
            echo "<script>
                    swal({
                      text: 'Please provide a valid brand for your lost item',
                      icon: 'error',
                    })
                    .then((value) => {
          			         get_itembrand();
                    });
                  </script>";
          }
          elseif($pos == "itemcolor"){
            echo "<script>
                    swal({
                      text: 'Please provide a valid color for your lost item',
                      icon: 'error',
                    })
                    .then((value) => {
          			         get_itemcolor();
                     });
                  </script>";
          }
          elseif($pos == "identifier"){
            echo "<script>
                    swal({
                      text: 'Please provide a valid description for your lost item',
                      icon: 'error',
                    })
                    .then((value) => {
            			       get_identifier();
                    });
                  </script>";
          }
          elseif($pos == "description"){
            echo "<script>
                    swal({
                      text: 'Please provide a valid description for your lost item',
                      icon: 'error',
                    })
                    .then((value) => {
            			       get_description();
                    });
                  </script>";
          }
          elseif($pos == "timelost"){
            echo "<script>
                    swal({
                      text: 'Please provide when you lost your item',
                      icon: 'error',
                    })
                    .then((value) => {
                      $('#datetimepicker').focus();
                    });
                  </script>";
          }
          elseif($pos == "place"){
            echo "<script>
                    swal({
                      text: 'Please specify where you lost your item',
                      icon: 'error',
                    })
                    .then((value) => {
                      get_placelost();
                    });
                  </script>";
          }
        }

        if(isset($_SESSION['lost']['complete']) )
        {
          if(!$_SESSION['lost']['complete'])
          {
            echo "<script>
                    swal({
                      text: 'Something went wrong, please check your inputs',
                      icon: 'error',
                    })
                    .then((value) => {
                      review();
                    });
                    </script>";
            }
          unset($_SESSION['lost']['complete']);
        }
      ?>
    </div>
  </body>
</html>
