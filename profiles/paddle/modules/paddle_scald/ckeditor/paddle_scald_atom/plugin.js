(function ($) {

  CKEDITOR.plugins.add('paddle_scald_atom', {
    lang: 'en',

    init: function (editor) {
      editor.addCommand('openScaldLibraryModal', {
        exec: function(editor, data) {
          var origin = editor.name;
          var callback = 'paddle_scald_ckeditor_insert';

          var settings = {
            "url": Drupal.settings.basePath + 'admin/content_manager/assets/nojs/library?origin=' + origin + '&callback=' + callback,
            "event": 'open_scald_ckeditor_modal',
            "progress": {
              "type": 'throbber'
            }
          };
          var base = settings.url;
          var element = $('body');

          Drupal.ajax[base] = new Drupal.ajax(base, element, settings);
          Drupal.CTools.Modal.show('medium-modal');

          element.trigger('open_scald_ckeditor_modal');
        }
      });

      editor.ui.addButton && editor.ui.addButton('OpenScaldLibraryModal', {
        label: editor.lang.paddle_scald_atom.open_library,
        command: 'openScaldLibraryModal',
        icon: this.path + 'icons/icon.gif'
      });
    }

  });
})(jQuery);
