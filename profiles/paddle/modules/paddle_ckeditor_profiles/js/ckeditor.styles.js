/*
Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/*
 * This file is used/requested by the 'Styles' button.
 * The 'Styles' button is not enabled by default in DrupalFull and DrupalFiltered toolbars.
 */
if(typeof(CKEDITOR) !== 'undefined') {
  CKEDITOR.addStylesSet('drupal',
    [
        /* Block Styles */

        // These styles are already available in the "Format" drop-down list, so they are
        // not needed here by default. You may enable them to avoid placing the
        // "Format" drop-down list in the toolbar, maintaining the same features.

      {
        name : 'Normal',
        element : 'p'
      },
      {
        name : 'Heading 2',
        element : 'h2'
      },
      {
        name : 'Heading 3',
        element : 'h3'
      },
      {
        name : 'Heading 4',
        element : 'h4'
      },
      {
        name : 'Heading 5',
        element : 'h5'
      }
    ]
  );

  CKEDITOR.on('instanceReady', function(ev) {
    ev.editor.on('maximize', function(evt) {
      var body = CKEDITOR.document.getBody(),
          class_name = 'ckeditor-fullscreen';

      if (evt.data === CKEDITOR.TRISTATE_OFF) {
        body.removeClass(class_name);
      }
      else if (evt.data === CKEDITOR.TRISTATE_ON) {
        body.addClass(class_name);
      }
    });
  });
}
