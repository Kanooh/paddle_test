/**
 * @file
 *
 * Implements a select box for the language switcher.
 *
 */
(function ($) {
  /**
   * Returns the markup for the language switcher.
   *
   * @param {string} options
   *   A list of options as string.
   *
   * @returns {*|HTMLElement}
   *   The switcher markup as jQuery element.
   */
  Drupal.theme.prototype.paddleThemeLanguageSwitcher = function (options) {
    return $('<div class="language-switcher-wrapper"><select id="language-switcher-select" class="language-switcher-select">' + options + '</select></div>');
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

  // Create the language switcher as select element.
  Drupal.behaviors.languageSwitcher = {
    attach: function (context, settings) {
      $('body').once('language-switcher', function() {
        var $languages = $('#block-locale-language-content ul.language-switcher-locale-session > li > a');
        var options = '';
        var $languageSwitcher;
        var defaultValue;

        // Only make a select of the switcher contains more then 4 languages
        if ($languages.length < 5) {
          return;
        }

        var vars = [], hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for(var i = 0; i < hashes.length; i++)
        {
          hash = hashes[i].split('=');
          vars.push(hash[0]);
          vars[hash[0]] = hash[1];
        }

        defaultValue = Drupal.settings.paddle_i18n.language;

        // Convert all the languages to option entries.
        $languages.each(function() {
          var $this = $(this);
          var language = $this.text();
          var selected = false;

          if (defaultValue && language == defaultValue) {
            selected = true;
          }

          options += Drupal.theme('paddleThemeLanguageSwitcherOption', $this.attr('href'), $this.text(), selected);
        });

        // Create the select element.
        $languageSwitcher = Drupal.theme('paddleThemeLanguageSwitcher', options);
        // Append the element just before the main content accessibility anchor.
        $languageSwitcher.insertBefore('.language-switcher-locale-session');
        $('.language-switcher-locale-session').remove();

        // Append all the options and add the change behavior.
        // Use the id to retrieve the element, as it's the fastest selector.
        $('#language-switcher-select').change(function () {
          window.location = $(this).val();
        });
      });
    }
  };
})(jQuery);
