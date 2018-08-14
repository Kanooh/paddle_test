/**
 * @file
 *
 * Implements droop down options for vertical menu theme.
 *
 */

(function ($) {

// Create the second level menu navigation as select element.
Drupal.behaviors.mobileVerticalSubMenu = {
  attach: function (context, settings) {
    $('body').once('mobile-submenu', function() {
      var $secondLevelLinks = $('#paddle-vertical-menu ul.menu > li > ul.menu > li > a');
      var options = '';
      var $subMenu;

      if (! $secondLevelLinks.length) {
        // No submenu links found.
        return;
      }

      // Use a string to prepare all the options, as dom insertion is expensive.
      // First, use a default labeled one.
      options += Drupal.theme('paddleThemeMobileSubmenuOption', '', Drupal.t('Go to...'), true);

      // Convert all the links to option entries.
      $secondLevelLinks.each(function() {
        var $this = $(this);

        options += Drupal.theme('paddleThemeMobileSubmenuOption', $this.attr('href'), $this.text());
      });

      // Create the mobile menu select element.
      $subMenu = Drupal.theme('paddleThemeMobileSubmenu', options);
      // Append the element just before the main content accessibility anchor.
      $subMenu.insertBefore('#main-content');

      // Append all the options and add the change behavior.
      // Use the id to retrieve the element, as it's the fastest selector.
      $('#mobile-submenu-select').change(function () {
        window.location = $(this).val();
      });
    });
  }
};
})(jQuery);
