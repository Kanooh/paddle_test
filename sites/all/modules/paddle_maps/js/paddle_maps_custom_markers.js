(function ($) {

    Drupal.behaviors.paddle_maps_custom_markers = {
        attach: function (context, settings) {
            var $settings = Drupal.settings.custom_markers;

            $('table.marker-config tbody tr').each(function (cnt) {
                if ($(this).find('input.marker_is_default').attr('checked')) {
                    $(this).find('td.marker-tid-selection').hide();
                }

                var term_data = $settings[cnt]['terms'];
                var terms_selected = $settings[cnt]['selected'];
                $('#magicsuggest_' + cnt).magicSuggest({
                    placeholder: Drupal.t('Select term'),
                    allowFreeEntries: false,
                    value: terms_selected,
                    data: term_data,
                    noSuggestionText: Drupal.t('No matching terms'),
                    selectionPosition: 'bottom',
                    selectionStacked: false,
                    selectionRenderer: function (data) {
                      if( typeof data.extra_info === 'undefined' || data.extra_info.length < 1){
                        return data.name;
                      }else{
                        return data.name + ' (' + data.extra_info + ')';
                      }
                    }
                });
            });

            $('table.marker-config input.marker_is_default').click(function () {
                var the_delta = $(this).attr('delta');

                if ($(this).attr('checked')) {
                    $('table.marker-config tbody tr').each(function () {
                        var delta = $(this).attr('marker-row');

                        if (delta == the_delta) {
                            $(this).find('td.marker-tid-selection').hide();
                        } else {
                            $(this).find('td.marker-tid-selection').show();
                            $(this).find('input.marker_is_default').attr('checked', false);
                        }
                    });
                } else {
                    $('table.marker-config tbody tr[marker-row="' + the_delta + '"]').find('td.marker-tid-selection').show();
                }
            });
        }
    };

    Drupal.behaviors.form_submit_processor = {
        attach: function (context, settings) {
            $("form#paddle-maps-configuration-form").submit(function (e) {
                $('table.marker-config tbody tr').each(function () {
                    var delta = $(this).attr('marker-row');
                    var scope = $(this);
                    var tids = [];

                    scope.find('.ms-sel-ctn input[type="hidden"]').each(function () {
                        tids.push($(this).val());
                    });
                    scope.find('input#selected_tids_' + delta).val(tids.join(','));
                });
            });
        }
    };

})(jQuery);