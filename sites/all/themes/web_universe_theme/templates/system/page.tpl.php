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
<?php if (!empty($vo_global_header)): ?>
  <div id="vlaanderen-header">
    <!-- insert your global header here -->
    <?php print $vo_global_header; ?>
    <!-- end global header-->
  </div>
<?php endif; ?>
<!-- header here -->
<?php if (!empty($header)): ?>
    <?php print $header; ?>
<?php endif; ?>
<!-- end header -->
<!-- Start page content -->
<div class="page">
  <div class="layout layout--wide">
    <div class="skiplink">
      <a href="#main"><?php print t('Skip to main content'); ?></a>
    </div>
  </div>

  <div id="main" itemprop="mainContentOfPage" role="main" tabindex="-1"
       class="main-content">
    <div class="region">
      <div class="layout layout--wide">
        <?php print render($title_prefix); ?>
        <?php if ($title && isset($show_title) && $show_title): ?>
          <h1 class="h1" id="page-title">
            <?php print $title; ?>
          </h1>
        <?php endif; ?>
        <?php print render($title_suffix); ?>
        <?php if ($messages): ?>
          <div id="messages">
            <div class="section clearfix">
              <?php print $messages; ?>
            </div>
          </div>
          <!-- /.section, /#messages -->
        <?php endif; ?>
        <?php if ($page['featured']): ?>
          <div id="featured">
            <div
              class="section clearfix"><?php print render($page['featured']); ?></div>
          </div> <!-- /.section, /#featured -->
        <?php endif; ?>
        <?php if ($page['highlighted']): ?>
          <div
            id="highlighted"><?php print render($page['highlighted']); ?></div>
        <?php endif; ?>
        <a id="main-content"></a>
        <?php print render($page['help']); ?>
        <?php if (!empty($action_links)): ?>
          <ul class="action-links"><?php print render($action_links); ?></ul>
        <?php endif; ?>
        <?php if ($page['content']): ?><?php print render($page['content']); ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<!-- End page content -->
<?php if ($page['footer'] || (!empty($page['disclaimer']) && $show_disclaimer_menu)): ?>
  <footer class="content-footer">
    <div class="content-footer__wrapper">
      <div class="layout layout--wide">
        <?php if ($page['footer']): ?>
          <?php print render($page['footer']); ?>
        <?php endif; ?>
      </div>
    </div>
  </footer>
<?php endif; ?>
<?php if (!empty($vo_global_footer)): ?>
  <div id="vlaanderen-footer">
    <!-- insert your global footer code here -->
    <?php print $vo_global_footer; ?>
    <!-- End global footer-->
  </div>
<?php endif; ?>
