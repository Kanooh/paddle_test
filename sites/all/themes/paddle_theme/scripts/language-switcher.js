/**
 * @file
 * Contains language switcher specific scripts.
 */
(function ($) {
  /**
   * Returns the markup for the mobile language switcher.
   *
   * @param {string} options
   *   A list of options as string.
   *
   * @returns {*|HTMLElement}
   *   The submenu markup as jQuery element.
   */
  Drupal.theme.prototype.paddleThemeLanguageSwitcherSelect = function (current, options) {
    return $('<div class="mobile-language-switcher"><div class="current-language">' + current + '<i class="fa fa-caret-down"></i></div><select id="language-switcher-select" class="language-switcher-select">' + options + '</select></div>');
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
  Drupal.theme.prototype.paddleThemeLanguageSwitcherOption = function (value, text, selected) {
    var string = '<option ';

    // Add the selected if needed.
    if (selected) {
      string += 'selected="selected" ';
    }

    string += 'value="' + value + '">' + text + '</option>';

    return string;
  };

  /**
   * Dropdown behaviour for the language switcher.
   */
  Drupal.behaviors.languageSwitcher = {
    attach: function (context, settings) {
      $('.language-switcher-btn').once('dropdown', function() {
        var $langSwitcher = $('.language-switcher-btn'),
            $blockContent = $langSwitcher.parent(),
            options = '',
            $select;

        $langSwitcher.on('click.ls', function () {
          var $this = $(this),
              isActive = $blockContent.hasClass('is-open');

          $this.attr('aria-expanded', !isActive);
          $blockContent.toggleClass('is-open');

          return false;
        });

        $(document).on('click.ls', function() {
          $langSwitcher.attr('aria-expanded', false);
          $blockContent.removeClass('is-open');
        });

        // Create the switcher select for mobile viewports.
        options += Drupal.theme('paddleThemeLanguageSwitcherOption', '', Drupal.t('Go to...'), true);

        $blockContent.find('a.language-link').each(function () {
          var $this = $(this);

          options += Drupal.theme('paddleThemeLanguageSwitcherOption', $this.attr('href'), $this.text());
        });

        $select = Drupal.theme('paddleThemeLanguageSwitcherSelect', $langSwitcher.text(), options);

        $select
          .insertAfter('.mobile-search-btn')
          .on('change.ls', function() {
            var href = $( 'option:selected', this ).val();

            if ( href ) {
              window.location.href = href;
            }
          });
      });
    }
  };
})(jQuery);
