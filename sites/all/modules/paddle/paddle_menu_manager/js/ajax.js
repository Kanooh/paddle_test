/**
 * @file
 * Ajax commands definitions.
 */
(function ($) {

  Drupal.ajax.prototype.commands.lock = function(ajax, data, status) {
    $('body').append('<div class="lock-backdrop"></div>');
  };

})(jQuery);