/**
 * @file
 * Javascript functionality to split large vocabularies into bite-sized chunks.
 */

(function ($) {
  // Start the call queue processing.
  window.setTimeout(function() {processCallsQueue()}, 100);

  // Call queue container to prevent simultaneous calls.
  Drupal.behaviors.paddle_taxonomy_manager_call_queue = [];

  /**
   * JS behaviour for the Paddle Taxonomy Manager Big vocabulary.
   */
  Drupal.behaviors.paddle_taxonomy_manager_big_vocabulary = {
    attach: function (context, settings) {
      $('a.paddle-big-vocabulary-expandable').not('.bigvocabulary-processed')
      .addClass('bigvocabulary-processed').click(function (event) {
        // Prevent following the href of the link.
        event.preventDefault();

        var triggeringLink = $(this);

        // Get the tid of the term.
        var tid = triggeringLink.attr('rel');

        // Prevent impatient users from starting this again while it is running.
        if (triggeringLink.hasClass('paddle-big-vocabulary-processing')) {
          return false;
        }
        triggeringLink.addClass('paddle-big-vocabulary-processing');

        // If the children were already fetched, then just show them.
        if (triggeringLink.hasClass('paddle-big-vocabulary-fetched')) {
          if (triggeringLink.hasClass('paddle-big-vocabulary-folded')) {
            // Show the item.
            $('.paddle-big-vocabulary-parent-tid-' + tid).css('display', '');
            // Toggle the '+' symbol to a '-' symbol (Unicode U+229F).
            $('a.paddle-big-vocabulary-expandable[rel=' + tid + '] > span').text('\u229F');
            // Indicate the items are unfolded.
            triggeringLink.removeClass('paddle-big-vocabulary-folded').addClass('paddle-big-vocabulary-unfolded');
          }
          else {
            // Hide the item.
            hideChildrenRecursively(triggeringLink);
          }
          triggeringLink.removeClass('paddle-big-vocabulary-processing');
          return false;
        }

        // Fetch the sub-term and inject it below its parent.
        var url = triggeringLink.attr('href');
        var parentRow = triggeringLink.parents('tr').get();
        var form = $('.term-id', parentRow).attr('form');
        var formId = $('input[name="form_id"]', form).val();
        var formBuildId = $('input[name="form_build_id"]', form).val();
        url += "/" + formId + "/" + formBuildId;
        Drupal.behaviors.paddle_taxonomy_manager_call_queue.push({
          'running': false,
          'url': url,
          'parentRow': parentRow,
          'tid': tid,
          'triggeringLink': triggeringLink
        });
        return false;
      });
    }
  }

  function hideChildrenRecursively(parentLink) {
    // Indicate the items are folded.
    parentLink.removeClass('paddle-big-vocabulary-unfolded').addClass('paddle-big-vocabulary-folded');

    var tid = parentLink.attr('rel');
    // Hide the children.
    $('.paddle-big-vocabulary-parent-tid-' + tid).css('display', 'none');
    // Toggle the '-' symbol to a '+' symbol (Unicode U+229E) for each of them.
    $('a.paddle-big-vocabulary-expandable[rel=' + tid + '] > span').text('\u229E');
    // Fold each child if it is unfolded.
    $('.paddle-big-vocabulary-parent-tid-' + tid + ' a.paddle-big-vocabulary-expandable.paddle-big-vocabulary-unfolded').each(function (){
      hideChildrenRecursively($(this));
    });
  }

  function processCallsQueue() {
    if (Drupal.behaviors.paddle_taxonomy_manager_call_queue.length) {
      // If we have nothing being processed but there are calls in the queue
      // start the first one.
      if (Drupal.behaviors.paddle_taxonomy_manager_call_queue[0].running == false) {
        Drupal.behaviors.paddle_taxonomy_manager_call_queue[0].running = true;
        getSubterm(Drupal.behaviors.paddle_taxonomy_manager_call_queue[0]);
      }
    }
    window.setTimeout(function() {processCallsQueue()}, 100);
  }

  function getSubterm(call) {
    $.ajax({
      url: call.url,
      dataType: 'HTML',
      success: function (data) {
        var trs = $(data).filter(function () {
          return $(this).is('tr');
        });
        var lastTableRow = call.parentRow;
        trs.each(function () {
          $(this).addClass('paddle-big-vocabulary-parent-tid-' + call.tid);
          $(lastTableRow).after($(this));
          lastTableRow = $(this);
        });
        $('.paddle-big-vocabulary-parent-tid-' + call.tid).show();
        call.triggeringLink.addClass('paddle-big-vocabulary-fetched').addClass('paddle-big-vocabulary-unfolded');
        // Toggle the '+' symbol to a '-' symbol (Unicode U+229F).
        call.triggeringLink.children('span').text('\u229F');
        $('#taxonomy').removeClass('tabledrag-processed');
        $('#taxonomy .tabledrag-handle').remove();
        Drupal.attachBehaviors();
        $('.tabledrag-toggle-weight-wrapper').first().remove();
        // Remove the first call in the queue - we must have running it.
        Drupal.behaviors.paddle_taxonomy_manager_call_queue.shift();
        call.triggeringLink.removeClass('paddle-big-vocabulary-processing');
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        // Failure...
        call.triggeringLink.removeClass('paddle-big-vocabulary-processing');
        alert(Drupal.t('Error fetching subterm: @error', { '@error': textStatus }));
      }
    });
  }

  Drupal.theme.prototype.tableDragChangedWarning = function () {
    // Fix a side effect of the Big Taxonomy - the warning appears twice.
    if ($('div.tabledrag-changed-warning').length == 0) {
      return '<div class="tabledrag-changed-warning messages warning">' + Drupal.theme('tableDragChangedMarker') + ' ' + Drupal.t('Changes made in this table will not be saved until the form is submitted.') + '</div>';
    }

    return '';
  };
})(jQuery);
