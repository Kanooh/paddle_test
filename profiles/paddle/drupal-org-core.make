api = 2
core = 7.x
projects[drupal][type] = core
projects[drupal][download][type] = git
projects[drupal][download][revision] = 7.57
projects[drupal][download][branch] = 7.x

; Recursive module dependencies of installation profile are not enabled in
; DrupalWebTestCase::setUp.
projects[drupal][patch][1093420] = https://www.drupal.org/files/simpletest-module_enable_dependencies-1093420-9.patch

; Remove static cache in drupal_valid_test_ua().
projects[drupal][patch][1436684] = https://www.drupal.org/files/1436684_failing_tests_d7.patch

; DrupalWebTestCase::buildXPathQuery() tries to handle backreferences in
; argument values.
projects[drupal][patch][1988780] = https://www.drupal.org/files/1988780-6-simpletest-backreferences.patch

; prepareInstallDirectory() doesn't create installation directory.
projects[drupal][patch][2061333] = https://www.drupal.org/files/updater-installation_directory_not_created-2061333-1.patch

; _menu_load_objects() is not always called when building menu trees.
projects[drupal][patch][1697570] = https://www.drupal.org/files/drupal7.menu-system.1697570-29.patch

; Breadcrumbs are not shown correctly when following the menu trail.
projects[drupal][patch][2205503] = https://www.drupal.org/files/issues/menu-breadcrumbs_not_shown_correctly-2205503-1.patch

; Fix infinite weight increment when the weight field is textfield.
projects[drupal][patch][2332785] = https://www.drupal.org/files/issues/2332785-1-weight-textfield-infinete-increase.patch

; Enable standard Simpletest tests to run from within a Paddle installation.
projects[drupal][patch][2376437] = https://www.drupal.org/files/issues/node_dependency_in_comment_info_file-2376437-1.patch

; Enable Simpletest tests to run with the Memcache cache backend.
projects[drupal][patch][362373] = https://www.drupal.org/files/issues/simpletest_refresh_vars-362373-11.patch

; Do not throw exception in list_field_update_forbid() if allowed_values_function is being used.
projects[drupal][patch][2453195] = https://www.drupal.org/files/issues/2453195-no-exception-with-allowed-values-function-7x-14.patch

; Autocomplete appears on the wrong position.
projects[drupal][patch][1218684] = https://www.drupal.org/files/issues/autocomplete2_2.patch

; Fix wrong Drupal static cache values when running Simpletest with a different profile than main installation one
projects[drupal][patch][2679557] = https://www.drupal.org/files/issues/fix_wrong_drupal_static-2679557-2.patch

; Wrong schema information when running Simpletest with a different profile than main installation one
projects[drupal][patch][2685705] = https://www.drupal.org/files/issues/wrong_schema-2685705-2.patch

; Support reinitializing included settings from the general settings file on Drupal multisite installations.
projects[drupal][patch][1118520] = https://www.drupal.org/files/issues/drupal-add-local-settings-1118520-60.patch

; Don't fail if the default theme blocks already got created before reaching the profile install hook implementation.
projects[drupal][patch][2729313] = https://www.drupal.org/files/issues/drupal-dont_fail_profile_install_on_existing_blocks-2729313-6.patch

; When trying to create an already existing table, drop the existing table if empty instead of throwing an error.
projects[drupal][patch][1551132] = https://www.drupal.org/files/issues/1551132-drupal-reinstall-schema-empty-tables-87-D7.patch

; Add user action "unblock current user" to user.module.
projects[drupal][patch][512042-54] = https://www.drupal.org/files/issues/edit_add_user_action-512042-54.patch

; locale_entity_info_alter() causes notices.
projects[drupal][patch][2869038] = https://www.drupal.org/files/issues/locale_entity_info_alter_causes_notices-2869038-1.patch

; Options/ checkbox on default value for existing content.
projects[drupal][patch][2886229] = https://www.drupal.org/files/issues/option_on_default_value_2886229-10.patch

; Add a unique primary key in the taxonomy_index table so that a node can only be linked to a term once.
projects[drupal][patch][2886229] = https://www.drupal.org/files/issues/drupal-n610076-75.patch

; Add the HTML5 'lang' attribute to the language links.
projects[drupal][patch][1904528] = https://www.drupal.org/files/issues/drupal-language-switch-lang-attribute-1904528-15.patch
