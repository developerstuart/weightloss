<?php
  $headers = Array();
  $rows = Array();

  $row = 1;
if (($handle = fopen("../assets/data/all.csv", "r")) !== FALSE) {
    while (($row = fgetcsv($handle, 2000, ",")) !== FALSE) {
      if(!sizeof($headers)) {
        $headers = $row;
      } else {
        $data = Array();
        foreach($headers as $k => $v) {
          $data[$v] = $row[$k];
        }
        $rows[] = $data;
      }
    }
    fclose($handle);
}

  // Create job list!
  $job_list = Array();
  foreach($rows as $row) {
    if(!isset($job_list[$row['job_id']])) {
      $job_list[$row['job_id']] = $row['job_name'];
    }
  }

  $handle = fopen("../assets/data/jobs.json", "w+");
  fwrite($handle, json_encode($job_list));
  fclose($handle);

  // Create individual job files
  foreach($job_list as $job_id => $job_name) {
    $this_list = Array();
    foreach($rows as $row) {
      if($row['job_id'] == $job_id) {
        $this_list[$row['related_rank']] = $row;
      }
    }
    $handle = fopen("../assets/data/jobs/{$job_id}.json", "w+");
    fwrite($handle, json_encode($this_list));
    fclose($handle);
  }

?>
[/eof]
