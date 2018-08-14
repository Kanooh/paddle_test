/**
 * Initialize the flexslider instance
 */
(function ($) {
  if ($(".paddle-apps-paddlet-screenshots") && $(".flexslider")) {
    $(".flexslider").flexslider({animation: "fade"});
    $(".flex-direction-nav").remove();
  }
}(jQuery));
