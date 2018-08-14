/**
 * @file
 * Creates a select out of a list of links for responsive usage.
 */
(function ($) {
  Drupal.behaviors.paddleThemeLinksToSelect = {
    attach: function (context, settings) {
      $('.paddle-glossary-pager', context).once('links-to-select', function () {
        var $this = $(this);
        var $links = $this.find('a');
        var options = '';
        var $select;
        var activeIndex;

        if (!$links.length) {
          return;
        }

        // Use a string to prepare all the options, as dom insertion is expensive.
        // First, use a default labeled one.
        options += '<option value="">' + Drupal.t('Go to...') + '</value>';

        // Convert all the links to option entries.
        $links.each(function (i) {
          var $this = $(this);

          options += '<option value="' + i + '">' + $this.text() + '</option>';
        });

        // Create the select.
        $select = $('<select class="paddle-glossary-mobile-pager">' + options + '</select>');

        // Mark the current active element.
        // The index() function will return -1 if not present.
        // We have already prepended one option at the beginning, so add 1
        // to mark the correct option.
        activeIndex = $links.index($links.filter('.active')) + 1;
        $select.find('option').eq(activeIndex).attr('selected', 'selected');

        // Append the select to the parent of the current element.
        $select.appendTo($this.parent());

        // Bind the change event to redirect to the link.
        $select.bind('change', function () {
          var index = $(this).val();

          if (index) {
            $links.eq(index).click();
            $select.attr('disabled', 'disabled');
          }
        });
      });
    }
  };
})(jQuery);
