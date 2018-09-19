(function ($) {

Drupal.behaviors.paddle_editorial_notes = {
  attach: function (context, settings) {
    $(".submit-editorial-note").hide();
    $(".editorial-note-text-area").blur(function() {
      $(".submit-editorial-note").hide();
    });
    $(".editorial-note-text-area").focus(function() {
      $(".submit-editorial-note").show();
    });
    var max_items = 3;
    var items = $("div.paddle-editorial-note-list").find("div.paddle-editorial-note");
    if (items.length > max_items) {
      $("div.paddle-editorial-note-list").find("div.paddle-editorial-note:gt(" + (max_items - 1) + ")")
      .hide()
      .end();
      if (!$("a.show-more")[0]) {
        $("div.paddle-editorial-note-list").append($("<a href=\"#\" class=\"show-more\">" + Drupal.t('Show more') + "</a>").click(function(){
          $(this).siblings(":hidden").slideDown("fast").end().remove();
          return false;
        }))
      }
    }

    $('a.delete-editorial-note', context).once('paddle_editorial_notes', function () {
      $(this).click(function(e) {
        e.preventDefault();
        if (confirm(Drupal.t('Are you sure?'))) {
          return true;
        }
        e.stopImmediatePropagation();
        return false;
      }).addClass('editorial-note-processed');

      var event = 'click';

      var owner = $(this);

      // Move our click handler to the front of the queue,
      // before the Drupal ajax handler.
      var events = owner.data('events')[event];
      events.unshift(events.pop());
    });
  }
}
})(jQuery);
