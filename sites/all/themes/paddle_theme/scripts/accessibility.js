(function ($) {

  /**
   * Overrides the auto complete attributes.
   */
  Drupal.behaviors.autocompleteModification = {
    attach: function (context, settings) {
      // Removes the application role from the auto complete wrapper.
      $('input.autocomplete').parent().removeAttr('role');
    }
  };

  /**
   * Removes empty basic page entity revision blocks.
   */
  Drupal.behaviors.hideEmptyBasicPage = {
    attach: function (context, settings) {
      if ($.trim($('.node-basic-page:not(.node-listing-title)').html()).length == 0) {
        $('div.node-basic-page:not(.node-listing-title)').parent().remove();
      }
    }
  };

  /**
   * Overrides the auto complete function from Drupal core, but made the added
   * container more accessible.
   */
  Drupal.behaviors.autocomplete = {
    attach: function (context, settings) {
      var acdb = [];
      $('input.autocomplete', context).once('autocomplete', function () {
        var uri = this.value;
        if (!acdb[uri]) {
          acdb[uri] = new Drupal.ACDB(uri);
        }
        var $input = $('#' + this.id.substr(0, this.id.length - 13))
            .attr('autocomplete', 'OFF')
            .attr('aria-autocomplete', 'list');
        $($input[0].form).submit(Drupal.autocompleteSubmit);
        $input.parent()
            .attr('role', 'application')
            .append($('<span class="element-invisible" aria-live="assertive" aria-atomic="true"></span>')
                .attr('id', $input.attr('id') + '-autocomplete-aria-live')
            );
        new Drupal.jsAC($input, acdb[uri]);
      });
    }
  };

})(jQuery);