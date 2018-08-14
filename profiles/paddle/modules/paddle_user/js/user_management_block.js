/**
 * @file
 * Javascript for the user management block.
 */
(function ($) {

  Drupal.behaviors.showUserManagementLinks = {
    attach: function (context, settings) {
      $('.personal-info', context).once('links-hover', function() {
        $(this).hover(function() {
          $('.user-links', this).toggle();
        });
      })
    }
  };

})(jQuery);
