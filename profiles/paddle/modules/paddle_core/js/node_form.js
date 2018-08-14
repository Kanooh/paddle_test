/**
 * @file
 * Javascript enhancements for node forms.
 */

(function ($) {
// Provide a button to clear the scheduler options.
Drupal.behaviors.nodeFormClearSchedulerOptions = {
  attach: function (context) {
    // Defines clear button.
    $('#edit-publish-on input[type="text"], #edit-unpublish-on input[type="text"]', context).once(function () {
      // Check on page load if any values have been entered.
      // Add clear button.
      $('#edit-publish-on input[type="text"], #edit-unpublish-on input[type="text"]').each(function () {
        if ($(this).val() !== '') {
          $(this).cloneClearButton();
        }
      });

      $(this).bind("keyup keydown keypress change focusin focusout", function () {
        // First check if textfield has a value and if the value entered was
        // changed.
        if ($(this).val() !== '' && $(this).val() !== $.data(this, "lastvalue")) {
          $(this).cloneClearButton();
        }
        else {
          // Removes clear button if values are deleted not using the clear
          // button.
          if ($(this).parent().parent().find('div[class*=form-item] input[type="text"]').first().val() === '' && $(this).parent().parent().find('div[class*=form-item] input[type="text"]').last().val() === '') {
            $(this).removeClearButton();
          }
        }
        // Stores the last inserted value to check next time we enter the
        // textfield.
        $.data(this, "lastvalue", $(this).val());
      });
    });
    // Click event to clear values of textfield in group.
    $('.clear-btn', context).live("click", function (event) {
      event.preventDefault();
      $(this).parent().parent().parent().find('input[type="text"]').val('').removeClass('error');
      $(this).remove();
    });
  }
}

// Clone button.
$.fn.cloneClearButton = function () {
  var clearBtn = $('<div class="clear-btn"><a href="#" title="' + Drupal.t('Clear') + '"><span>' + Drupal.t('Clear') + '</span></a></div>');
  // Avoid duplicated clones.
  $(this).removeClearButton();
  // Clone the clear button.
  var cloneClearButton = clearBtn.clone();
  // Insert cloned button after last textfield.
  cloneClearButton
    .addClass('cloned')
    .insertAfter($(this)
    .parent()
    .parent()
    .last()
    .find('input')
    .last());
}

// Removes clear button.
$.fn.removeClearButton = function () {
  if ($(this).parent().parent().find('.cloned').length === 0) {
    return;
  }
  $(this).parent().parent().find('.cloned').remove();
}
})(jQuery);
