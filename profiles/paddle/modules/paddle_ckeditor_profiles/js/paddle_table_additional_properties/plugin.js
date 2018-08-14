/**
 * @file
 * Adds some additional properties to the table like zebra striping, hover effect and table borders.
 */
(function ($) {
  CKEDITOR.plugins.add('paddle_table_additional_properties', {
    lang: 'en,nl',

    init: function (editor) {
      CKEDITOR.on('dialogDefinition', function(ev) {
        // Take the dialog name and its definition from the event data.
        var dialog_name = ev.data.name,
            dialog_definition = ev.data.definition,
            lang = editor.lang.paddle_table_additional_properties,
            border_options = [
              [lang.defaultStyle, 'default-style'],
              [lang.noBorder, 'no-border'],
              [lang.horizontalBorder, 'horizontal-border'],
              [lang.verticalBorder, 'vertical-border'],
              [lang.allBorders, 'full-border']
            ],
            info_tab,
            advTab,
            classField;

        if (dialog_name == 'table' || dialog_name == 'tableProperties') {
          info_tab = dialog_definition.getContents('info');

          // Add the additional fields to the "Table Properties" tab.
          // We check if the first one is not defined because this event
          // gets called twice by CKEditor, causing duplicate fields.
          if (info_tab.get('tableBorders') == undefined) {
            info_tab.add({
              type: 'select',
              default: 'horizontal-border',
              label: lang.tableBorders,
              id: 'tableBorders',
              items: border_options,

              setup: function (selectedTable) {
                var me = this,
                    class_added;

                // Check if the existing table has any of the border classes,
                // and set the field initial value accordingly.
                $.each(border_options, function (index, value) {
                  if (selectedTable.hasClass(value[1])) {
                    me.setValue(value[1]);
                    class_added = true;
                  }
                });

                // If the table has no classes, we are facing an old table.
                // Set the default style class to avoid changing client styles.
                if (!class_added) {
                  me.setValue('default-style');
                }
              }
            });

            info_tab.add({
              type: 'checkbox',
              label: lang.hoverEffect,
              id: 'hoverEffect',
              default: true,

              setup: function (selectedTable) {
                this.setValue(!selectedTable.hasClass('no-table-hover'));
              }
            });

            info_tab.add({
              type: 'checkbox',
              label: lang.zebraStriping,
              id: 'zebraStriping',

              setup: function (selectedTable) {
                this.setValue(selectedTable.hasClass('zebra-striping'));
              }
            });

            advTab = dialog_definition.getContents('advanced');
            classField = advTab && advTab.get('advCSSClasses');

            if (classField) {
              // Override the current setup method of the classes field in order
              // to remove our additional classes from the field.
              // @see CKEditor showborders/plugin.js
              classField.setup = CKEDITOR.tools.override(classField.setup, function(originalSetup) {
                return function() {
                  var value;

                  // Call the original setup method.
                  originalSetup.apply(this, arguments);

                  // Remove all our classes from the field value.
                  value = this.getValue().replace('no-table-hover', '').replace('zebra-striping', '');
                  $.each(border_options, function (index, option_value) {
                    value = value.replace(option_value[1], '');
                  });
                  this.setValue(CKEDITOR.tools.trim(value));
                };
              });

              // Override also the commit method in order to apply our fields
              // settings to the table element.
              classField.commit = CKEDITOR.tools.override(classField.commit, function(originalCommit) {
                return function( data, element ) {
                  var dialog = this.getDialog(),
                      border = dialog.getContentElement('info', 'tableBorders'),
                      hover = dialog.getContentElement('info', 'hoverEffect'),
                      zebra = dialog.getContentElement('info', 'zebraStriping');

                  originalCommit.apply( this, arguments );
                  element.addClass(border.getValue());

                  if (!hover.getValue()) {
                    element.addClass('no-table-hover');
                  }

                  if (zebra.getValue()) {
                    element.addClass('zebra-striping');
                  }
                };
              });
            }
          }
        }
      });
    }
  });

})(jQuery);
