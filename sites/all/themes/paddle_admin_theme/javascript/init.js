jQuery(function () {
  myPage.init();
});

var myPage = (function ($) {
  var that = {};

  that.init = function () {
    $('#header').scrollToFixed();
  }

  return that;
})(jQuery);
