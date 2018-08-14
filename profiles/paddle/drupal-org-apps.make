api = 2
core = 7.x

; Apps are listed alphabetically. The required dependencies are listed
; alphabetically under their related app. Duplicated dependencies are commented
; out.


; Paddle Advanced Search.
projects[paddle_advanced_search][download][type] = git
projects[paddle_advanced_search][download][url] = http://git.drupal.org/sandbox/iSoLate/2722309.git
projects[paddle_advanced_search][download][branch] = 7.x-1.x
projects[paddle_advanced_search][type] = module

projects[options_element][version] = 1.12


; Paddle Calendar.
projects[paddle_calendar][download][type] = git
projects[paddle_calendar][download][url] = http://git.drupal.org/sandbox/iSoLate/2496311.git
projects[paddle_calendar][download][branch] = 7.x-1.x
projects[paddle_calendar][type] = module

projects[calendar][version] = 3.5

projects[date_ical][version] = 3.4

; Already mentioned earlier in this file.
;projects[options_element][version] = 1.12

libraries[iCalcreator][download][type] = "get"
libraries[iCalcreator][download][url] = "https://github.com/iCalcreator/iCalcreator/archive/e3dbec2cb3bb91a8bde989e467567ae8831a4026.zip"
libraries[iCalcreator][directory_name] = "iCalcreator"
libraries[iCalcreator][destination] = "libraries"

; Paddle Cultuurnet
projects[paddle_cultuurnet][download][type] = git
projects[paddle_cultuurnet][download][url] = http://git.drupal.org/michel.g/2831136.git
projects[paddle_cultuurnet][download][branch] = 7.x-1.x
projects[paddle_cultuurnet][type] = module

projects[culturefeed][download][type] = git
projects[culturefeed][download][url] = https://github.com/cultuurnet/culturefeed
projects[culturefeed][download][branch] = develop
projects[culturefeed][download][revision] = 650683fed4a3eef547817c2e619f20b09607c229


; Paddle Carousel.
projects[paddle_carousel][download][type] = git
projects[paddle_carousel][download][url] = http://git.drupal.org/sandbox/bertramakers/2351297.git
projects[paddle_carousel][download][branch] = 7.x-1.x
projects[paddle_carousel][type] = module


; Paddle Comment.
projects[paddle_comment][download][type] = git
projects[paddle_comment][download][url] = http://git.drupal.org/sandbox/angel.h/2604072.git
projects[paddle_comment][download][branch] = 7.x-1.x
projects[paddle_comment][type] = module


; Paddle Contact Person.
projects[paddle_contact_person][download][type] = git
projects[paddle_contact_person][download][url] = http://git.drupal.org/sandbox/pfrenssen/2281257.git
projects[paddle_contact_person][download][branch] = 7.x-1.x
projects[paddle_contact_person][type] = module

projects[addressfield][version] = 1.0

projects[email][version] = 1.3

projects[field_collection][version] = 1.0-beta12
projects[field_collection][patch][2841546] = https://www.drupal.org/files/issues/field-collection-hidden-bug-2841546.patch


projects[hardened_computed_field][download][type] = git
projects[hardened_computed_field][download][url] = http://git.drupal.org/sandbox/pfrenssen/2249407.git
projects[hardened_computed_field][download][branch] = 7.x-1.x
projects[hardened_computed_field][download][revision] = 4e4bf275663f8558c5d4e5ade679d3c1a228fdef
projects[hardened_computed_field][type] = module

; Already mentioned earlier in this file.
;projects[options_element][version] = 1.12

projects[telephone][version] = 1.0-alpha1

; Paddle Custom CSS.
projects[paddle_custom_css][download][type] = git
projects[paddle_custom_css][download][url] = http://git.drupal.org/sandbox/iSoLate/2511132.git
projects[paddle_custom_css][download][branch] = 7.x-1.x
projects[paddle_custom_css][type] = module

projects[context][version] = 3.7

; Paddle Custom JavaScript.
projects[paddle_custom_javascript][download][type] = git
projects[paddle_custom_javascript][download][url] = http://git.drupal.org/sandbox/iSoLate/2766787.git
projects[paddle_custom_javascript][download][branch] = 7.x-1.x
projects[paddle_custom_javascript][type] = module


; Paddle Custom Page Layout.
; Not freely available but requires:
projects[layout][version] = 1.0-alpha6
projects[layout][patch][1760978] = https://www.drupal.org/files/issues/layout-hide-disabled-breakpoints-1760978-4.patch
projects[layout][patch][1732576] = https://www.drupal.org/files/issues/avoid_media_query-1732576-4.patch
projects[layout][patch][2918698] = https://www.drupal.org/files/issues/show_the_breadcrumb_labels-2918698-2.patch
projects[layout][patch][2918951] = https://www.drupal.org/files/issues/correct_breakpoint_spelling-2918951-2.patch
projects[layout][patch][2921567] = https://www.drupal.org/files/issues/option_to_have_regions-2921567-1.patch
projects[layout][patch][2921804] = https://www.drupal.org/files/issues/some_improvements_for_add_region_to_modal-2921804-3.patch
projects[layout][patch][2922566] = https://www.drupal.org/files/issues/large-breakpoints-broken-2922566-3.patch
projects[layout][patch][2923095] = https://www.drupal.org/files/issues/fix-column-resize-calculations-2923095-1.patch
projects[layout][patch][2924895] = https://www.drupal.org/files/issues/layout_simplified_add_region_modal-2924895-2.patch
projects[gridbuilder][version] = 1.0-alpha2
projects[json2][version] = 1.1
projects[jquery_update][version] = 2.7


; Paddle Embed.
projects[paddle_embed][download][type] = git
projects[paddle_embed][download][url] = http://git.drupal.org/sandbox/iSoLate/2323791.git
projects[paddle_embed][download][branch] = 7.x-1.x
projects[paddle_embed][type] = module


; Paddle External Links.
projects[extlink][download][type] = git
projects[extlink][download][branch] = 7.x-1.x
projects[extlink][download][revision] = dd5f208d7c2a698a8419e4657bc4614f9a42c006
projects[extlink][patch][2153201] = https://www.drupal.org/files/issues/AddFontAwesomeSupport-2153201-16.patch
projects[extlink][patch][2773545] = https://www.drupal.org/files/issues/extlink-allow-display-block-2773545-10.patch
projects[extlink][type] = module


; Paddle Faceted Search.
projects[paddle_faceted_search][download][type] = git
projects[paddle_faceted_search][download][url] = http://git.drupal.org/sandbox/bertramakers/2305099.git
projects[paddle_faceted_search][download][branch] = 7.x-1.x
projects[paddle_faceted_search][type] = module


; Paddle Fly Out Menu.
projects[paddle_fly_out_menu][download][type] = git
projects[paddle_fly_out_menu][download][url] = http://git.drupal.org/sandbox/iSoLate/2349489.git
projects[paddle_fly_out_menu][download][branch] = 7.x-1.x
projects[paddle_fly_out_menu][type] = module


; Paddle Formbuilder.
projects[paddle_formbuilder][download][type] = git
projects[paddle_formbuilder][download][url] = http://git.drupal.org/sandbox/iSoLate/2463549.git
projects[paddle_formbuilder][download][branch] = 7.x-1.x
projects[paddle_formbuilder][type] = module

projects[form_builder][version] = 1.20

projects[mailsystem][version] = 2.34

projects[mimemail][version] = 1.0
projects[mimemail][patch][2624516] = https://www.drupal.org/files/issues/mimemail-additional_paths-2624516-11.patch

; Already mentioned earlier in this file.
;projects[options_element][version] = 1.12

projects[webform][version] = 4.16

projects[webform2pdf][download][type] = git
projects[webform2pdf][download][tag] = 7.x-4.0


; Paddle Glossary.
projects[paddle_glossary][download][type] = git
projects[paddle_glossary][download][url] = http://git.drupal.org/sandbox/iSoLate/2562437.git
projects[paddle_glossary][download][branch] = 7.x-1.x
projects[paddle_glossary][type] = module


; Paddle Google Analytics.
projects[paddle_google_analytics][download][type] = git
projects[paddle_google_analytics][download][url] = http://git.drupal.org/sandbox/Cyberwolf/2055403.git
projects[paddle_google_analytics][download][branch] = 7.x-1.x
projects[paddle_google_analytics][type] = module

projects[google_analytics][version] = 1.3
projects[google_analytics][patch][2463613] = https://www.drupal.org/files/issues/better-search-term-tracking_2463613_1.patch


; Paddle Google Custom Search.
projects[paddle_google_custom_search][download][type] = git
projects[paddle_google_custom_search][download][url] = http://git.drupal.org/sandbox/iSoLate/2288319.git
projects[paddle_google_custom_search][download][branch] = 7.x-1.x
projects[paddle_google_custom_search][type] = module


projects[google_api][download][type] = git
projects[google_api][download][url] = http://git.drupal.org/sandbox/iSoLate/2288217.git
projects[google_api][download][branch] = 7.x-1.x
projects[google_api][download][revision] = cbd892a1c8cc9cfdf0cf4ff87530c819cab50fe0
projects[google_api][type] = module

libraries[google-api-php-client][download][type] = "git"
libraries[google-api-php-client][download][url] = https://github.com/google/google-api-php-client.git
libraries[google-api-php-client][download][branch] = master
libraries[google-api-php-client][download][tag] = 1.0.4-beta

; Paddle holiday Participation.
projects[paddle_opening_hours][download][type] = git
projects[paddle_opening_hours][download][url] = http://git.drupal.org/sandbox/iSoLate/2819503.git
projects[paddle_opening_hours][download][branch] = 7.x-1.x
projects[paddle_opening_hours][type] = module

projects[feeds_ex][version] = 1.0-beta2
projects[feeds_ex][patch][2552887] = https://www.drupal.org/files/issues/add_missing_files_in_registry_to_info_file_2552887-11.patch

projects[feeds_tamper][version] = 1.1

libraries[jsonpath][download][type] = "get"
libraries[jsonpath][download][url] = "https://storage.googleapis.com/google-code-archive-downloads/v2/code.google.com/jsonpath/jsonpath-0.8.1.php"
libraries[jsonpath][directory_name] = "jsonpath"
libraries[jsonpath][destination] = "libraries"

; Paddle Multilingual.
projects[paddle_i18n][download][type] = git
projects[paddle_i18n][download][url] = http://git.drupal.org/sandbox/angel.h/2614938.git
projects[paddle_i18n][download][branch] = 7.x-1.x
projects[paddle_i18n][type] = module

projects[admin_language][version] = 1.0-beta4


; Paddle Incoming RSS.
projects[paddle_incoming_rss][download][type] = git
projects[paddle_incoming_rss][download][url] = http://git.drupal.org/sandbox/sardara/2492647.git
projects[paddle_incoming_rss][download][branch] = 7.x-1.x
projects[paddle_incoming_rss][type] = module

; Already mentioned earlier in this file.
projects[feeds_tamper][version] = 1.1

projects[feeds][download][type] = git
projects[feeds][download][branch] = 7.x-2.beta4
projects[feeds][type] = module
projects[feeds][patch][2492267] = https://www.drupal.org/files/issues/2492267_feeds_common_syndication_parser_alter-2.patch
projects[feeds][patch][2914119] = https://www.drupal.org/files/issues/add_check_on_content_types_in_simple_test_2914119-2.patch

projects[feeds_entity_processor][download][type] = git
projects[feeds_entity_processor][download][revision] = b3866a1e1d68f9c1d24c20cb29cf5cc15f73ae7a
projects[feeds_entity_processor][download][branch] = 7.x-1.x
projects[feeds_entity_processor][type] = module

projects[job_scheduler][download][type] = git
projects[job_scheduler][download][revision] = 9baaba6bebd34ad6842b1a5292d4d8b32dc9c65c
projects[job_scheduler][download][branch] = 7.x-2.x
projects[job_scheduler][type] = module

projects[smart_trim][version] = 1.5


; Paddle Mailchimp.
projects[paddle_mailchimp][download][type] = git
projects[paddle_mailchimp][download][url] = http://git.drupal.org/sandbox/angel.h/2425335.git
projects[paddle_mailchimp][download][branch] = 7.x-1.x
projects[paddle_mailchimp][type] = module

projects[mailchimp][download][type] = git
projects[mailchimp][download][revision] = 2c98def2e65c273f2c9b1fa808767879e2dcd618
projects[mailchimp][download][branch] = 7.x-1.x
projects[mailchimp][type] = module
projects[mailchimp][patch][2311585] = https://www.drupal.org/files/issues/2446683-1-signup-forms-entity-form-translation.patch

; Already mentioned earlier in this file.
;projects[options_element][version] = 1.12

libraries[mailchimp][download][type] = "get"
libraries[mailchimp][download][url] = "https://bitbucket.org/mailchimp/mailchimp-api-php/get/2.0.6.zip"
libraries[mailchimp][directory_name] = "mailchimp"
libraries[mailchimp][destination] = "libraries"


; Paddle Mega Dropdown.
projects[paddle_mega_dropdown][download][type] = git
projects[paddle_mega_dropdown][download][url] = http://git.drupal.org/sandbox/Cyberwolf/2192607.git
projects[paddle_mega_dropdown][download][branch] = 7.x-1.x
projects[paddle_mega_dropdown][type] = module


; Paddle News.
projects[paddle_news][download][type] = git
projects[paddle_news][download][url] = http://git.drupal.org/sandbox/bertramakers/2303539.git
projects[paddle_news][download][branch] = 7.x-1.x
projects[paddle_news][type] = module

; Already mentioned earlier in this file.
;projects[options_element][version] = 1.12

; Already mentioned earlier in this file.
;projects[smart_trim][version] = 1.5


; Paddle Opening Hours.
projects[paddle_opening_hours][download][type] = git
projects[paddle_opening_hours][download][url] = http://git.drupal.org/sandbox/iSoLate/2792119.git
projects[paddle_opening_hours][download][branch] = 7.x-1.x
projects[paddle_opening_hours][type] = module

; Opening Hours Sets.
projects[opening_hours_sets][download][type] = git
projects[opening_hours_sets][download][url] = http://git.drupal.org/sandbox/iSoLate/2792379.git
projects[opening_hours_sets][download][branch] = 7.x-1.x
projects[opening_hours_sets][type] = module


; Paddle Organizational Unit.
projects[paddle_organizational_unit][download][type] = git
projects[paddle_organizational_unit][download][url] = http://git.drupal.org/sandbox/iSoLate/2234951.git
projects[paddle_organizational_unit][download][branch] = 7.x-1.x
projects[paddle_organizational_unit][type] = module

; Already mentioned earlier in this file.
;projects[addressfield][version] = 1.0

; Already mentioned earlier in this file.
;projects[email][version] = 1.3

; Already mentioned earlier in this file.
;projects[hardened_computed_field][download][type] = git
;projects[hardened_computed_field][download][url] = http://git.drupal.org/sandbox/pfrenssen/2249407.git
;projects[hardened_computed_field][download][branch] = 7.x-1.x
;projects[hardened_computed_field][download][revision] = 4e4bf27
;projects[hardened_computed_field][type] = module

; Already mentioned earlier in this file.
;projects[options_element][version] = 1.12

; Already mentioned earlier in this file.
;projects[telephone][version] = 1.0-alpha1


; Paddle Outgoing RSS.
projects[paddle_outgoing_rss][download][type] = git
projects[paddle_outgoing_rss][download][url] = http://git.drupal.org/sandbox/iSoLate/2442719.git
projects[paddle_outgoing_rss][download][branch] = 7.x-1.x
projects[paddle_outgoing_rss][type] = module


; Paddle Poll.
projects[paddle_poll][download][type] = git
projects[paddle_poll][download][url] = http://git.drupal.org/sandbox/sardara/2634532.git
projects[paddle_poll][download][branch] = 7.x-1.x
projects[paddle_poll][type] = module

projects[charts][version] = 2.0-rc1

; Already mentioned earlier in this file.
;projects[options_element][version] = 1.12


; Paddle Product.
projects[paddle_product][download][type] = git
projects[paddle_product][download][url] = http://git.drupal.org/sandbox/iSoLate/2774531.git
projects[paddle_product][download][branch] = 7.x-1.x
projects[paddle_product][type] = module

; Paddle Quiz.
projects[paddle_quiz][download][type] = git
projects[paddle_quiz][download][url] = http://git.drupal.org/sandbox/pieterdc/2403113.git
projects[paddle_quiz][download][branch] = 7.x-1.x
projects[paddle_quiz][type] = module

; Already mentioned earlier in this file.
projects[field_collection][version] = 1.0-beta12


projects[field_group][version] = 1.5

; Already mentioned earlier in this file.
;projects[options_element][version] = 1.12

libraries[phpexcel][download][type] = "git"
libraries[phpexcel][download][url] = https://github.com/PHPOffice/PHPExcel.git
libraries[phpexcel][download][branch] = 1.8


; Paddle Rate.
projects[paddle_rate][download][type] = git
projects[paddle_rate][download][url] = http://git.drupal.org/sandbox/iSoLate/2774623.git
projects[paddle_rate][download][branch] = 7.x-1.x
projects[paddle_rate][type] = module

; Fixed on a commit from 7.x-2.x 2016-Aug-18 to avoid sudden bugs with untested updates.
projects[authcache][download][type] = git
projects[authcache][download][revision] = fcb64edfa9b818fca88cdd1a6cd900a71f5cc980
projects[authcache][download][branch] = 7.x-2.x
projects[authcache][type] = module

projects[fivestar][version] = 2.2

projects[session_api][version] = 1.0-rc1

projects[votingapi][version] = 2.12
projects[votingapi][patch][798790] = https://www.drupal.org/files/issues/votingapi_798790_16_cookie_source_tag.patch


; Paddle Recaptcha.
; Not freely available but requires:
projects[captcha][version] = 1.4

projects[recaptcha][version] = 2.2


; Paddle Redirect.
projects[paddle_redirect][download][type] = git
projects[paddle_redirect][download][url] = http://git.drupal.org/sandbox/iSoLate/2357929.git
projects[paddle_redirect][download][branch] = 7.x-1.x
projects[paddle_redirect][type] = module

projects[redirect][version] = 1.0-rc3
projects[redirect][patch][2543606] = https://www.drupal.org/files/issues/2543606-redirect-to-local-files-no-language-prefix-4.patch
projects[redirect][patch][1983444] = https://www.drupal.org/files/issues/redirect-language-neutral-source-1983444-48.patch


; Paddle Rich Footer.
projects[paddle_rich_footer][download][type] = git
projects[paddle_rich_footer][download][url] = http://git.drupal.org/sandbox/Cyberwolf/2092025.git
projects[paddle_rich_footer][download][branch] = 7.x-1.x
projects[paddle_rich_footer][type] = module


; Paddle Simple Contact.
projects[paddle_simple_contact][download][type] = git
projects[paddle_simple_contact][download][url] = http://git.drupal.org/sandbox/Cyberwolf/2082229.git
projects[paddle_simple_contact][download][branch] = 7.x-1.x
projects[paddle_simple_contact][type] = module

; Already mentioned earlier in this file.
;projects[options_element][version] = 1.12


; Paddle Social Identities.
projects[paddle_social_identities][download][type] = git
projects[paddle_social_identities][download][url] = http://git.drupal.org/sandbox/angel.h/2366927.git
projects[paddle_social_identities][download][branch] = 7.x-1.x
projects[paddle_social_identities][type] = module

; Already mentioned earlier in this file.
;projects[url][version] = 1.0


; Paddle Social Media.
projects[paddle_social_media][download][type] = git
projects[paddle_social_media][download][url] = http://git.drupal.org/sandbox/angel.h/2336015.git
projects[paddle_social_media][download][branch] = 7.x-1.x
projects[paddle_social_media][type] = module


; Paddle Splash Page.
; Not freely available but requires:
projects[language_cookie][version] = 2.0
projects[language_cookie][patch][2882384] = https://www.drupal.org/files/issues/set_cookie_path_root_folder_second-2882384-2.patch

projects[language_selection_page][version] = 2.0
projects[language_selection_page][patch][2882382] = https://www.drupal.org/files/issues/fix_root_in_subfolder-2882382.patch


; Paddle Web Service.
projects[paddle_web_service][download][type] = git
projects[paddle_web_service][download][url] = http://git.drupal.org/sandbox/pieterdc/2734167.git
projects[paddle_web_service][download][branch] = 7.x-1.x
projects[paddle_web_service][type] = module

projects[plug][version] = 1.1

projects[registry_autoload][version] = 1.3

projects[restful][version] = 2.13

projects[taxonomy_tag_order][version] = 1.0
projects[taxonomy_tag_order][patch][2886437] = https://www.drupal.org/files/issues/multilingual_support-2886437-1.patch

projects[link][version] = 1.4

projects[restws][version] = 2.7
projects[restws][patch][2555353] = https://www.drupal.org/files/issues/restws-fields-2555353-2.patch
projects[restws][patch][1720602] = https://www.drupal.org/files/issues/creating_nodes_with-1720602-126.patch
projects[restws][patch][2980478] = https://www.drupal.org/files/issues/2018-06-19/initialize-workbench-moderation-state-2980478-2.patch

projects[restws_file][version] = 1.2
projects[restws_file][patch][2780125] = https://www.drupal.org/files/issues/restws_file_field_info_file_directory-2780125-5.patch
