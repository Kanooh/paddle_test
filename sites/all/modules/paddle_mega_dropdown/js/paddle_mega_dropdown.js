(function ($) {
  Drupal.behaviors.paddleMegaDropdown = {
    attach: function (context, settings) {
      if ($('.paddle-mega-dropdown').length != 0) {
        $('.menu-item[data-mlid]').hoverIntent({
          over: hoverIn,
          out: hoverOut,
          sensitivity: 1,
          timeout: 200
        });
        $('.paddle-mega-dropdown').mouseleave(function(e) {
          hoverOut.call(this,e);
        });
      }
    }
  }

  function hoverIn() {
    $(this).find('a').addClass('megadropdown-expanded');
    var activeitem = $(this).data("mlid");
    $('.paddle-mega-dropdown[data-mlid=' + activeitem + ']').css({"height": "auto", "visibility": "visible"}).fadeIn("fast");
  }
  function hoverOut(e) {
    var activeitem = $(this).data("mlid");
    var targetElement = e.relatedTarget;
    if ($(targetElement).closest('[data-mlid=' + activeitem + ']').length == 0) {
      $('.paddle-mega-dropdown[data-mlid=' + activeitem + ']').css({"height": "0px"}).fadeOut("fast");
      $('.menu-item[data-mlid=' + activeitem + ']').find('a').removeClass('megadropdown-expanded');
    }
  }
})(jQuery);
