<?php
  $global_version = "1.0";
  $global_production = false;

  if(isset($_GET['export'])) {
    $global_production = true;
  }

  $url = $url_resources = "";

  if($global_production) {
    $url = "";
    $url_resources = "assets/";
  } else {
    $url = $url_resources = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}" . "/";
    $url_resources .= "assets/";
  }


  if(!$url_resources || $url === $url_resources) {
    $url_resources .= "assets/";
  }

  $meta_title = "Stuart's weight loss";
  $meta_description = "";

  $social_title = "Stuart's weight loss";
  $social_description = "";

  $social_twitter_title = "Stuart's weight loss";
  $social_twitter_description = "";

  $twitter_text = "Stuart's weight loss %url%";

  $social_image = "facebook.png";

  $canonical_url = $url;

  if($canonical_url == "") {
    //$canonical_url = ""
  }

?>
