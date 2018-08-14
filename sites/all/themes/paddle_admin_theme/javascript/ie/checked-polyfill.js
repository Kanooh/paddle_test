/*  Checked Polyfill
    Author: Ryan DeBeasi (352 Media Group)
    Description: Provides a .checked class that works like the :checked pseudo class on radio buttons and checkboxes but is available in older browsers such as IE7/8. */

(function ($) {
  $.fn.checkedPolyfill = function (options) {
    function checkValue ($elem) {
      var $label = $('label[for="' + $elem.attr('id') + '"]');
      // TODO: also find labels wrapped around the input
      if ($elem.is(":checked")) {
        $label.addClass('checked');
        $elem.addClass('checked');
      } else {
        $label.removeClass('checked');
        $elem.removeClass('checked');
      }
      // We modify the label as well as the input because authors may want to style the labels based on the state of the chebkox, and IE7 and IE8 don't fully support sibling selectors.
      // For more info: http://www.quirksmode.org/css/selectors/#t11
      return $elem;
    }

    return this.each(function () {
      var $self = $(this);

      if ($self.attr('type') === 'radio') {
        $('input[name="' + $self.attr('name') + '"]').change(function() {
          checkValue($self);

        });
      } else if ($self.attr('type') === 'checkbox') {
        $self.change(function() {
          checkValue($self);
        });
      }
      checkValue($self); // Check value when plugin is first called, in case a value has already been set.
    });

  };

  Drupal.behaviors.checkedPolyfill = {
    attach: function (context, settings) {
      $('.form-checkbox,.form-radio').checkedPolyfill();
    }
  }

})(jQuery);
