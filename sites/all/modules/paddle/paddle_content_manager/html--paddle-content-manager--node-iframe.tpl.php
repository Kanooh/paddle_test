<?php

/**
 * @file
 * HTML template for html__paddle_content_manager__node_iframe theme
 * implementation.
 */

?>

<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="<?php print $language->language; ?>"> <![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8" lang="<?php print $language->language; ?>"> <![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9" lang="<?php print $language->language; ?>"> <![endif]-->
<!--[if gt IE 8]><html class="ie9" lang="<?php print $language->language; ?>"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><html class="no-js" lang="<?php print $language->language; ?>"><![endif]-->
<head>
  <meta charset="utf-8">
  <meta name="viewport"  content=" width=device-width,initial-scale=1">
  <!-- Add base tag to HTML head, this will force all links within the iframe to open in the parent frame. -->
  <base target="_parent" />
  <?php print $head; ?>
  <?php print $styles; ?>
  <?php print $scripts; ?>
  <!-- HTML5 support for IE browsers version <IE9  -->
  <!--[if lt IE 9]>
    <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
  <![endif]-->
</head>
<body class="node-iframe <?php print $classes; ?>" <?php print $attributes;?>>
  <?php print $page; ?>
</body>
</html>
