var themeTableHeaderOffset = function() { 
  var offsetheight = jQuery("#header").height(); 
  return offsetheight; 
};

(function ($) {
  Drupal.behaviors.formUi = {
     attach: function (context, settings) {
      // tunrns form selects into fancy dropdowns
      if(jQuery('select').length > 0) {
      jQuery('select').selectmenu({escapeHtml: true});
      }
    }
  }
})(jQuery);