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
<!--[if (gt IE 9)|!(IE)]><!--><html class="no-js" lang="<?php print $language->language; ?>"><!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title><?php print $head_title; ?></title>
  <?php print $head; ?>
  <?php print $styles; ?>
  <?php print $scripts; ?>
  <!-- HTML5 support for IE browsers -->
  <!--[if lte IE 9]>
  <script src="<?php print base_path() . path_to_theme() . '/javascript/ie/placeholder-polyfill.js'; ?>"></script>
  <![endif]-->
  <!--[if lt IE 9]>
  <script src="<?php print base_path() . path_to_theme() . '/javascript/ie/checked-polyfill.js'; ?>"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/respond.js/1.2.0/respond.min.js"></script>
  <![endif]-->
</head>
<body class="<?php print $classes; ?>" <?php print $attributes;?>>
  <?php print $page_top; ?>
  <?php print $page; ?>
  <?php print $page_bottom; ?>
</body>
</html>
