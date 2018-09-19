/**
 * @file
 * Contains override for the form builder scroll behaviour.
 */

(function($) {
  Drupal.behaviors.formBuilderBlockScroll.attach = function(context) {
    var $list = $('ul.form-builder-fields', context);

    if ($list.length) {
      var $block = $list.parents('div.block:first').css('position', 'relative');
      var blockScrollStart = $block.offset().top;

      function blockScroll() {
        // Do not move the palette while dragging a field.
        if (Drupal.formBuilder.activeDragUi) {
          return;
        }

        var headerHeight = $('#header').height();
        var windowOffset = $(window).scrollTop();
        var blockHeight = $block.height();
        var formBuilderHeight = $('#form-builder').height();
        if (windowOffset + headerHeight - blockScrollStart > 0) {
          // Do not scroll beyond the bottom of the editing area.
          var newTop = Math.min(windowOffset + headerHeight - blockScrollStart + 20, formBuilderHeight - blockHeight);
          $block.animate({ top: (newTop + 'px') }, 'fast');
        }
        else {
          $block.animate({ top: '0px' }, 'fast');
        }
      }

      var timeout = false;
      function scrollTimeout() {
        if (timeout) {
          clearTimeout(timeout);
        }
        timeout = setTimeout(blockScroll, 100);
      }

      $(window).scroll(scrollTimeout);
    }
  };
})(jQuery);
