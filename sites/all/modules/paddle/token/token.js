
(function ($) {

Drupal.behaviors.tokenTree = {
  attach: function (context, settings) {
    $('table.token-tree', context).once('token-tree', function () {
      $(this).treeTable();
    });
  }
};

Drupal.behaviors.tokenDialog = {
  attach: function (context, settings) {
    $('a.token-dialog', context).once('token-dialog').click(function() {
      var url = $(this).attr('href');
      var dialog = $('<div style="display: none" class="loading">' + Drupal.t('Loading token browser...') + '</div>').appendTo('body');

      if (Drupal.settings.tokenFocusedField && Drupal.settings.tokenFocusedField.tokenHasFocus) {
        Drupal.settings.tokenFocusedField.tokenDialogFocus = true;
      }

      // Emulate the AJAX data sent normally so that we get the same theme.
      var data = {};
      data['ajax_page_state[theme]'] = Drupal.settings.ajaxPageState.theme;
      data['ajax_page_state[theme_token]'] = Drupal.settings.ajaxPageState.theme_token;

      dialog.dialog({
        title: $(this).attr('title') || Drupal.t('Available tokens'),
        width: 700,
        close: function(event, ui) {
          delete Drupal.settings.tokenFocusedField.tokenDialogFocus;
          dialog.remove();
        }
      });
      // Load the token tree using AJAX.
      dialog.load(
        url,
        data,
        function (responseText, textStatus, XMLHttpRequest) {
          dialog.removeClass('loading');
        }
      );
      // Prevent browser from following the link.
      return false;
    });
  }
}

Drupal.behaviors.tokenInsert = {
  attach: function (context, settings) {
    // Keep track of the last active text field.
    $('textarea, input[type=text]', context).once('token-insert', function() {
      $(this).focus(tokenSetActive).blur(tokenRemoveActive);
    });

    // Replace token keys with clickable alternative.
    $('.token-click-insert .token-key', context).once('token-click-insert', function(e) {
      var newThis = $('<a href="javascript:void(0);" title="' + Drupal.t('Insert this token into your form') + '">' + $(this).html() + '</a>').click(insertIntoActiveEditor);
      $(this).html(newThis);
    });

    function tokenSetActive() {
      Drupal.settings.tokenFocusedField = this;
      this.tokenHasFocus = true;
    }

    function tokenRemoveActive() {
      if (Drupal.settings.tokenFocusedField == this) {
        var thisTextField = this;
        setTimeout(function() {
          thisTextField.tokenHasFocus = false;
        }, 1000);
      }
    }

    function insertIntoActiveEditor() {
      var content = this.text;

      // Always work in normal text areas that currently have focus.
      if (Drupal.settings.tokenFocusedField && (Drupal.settings.tokenFocusedField.tokenDialogFocus || Drupal.settings.tokenFocusedField.tokenHasFocus)) {
        insertAtCursor(Drupal.settings.tokenFocusedField, this.text);
      }
      // Direct tinyMCE support.
      else if (typeof(tinyMCE) != 'undefined' && tinyMCE.activeEditor) {
        tinyMCE.activeEditor.execCommand('mceInsertContent', false, content);
      }
      // Direct CKEditor support. Only works if the field currently has focus,
      // which is unusual since the dialog is open.
      else if (typeof(CKEDITOR) != 'undefined' && CKEDITOR.currentInstance) {
        CKEDITOR.currentInstance.insertHtml(content);
      }
      // WYSIWYG support, should work in all editors if available.
      else if (Drupal.wysiwyg && Drupal.wysiwyg.activeId) {
        Drupal.wysiwyg.instances[Drupal.wysiwyg.activeId].insert(content)
      }
      // CKeditor module support.
      else if (typeof(CKEDITOR) != 'undefined' && typeof(Drupal.ckeditorActiveId) != 'undefined') {
        CKEDITOR.instances[Drupal.ckeditorActiveId].insertHtml(content);
      }
      else if (Drupal.settings.tokenFocusedField) {
        insertAtCursor(Drupal.settings.tokenFocusedField, content);
      }
      else {
        alert(Drupal.t('First click a text field to insert your tokens into.'));
      }

      return false;
    }

    function insertAtCursor(editor, content) {
      // Record the current scroll position.
      var scroll = editor.scrollTop;

      // IE support.
      if (document.selection) {
        editor.focus();
        sel = document.selection.createRange();
        sel.text = content;
      }

      // Mozilla/Firefox/Netscape 7+ support.
      else if (editor.selectionStart || editor.selectionStart == '0') {
        var startPos = editor.selectionStart;
        var endPos = editor.selectionEnd;
        editor.value = editor.value.substring(0, startPos) + content + editor.value.substring(endPos, editor.value.length);
      }

      // Fallback, just add to the end of the content.
      else {
        editor.value += content;
      }

      // Ensure the textarea does not unexpectedly scroll.
      editor.scrollTop = scroll;
    }
  }
};

})(jQuery);
