/**
 * @file
 *
 * Implements a sticky footer.
 *
 * Only when the content of the page is too short and the footer needs to be sticky to the bottom of the page.
 */
(function ($) {
  Drupal.behaviors.paddleThemeFooterSticky = {
    attach: function (context, settings) {
      if(!$('footer').length) {
        return;
      }
      //if($('#page-content').height() < $(window).height()) {
        var footerHeight = -1 * $('footer').height();
        $('#page-content').css('paddingBottom', -1 * footerHeight);
        $('footer').css('marginTop', footerHeight);
      //}
    }
  }
})(jQuery);
