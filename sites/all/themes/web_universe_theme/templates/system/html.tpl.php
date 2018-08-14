<?php
/**
 * @file
 * Theme implementation to display the html basics.
 */
?>
<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="<?php print $language->language; ?>"> <![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8" lang="<?php print $language->language; ?>"> <![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9" lang="<?php print $language->language; ?>"> <![endif]-->
<!--[if gt IE 8]><html class="ie9" lang="<?php print $language->language; ?>"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html class="no-js" lang="<?php print $language->language; ?>">
<!--<![endif]-->
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport"  content=" width=device-width,initial-scale=1">
  <title><?php print $head_title_array['title'] . ' - ' . $head_title_array['name']; ?></title>
  <?php print $head; ?>
  <?php print $styles; ?>
  <?php print $scripts; ?>
  <!-- HTML5 support for IE lower than IE9  -->
  <!-- Media Queries support for IE lower than IE9  -->
  <!--[if lt IE 9]>
  <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/respond.js/1.2.0/respond.min.js"></script>
  <![endif]-->
  <!-- Load the Web Universe related meta data -->
  <link rel="icon" sizes="192x192" href="https://dij151upo6vad.cloudfront.net/2.latest/icons/app-icon/icon-highres-precomposed.png">
  <link rel="apple-touch-icon" href="https://dij151upo6vad.cloudfront.net/2.latest/icons/app-icon/touch-icon-iphone-precomposed.png">
  <link rel="apple-touch-icon" sizes="76x76" href="https://dij151upo6vad.cloudfront.net/2.latest/icons/app-icon/touch-icon-ipad-precomposed.png">
  <link rel="apple-touch-icon" sizes="120x120" href="https://dij151upo6vad.cloudfront.net/2.latest/icons/app-icon/touch-icon-iphone-retina-precomposed.png">
  <link rel="apple-touch-icon" sizes="152x152" href="https://dij151upo6vad.cloudfront.net/2.latest/icons/app-icon/touch-icon-ipad-retina-precomposed.png">

  <meta name="msapplication-square70x70logo" content="https://dij151upo6vad.cloudfront.net/2.latest/icons/app-icon/tile-small.png" />
  <meta name="msapplication-square150x150logo" content="https://dij151upo6vad.cloudfront.net/2.latest/icons/app-icon/tile-medium.png" />
  <meta name="msapplication-wide310x150logo" content="https://dij151upo6vad.cloudfront.net/2.latest/icons/app-icon/tile-wide.png" />
  <meta name="msapplication-square310x310logo" content="https://dij151upo6vad.cloudfront.net/2.latest/icons/app-icon/tile-large.png" />
  <meta name="msapplication-TileColor" content="#FFE615" />
</head>
<body class="<?php print $classes; ?>" <?php print $attributes;?>>
<?php print $page_top; ?>
<?php print $page; ?>
<?php print $page_bottom; ?>
</body>
</html>
