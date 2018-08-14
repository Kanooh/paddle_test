/**
 * @file
 * Javascript functionality to split large menus into bite-sized chunks.
 */

(function ($) {
  // Start the call queue processing.
  window.setTimeout(function() {processCallsQueue()}, 100);

  // Call queue container to prevent simultaneous calls.
  Drupal.behaviors.paddle_menu_manager_call_queue = [];

  /**
   * JS behaviour for the Paddle Menu Manager Big menus.
   */
  Drupal.behaviors.paddle_menu_manager_big_menu = {
    attach: function (context, settings) {
      $('a.paddle-big-menu-expandable').not('.bigmenu-processed')
      .addClass('bigmenu-processed').click(function (event) {
        // Prevent following the href of the link.
        event.preventDefault();

        var triggeringLink = $(this);

        // Get the mlid of the menu item.
        var mlid = triggeringLink.attr('rel');

        // Prevent impatient users from starting this again while it is running.
        if (triggeringLink.hasClass('paddle-big-menu-processing')) {
          return false;
        }
        triggeringLink.addClass('paddle-big-menu-processing');

        // If the children were already fetched, then just show them.
        if (triggeringLink.hasClass('paddle-big-menu-fetched')) {
          if (triggeringLink.hasClass('paddle-big-menu-folded')) {
            // Show the item.
            $('.paddle-big-menu-parent-mlid-' + mlid).css('display', '');
            // Toggle the '+' symbol to a '-' symbol (Unicode U+229F).
            $('a.paddle-big-menu-expandable[rel=' + mlid + '] > span').text('\u229F');
            // Indicate the items are unfolded.
            triggeringLink.removeClass('paddle-big-menu-folded').addClass('paddle-big-menu-unfolded');
          }
          else {
            // Hide the item.
            hideChildrenRecursively(triggeringLink);
          }
          triggeringLink.removeClass('paddle-big-menu-processing');
          return false;
        }

        // Fetch the sub-menu and inject it below its parent.
        var url = triggeringLink.attr('href');
        var parentRow = triggeringLink.parents('tr').get();
        var form = $('.menu-mlid', parentRow).attr('form');
        var formId = $('input[name="form_id"]', form).val();
        var formBuildId = $('input[name="form_build_id"]', form).val();
        url += "/" + formId + "/" + formBuildId;
        Drupal.behaviors.paddle_menu_manager_call_queue.push({
          'running': false,
          'url': url,
          'parentRow': parentRow,
          'mlid': mlid,
          'triggeringLink': triggeringLink
        });
        return false;
      });
    }
  }

  function hideChildrenRecursively(parentLink) {
    // Indicate the items are folded.
    parentLink.removeClass('paddle-big-menu-unfolded').addClass('paddle-big-menu-folded');

    var mlid = parentLink.attr('rel');
    // Hide the children.
    $('.paddle-big-menu-parent-mlid-' + mlid).css('display', 'none');
    // Toggle the '-' symbol to a '+' symbol (Unicode U+229E) for each of them.
    $('a.paddle-big-menu-expandable[rel=' + mlid + '] > span').text('\u229E');
    // Fold each child if it is unfolded.
    $('.paddle-big-menu-parent-mlid-' + mlid + ' a.paddle-big-menu-expandable.paddle-big-menu-unfolded').each(function (){
      hideChildrenRecursively($(this));
    });
  }

  function processCallsQueue() {
    if (Drupal.behaviors.paddle_menu_manager_call_queue.length) {
      // If we have nothing being processed but there are calls in the queue
      // start the first one.
      if (Drupal.behaviors.paddle_menu_manager_call_queue[0].running == false) {
        Drupal.behaviors.paddle_menu_manager_call_queue[0].running = true;
        getSubmenu(Drupal.behaviors.paddle_menu_manager_call_queue[0]);
      }
    }
    window.setTimeout(function() {processCallsQueue()}, 100);
  }

  function getSubmenu(call) {
    $.ajax({
      url: call.url,
      dataType: 'HTML',
      success: function (data) {
        var trs = $(data).filter(function () {
          return $(this).is('tr');
        });
        var lastTableRow = call.parentRow;
        trs.each(function () {
          $(this).addClass('paddle-big-menu-parent-mlid-' + call.mlid).css('opacity', 0.2);
          $(lastTableRow).after($(this));
          lastTableRow = $(this);
        });
        $('.paddle-big-menu-parent-mlid-' + call.mlid).animate({opacity: '1'}, 1500);
        call.triggeringLink.addClass('paddle-big-menu-fetched').addClass('paddle-big-menu-unfolded');
        // Toggle the '+' symbol to a '-' symbol (Unicode U+229F).
        call.triggeringLink.children('span').text('\u229F');
        $('#menu-overview').removeClass('tabledrag-processed');
        $('#menu-overview .tabledrag-handle').remove();
        Drupal.attachBehaviors();
        $('.tabledrag-toggle-weight-wrapper').first().remove();
        // Remove the first call in the queue - we must have running it.
        Drupal.behaviors.paddle_menu_manager_call_queue.shift();
        call.triggeringLink.removeClass('paddle-big-menu-processing');
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        // Failure...
        call.triggeringLink.removeClass('paddle-big-menu-processing');
        alert(Drupal.t('Error fetching submenu: @error', { '@error': textStatus }));
      }
    });
  }

  Drupal.theme.prototype.tableDragChangedWarning = function () {
    // Fix a side effect of the Big Menu - the warning appears twice.
    if ($('div.tabledrag-changed-warning').length == 0) {
      return '<div class="tabledrag-changed-warning messages warning">' + Drupal.theme('tableDragChangedMarker') + ' ' + Drupal.t('Changes made in this table will not be saved until the form is submitted.') + '</div>';
    }

    return '';
  };
})(jQuery);
