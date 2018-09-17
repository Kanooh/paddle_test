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
  <?php print $paddle_custom_javascript; ?>
</head>
<body class="<?php print $classes; ?>" <?php print $attributes;?>>
  <?php print $page_top; ?>
  <?php print $page; ?>
  <?php print $page_bottom; ?>
</body>
</html>
