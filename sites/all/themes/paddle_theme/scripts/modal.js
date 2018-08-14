/**
 * @file
 *
 * Implement a modal form.
 *
 * @see modal.inc for documentation.
 *
 * This javascript relies on the CTools ajax responder.
 */

(function ($) {
  // Make sure our objects are defined.
  Drupal.CTools = Drupal.CTools || {};
  Drupal.CTools.Modal = Drupal.CTools.Modal || {};

  Drupal.CTools.Modal.modal = null;
  Drupal.CTools.Modal.modals = [];

  Drupal.CTools.Modal.modalId = 0;
  Drupal.CTools.Modal.modalIds = [];

  /* Backwards-compatibility. */
  modalContentResize = function() {}

  /**
   * Display the modal
   *
   * @todo -- document the settings.
   */
  Drupal.CTools.Modal.show = function(choice) {
    var opts = {};
    var modalId = new Date().getTime();

    if (choice && typeof choice == 'string' && Drupal.settings[choice]) {
      // This notation guarantees we are actually copying it.
      $.extend(true, opts, Drupal.settings[choice]);
    }
    else if (typeof choice == 'string') {
      $.extend(true, opts, {'choice': choice});
    }
    else if (choice) {
      $.extend(true, opts, choice);
    }

    var defaults = {
      modalId: modalId,
      modalTheme: 'CToolsModalDialog',
      throbberTheme: 'CToolsModalThrobber',
      animation: 'fade',
      animationSpeed: 'fast',
      modalSize: {
        type: 'standard',
        width: 570,
        height: .6,
        addWidth: 0,
        addHeight: 0,
        // How much to remove from the inner content to make space for the
        // theming.
        contentRight: 25,
        contentBottom: 45
      },
      modalOptions: {
        opacity: .55
      }
    };

    var settings = {};
    $.extend(true, settings, defaults, Drupal.settings.CToolsModal, opts);

    Drupal.CTools.Modal.currentSettings = settings;

    Drupal.CTools.Modal.modal = $(Drupal.theme(settings.modalTheme));
    Drupal.CTools.Modal.modals.push(Drupal.CTools.Modal.modal);

    Drupal.CTools.Modal.modalId = modalId;
    Drupal.CTools.Modal.modalIds.push(modalId);

    $('span.modal-title', Drupal.CTools.Modal.modal).html(Drupal.CTools.Modal.currentSettings.loadingText);
    Drupal.CTools.Modal.modalContent(Drupal.CTools.Modal.modal, settings.modalOptions, settings.animation, settings.animationSpeed);
    $('#modalContent-' + modalId + ' .modal-content').html(Drupal.theme(settings.throbberTheme));

    Drupal.CTools.Modal.setActiveClass();
  };

  /**
   * Hide the modal
   */
  Drupal.CTools.Modal.dismiss = function() {
    if (Drupal.CTools.Modal.modal) {
      Drupal.CTools.Modal.unmodalContent(Drupal.CTools.Modal.modal);
    }
  };

  /**
   * Provide the HTML to create the modal dialog.
   */
  Drupal.theme.prototype.CToolsModalDialog = function () {
    // Because we want to be able to use multiple modals, we can't use ID's for
    // things like #wide-modal. Use classes instead.
    var id = Drupal.CTools.Modal.currentSettings.id && Drupal.CTools.Modal.currentSettings.id.length > 0 ? Drupal.CTools.Modal.currentSettings.id : '';
    var html = ''
    html += '  <div>'
    html += '    <div id="ctools-modal" class="modal-wrapper-inner">'
    html += '      <div class="ctools-modal-content modal ' + id + '">'
    html += '        <div class="modal-header">';
    html += '          <a class="close" href="#">';
    html +=              Drupal.CTools.Modal.currentSettings.closeImage;
    html += '          </a>';
    html += '          <span class="modal-title">&nbsp;</span>';
    html += '        </div>';
    html += '        <div class="modal-content">';
    html += '        </div>';
    html += '      </div>';
    html += '    </div>';
    html += '  </div>';

    return html;
  }

  /**
   * Provide the HTML to create the throbber.
   */
  Drupal.theme.prototype.CToolsModalThrobber = function () {
    var html = '';
    html += '  <div id="modal-throbber">';
    html += '    <div class="modal-throbber-wrapper">';
    html += Drupal.CTools.Modal.currentSettings.throbber;
    html += '    </div>';
    html += '  </div>';

    return html;
  };

  /**
   * Figure out what settings string to use to display a modal.
   */
  Drupal.CTools.Modal.getSettings = function (object) {
    var match = $(object).attr('class').match(/ctools-modal-(\S+)/);
    if (match) {
      return match[1];
    }
  }

  /**
   * Click function for modals that can be cached.
   */
  Drupal.CTools.Modal.clickAjaxCacheLink = function () {
    Drupal.CTools.Modal.show(Drupal.CTools.Modal.getSettings(this));
    return Drupal.CTools.AJAX.clickAJAXCacheLink.apply(this);
  };

  /**
   * Handler to prepare the modal for the response
   */
  Drupal.CTools.Modal.clickAjaxLink = function () {
    Drupal.CTools.Modal.show(Drupal.CTools.Modal.getSettings(this));
    return false;
  };

  /**
   * Submit responder to do an AJAX submit on all modal forms.
   */
  Drupal.CTools.Modal.submitAjaxForm = function(e) {
    var $form = $(this);
    var url = $form.attr('action');

    setTimeout(function() { Drupal.CTools.AJAX.ajaxSubmit($form, url); }, 1);
    return false;
  }

  /**
   * Bind links that will open modals to the appropriate function.
   */
  Drupal.behaviors.ZZCToolsModal = {
    attach: function(context) {
      // Bind links
      // Note that doing so in this order means that the two classes can be
      // used together safely.
      /*
       * @todo remimplement the warm caching feature
       $('a.ctools-use-modal-cache', context).once('ctools-use-modal', function() {
         $(this).click(Drupal.CTools.Modal.clickAjaxCacheLink);
         Drupal.CTools.AJAX.warmCache.apply(this);
       });
        */

      $('area.ctools-use-modal, a.ctools-use-modal', context).once('ctools-use-modal', function() {
        var $this = $(this);
        $this.click(Drupal.CTools.Modal.clickAjaxLink);
        // Create a drupal ajax object
        var element_settings = {};
        if ($this.attr('href')) {
          element_settings.url = $this.attr('href');
          element_settings.event = 'click';
          element_settings.progress = { type: 'throbber' };
        }
        var base = $this.attr('href');
        Drupal.ajax[base] = new Drupal.ajax(base, this, element_settings);
      });

      // Bind buttons
      $('input.ctools-use-modal, button.ctools-use-modal', context).once('ctools-use-modal', function() {
        var $this = $(this);
        $this.click(Drupal.CTools.Modal.clickAjaxLink);
        var button = this;
        var element_settings = {};

        // AJAX submits specified in this manner automatically submit to the
        // normal form action.
        element_settings.url = Drupal.CTools.Modal.findURL(this);
        element_settings.event = 'click';

        var base = $this.attr('id');
        Drupal.ajax[base] = new Drupal.ajax(base, this, element_settings);

        // Make sure changes to settings are reflected in the URL.
        $('.' + $(button).attr('id') + '-url').change(function() {
          Drupal.ajax[base].options.url = Drupal.CTools.Modal.findURL(button);
        });
      });

      // Bind our custom event to the form submit
      $('.modal-content form', context).once('ctools-use-modal', function() {
        var $this = $(this);
        var element_settings = {};

        element_settings.url = $this.attr('action');
        element_settings.event = 'submit';
        element_settings.progress = { 'type': 'throbber' }
        var base = $this.attr('id');

        Drupal.ajax[base] = new Drupal.ajax(base, this, element_settings);
        Drupal.ajax[base].form = $this;

        $('input[type=submit], button', this).click(function(event) {
          Drupal.ajax[base].element = this;
          this.form.clk = this;
          // An empty event means we were triggered via .click() and
          // in jquery 1.4 this won't trigger a submit.
          if (event.bubbles == undefined) {
            $(this.form).trigger('submit');
            return false;
          }
        });
      });

      // Bind a click handler to allow elements with the 'ctools-close-modal'
      // class to close the modal.
      $('.ctools-close-modal', context).once('ctools-close-modal')
        .click(function() {
          Drupal.CTools.Modal.dismiss();
          return false;
        });
      // We have to trigger the event here so we're sure it's the last thing
      // to be executed.
      $('body').trigger('loadStop');
    }
  };

  // The following are implementations of AJAX responder commands.

  /**
   * AJAX responder command to place HTML within the modal.
   */
  Drupal.CTools.Modal.modal_display = function(ajax, response, status) {
    var modalContentId = '#modalContent-' + Drupal.CTools.Modal.modalId;
    var modalContent = $(modalContentId);
    if (modalContent.length == 0) {
      Drupal.CTools.Modal.show(Drupal.CTools.Modal.getSettings(ajax.element));
    }
    modalContent.find('.modal-title').html(response.title);
    // Simulate an actual page load by scrolling to the top after adding the
    // content. This is helpful for allowing users to see error messages at the
    // top of a form, etc.
    modalContent.find('.modal-content').html(response.output).scrollTop(0);

    Drupal.attachBehaviors(modalContent);
  }

  /**
   * AJAX responder command to dismiss the modal.
   */
  Drupal.CTools.Modal.modal_dismiss = function(command) {
    Drupal.CTools.Modal.dismiss();
    $('link.ctools-temporary-css').remove();
  }

  /**
   * Display loading
   */
  //Drupal.CTools.AJAX.commands.modal_loading = function(command) {
  Drupal.CTools.Modal.modal_loading = function(command) {
    Drupal.CTools.Modal.modal_display({
      output: Drupal.theme(Drupal.CTools.Modal.currentSettings.throbberTheme),
      title: Drupal.CTools.Modal.currentSettings.loadingText
    });
  }

  /**
   * Find a URL for an AJAX button.
   *
   * The URL for this gadget will be composed of the values of items by
   * taking the ID of this item and adding -url and looking for that
   * class. They need to be in the form in order since we will
   * concat them all together using '/'.
   */
  Drupal.CTools.Modal.findURL = function(item) {
    var url = '';
    var url_class = '.' + $(item).attr('id') + '-url';
    $(url_class).each(
      function() {
        var $this = $(this);
        if (url && $this.val()) {
          url += '/';
        }
        url += $this.val();
      });
    return url;
  };

  /**
   * modalContent
   * @param content string to display in the content box
   * @param css obj of css attributes
   * @param animation (fadeIn, slideDown, show)
   * @param speed (valid animation speeds slow, medium, fast or # in ms)
   */
  Drupal.CTools.Modal.modalContent = function(content, css, animation, speed) {
    var modalId = Drupal.CTools.Modal.modalId;
    var modalContent = $('<div id="modalContent-' + modalId + '" class="modal-wrapper"/>').html(content);

    // Build our base attributes and allow them to be overriden
    css = jQuery.extend({
      position: 'fixed',
      left: '0px',
      margin: '0px',
      opacity: '.55'
    }, css);

    // Add opacity handling for IE.
    css.filter = 'alpha(opacity=' + (100 * css.opacity) + ')';
    content.hide();

    // Create our divs.
    $('body').append('<div id="modalBackdrop-' + modalId + '" class="modalBackdrop" style="display: none;"><div id="modalContent-' + modalId + '" class="modalContent" style="display:none; z-index: 1001; position: absolute;">' + $(content).html() + '</div></div>');
    var modalContent = $('#modalContent-' + modalId);

    // Bind the custom loadStop event to body to observe when content has been
    // loaded.
    $('body').live("loadStop", function() {
      // Focus the first input element inside the modal (if it's enabled)
      $('.modalContent form :input:visible:enabled:first').focus();
      // Apply the correct max-height to the scrollable area of the modal once
      // it's been loaded.
      if ($('.modalContent').length > 0) {
        Drupal.CTools.Modal.setModalContentMaxHeight();
      }
    });

    // Create our content div.
    $('#modalBackdrop-' + modalId).show();

    // Bind a click for closing the modalContent
    $('#modalContent-' + modalId + ' .close').bind('click', Drupal.CTools.Modal.modalContentClose);

    Drupal.CTools.Modal.bindEvents();

    $('#modalContent-' + modalId).focus();
    Drupal.CTools.Modal.setModalContentMaxHeight();
  };

  /**
   * Sets the max-height for the scrollable content of the modal.
   */
  Drupal.CTools.Modal.setModalContentMaxHeight = function()
  {
    var wrapper = jQuery(".modalContent");

    wrapper.each(function(modal) {
      var content = wrapper.find(".form-body");
      var header = wrapper.find(".modal-header");
      var footer = wrapper.find(".form-buttons");
      var messages = wrapper.find(".messages");

      var maxHeight = wrapper.outerHeight() - header.outerHeight() - footer.outerHeight();
      if (messages.length) {
        maxHeight -= messages.outerHeight(true);
      }

      content.css("maxHeight", maxHeight + "px");

      wrapper.show();
      wrapper.parent().show();
    });
  };

  /**
   * Handles keyboard button events, to close the active modal on escape.
   * @param event
   */
  Drupal.CTools.Modal.modalEventEscapeCloseHandler = function(event) {
    if (event.keyCode == 27) {
      Drupal.CTools.Modal.close();
      return false;
    }
  };

  /**
   * Handles events on elements and checks whether the element is inside or
   * outside the active modal. If inside, the event can continue to trigger.
   * @param event
   */
  Drupal.CTools.Modal.modalEventHandler = function(event) {
    target = null;
    // Mozilla.
    if (event) {
      target = event.target;
      // IE.
    } else {
      event = window.event;
      target = event.srcElement;
    }

    var modalId = Drupal.CTools.Modal.modalId;
    if ($(target).filter('*:visible').parents('#modalContent-' + modalId).size()) {
      // Allow the event only if target is a visible child node of the active
      // modal.
      return true;
    }

    return false;
  };

  /**
   * Closes the modals and prevents an event from continueing. (If any event
   * triggered the closing.)
   */
  Drupal.CTools.Modal.modalContentClose = function()
  {
    Drupal.CTools.Modal.close();
    return false;
  };

  /**
   * Closes the modal and backdrop completely.
   */
  Drupal.CTools.Modal.close = function() {
    var modalId = Drupal.CTools.Modal.modalId;

    // Unbind the events.
    Drupal.CTools.Modal.unbindEvents();

    $('body').css({'overflow-y':'scroll'});

    $(document).trigger('CToolsDetachBehaviors', $('#modalContent-' + modalId));

    // Remove the content
    $('#modalContent-' + modalId).remove();
    $('#modalBackdrop-' + modalId).remove();

    // Remove the modal from the modals array and set a new active modal.
    Drupal.CTools.Modal.modals.pop();
    Drupal.CTools.Modal.modalIds.pop();
    var modal_count = Drupal.CTools.Modal.modals.length;
    if (modal_count > 0) {
      Drupal.CTools.Modal.modal = Drupal.CTools.Modal.modals[modal_count-1]
      Drupal.CTools.Modal.modalId = Drupal.CTools.Modal.modalIds[modal_count-1];
    } else {
      Drupal.CTools.Modal.modal = null;
      Drupal.CTools.Modal.modalId = 0;
    }

    Drupal.CTools.Modal.setActiveClass();
  };

  /**
   * unmodalContent
   * @param content (The jQuery object to remove)
   * @param animation (fadeOut, slideUp, show)
   * @param speed (valid animation speeds slow, medium, fast or # in ms)
   */
  Drupal.CTools.Modal.unmodalContent = function(content, animation, speed)
  {
    // Use our custom close method. We keep unmodalContent for legacy purposes.
    Drupal.CTools.Modal.close();
  };

  /**
   * Sets an active-modal class on the last opened modal.
   */
  Drupal.CTools.Modal.setActiveClass = function()
  {
    jQuery.each(Drupal.CTools.Modal.modalIds, function(index, modalId) {
      if (modalId == Drupal.CTools.Modal.modalId) {
        $('#modalContent-' + modalId).addClass('active-modal');
        $('#modalBackdrop-' + modalId).addClass('active-backdrop');
      } else {
        $('#modalContent-' + modalId).removeClass('active-modal');
        $('#modalBackdrop-' + modalId).removeClass('active-backdrop');
      }
    });
  };

  /**
   * Binds all necessary events when the first modal is opened.
   */
  Drupal.CTools.Modal.bindEvents = function()
  {
    if (Drupal.CTools.Modal.modals.length === 1) {
      $('body').bind('focus', Drupal.CTools.Modal.modalEventHandler);
      $(document).bind('keydown', Drupal.CTools.Modal.modalEventEscapeCloseHandler);
      $(window).bind('resize', Drupal.CTools.Modal.setModalContentMaxHeight);
    }
  };

  /**
   * Unbinds all bound events when the last modal (first opened) is closed.
   */
  Drupal.CTools.Modal.unbindEvents = function()
  {
    if (Drupal.CTools.Modal.modals.length === 1) {
      $('body').unbind('focus', Drupal.CTools.Modal.modalEventHandler);
      $(document).unbind('keydown', Drupal.CTools.Modal.modalEventEscapeCloseHandler);
      $(window).unbind('resize', Drupal.CTools.Modal.setModalContentMaxHeight);
    }
  };


$(function() {
  Drupal.ajax.prototype.commands.modal_display = Drupal.CTools.Modal.modal_display;
  Drupal.ajax.prototype.commands.modal_dismiss = Drupal.CTools.Modal.modal_dismiss;
});

})(jQuery);
