<?php
function get_timeago( $ptime )
{
  $estimate_time = time() - $ptime;

  if( $estimate_time < 1 )
    return 'less than 1 second ago';

  $condition = array(
                      12 * 30 * 24 * 60 * 60  =>  'year',
                      30 * 24 * 60 * 60       =>  'month',
                      7 * 24 * 60 * 60				=>	'week',
                      24 * 60 * 60            =>  'day',
                      60 * 60                 =>  'hour',
                      60                      =>  'minute',
                      1                       =>  'second'
                );

  foreach( $condition as $secs => $str )
  {
    $d = $estimate_time / $secs;
    if( $d >= 1 )
    {
      $r = round( $d );
      return 'about ' . $r . ' ' . $str . ( $r > 1 ? 's' : '' ) . ' ago';
    }
  }
}

  class Exam extends Test
  {
    function __construct()
    {

    }

    function rep(){
      $r = new Exam;
      $r->des();
      return;
      echo "beep";
    }
  }

  abstract class Test{
    private $name;

    function __construct(){
      $this->name = "Desmond";
    }

    function __destruct(){

    }

    protected function des(){
      /*
      $v = new Test;
      $v->name = "bby";
      return $v;
      */
      if(isset($this->name))
        echo "here";
      else
        echo "absent";
    }
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" >
    <script src="js/sweetalert.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script>
      $(document).on('click', 'button', function(){
          alert(event.target.id);
      });
    </script>
  </head>
  <body>
    <?php
      // echo date("Y-m-d", mktime(0, 0, 0, date("m")-6, date("d"), date("Y")));
      //echo date("Y-m-d H:i", mktime(date("H"), date("i"), 0, date("m"), date("d"), date("Y")));
      /*
      $test = new Test;
      $test->des();
      if(isset($test))
        echo $test->name;
      else
        echo "does not exist";
      */

      /*
      $val['found']['desc'] = "apples";
      $val['lost']['desc'] = "hello";
      $val['lost']['time'] = "midnight";
      unset($val['lost']);
      print_r($val);
      */

      /*
      $d = new Exam;
      $des = $d->rep();
      print_r($des);
      */

      /*
      if(date("Y-m-d", mktime(0, 0, 0, date("m")-6, date("d"), date("Y"))) < date("Y-m-d H:i:s"))
      {
        echo "this";
      }
      */

      //echo date("Y-m-d H:i:s", mktime(date("H"), date("i"), 0, date("m"), date("d"), date("Y")));

      //$time = "2019-08-25 22:30:00";
      //echo get_timeago(strtotime($time));

      $t;
      if(isset($_POST['test']))
      {
        $t = $_POST['test'];
        $reg_pattern = "/\\d{10}/";
        if(preg_match($reg_pattern, $t))
        {
          echo "valid";
        }
        else
        {
          echo "invalid";
        }
      }
    ?>
    <script>

    </script>
  </body>
</html>
