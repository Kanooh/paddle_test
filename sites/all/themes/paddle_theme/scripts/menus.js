/**
 * @file
 *
 * Implements a horizontal slider for long menus.
 *
 */
(function ($) {
  /**
   * Returns the submenu markup for the level 2 navigation on mobile screens.
   *
   * @param {string} options
   *   A list of options as string.
   *
   * @returns {*|HTMLElement}
   *   The submenu markup as jQuery element.
   */
  Drupal.theme.prototype.paddleThemeMobileSubmenu = function (options) {
    return $('<div class="mobile-submenu-wrapper"><select id="mobile-submenu-select" class="mobile-submenu-select" aria-labelledby="' + Drupal.t('next level menu items') + '">' + options + '</select></div>');
  };

  /**
   * Generate the markup for an option element.
   *
   * This intentionally does not return a jQuery element, as dom manipulation
   * is expensive and we can save time by appending all the options at once
   * using .html() method.
   *
   * @param {string} value
   *   The value of the option element.
   * @param {string} text
   *   The text to use for the option element.
   * @param {bool} selected
   *   If the element has the selected status. Defaults to false.
   *
   * @returns {string}
   *   The markup for the option element.
   */
  Drupal.theme.prototype.paddleThemeMobileSubmenuOption = function (value, text, selected) {
    var string = '<option ';

    // Add the selected if needed.
    if (selected) {
      string += 'selected="selected" ';
    }

    string += 'value="' + value + '">' + text + '</option>';

    return string;
  };

  Drupal.behaviors.paddle_theme_vo_menuslider = {
    attach: function (context, settings) {
      $('#menu-display-first-level').paddleThemeMenuSlider();
      $('#menu-display-current-level-plus-one').paddleThemeMenuSlider();
    }
  };

  // Responsive menu trigger.
  Drupal.behaviors.respMenuTrigger = {
    attach: function (context, settings) {
      $('a.mobile-menu-trigger', context).once('mobile-trigger', function() {
        var $body = $('body');
        $('a.mobile-menu-trigger').attr('aria-expanded', 'false');

        $(this).click(function() {
          $body[$body.hasClass('resp-open') ? 'removeClass' : 'addClass']('resp-open');

          if ($body.hasClass('resp-open')) {
            $('a.mobile-menu-trigger').attr('aria-expanded', 'true');
          }
          else {
            $('a.mobile-menu-trigger').attr('aria-expanded', 'false');
          }

          return false;
        });

        $('div.content-wrapper').click(function() {
          $body.hasClass('resp-open') && $body.removeClass('resp-open');
        });
      });
    }
  };

  // Create the second level menu navigation as select element.
  Drupal.behaviors.mobileSubMenu = {
    attach: function (context, settings) {
      $('body').once('mobile-submenu', function() {
        var $secondLevelLinks = $('#menu-display-current-level-plus-one ul.menu > li > a');
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
