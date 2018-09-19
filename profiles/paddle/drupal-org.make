api = 2
core = 7.x

; The active_tags.js file is being overridden within paddle_content_manager.
; Any updates to this file will be ignored.
projects[active_tags][download][type] = git
projects[active_tags][download][revision] = 7974298dd17e11468568f62eae2b6927008ac149
projects[active_tags][download][branch] = 7.x-2.x
projects[active_tags][subdir] = paddle

; Several problems on element validation: retheming already added tags,
; processing tags in the textfield.
projects[active_tags][patch][1229532] = https://www.drupal.org/files/issues/1229532-17.patch

projects[better_exposed_filters][type] = module
projects[better_exposed_filters][download][type] = git
projects[better_exposed_filters][download][revision] = 9f3231c15538c3063ea7d0e582b0bb1296be8803
projects[better_exposed_filters][download][branch] = 7.x-3.x
projects[better_exposed_filters][subdir] = paddle

projects[better_formats][download][type] = git
projects[better_formats][download][tag] = 7.x-1.0-beta1
projects[better_formats][subdir] = paddle
projects[better_formats][patch][2845424] = https://www.drupal.org/files/issues/fapi_support-2845424-5.patch

; A recent ref is pinned because it contains a fix we need:
; Issue #1991076: Select link from autocomplete list using keyboard.
; @see https://www.drupal.org/node/1991076
projects[ckeditor_link][download][type] = git
projects[ckeditor_link][download][revision] = 862bef80dcaa1b0fb506c8d3aff4e64509838775
projects[ckeditor_link][download][branch] = 7.x-2.x
projects[ckeditor_link][subdir] = paddle

projects[node_clone][download][type] = git
projects[node_clone][download][tag] = 7.x-1.0
projects[node_clone][subdir] = paddle

projects[content_lock][download][type] = git
projects[content_lock][download][tag] = 7.x-2.2
projects[content_lock][subdir] = paddle

; After creating a panel it is possible to navigate away from the IPE panels screen without saving your page
projects[content_lock][patch][2817851] = https://www.drupal.org/files/issues/2817851-fix-modal-form-2.1.patch

projects[ctools][download][type] = git
projects[ctools][download][tag] = 7.x-1.12
projects[ctools][subdir] = paddle

; Align ctools_css_flush_caches() with how Drupal core handles its CSS directory.
projects[ctools][patch][2275011] = https://www.drupal.org/files/issues/keep_ctools_generated_css_files_longer-2275011.patch

; Ajax + Allow settings: Allowed settings lost on ajax (exposed forms/pager).
projects[ctools][patch][1910608] = https://www.drupal.org/files/issues/views_content-ajax-1910608-51.patch

; Node View Task Handler Doesn't Include a Canonical URL.
projects[ctools][patch][1444598] = https://www.drupal.org/files/issues/ctools_canonical_link_node_view-1444598-6.patch

projects[date][download][type] = git
projects[date][download][tag] = 7.x-2.10
projects[date][subdir] = paddle
projects[date][patch][2889636] = https://www.drupal.org/files/issues/date-2889636-7.patch
projects[date][patch][2843367] = https://www.drupal.org/files/issues/2843367-php71-string-offset-26.patch
projects[date][patch][2601110] = https://www.drupal.org/files/issues/2018-05-09/date-n2601110-13.patch

projects[diff][download][type] = git
projects[diff][download][tag] = 7.x-3.3
projects[diff][subdir] = paddle

; A recent version of Elysia Cron is pinned since the latest release is ancient.
projects[elysia_cron][download][type] = git
projects[elysia_cron][download][tag] = 7.x-2.4
projects[elysia_cron][subdir] = paddle

projects[entity][download][type] = git
projects[entity][download][tag] = 7.x-1.7
projects[entity][subdir] = paddle

; Only flush Entity Cache tables that Entity API created itself.
projects[entity][patch][2455361] = https://www.drupal.org/files/issues/entity-dont_flush_non_existing_entity_cache_tables-2455361-1.patch

; Use entity_metadata_wrapper with revisions.
projects[entity][patch][1788568] = https://www.drupal.org/files/issues/entity-1788568-30-entity_metadata_wrapper_revisions.patch

; Return Null if checkbox is not checked instead of exception.
projects[entity][patch][1596594] = https://www.drupal.org/files/issues/entity-on-exception-return-null-1596594-49.patch

projects[entitycache][download][type] = git
projects[entitycache][download][tag] = 7.x-1.5
projects[entitycache][subdir] = paddle

projects[entityreference][download][type] = git
projects[entityreference][download][tag] = 7.x-1.5
projects[entityreference][subdir] = paddle

projects[expire][download][type] = git
projects[expire][download][tag] = 7.x-2.0-rc3
projects[expire][subdir] = paddle

projects[facetapi][download][type] = git
projects[facetapi][download][tag] = 7.x-1.5
projects[facetapi][subdir] = paddle

; Regression: 'Show more/fewer' links are no longer translatable.
projects[facetapi][patch][2311585] = https://www.drupal.org/files/issues/2311585-3-facetapi-7.x-1.x-translate_more_link.patch

; Don't forget selected filter values, even if that filter alias contain a single value filter alias.
projects[facetapi][patch][2899295] = https://www.drupal.org/files/issues/filter_active_values-2899295-2.patch

projects[flexslider][download][type] = git
projects[flexslider][download][tag] = 7.x-2.0-alpha3
projects[flexslider][subdir] = paddle

projects[features][download][type] = git
projects[features][download][tag] = 7.x-2.10
projects[features][subdir] = paddle
projects[features][patch][2855810] = https://www.drupal.org/files/issues/variable_to_disable_content_types-2855810-4.patch

projects[field_instance_sync][download][type] = git
projects[field_instance_sync][download][revision] = 6137cb2
projects[field_instance_sync][download][branch] = 7.x-1.x
projects[field_instance_sync][subdir] = paddle

projects[gmap][download][type] = git
projects[gmap][download][tag] = 7.x-2.11
projects[gmap][subdir] = paddle

; Gmap collapsehack don't work properly with fieldset.
projects[gmap][patch][2757953] = https://www.drupal.org/files/issues/gmap-collapsehack-fix-2456843-1_0.patch

projects[geocoder][download][type] = git
projects[geocoder][download][tag] = 7.x-1.3
projects[geocoder][subdir] = paddle
; Do not send a request to the Geocoder API if there is no key or client defined.
projects[geocoder][patch][2954088] = https://www.drupal.org/files/issues/2018-03-18/geocoder-google_request_without_key-2954088-7.patch
; Add a killswitch to not geocode during big migrations.
projects[geocoder][patch][2023505] = https://www.drupal.org/files/issues/2023505-18.patch

projects[geofield][download][type] = git
projects[geofield][download][tag] = 7.x-2.3
projects[geofield][subdir] = paddle

; Google maps requires API keys for sites going live after 22/6/2016
projects[geofield][patch][2757953] = https://www.drupal.org/files/issues/geofield-google-api-key-2757953-42.patch

projects[geophp][download][type] = git
projects[geophp][download][tag] = 7.x-1.7
projects[geophp][subdir] = paddle

projects[honeypot][download][type] = git
projects[honeypot][download][tag] = 7.x-1.21
projects[honeypot][subdir] = paddle

projects[htmlpurifier][download][type] = git
projects[htmlpurifier][download][revision] = e29f1cb
projects[htmlpurifier][download][branch] = 7.x-1.x
projects[htmlpurifier][subdir] = paddle

projects[i18n][download][type] = git
projects[i18n][download][revision] = 3777b86
projects[i18n][download][branch] = 7.x-1.x
projects[i18n][subdir] = paddle

projects[i18n][patch][1107494] = https://www.drupal.org/files/issues/respect_language_display_setting-1107494-15.patch
; Enabling i18n modules fail in install profile.
projects[i18n][patch][1681414] = https://www.drupal.org/files/issues/1681414-29-i18n-fatal_error_installlll.patch
; Taxonomy autocomplete widget should only match terms that match the content language.
projects[i18n][patch][2506981] = https://www.drupal.org/files/issues/i18n_taxonomy-handle_language_autocomplet_terms-2506981-10.patch

projects[ife][download][type] = git
projects[ife][download][tag] = 7.x-2.0-alpha2
projects[ife][subdir] = paddle

projects[image_resize_filter][download][type] = git
projects[image_resize_filter][download][tag] = 7.x-1.16
projects[image_resize_filter][subdir] = paddle

projects[jquery_colorpicker][download][type] = git
projects[jquery_colorpicker][download][tag] = 7.x-1.0-rc2
projects[jquery_colorpicker][subdir] = paddle

projects[l10n_update][version] = 2.1
projects[l10n_update][subdir] = paddle

projects[libraries][download][type] = git
projects[libraries][download][tag] = 7.x-2.2
projects[libraries][subdir] = paddle

projects[login_destination][download][type] = git
projects[login_destination][download][tag] = 7.x-1.4
projects[login_destination][subdir] = paddle

projects[logintoboggan][download][type] = git
projects[logintoboggan][download][tag] = 7.x-1.5
projects[logintoboggan][subdir] = paddle

projects[manualcrop][download][type] = git
projects[manualcrop][download][tag] = 7.x-1.5
projects[manualcrop][subdir] = paddle

; Make it possible to enable "default automatic crop".
projects[manualcrop][patch][2145939] = https://www.drupal.org/files/issues/manualcrop--automatic_default_crop--2145939-9_0.patch

projects[mediaelement][download][type] = git
projects[mediaelement][download][tag] = 7.x-1.2
projects[mediaelement][subdir] = paddle

; Support the newest library.
projects[mediaelement][patch][2863615] = https://www.drupal.org/files/issues/7.x-update-mediaelement-library-2863615.patch

projects[memcache][download][type] = git
projects[memcache][download][branch] = 7.x-1.6
projects[memcache][subdir] = paddle

projects[message][download][type] = git
projects[message][download][tag] = 7.x-1.12
projects[message][subdir] = paddle

projects[message_notify][download][type] = git
projects[message_notify][download][revision] = 1fbea08093e16231ca313f0e6ebcf454316f3610
projects[message_notify][download][branch] = 7.x-2.x
projects[message_notify][subdir] = paddle

; HTML entities in email subject.
projects[message_notify][patch][2386273] = https://www.drupal.org/files/issues/message_notify-html_entities_decode-2386273-4.patch

projects[panelizer][download][type] = git
projects[panelizer][download][revision] = 0166dcb8d6c830f2c38fb8c24f7c383b0e8859dd
projects[panelizer][download][branch] = 7.x-3.x
projects[panelizer][subdir] = paddle

; Simpletest notices when upgrading ctools / panels / panelizer.
projects[panelizer][patch][2454771] = https://www.drupal.org/files/issues/panelizer-simpletest_notices-2454771-1.patch

projects[panels][download][type] = git
projects[panels][download][tag] = 7.x-3.9
projects[panels][subdir] = paddle
projects[panels][patch][2923035] = https://www.drupal.org/files/issues/ipe_change_layout_allowed_layouts-2923035-2.patch

projects[pathauto][download][type] = git
projects[pathauto][download][tag] = 7.x-1.2
projects[pathauto][subdir] = paddle

projects[pathauto_persist][download][type] = git
projects[pathauto_persist][download][tag] = 7.x-1.4
projects[pathauto_persist][subdir] = paddle

projects[plupload][download][type] = git
projects[plupload][download][tag] = 7.x-1.7
projects[plupload][subdir] = paddle

projects[pm_existing_pages][download][type] = git
projects[pm_existing_pages][download][tag] = 7.x-1.4
projects[pm_existing_pages][subdir] = paddle

projects[purge][download][type] = git
projects[purge][download][tag] = 7.x-1.7
projects[purge][subdir] = paddle

projects[override_node_options][download][type] = git
projects[override_node_options][download][tag] = 7.x-1.12

projects[scald][download][type] = git
projects[scald][download][tag] = 7.x-1.6
projects[scald][subdir] = paddle

projects[scheduler][download][type] = git
projects[scheduler][download][tag] = 7.x-1.5
projects[scheduler][subdir] = paddle

projects[token][download][type] = git
projects[token][download][branch] = 7.x-1.7
projects[token][subdir] = paddle

projects[token][patch][2235581] = https://www.drupal.org/files/issues/token_wysiwyg-2235581_0.patch

projects[uuid][download][type] = git
projects[uuid][download][tag] = 7.x-1.0-alpha5
projects[uuid][subdir] = paddle

projects[variable][download][type] = git
projects[variable][download][tag] = 7.x-2.5
projects[variable][subdir] = paddle

projects[views_bulk_operations][download][type] = git
projects[views_bulk_operations][download][tag] = 7.x-3.3
projects[views_bulk_operations][subdir] = paddle

projects[paddle_admin_theme][type] = theme
projects[paddle_admin_theme][download][type] = git
projects[paddle_admin_theme][download][branch] = "7.x-1.x"
projects[paddle_admin_theme][download][url] = http://git.drupal.org/project/paddle_admin_theme.git

projects[paddle_theme][type] = theme
projects[paddle_theme][download][type] = git
projects[paddle_theme][download][branch] = "7.x-1.x"
projects[paddle_theme][download][url] = http://git.drupal.org/project/paddle_theme.git

projects[paddle_themer][type] = module
projects[paddle_themer][download][type] = git
projects[paddle_themer][download][branch] = "7.x-1.x"
projects[paddle_themer][download][url] = http://git.drupal.org/project/paddle_themer.git
projects[paddle_themer][subdir] = paddle

projects[paddle_style][type] = module
projects[paddle_style][download][type] = git
projects[paddle_style][download][branch] = "7.x-1.x"
projects[paddle_style][download][url] = http://git.drupal.org/project/paddle_style.git
projects[paddle_style][subdir] = paddle

projects[paddle_color_palettes][type] = module
projects[paddle_color_palettes][download][type] = git
projects[paddle_color_palettes][download][branch] = "7.x-1.x"
projects[paddle_color_palettes][download][url] = http://git.drupal.org/project/paddle_color_palettes.git
projects[paddle_color_palettes][subdir] = paddle

projects[paddle_content_manager][type] = module
projects[paddle_content_manager][download][type] = git
projects[paddle_content_manager][download][branch] = "7.x-1.x"
projects[paddle_content_manager][download][url] = http://git.drupal.org/project/paddle_content_manager.git
projects[paddle_content_manager][subdir] = paddle

projects[paddle_content_region][type] = module
projects[paddle_content_region][download][type] = git
projects[paddle_content_region][download][branch] = "7.x-1.x"
projects[paddle_content_region][download][url] = http://git.drupal.org/project/paddle_content_region.git
projects[paddle_content_region][subdir] = paddle

projects[paddle_contextual_toolbar][type] = module
projects[paddle_contextual_toolbar][download][type] = git
projects[paddle_contextual_toolbar][download][branch] = "7.x-1.x"
projects[paddle_contextual_toolbar][download][url] = http://git.drupal.org/project/paddle_contextual_toolbar.git
projects[paddle_contextual_toolbar][subdir] = paddle

projects[paddle_editorial_notes][type] = module
projects[paddle_editorial_notes][download][type] = git
projects[paddle_editorial_notes][download][branch] = "7.x-1.x"
projects[paddle_editorial_notes][download][url] = http://git.drupal.org/project/paddle_editorial_notes.git
projects[paddle_editorial_notes][subdir] = paddle

projects[paddle_landing_page][type] = module
projects[paddle_landing_page][download][type] = git
projects[paddle_landing_page][download][branch] = "7.x-1.x"
projects[paddle_landing_page][download][url] = http://git.drupal.org/sandbox/Cyberwolf/1948826.git
projects[paddle_landing_page][subdir] = paddle

projects[paddle_menu_display][type] = module
projects[paddle_menu_display][download][type] = git
projects[paddle_menu_display][download][branch] = "7.x-1.x"
projects[paddle_menu_display][download][url] = http://git.drupal.org/project/paddle_menu_display.git
projects[paddle_menu_display][subdir] = paddle

projects[paddle_menu_manager][type] = module
projects[paddle_menu_manager][download][type] = git
projects[paddle_menu_manager][download][branch] = "7.x-1.x"
projects[paddle_menu_manager][download][url] = http://git.drupal.org/project/paddle_menu_manager.git
projects[paddle_menu_manager][subdir] = paddle

projects[paddle_panels_renderer][type] = module
projects[paddle_panels_renderer][download][type] = git
projects[paddle_panels_renderer][download][branch] = "7.x-1.x"
projects[paddle_panels_renderer][download][url] = http://git.drupal.org/project/paddle_panels_renderer.git
projects[paddle_panels_renderer][subdir] = paddle

projects[paddle_path_by_menu][type] = module
projects[paddle_path_by_menu][download][type] = git
projects[paddle_path_by_menu][download][branch] = "7.x-1.x"
projects[paddle_path_by_menu][download][url] = http://git.drupal.org/project/paddle_path_by_menu.git
projects[paddle_path_by_menu][subdir] = paddle

projects[paddle_taxonomy_manager][type] = module
projects[paddle_taxonomy_manager][download][type] = git
projects[paddle_taxonomy_manager][download][branch] = "7.x-1.x"
projects[paddle_taxonomy_manager][download][url] = http://git.drupal.org/project/paddle_taxonomy_manager.git
projects[paddle_taxonomy_manager][subdir] = paddle

projects[paddle_vo_additional_themes][type] = module
projects[paddle_vo_additional_themes][download][type] = git
projects[paddle_vo_additional_themes][download][branch] = "7.x-1.x"
projects[paddle_vo_additional_themes][download][url] = http://git.drupal.org/project/paddle_vo_additional_themes.git
projects[paddle_vo_additional_themes][subdir] = paddle

projects[paddle_vo_themes][type] = module
projects[paddle_vo_themes][download][type] = git
projects[paddle_vo_themes][download][branch] = "7.x-1.x"
projects[paddle_vo_themes][download][url] = http://git.drupal.org/project/paddle_vo_themes.git
projects[paddle_vo_themes][subdir] = paddle

projects[raven][download][type] = git
projects[raven][download][tag] = 7.x-2.5
projects[raven][subdir] = paddle

projects[reference_tracker][type] = module
projects[reference_tracker][download][type] = git
projects[reference_tracker][download][branch] = "revamp-by-paddle"
projects[reference_tracker][download][url] = http://git.drupal.org/project/reference_tracker.git
projects[reference_tracker][subdir] = paddle

projects[search_api][download][type] = git
projects[search_api][download][tag] = 7.x-1.18
projects[search_api][subdir] = paddle
projects[search_api][patch][2976563] = https://www.drupal.org/files/issues/2018-05-31/search_api-n2976563-2.patch

projects[search_api_autocomplete][download][type] = git
projects[search_api_autocomplete][download][tag] = 7.x-1.3
projects[search_api_autocomplete][subdir] = paddle

projects[search_api_db][download][type] = git
projects[search_api_db][download][tag] = 7.x-1.4
projects[search_api_db][subdir] = paddle

projects[search_api_page][download][type] = git
projects[search_api_page][download][tag] = 7.x-1.3
projects[search_api_page][subdir] = paddle

projects[search_api_solr][download][type] = git
projects[search_api_solr][download][tag] = 7.x-1.12
projects[search_api_solr][subdir] = paddle

; Min should match does not seem to work.
projects[search_api_solr][patch][2795555] = https://www.drupal.org/files/issues/mm_does_not_work-2795555-1.patch

projects[search_api_sorts][download][type] = git
projects[search_api_sorts[download][tag] = 7.x-1.6
projects[search_api_sorts][subdir] = paddle

projects[term_reference_tree][download][type] = git
projects[term_reference_tree][download][branch] = "7.x-1.x"
projects[term_reference_tree][download][url] = http://git.drupal.org/sandbox/angel.h/1967650.git
projects[term_reference_tree][subdir] = paddle

projects[views_data_export][type] = module
projects[views_data_export][download][type] = git
projects[views_data_export][download][revision] = 06aca432e2c3708d92b81302cb63f7d80d69bd79
projects[views_data_export][download][branch] = "7.x-3.x"
projects[views_data_export][download][url] = http://git.drupal.org/project/views_data_export.git
projects[views_data_export][subdir] = paddle

projects[views_languages_field][type] = module
projects[views_languages_field][download][type] = git
projects[views_languages_field][download][branch] = "7.x-1.x"
projects[views_languages_field][download][url] = http://git.drupal.org/project/views_languages_field.git
projects[views_languages_field][subdir] = paddle

projects[varnish][download][type] = git
projects[varnish][download][tag] = 7.x-1.0-beta2
projects[varnish][subdir] = paddle

projects[webclient][download][tag] = 7.x-1.6
projects[webclient][download][type] = git
projects[webclient][subdir] = paddle

projects[widget_block][download][tag] = 7.x-1.0-rc4
projects[widget_block][download][type] = git
projects[widget_block][subdir] = paddle

projects[xautoload][download][tag] = 7.x-5.5
projects[xautoload][download][type] = git
projects[xautoload][subdir] = paddle

projects[ckeditor][download][type] = git
projects[ckeditor][download][tag] = 7.x-1.18
projects[ckeditor][subdir] = paddle

; Libraries

libraries[ckeditor][download][type] = file
libraries[ckeditor][download][url] = https://download.cksource.com/CKEditor/CKEditor/CKEditor%204.9.2/ckeditor_4.9.2_full.zip
libraries[ckeditor][directory_name] = ckeditor

libraries[flexslider][download][type] = file
libraries[flexslider][download][url] = https://github.com/woothemes/FlexSlider/archive/version/2.2.0.tar.gz
libraries[flexslider][directory_name] = flexslider

libraries[htmlpurifier][download][type] = file
libraries[htmlpurifier][download][url] = http://htmlpurifier.org/releases/htmlpurifier-4.6.0.tar.gz
libraries[htmlpurifier][directory_name] = htmlpurifier

libraries[jquery_colorpicker][download][type] = file
libraries[jquery_colorpicker][download][url] = http://www.eyecon.ro/colorpicker/colorpicker.zip
libraries[jquery_colorpicker][directory_name] = colorpicker

libraries[imagesloaded][download][type] = file
libraries[imagesloaded][download][url] = https://github.com/desandro/imagesloaded/archive/v2.1.2.tar.gz
libraries[imagesloaded][directory_name] = jquery.imagesloaded

libraries[imgareaselect][download][type] = file
libraries[imgareaselect][download][url] = http://odyniec.net/projects/imgareaselect/jquery.imgareaselect-0.9.10.zip
libraries[imgareaselect][directory_name] = jquery.imgareaselect

libraries[mediaelement][download][type] = file
libraries[mediaelement][download][url] = https://github.com/mediaelement/mediaelement/archive/4.1.3.zip
libraries[mediaelement][directory_name] = mediaelement

libraries[plupload][download][type] = file
libraries[plupload][download][url] = https://github.com/moxiecode/plupload/archive/v1.5.8.zip
libraries[plupload][directory_name] = plupload
; Remove the examples directory from the library.
libraries[plupload][patch][1903850] = https://www.drupal.org/files/issues/plupload-1_5_8-rm_examples-1903850-21.patch

libraries[sentry-php][download][type] = file
libraries[sentry-php][download][url] = https://github.com/getsentry/sentry-php/archive/1.8.1.zip
libraries[sentry-php][directory_name] = sentry-php

; Patched modules

projects[workbench_moderation][download][type] = git
projects[workbench_moderation][download][revision] = 56d8d1dc595058396cd06358e7c7cdc9f4c5e168
projects[workbench_moderation][download][branch] = 7.x-1.x
projects[workbench_moderation][subdir] = paddle

; Support Panels node\edit forms in Workbench Moderation.
projects[workbench_moderation][patch][1285090] = https://www.drupal.org/files/issues/workbench_moderation-playnicewithpanels-40.patch

; Users with view "View moderation history" but no edit access cannot view
; moderation history.
projects[workbench_moderation][patch][1512442] = https://www.drupal.org/files/issues/1512442-15-workbench_moderation-fix_access_check.patch

; Introduce an option to cache_clear_all() when a published revision changes
; moderation state.
projects[workbench_moderation][patch][2377423] = https://www.drupal.org/files/issues/workbench_moderation-option_cache_clear_all-2377423-14.patch

; Missing argument for Diff menu callback.
projects[workbench_moderation][patch][1791898] = https://www.drupal.org/files/workbench_moderation-diff_missing_argument-1791898-1.patch

; Function menu_get_object() did not retrieve the correct node revision of the page.
projects[workbench_moderation][patch][2021903] = https://www.drupal.org/files/issues/2021903-workbench_moderation-menu_get_object-fix-19.patch

; Allow restws_page_callback() to work.
projects[workbench_moderation][patch][1838640] = https://www.drupal.org/files/issues/1838640-fix-integration-with-restws-page-callback-altering.patch
projects[workbench_moderation][patch][1838640-2] = https://www.drupal.org/files/issues/1838640-tests.patch

projects[strongarm][download][type] = git
projects[strongarm][download][tag] = 7.x-2.0
projects[strongarm][subdir] = paddle

; Variables not set when enabling feature.
projects[strongarm][patch][1965588] = https://www.drupal.org/files/1965588-strongarm-enable_feature.patch

; The patches for the Scheduler Workbench Integration module do not apply on
; the stable branch, so a recent commit is pinned.
projects[scheduler_workbench][download][type] = git
projects[scheduler_workbench][download][revision] = 7b8ccc5c5a22
projects[scheduler_workbench][download][branch] = 7.x-1.x
projects[scheduler_workbench][subdir] = paddle

; Test the Scheduler Workbench Integration.
projects[scheduler_workbench][patch][1966814] = https://www.drupal.org/files/issues/simpletest-1966814-10.patch

; Publish only "approved" nodes.
projects[scheduler_workbench][patch][1955938] = https://www.drupal.org/files/issues/1955938-29-scheduler_workbench-only_publish_approved.patch

projects[views][download][type] = git
projects[views][download][tag] = 7.x-3.17
projects[views][subdir] = paddle

; Add the option to remove destination when creating node edit/delete links.
projects[views][patch][1239566] = https://www.drupal.org/files/views-make_destination_parameter_configurable-1239566-26.patch

; Fix for checkbox with allow multiple value exposed filter when using the pager.
projects[views][patch][1986306] = https://www.drupal.org/files/issues/illegal_choice_0_in-1986306-30.patch

; Views Roles Sandbox module
projects[views_roles][download][type] = git
projects[views_roles][download][url] = http://git.drupal.org/sandbox/rv0/1937404.git
projects[views_roles][download][branch] = 7.x-1.x
projects[views_roles][type] = module

; Menu block module
projects[menu_block][download][type] = git
projects[menu_block][download][branch] =  7.x-2.x
projects[menu_block][subdir] = paddle

; Url field
projects[url][download][type] = git
projects[url][download][tag]= 7.x-1.0

; Add url field support to feeds module.
projects[url][patch][2013238] = https://www.drupal.org/files/issues/feeds_integration-2013238-8.patch

; Url field support for the Entity API
projects[url][patch][1778238] = https://www.drupal.org/files/issues/1778238-entity-api-support.patch

; Tunit for simpletests
projects[tunit][type] = module
projects[tunit][download][type] = git
projects[tunit][download][branch] = "7.x-1.x"
projects[tunit][download][url] = http://git.drupal.org/project/tunit.git
projects[tunit][subdir] = paddle

projects[search_api_attachments][download][type] = git
projects[search_api_attachments][download][tag] = 7.x-1.11
projects[search_api_attachments][subdir] = paddle

projects[eu_cookie_compliance][version] = 1.14

; Add i18n support to eu_cookie_compliance
; projects[eu_cookie_compliance][patch][2528272] = https://www.drupal.org/files/issues/eu_cookie_compliance-7.x-1.14-i18n_variable-2528272-2.patch
