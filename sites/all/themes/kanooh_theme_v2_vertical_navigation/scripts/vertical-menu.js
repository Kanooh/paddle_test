/**
 * @file
 *
 * Implements sticky vertical menu when scrolling.
 *
 */

(function ($) {
  Drupal.behaviors.mobileVerticalStickToTop = {
    attach: function (context, settings) {
      reposition();
      function reposition() {
        if (($('#paddle-vertical-menu', context).length) && checkSize()) {
          var stickyElement = $('#paddle-vertical-menu', context);
          var stickyHeight = $('#paddle-vertical-menu', context).height();
          var contentHeight = $('.col-md-12').height();

          var padding = 20;
          var headerHeight = 0;
          var voHeaderHeight = 0;
          var federalHeader = 0;
          var footerTop = contentHeight+headerHeight+voHeaderHeight+federalHeader+padding;
          var footerHeight = 0;
          var voFooterHeight = 0;
          var limit = footerTop - stickyHeight;

          if ($('.vo-global-header').length > 0) {
            headerHeight = $('.vo-global-header', context).height();
          }

          if ($('header').length > 0) {
            voHeaderHeight = $('header', context).height();
          }

          if($('#blgm_belgiumHeader').length > 0){
            federalHeader = $('#blgm_belgiumHeader', context).height();
          }

          if ($('footer').length > 0) {
            footerTop = $('footer', context).offset().top;
            footerHeight = $('footer', context).height();
            voFooterHeight = $('.vo-global-footer', context).height();
            limit = footerTop - stickyHeight;
          }

          if ((stickyHeight < contentHeight) && checkSize()) {
            var stickyTop = $('#paddle-vertical-menu', context).offset().top + ( (70 / 100) * stickyHeight );
            $(window).scroll(function () { // Scroll event.

              var windowTop = $(window).scrollTop();
              if ((windowTop < stickyHeight) && checkSize()) {
                stickyElement.css('margin-top', 0);
              }
              else
                if (stickyTop < windowTop && checkSize()) {
                  stickyElement.css('margin-top', (windowTop - headerHeight - voHeaderHeight - federalHeader - padding));
                  stickyElement.css('transition', 'all .7s ease');
                }
                else {
                  return false;
                }

              if (limit < windowTop) {
                stickyElement.css('margin-top', (contentHeight - stickyHeight - footerHeight - voFooterHeight - padding));
                stickyElement.css('transition', 'all .7s ease');
              }
              else {
                return false;
              }
            });
          }
          else {
            return false;
          }
        }
        else {
          return false;
        }
      }

      $(window).resize(function () {
        if (checkSize()) {
          reposition();
        }
      });

      function checkSize() {
        return ($(window).width() > 889)
      }
    }
  };
})(jQuery);
