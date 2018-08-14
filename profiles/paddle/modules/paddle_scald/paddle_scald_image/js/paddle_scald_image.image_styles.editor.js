/**
 * @file
 * Adds a select to the Image Properties dialog to allow selection of an image style.
 */
(function ($) {
  Drupal.behaviors.paddleScaldImageImageStylesEditor = {
    attach: function () {
      if (typeof CKEDITOR !== 'undefined') {
        $('body').once('scald-image-image-styles', function () {
          CKEDITOR.on('dialogDefinition', function (ev) {
            var dialogName = ev.data.name,
                dialogDefinition = ev.data.definition,
                imageStyles = [],
                IMAGE = 1,
                infoTab;

            if (dialogName == 'paddle_scald_image') {
              infoTab = dialogDefinition.getContents('info');

              // Prepare the list of image styles to be used for the element.
              $.each(Drupal.settings.paddle_scald_image.image_styles, function (index, value) {
                imageStyles.push([value, index]);
              });

              infoTab.add({
                type: 'select',
                label: Drupal.t('Image style'),
                id: 'imageStyles',
                'default': '',
                items: imageStyles,
                setup: function (type, element) {
                  var haystack = Drupal.settings.paddle_scald_image.image_styles,
                      needle;

                  // Set the value in the interface.
                  if (type == IMAGE && element.hasAttribute('data-image-style')) {
                    needle = element.getAttribute('data-image-style');
                    haystack.hasOwnProperty(needle) && this.setValue(needle);
                  }
                },
                onChange: function () {
                  var dialog = this.getDialog(),
                      updatedSrc;

                  // Commit the changes to the data attribute.
                  this.commit(IMAGE, dialog.imageElement);

                  // Retrieve now the eventually updated src.
                  updatedSrc = this._retrieveUrl(dialog.imageElement);
                  if (updatedSrc) {
                    // Avoid setting the size automatically.
                    dialog.dontResetSize = true;
                    dialog
                        .getContentElement('info', 'txtUrl')
                        .setValue(updatedSrc);
                  }
                },
                commit: function (type, element) {
                  var value = this.getValue();

                  if (type == IMAGE) {
                    if (value) {
                      element.setAttribute('data-image-style', value);
                    } else {
                      element.removeAttribute('data-image-style');
                    }
                  }
                },
                _retrieveUrl: function (element) {
                  var fileSchemes = Drupal.settings.paddle_scald_image.file_schemes,
                      scheme = element.getAttribute('data-file-scheme'),
                      style = element.getAttribute('data-image-style'),
                      matches,
                      re,
                      src;

                  if (scheme && fileSchemes.hasOwnProperty(scheme)) {
                    // Extract the parts of the url. At index 1 we have the
                    // file position without scheme.
                    re = '^' + escapeRegExp(fileSchemes[scheme]) +
                        '(?:styles\/[^\/]+\/' + escapeRegExp(scheme) + '\/)?' +
                        '([^\?]+)';
                    matches = element.getAttribute('src').match(re);

                    if (matches && matches[1]) {
                      // Prepare the updated src. Start with the file scheme path.
                      src = fileSchemes[scheme];

                      // If a style was selected, append its path.
                      if (style) {
                        src += 'styles/' + style + '/' + scheme + '/';
                      }

                      // Add the file name.
                      src += matches[1];

                      // Append a cache invalidator like manualcrop module does.
                      src += '?c=' + new Date().getTime();

                      return src;
                    }

                    return false;
                  }
                }
              });
            }
          });
        });
      }
    }
  };

  /**
   * Escape safely a string to be used in a regular expression
   *
   * @see https://developer.mozilla.org/en/docs/Web/JavaScript/Guide/Regular_Expressions
   */
  function escapeRegExp(string) {
    return string.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
  }
})(jQuery);