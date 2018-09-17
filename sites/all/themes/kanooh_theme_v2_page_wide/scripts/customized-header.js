/**
 * @file
 * Contains the customized header behaviors.
 */
(function ($) {

  /**
   * Resize the height of a customized header on tablet view.
   *
   * @type {{attach: Drupal.behaviors.tabletResizeHeader.attach}}
   */
  Drupal.behaviors.tabletResizeHeader = {
    attach: function (context, settings) {
      // var header_height = 0;

      jQuery(window).load(function (e) {
          // Initialize the header on first load on tablet.
          if ($(window).width() < 888 && $(window).width() > 578) {
             // move the navigation out of the ...
              $('.navigation').appendTo('.page-wide-container.header-wrapper');
              $('.page-wide-container.header-wrapper .row.header-row').prependTo('.page-wide-container.header-wrapper');

              var header_height = $(".header-background-canvas").outerHeight(true);
              var navigation_height = $(".navigation").outerHeight(true);
              var new_height = header_height + navigation_height;
              $(".header-background-canvas")[0].style.height = new_height + 'px';
          }

        // Make room for the language block if required.
        if ($(window).width() > 888 && $('div.block-locale').length) {
          $('.navigation')[0].style.marginRight = '55px';
        }

      });

      jQuery(window).resize(function (e) {
        // We always remove the style at resize, this way we can recalculate more
        // more accurately the height of the header, top menu indentation included.
        // It also makes sure other viewports like mobile and desktop are not
        // affected.
        $(".header-background-canvas").removeAttr('style');

        if ($(window).width() < 888 && $(window).width() > 578) {
          $('.navigation').appendTo('.page-wide-container.header-wrapper');
          $('.page-wide-container.header-wrapper .row.header-row').prependTo('.page-wide-container.header-wrapper');

          var header_height = $(".header-background-canvas").outerHeight(true);
          var navigation_height = $(".navigation").outerHeight(true);
          var new_height = header_height + navigation_height;
          $(".header-background-canvas")[0].style.height = new_height + 'px';
        }
        else {
          // move the navigation back
          $('.navigation').appendTo('.page-wide-container.header-wrapper .row.header-row');
          $('.page-wide-container.header-wrapper .row.service-links-row').prependTo('.page-wide-container.header-wrapper');
        }

        // Make room for the language block if required.
        if ($(window).width() > 888 && $('div.block-locale').length) {
          $('.navigation')[0].style.marginRight = '55px';
        }
      });
    }
  };

})(jQuery);