/**
 * @file
 * Opening Hours js.
 */

(function ($) {
  // Day elements will be shown on basis of the assigned day title box which is clicked on.
  Drupal.behaviors.showOpeningHours = {
    attach: function (context, settings) {
      Drupal.behaviors.showOpeningHours.initializeOpeningHours(context, settings);
      $('.opening-hours-set').each(function (index) {
        var $parent_element = this;
        $($parent_element).find('.ohs-upcoming-week .title-box').click(function () {
          $($parent_element).find('.ohs-upcoming-day').addClass('element-invisible');
          $($parent_element).find('.ohs-upcoming-week .title-box').removeClass('selected');
          $(this).addClass('selected');
          $index = $(this).index();
          $($parent_element).find('.ohs-upcoming-day:eq(' + $index + ')').removeClass('element-invisible');
        });
      });
    },

    initializeOpeningHours: function (context, settings) {
      $('.opening-hours-set').each(function (index) {
        $(this).find('.ohs-upcoming-day:first').removeClass('element-invisible');
        $(this).find('.ohs-upcoming-week .title-box:first').addClass('selected');
      });
    }
  }
})(jQuery);
