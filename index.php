<?php

  include("config.php");

  function get_csv($filename) {
    $rows   = array_map('str_getcsv', file("assets/data/{$filename}.csv"));
    $header = array_shift($rows);
    $csv    = array();
    foreach($rows as $row) {
      $assoc = array_combine($header, $row);
      $csv[] = $assoc;
    }

    return $csv;
  }

  function stime($str) {
    $str = str_replace("/", "-", $str);
    return strtotime( $str );
  }

  $data = get_csv("data");

  $start_date = Array('doy' => 60, 'year' => 2015);
  $end_stamp = stime($data[sizeof($data)-1]['date']);
  $end_date = Array('doy' => 1*date("z", $end_stamp), 'year' => 1*date("Y", $end_stamp ));

  //die("{$start_date}<br>{$end_date}");

  $points = Array();

  $i = $start_date;
  while($i['doy'] <= $end_date['doy'] || $i['year'] < $end_date['year']) {

    $points[$i['doy'] . "-" . $i['year']] = Array();

    $i['doy']++;
    if($i['doy'] > 365) {
      $i['year']++;
      $i['doy'] = 0;
    }

  }
  //print_r($i);

  $min = 200;
  $max = 0;

  foreach($data as $row) {
    $date = stime($row['date']);

    $str = date("z", $date) . "-" . date("Y", $date);

    if(!isset($points[$str])) {
      print_r($points);
      die("Where is the date? {$row['date']} {$str}");
    }

    $points[$str] = Array('weight' => 1*$row['weight'], 'date' => $date);

    if($row['weight'] > $max)
      $max = 1*$row['weight'];

    if($row['weight'] < $min)
      $min = 1*$row['weight'];
  }

  $ratio = 10;

  ob_start();

?><!doctype html>
<html lang="en">
  <head>
    <?php include("inc/_head.php"); ?>
  </head>
  <body>

    <header class="section-header">
      <div class="inner">
        <h1>Stuart's weight loss</h1>
        <blockquote>
          <p>“Time isn’t the main thing. It’s the only thing.” – Miles Davis</p>
        </blockquote>
      </div>
    </header>

    <div class="graph">
      <svg focusable="false" viewBox="0 0 <?php echo sizeof($points)/$ratio; ?> <?php echo ($max - $min);?>" xmlns="http://www.w3.org/2000/svg">
        <g>
          <path fill="none" stroke="#000000" stroke-width=".2" d="

            <?php
              $first = true;
              $count = 0;
              foreach($points as $date => $point) {
                if(isset($point['weight'])) {
                  $x = ($count/$ratio);
                  $y = abs($point['weight'] - $max);
                  echo (!$first ? " L" : " M") . $x . "," . $y;
                  $points[$date]['coords'] = Array($x / (sizeof($points)/$ratio), $y / ($max - $min));
                  $first = false;
                }
                $count++;

              }
            ?>
          " />

        </g>
      </svg>

      <script type="text/javascript">
        const dataSet = JSON.parse('<?=json_encode($points); ?>');
      </script>



      <div class="graph__circle">

      </div>
    </div>
    <div class="graph__yaxis">
      <?php
        for($i=$max; $i >= $min; $i-=10) {
      ?>
      <div class="marker" style="top: <?=(1 - (($i - $min)/($max - $min))) * 100;?>%;"><?=$i; ?>kg</div>
      <?php
        }
      ?>
    </div>

    <main>
      <?php for($i=2015; $i<=2020; $i++) { ?>
      <section>
        <div class="inner">
          <h2><?=$i;?></h2>
          <p>
            Lorem ipsum dolor cake amet
          </p>
        </div>
      </section>
      <?php } ?>


    </main>

    <footer class="footer">
      <div class="inner">
        <p>
          Footer here
        </p>
      </div>
    </footer>

    <!-- Begin end scripts //-->
    <script crossorigin="anonymous" src="https://polyfill.io/v3/polyfill.min.js?features=Array.prototype.forEach%2CNodeList.prototype.forEach%2CElement.prototype.append%2CArray.prototype.includes"></script>
    <?php if(!$global_production) { ?>
    <script type="text/javascript" src="<?php echo $url_resources;?>js/app-min.js?v=<?=$global_version;?>"></script>
    <?php
      } else {
    ?>
    <script type="text/javascript">
    <?php echo file_get_contents("assets/js/app-min.js"); ?>
    </script>
    <?php
      }
    ?>
  </body>
</html><?php
  $html = ob_get_clean();

  if(isset($_GET['export'])) {
    $dir = "_builds/v{$global_version}";
    if(!is_dir($dir))
      mkdir($dir);
    $handle = fopen("{$dir}/index.html", "w+");
    fwrite($handle, $html);
    fclose($handle);

    $handle = fopen("{$dir}/index-min.html", "w+");
    $html = preg_replace("/[\s\t\r\n]+/", " ", $html);
    fwrite($handle, $html);
    fclose($handle);
  }

  echo $html;
?>
