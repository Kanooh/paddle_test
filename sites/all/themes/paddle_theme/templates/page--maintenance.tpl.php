<?php
/**
 * @file
 * Theme implementation to display a single Drupal page.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 * least, this will always default to /.
 * - $css: An array of CSS files for the current page.
 * - $directory: The directory the theme is located in, e.g. themes/garland or
 * themes/garland/minelli.
 * - $is_front: TRUE if the current page is the front page.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Page metadata:
 * - $language: (object) The language the site is being displayed in.
 * $language->language contains its textual representation.
 * $language->dir contains the language direction. It will either be 'ltr' or
 * 'rtl'.
 * - $head_title: A modified version of the page title, for use in the TITLE
 * element.
 * - $head: Markup for the HEAD element (including meta tags, keyword tags, and
 * so on).
 * - $styles: Style tags necessary to import all CSS files for the page.
 * - $scripts: Script tags necessary to load the JavaScript files and settings
 * for the page.
 * - $body_classes: A set of CSS classes for the BODY tag. This contains flags
 * indicating the current layout (multiple columns, single column), the
 * current path, whether the user is logged in, and so on.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 * when linking to the front page. This includes the language domain or
 * prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled in
 * theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 * in theme settings.
 * - $mission: The text of the site mission, empty when display has been
 * disabled in theme settings.
 *
 * Navigation:
 * - $search_box: HTML to display the search box, empty if search has been
 * disabled.
 * - $primary_links (array): An array containing primary navigation links for
 * the site, if they have been configured.
 * - $secondary_links (array): An array containing secondary navigation links
 * for the site, if they have been configured.
 *
 * Page content (in order of occurrence in the default page.tpl.php):
 * - $left: The HTML for the left sidebar.
 * - $breadcrumb: The breadcrumb trail for the current page.
 * - $title: The page title, for use in the actual HTML content.
 * - $help: Dynamic help text, mostly for admin pages.
 * - $messages: HTML for status and error messages. Should be displayed
 * prominently.
 * - $tabs: Tabs linking to any sub-pages beneath the current page (e.g., the
 * view and edit tabs when displaying a node).
 * - $content: The main content of the current Drupal page.
 * - $right: The HTML for the right sidebar.
 * - $node: The node object, if there is an automatically-loaded node associated
 * with the page, and the node ID is the second argument in the page's path
 * (e.g. node/12345 and node/12345/revisions, but not comment/reply/12345).
 *
 * Footer/closing data:
 * - $feed_icons: A string of all feed icons for the current page.
 * - $footer_message: The footer message as defined in the admin settings.
 * - $footer : The footer region.
 * - $closure: Final closing markup from any modules that have altered the page.
 * This variable should always be output last, after all other dynamic
 * content.
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
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
  </head>
  <body class="<?php print $classes; ?>" <?php print $attributes;?>>
    <div class="page_background"></div>
    <div id="page" class="full-height">
      <div id="page-content" class="max-width" role="main">
        <div class="paddingizer">
          <div class="row">
            <div class="col-md-12">
              <?php print render($title_prefix); ?>
              <?php if ($title): ?><h1 class="title" id="page-title"><span><?php print $title; ?></span></h1><?php endif; ?>
              <?php print render($title_suffix); ?>
              <?php if ($messages): ?>
                <div id="messages">
                  <div class="section clearfix">
                    <?php print $messages; ?>
                  </div>
                </div>
                <!-- /.section, /#messages -->
              <?php endif; ?>
              <div id="content">
                <?php print $content; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
