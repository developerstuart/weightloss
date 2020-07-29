<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<title><?php echo $meta_title; ?></title>
<meta name="description" content="<?php echo $meta_description; ?>">

<link rel="canonical" href="<?php echo $canonical_url; ?>">
<?php if(!$global_production) { ?>
<meta name="robots" content="noindex,nofollow">
<?php } ?>

<meta http-equiv="x-dns-prefetch-control" content="on">
<link rel="dns-prefetch" href="//fonts.googleapis.com">

<meta property="og:title" content="<?php echo $social_title; ?>">
<meta property="og:description" content="<?php echo $social_description; ?>">
<meta property="og:image" content="<?php echo $url; ?>assets/images/<?=$social_image;?>?v=<?=$global_version; ?>">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:url" content="<?php echo $url; ?>">
<meta property="og:type" content="website">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="<?php echo $url; ?>">
<meta name="twitter:title" content="<?php echo $social_twitter_title; ?>">
<meta name="twitter:description" content="<?php echo $social_twitter_description; ?>">
<meta name="twitter:image" content="<?php echo $url; ?>assets/images/<?=$social_image;?>?v=<?=$global_version; ?>">

<?php if(1 || !$global_production) { ?>
<link rel="stylesheet" href="<?php echo $url_resources;?>css/general.css?v=<?=$global_version;?>" />
<?php
      } else {
  $css = file_get_contents("assets/css/general.css");
  $css = str_replace("url(../", "url({$url_resources}", $css);
?>
<style type="text/css">
<?php echo $css; ?>
</style>
<?php } ?>

<?php
  /* Brand specific */
?>
<link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;1,400&family=Sanchez&display=swap" rel="stylesheet">
<link href="https://www.zenbusiness.com/images/favicon.png" rel="shortcut icon">
<meta content="#000000" name="theme-color">
<link href="https://www.zenbusiness.com/manifest.json" rel="manifest">
<?php
  /* EO Brand specific */
?>
