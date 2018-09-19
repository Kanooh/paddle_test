/**
 * @file
 * Contains scripts related to the search.
 */
(function ($) {
  /**
   * Behaviour for mobile search button.
   */
  Drupal.behaviors.mobileSearchButton = {
    attach: function (context, settings) {
      $('.mobile-search-btn').once('mobile-search-toggle', function() {
        $(this).click(function() {
          // This is to make sure that the search pop up functionality won't be interrupted by the mobile search.
          if($('#search-box-holder').hasClass('visuallyhidden')){
            $('#search-box-holder').removeClass('visuallyhidden')
            $('.search-pop-up .fa').toggleClass('fa-times');
            $('.search-pop-up .fa').toggleClass('fa-search');
          }

          $('#block-search-api-page-search').toggleClass('is-open');
          return false;
        });
      });
    }
   };

  /**
   * Behaviour for the search popup.
   *
   */
  Drupal.behaviors.paddleSearchPopup= {
    attach: function (context, settings) {
      $('.search-pop-up').once('search-pop-up-toggle', function () {
        $(this).click(function () {
          $('#search-box-holder').toggleClass('visuallyhidden');
          $('.search-pop-up .fa').toggleClass('fa-times');
          $('.search-pop-up .fa').toggleClass('fa-search');
          // Hide the top menu.
          $('#block-paddle-menu-display-top-menu').toggleClass('visuallyhidden');
          // Hide the language switcher.
          $('.block-locale').toggleClass('visuallyhidden');
          return false;
        });
      });
    }
  };
})(jQuery);
