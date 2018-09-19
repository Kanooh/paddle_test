/**
 * @file
 * Adds a checkbox to the Image Properties dialog to allow to apply Lightbox to the image.
 *
 */
(function ($) {
  Drupal.behaviors.paddleScaldImageColorboxEditor = {
    attach: function (context, settings) {
      if (typeof(CKEDITOR) !== 'undefined') {
        // Add a Lightbox checkbox.
        CKEDITOR.on('dialogDefinition', function(ev) {
          // Take the dialog name and its definition from the event data.
          var dialog_name = ev.data.name,
              dialog_definition = ev.data.definition,
              IMAGE = 1,
              info_tab;

          if (dialog_name == 'paddle_scald_image') {
            info_tab = dialog_definition.getContents('info');
            // Add a new field to the "Info" tab page.
            if (info_tab.get('lightboxImage') == undefined) {
              info_tab.add({
                type: 'checkbox',
                label: Drupal.t('Use Lightbox'),
                id: 'lightboxImage',
                setup: function(type, element) {
                  if (type == IMAGE && element.hasAttribute('class') && element.getAttribute('class').indexOf('colorbox-image') !== -1) {
                    this.setValue(true);
                  }
                },
                onChange: function() {
                  var dialog = this.getDialog();

                  this.commit(IMAGE, dialog.imageElement);
                  dialog.getContentElement('advanced', 'txtGenClass').setup(IMAGE, dialog.imageElement);
                },
                commit: function(type, element) {
                  if (type == IMAGE) {
                    if (this.getValue()) {
                      element.addClass('colorbox-image');
                    }
                    else {
                      element.removeClass('colorbox-image');
                    }
                  }
                }
              });
            }
          }
        });
      }
    }
  };

})(jQuery);
