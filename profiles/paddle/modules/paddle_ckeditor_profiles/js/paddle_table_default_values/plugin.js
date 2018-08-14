/**
 * @file
 * Set the default values of the table properties.
 *
 */
(function ($) {

  CKEDITOR.plugins.add('paddle_table_default_values', {
    init: function () {
      CKEDITOR.on('dialogDefinition', function(ev) {
        // Take the dialog name and its definition from the event data.
        var dialog_name = ev.data.name,
            dialog_definition = ev.data.definition;

        // Check if the definition is from the dialog we're
        // interested on (the "Table" dialog).
        if (dialog_name == 'table' || dialog_name == 'tableProperties') {
          // Get a reference to the "Table Info" tab.
          var info_tab = dialog_definition.getContents('info'),
              table_border = info_tab.get('txtBorder'),
              table_width = info_tab.get('txtWidth'),
              cell_padding = info_tab.get('txtCellPad');

          // Set the default border width to 0.
          table_border['default'] = '0';

          // Set the default table width to 100%.
          table_width['default'] = '100%';

          // Remove the cell spacing setting completely.
          info_tab.remove('txtCellSpace');

          // Set the default cell padding to 9px.
          cell_padding['default'] = '9';
        }
      });
    }
  });

})(jQuery);
