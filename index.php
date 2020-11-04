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

    $points[$str] = Array('weight' => 1*$row['weight'], 'date' => $date, 'loss' => 1*$row['loss']);

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
        <h1>Losing weight is fucking hard</h1>
        <p>
          I have always been overweight. I've never been BMI-approved "normal" weight. And I've spent the majority of the last decade obese.
        </p>
        <p>
          I was the fat kid in school. There was another, but he also joined in bullying me for my weight.
        </p>
        <p>
          I tried many diets that my mum put me on. Slim-fast. Cucumber and cream cheese on Rivitas. My dad even promised me an Xbox if I managed to slim down. But none of them worked.
        </p>
        <p>
          Then at 16, I had my heart broken for the first time. And I did a crash diet.
        </p>
        <p>
          Three servings of salt and vinegar on toast a day. Constantly running back and forth between the living room and the kitchen. And a lot of sit-ups.
        </p>
        <p>
          I lost 5 stone in less than three months. That's about 32kg.
        </p>
        <p>
          I felt great. I gained confidence. And mananged to exit my teenage years on a high.
        </p>
        <p>
          But crash diets don't last. That was 2008 and by 2014 I had gone from 85kg up to a whopping 136kg. Beating any weight I'd been as a teenager.
        </p>
        <p>
          The game was up and it was time to start getting moving.
        </p>
      </div>
    </header>

    <div class="graph">
      <svg focusable="false" viewBox="0 0 <?php echo sizeof($points)/$ratio; ?> <?php echo ($max - $min);?>" xmlns="http://www.w3.org/2000/svg">
        <g>
          <path fill="none" stroke="#EE6C4D" stroke-width=".2" d="

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
          " stroke-linecap="round" stroke-linejoin="round" />

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
        for($i=140; $i >= min($min, 85); $i-=5) {
      ?>
      <div class="marker" style="top: <?=(1 - (($i - $min)/($max - $min))) * 100;?>%;"><?=$i; ?>kg</div>
      <?php
        }
      ?>
    </div>

    <div class="graph__net">
      <dl>
        <dt>Loss</dt>
        <dd class="js_loss">0</dd>
        <dt>Gain</dt>
        <dd class="js_gain">0</dd>
        <dt>Net Loss</dt>
        <dd class="js_net">0</dd>
      </dl>
    </div>

    <main>
      <section>
        <div class="inner">
          <h2>2015</h2>
          <p>
            One hundred and thirty-six fucking kilos. My doctor was right - being vegetarian definitely doesn't mean that you eat well.
          </p>
          <h3>Step 1 - Go vegan</h3>
          <p>
            For years I'd told myself (and others) I'd never do something so extreme. But with a lot of weight to lose, animals to care about, and the environment to save, my priveleged moral compass kicked in and knew it was worth a try.
          </p>
          <p>
            It's amazing the first massive weight losses you experience just by a change of diet. Eating well for a few months lost me nearly 20kg.
          </p>
          <h3>Step 2 - Get off my ass</h3>
          <p>
            I joined a gym. Again. But this time forked over hundreds of &pound;s for personal training sessions to keep me going to the gym. And I started playing squash.
          </p>
          <p>
            Aside: I've always hated exercise. Somewhere between not being good at it, having incredibly poor hand-eye coordination, and not getting the endorphin high I hear everyone rave about. I finish exercising and I feel like shit. And it's <em>very rare</em> you feel like you've achieved something.
          </p>
          <p>
            The PT sessions didn't last. But the squash did and I continued to try and fight my way out of the bottom box of the local league for the next 5 years.
          </p>
        </div>
      </section>
      <section>
        <div class="inner">
          <h2>2016</h2>
          <p>
            
          </p>
        </div>
      </section>
      <?php for($i=2017; $i<=2020; $i++) { ?>
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
