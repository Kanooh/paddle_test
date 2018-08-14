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
<div id="page" class="full-height content-background-canvas">
  <div>
    <a href="#page-content" class="visuallyhidden focusable"><?php print t('Skip to main content'); ?></a>
    <a name="top" id="top"></a>
  </div>
  <?php if (!empty($vo_global_header) || !empty($federal_branding)): ?>
    <div role="banner" aria-label="global-header">
      <?php if (!empty($vo_global_header)): ?>
        <div class="vo-global-header">
          <?php print $vo_global_header; ?>
        </div>
      <?php endif; ?>
      <?php if (!empty($federal_branding)): ?>
        <div id="blgm_belgiumHeader">
          <div class="blgm_wrapper">
            <div class="federal-header">
              <div class="paddingizer">
                <?php if (!empty($federal_header)) : ?>
                  <?php print render($federal_header); ?>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  <?php endif; ?>
  <header>
    <div class="max-width header-background-canvas <?php print $header_search; ?>">
      <div class="mobile-menu">
        <a href="#" class="mobile-menu-trigger" aria-label="<?php print t('Navigation'); ?>">
          <i class="fa fa-align-left"></i> <span class="visuallyhidden"><?php print t('Navigation'); ?>></span>
        </a>
      </div>
      <?php if (variable_get('paddle_style_show_search_box', TRUE)): ?>
        <a href="#" class="mobile-search-btn">
          <i class="fa fa-search"></i> <span class="visuallyhidden"><?php print t('Search'); ?></span>
        </a>
      <?php endif; ?>
      <div class="paddingizer">
        <div class="row max-width">
            <?php if (!empty($header_title) || (!empty($logo) && variable_get('paddle_core_header_show_logo', FALSE)) || !empty($header_title_prefix)): ?>
            <div class="col-md-6 logo-wrapper">
              <?php if (!empty($logo) && variable_get('paddle_core_header_show_logo', FALSE)): ?>
                <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" id="logo">
                  <img src="<?php print $logo; ?>" alt="<?php print $logo_alt ?>" />
                </a>
              <?php endif; ?>
              <?php if (!empty($header_title)): ?>
                <h1 class="header-title"><?php print $header_title; ?></h1>
              <?php endif; ?>
              <?php if (!empty($header_title_prefix)): ?>
                <h2 class="header-subtitle"><?php print $header_title_prefix; ?></h2>
              <?php endif; ?>
            </div>
            <?php if (!empty($page['service_links'])): ?>
              <div class="col-md-6 region-service-links-wrapper">
                <?php print render($page['service_links']); ?>
              </div>
            <?php endif; ?>
          <?php else: ?>
            <?php if (!empty($page['service_links'])): ?>
              <div class="col-md-6 col-md-offset-6 region-service-links-wrapper">
                <?php print render($page['service_links']); ?>
              </div>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </header>
  <div class="content-wrapper">
      <div class="paddingizer">
        <div class="row hide-overflow max-width">
            <div class="col-md-15">
          <div class="col-md-3">
            <div class="paddle-main-menu-vertical" id="paddle-vertical-menu">
              <?php
              $block = module_invoke('menu_block', 'block_view', 'vertical-menu-' . $language->language);
              print render($block['content']);
              ?>
            </div>
          </div>
          <div class="col-md-12">
            <?php if ($page['content_top']):?>
              <?php print render($page['content_top']); ?>
            <?php endif; ?>
            <?php if (!empty($breadcrumb) && $show_breadcrumb): ?>
              <div id="breadcrumb"><?php print $breadcrumb; ?></div>
            <?php endif; ?>
            <div role="main">
            <?php print render($title_prefix); ?>
            <?php if ($title): ?>
              <h1 class="title" id="page-title"><?php print $title; ?></h1>
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
                <div class="section clearfix"><?php print render($page['featured']); ?></div>
              </div>
              <!-- /.section, /#featured -->
            <?php endif; ?>
            <?php if ($page['highlighted']): ?>
              <div id="highlighted"><?php print render($page['highlighted']); ?></div>
            <?php endif; ?>
            <a id="main-content"></a>
            <?php print render($page['help']); ?>
            <?php if ($action_links): ?>
              <ul class="action-links"><?php print render($action_links); ?></ul>
            <?php endif; ?>
            <?php if ($page['content']): ?>
              <div id="page-content">
                <?php print render($page['content']); ?>
              </div>
            <?php endif; ?>
          </div>
                </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php if ($page['footer'] || (!empty($page['disclaimer']) && $show_disclaimer_menu)): ?>
  <footer>
    <div class="max-width footer-background-canvas">
      <div class="paddingizer <?php print $footer_style; ?>">
        <div class="row">
          <div class="col-md-15">
            <?php if ($page['footer']): ?>
              <?php print render($page['footer']); ?>
              <div class="thin-footer-wrapper">
                <div class="footer-tagline">
                  <?php if (!empty($footer_title)): ?>
                    <div class="footer-title">
                      <p><?php print $footer_title; ?></p>
                    </div>
                  <?php endif; ?>
                  <?php if (!empty($footer_subtitle)): ?>
                    <div class="footer-subtitle">
                      <p><?php print $footer_subtitle; ?></p>
                    </div>
                  <?php endif; ?>
                </div>
                <div class="footer-service-links">
                </div>
              </div>

            <?php endif; ?>
            <?php if (!empty($page['disclaimer']) && $show_disclaimer_menu): ?>
              <small id="disclaimer">
                <?php print render($page['disclaimer']); ?>
              </small>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </footer>
<?php endif; ?>
<?php if (!empty($vo_global_footer)):?>
  <div class="vo-global-footer">
    <?php print $vo_global_footer; ?>
  </div>
<?php endif; ?>
<?php if ($page['sidebar_second']):?>
  <?php print render($page['sidebar_second']); ?>
<?php endif; ?>
