/**
 * @file
 * Javascript functionality for managing menu items in the node edit form.
 *
 * This refreshes the node menu items pane when the second step in the multistep
 * modal is dismissed with the close button.
 */

(function ($) {

  /**
   * Define an ajax call that will be triggered when the modal is closed.
   */
  var element_settings = {};
  // The url will be dynamically generated when the event is triggered.
  element_settings.url = '';
  element_settings.event = 'onload';
  element_settings.keypress = false;
  element_settings.prevent = false;

  Drupal.ajax['modal_content_close'] = new Drupal.ajax(null, $('div.pane-node-menu-items'), element_settings);

  /**
   * Bind a handler to the close button of step 2 of the node menu item form.
   */
  Drupal.behaviors.nodeMenuItemPaneRefresh = {
    attach: function (context, settings) {
      // Only bind to the close button of the second step of the multistep
      // modal. We should not refresh the pane when any other close buttons are
      // clicked.
      $('#paddle-menu-manager-node-menu-item-menu-placement-form', context).parents('.modalContent').find('a.close').once('node-menu-item', function () {
        $(this).bind('click', refreshPane);
        // Bind a handler to the keydown event so we can detect if the modal is
        // closed with the escape key.
        $(document).bind('keydown', keyDownHandler);
      });
    }
  }

  /**
   * Event handler. Triggers a pane refresh when the escape key.is pressed.
   */
  var keyDownHandler = function(event) {
    if (event.keyCode == 27) {
      refreshPane();
      return false;
    }
  };

  /**
   * Refreshes the node menu item pane via ajax.
   */
  var refreshPane = function () {
    // Unbind the events so they are not bound multiple times when the modal is
    // reopened.
    $('a.close').unbind('click', refreshPane);
    $(document).unbind('keydown', keyDownHandler);

    // Glean the node id from the node metadata.
    var nid = $('#node-metadata ul li.node-metadata-item-nid span.value').attr('data-raw');

    // Trigger the ajax response.
    Drupal.ajax['modal_content_close'].nodeMenuItemResponse(nid);

    return false;
  }

  /**
   * Ajax response handler for the menu node item pane refresh.
   *
   * @param int nid
   *   The node id of the node menu items pane.
   *
   * @see Drupal.ajax.prototype.eventResponse
   * @see http://deeson-online.co.uk/labs/trigger-drupal-managed-ajax-calls-any-time-drupal-7
   */
  Drupal.ajax.prototype.nodeMenuItemResponse = function(nid) {
    var ajax = this;

    // Do not perform another ajax command if one is already in progress.
    if (ajax.ajaxing) {
      return false;
    }

    ajax.options.url = Drupal.settings.basePath + 'admin/structure/menu_manager/node_menu_items_pane/' + nid;
    try {
      $.ajax(ajax.options);
    }
    catch (err) {
      alert('We\'re sorry but we were unable to update the list of menu items. Your menu item has been saved, and will be visible when you refresh or save the page.');
      return false;
    }

    return false;
  };

})(jQuery);
