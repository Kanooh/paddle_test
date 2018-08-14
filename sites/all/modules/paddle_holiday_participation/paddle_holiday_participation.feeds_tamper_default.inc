<?php
/**
 * @file
 * paddle_holiday_participation.feeds_tamper_default.inc
 */

/**
 * Implements hook_feeds_tamper_default().
 */
function paddle_holiday_participation_feeds_tamper_default() {
  $export = array();

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_day_trips-blank_source_1-rewrite';
  $feeds_tamper->importer = 'padvak_day_trips';
  $feeds_tamper->source = 'Blank source 1';
  $feeds_tamper->plugin_id = 'rewrite';
  $feeds_tamper->settings = array(
    'text' => '1[hp_svp_contract_id]',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Rewrite';
  $export['padvak_day_trips-blank_source_1-rewrite'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_day_trips-hp_contract_type-convert_case';
  $feeds_tamper->importer = 'padvak_day_trips';
  $feeds_tamper->source = 'hp_contract_type';
  $feeds_tamper->plugin_id = 'convert_case';
  $feeds_tamper->settings = array(
    'mode' => '1',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Convert case';
  $export['padvak_day_trips-hp_contract_type-convert_case'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_day_trips-hp_contract_type-rewrite';
  $feeds_tamper->importer = 'padvak_day_trips';
  $feeds_tamper->source = 'hp_contract_type';
  $feeds_tamper->plugin_id = 'rewrite';
  $feeds_tamper->settings = array(
    'text' => '1[hp_svp_contract_id]',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Rewrite';
  $export['padvak_day_trips-hp_contract_type-rewrite'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_day_trips-hp_country-default_value';
  $feeds_tamper->importer = 'padvak_day_trips';
  $feeds_tamper->source = 'hp_country';
  $feeds_tamper->plugin_id = 'default_value';
  $feeds_tamper->settings = array(
    'default_value' => 'be',
    'only_if_empty' => 0,
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Set value or default value';
  $export['padvak_day_trips-hp_country-default_value'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_day_trips-hp_facebook-find_replace';
  $feeds_tamper->importer = 'padvak_day_trips';
  $feeds_tamper->source = 'hp_facebook';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => 'http://',
    'replace' => '',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 1,
    'regex' => TRUE,
    'regex_find' => '/^http\\:\\/\\/$/ui',
  );
  $feeds_tamper->weight = 1;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_day_trips-hp_facebook-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_day_trips-hp_facebook-rewrite';
  $feeds_tamper->importer = 'padvak_day_trips';
  $feeds_tamper->source = 'hp_facebook';
  $feeds_tamper->plugin_id = 'rewrite';
  $feeds_tamper->settings = array(
    'text' => 'http://[hp_facebook]',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Rewrite';
  $export['padvak_day_trips-hp_facebook-rewrite'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_day_trips-hp_house_number-rewrite';
  $feeds_tamper->importer = 'padvak_day_trips';
  $feeds_tamper->source = 'hp_house_number';
  $feeds_tamper->plugin_id = 'rewrite';
  $feeds_tamper->settings = array(
    'text' => '[hp_street]  [hp_house_number]',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Rewrite';
  $export['padvak_day_trips-hp_house_number-rewrite'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_day_trips-hp_indoor_playground-find_replace';
  $feeds_tamper->importer = 'padvak_day_trips';
  $feeds_tamper->source = 'hp_indoor_playground';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_indoor_playground',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_day_trips-hp_indoor_playground-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_day_trips-hp_outdoor_playground-find_replace';
  $feeds_tamper->importer = 'padvak_day_trips';
  $feeds_tamper->source = 'hp_outdoor_playground';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_outdoor_playground',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_day_trips-hp_outdoor_playground-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_day_trips-hp_parking-find_replace';
  $feeds_tamper->importer = 'padvak_day_trips';
  $feeds_tamper->source = 'hp_parking';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_parking',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_day_trips-hp_parking-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_day_trips-hp_pets_allowed-find_replace';
  $feeds_tamper->importer = 'padvak_day_trips';
  $feeds_tamper->source = 'hp_pets_allowed';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_pets_allowed',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_day_trips-hp_pets_allowed-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_day_trips-hp_picknick_possible-find_replace';
  $feeds_tamper->importer = 'padvak_day_trips';
  $feeds_tamper->source = 'hp_picknick_possible';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_picknick_possible',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_day_trips-hp_picknick_possible-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_day_trips-hp_restaurant_snackbar-array_filter';
  $feeds_tamper->importer = 'padvak_day_trips';
  $feeds_tamper->source = 'hp_restaurant_snackbar';
  $feeds_tamper->plugin_id = 'array_filter';
  $feeds_tamper->settings = array();
  $feeds_tamper->weight = 3;
  $feeds_tamper->description = 'Filter empty items';
  $export['padvak_day_trips-hp_restaurant_snackbar-array_filter'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_day_trips-hp_restaurant_snackbar-explode';
  $feeds_tamper->importer = 'padvak_day_trips';
  $feeds_tamper->source = 'hp_restaurant_snackbar';
  $feeds_tamper->plugin_id = 'explode';
  $feeds_tamper->settings = array(
    'separator' => ',',
    'limit' => '',
    'real_separator' => ',',
  );
  $feeds_tamper->weight = 2;
  $feeds_tamper->description = 'Explode';
  $export['padvak_day_trips-hp_restaurant_snackbar-explode'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_day_trips-hp_restaurant_snackbar-find_replace';
  $feeds_tamper->importer = 'padvak_day_trips';
  $feeds_tamper->source = 'hp_restaurant_snackbar';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_restaurant_snackbar',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_day_trips-hp_restaurant_snackbar-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_day_trips-hp_restaurant_snackbar-hp_merge_daytrip_facilities';
  $feeds_tamper->importer = 'padvak_day_trips';
  $feeds_tamper->source = 'hp_restaurant_snackbar';
  $feeds_tamper->plugin_id = 'rewrite';
  $feeds_tamper->settings = array(
    'text' => '[hp_indoor_playground],[hp_outdoor_playground],[hp_parking],[hp_pets_allowed],[hp_picknick_possible],[hp_restaurant_snackbar]',
  );
  $feeds_tamper->weight = 1;
  $feeds_tamper->description = 'Merge facilities fields';
  $export['padvak_day_trips-hp_restaurant_snackbar-hp_merge_daytrip_facilities'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_day_trips-hp_twitter-find_replace';
  $feeds_tamper->importer = 'padvak_day_trips';
  $feeds_tamper->source = 'hp_twitter';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => 'http://',
    'replace' => '',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 1,
    'regex' => TRUE,
    'regex_find' => '/^http\\:\\/\\/$/ui',
  );
  $feeds_tamper->weight = 1;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_day_trips-hp_twitter-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_day_trips-hp_twitter-rewrite';
  $feeds_tamper->importer = 'padvak_day_trips';
  $feeds_tamper->source = 'hp_twitter';
  $feeds_tamper->plugin_id = 'rewrite';
  $feeds_tamper->settings = array(
    'text' => 'http://[hp_twitter]',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Rewrite';
  $export['padvak_day_trips-hp_twitter-rewrite'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_day_trips-hp_website-find_replace';
  $feeds_tamper->importer = 'padvak_day_trips';
  $feeds_tamper->source = 'hp_website';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => 'http://',
    'replace' => '',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 1,
    'regex' => TRUE,
    'regex_find' => '/^http\\:\\/\\/$/ui',
  );
  $feeds_tamper->weight = 1;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_day_trips-hp_website-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_day_trips-hp_website-rewrite';
  $feeds_tamper->importer = 'padvak_day_trips';
  $feeds_tamper->source = 'hp_website';
  $feeds_tamper->plugin_id = 'rewrite';
  $feeds_tamper->settings = array(
    'text' => 'http://[hp_website]',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Rewrite';
  $export['padvak_day_trips-hp_website-rewrite'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_day_trips-hp_youtube-find_replace';
  $feeds_tamper->importer = 'padvak_day_trips';
  $feeds_tamper->source = 'hp_youtube';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => 'http://',
    'replace' => '',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 1,
    'regex' => TRUE,
    'regex_find' => '/^http\\:\\/\\/$/ui',
  );
  $feeds_tamper->weight = 1;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_day_trips-hp_youtube-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_day_trips-hp_youtube-rewrite';
  $feeds_tamper->importer = 'padvak_day_trips';
  $feeds_tamper->source = 'hp_youtube';
  $feeds_tamper->plugin_id = 'rewrite';
  $feeds_tamper->settings = array(
    'text' => 'http://[hp_youtube]',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Rewrite';
  $export['padvak_day_trips-hp_youtube-rewrite'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-blank_source_1-rewrite';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'Blank source 1';
  $feeds_tamper->plugin_id = 'rewrite';
  $feeds_tamper->settings = array(
    'text' => '2[hp_svp_contract_id]',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Rewrite';
  $export['padvak_group_accomodations-blank_source_1-rewrite'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_accessibility_label-find_replace';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_accessibility_label';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => 'BASIC',
    'replace' => 'hp_accessibility_label_a',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_group_accomodations-hp_accessibility_label-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_accessibility_label-find_replace_none';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_accessibility_label';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => 'INFORMATION_AVAILABLE',
    'replace' => 'hp_accessibility_label_i',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 1;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_group_accomodations-hp_accessibility_label-find_replace_none'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_accessibility_label-find_replace_plus';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_accessibility_label';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => 'COMFORT',
    'replace' => 'hp_accessibility_label_a_plus',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 2;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_group_accomodations-hp_accessibility_label-find_replace_plus'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_bed_and_breakfast-find_replace';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_bed_and_breakfast';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_bed_and_breakfast',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_group_accomodations-hp_bed_and_breakfast-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_bikes_available-find_replace';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_bikes_available';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_bikes_available',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_group_accomodations-hp_bikes_available-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_contract_type-convert_case';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_contract_type';
  $feeds_tamper->plugin_id = 'convert_case';
  $feeds_tamper->settings = array(
    'mode' => '1',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Convert case';
  $export['padvak_group_accomodations-hp_contract_type-convert_case'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_contract_type-rewrite';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_contract_type';
  $feeds_tamper->plugin_id = 'rewrite';
  $feeds_tamper->settings = array(
    'text' => 'hp_[hp_contract_type]',
  );
  $feeds_tamper->weight = 1;
  $feeds_tamper->description = 'Rewrite';
  $export['padvak_group_accomodations-hp_contract_type-rewrite'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_country-default_value';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_country';
  $feeds_tamper->plugin_id = 'default_value';
  $feeds_tamper->settings = array(
    'default_value' => 'be',
    'only_if_empty' => 0,
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Set value or default value';
  $export['padvak_group_accomodations-hp_country-default_value'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_cycling_label-find_replace';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_cycling_label';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_cycling_label',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_group_accomodations-hp_cycling_label-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_extra_baby_bed_possible-find_replace';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_extra_baby_bed_possible';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_extra_baby_bed_possible',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_group_accomodations-hp_extra_baby_bed_possible-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_extra_baby_chair_possible-find_replace';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_extra_baby_chair_possible';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_extra_baby_chair_possible',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_group_accomodations-hp_extra_baby_chair_possible-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_facebook-find_replace';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_facebook';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => 'http://',
    'replace' => '',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 1,
    'regex' => TRUE,
    'regex_find' => '/^http\\:\\/\\/$/ui',
  );
  $feeds_tamper->weight = 1;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_group_accomodations-hp_facebook-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_facebook-rewrite';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_facebook';
  $feeds_tamper->plugin_id = 'rewrite';
  $feeds_tamper->settings = array(
    'text' => 'http://[hp_facebook]',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Rewrite';
  $export['padvak_group_accomodations-hp_facebook-rewrite'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_full_board-find_replace';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_full_board';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_full_board',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_group_accomodations-hp_full_board-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_green_key_label-array_filter';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_green_key_label';
  $feeds_tamper->plugin_id = 'array_filter';
  $feeds_tamper->settings = array();
  $feeds_tamper->weight = 3;
  $feeds_tamper->description = 'Filter empty items';
  $export['padvak_group_accomodations-hp_green_key_label-array_filter'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_green_key_label-explode';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_green_key_label';
  $feeds_tamper->plugin_id = 'explode';
  $feeds_tamper->settings = array(
    'separator' => ',',
    'limit' => '',
    'real_separator' => ',',
  );
  $feeds_tamper->weight = 2;
  $feeds_tamper->description = 'Explode';
  $export['padvak_group_accomodations-hp_green_key_label-explode'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_green_key_label-find_replace';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_green_key_label';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_green_key_label',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_group_accomodations-hp_green_key_label-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_green_key_label-rewrite';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_green_key_label';
  $feeds_tamper->plugin_id = 'rewrite';
  $feeds_tamper->settings = array(
    'text' => '[hp_accessibility_label],[hp_cycling_label],[hp_green_key_label]',
  );
  $feeds_tamper->weight = 1;
  $feeds_tamper->description = 'Rewrite';
  $export['padvak_group_accomodations-hp_green_key_label-rewrite'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_half_board-find_replace';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_half_board';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_half_board',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_group_accomodations-hp_half_board-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_house_number-rewrite';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_house_number';
  $feeds_tamper->plugin_id = 'rewrite';
  $feeds_tamper->settings = array(
    'text' => '[hp_street]  [hp_house_number]',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Rewrite';
  $export['padvak_group_accomodations-hp_house_number-rewrite'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_indoor_playground-find_replace';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_indoor_playground';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_indoor_playground',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_group_accomodations-hp_indoor_playground-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_internet_connection-find_replace';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_internet_connection';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_internet_connection',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_group_accomodations-hp_internet_connection-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_outdoor_playground-find_replace';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_outdoor_playground';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_outdoor_playground',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_group_accomodations-hp_outdoor_playground-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_parking-find_replace';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_parking';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_parking',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_group_accomodations-hp_parking-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_pets_allowed-find_replace';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_pets_allowed';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_pets_allowed',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_group_accomodations-hp_pets_allowed-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_picknick_possible-find_replace';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_picknick_possible';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_picknick_possible',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_group_accomodations-hp_picknick_possible-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_restaurant_snackbar-find_replace';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_restaurant_snackbar';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_restaurant_snackbar',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_group_accomodations-hp_restaurant_snackbar-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_self_cooking-array_filter';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_self_cooking';
  $feeds_tamper->plugin_id = 'array_filter';
  $feeds_tamper->settings = array();
  $feeds_tamper->weight = 3;
  $feeds_tamper->description = 'Filter empty items';
  $export['padvak_group_accomodations-hp_self_cooking-array_filter'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_self_cooking-explode';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_self_cooking';
  $feeds_tamper->plugin_id = 'explode';
  $feeds_tamper->settings = array(
    'separator' => ',',
    'limit' => '',
    'real_separator' => ',',
  );
  $feeds_tamper->weight = 2;
  $feeds_tamper->description = 'Explode';
  $export['padvak_group_accomodations-hp_self_cooking-explode'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_self_cooking-find_replace';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_self_cooking';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_self_cooking',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_group_accomodations-hp_self_cooking-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_self_cooking-rewrite';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_self_cooking';
  $feeds_tamper->plugin_id = 'rewrite';
  $feeds_tamper->settings = array(
    'text' => '[hp_full_board],[hp_half_board],[hp_bed_and_breakfast],[hp_self_cooking]',
  );
  $feeds_tamper->weight = 1;
  $feeds_tamper->description = 'Rewrite';
  $export['padvak_group_accomodations-hp_self_cooking-rewrite'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_shop-find_replace';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_shop';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_shop',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_group_accomodations-hp_shop-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_swimming-find_replace';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_swimming';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => 'INDOOR',
    'replace' => 'hp_swimming',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_group_accomodations-hp_swimming-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_swimming-find_replace_none';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_swimming';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => 'NONE',
    'replace' => '0',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 1;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_group_accomodations-hp_swimming-find_replace_none'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_swimming-find_replace_outdoor';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_swimming';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => 'OUTDOOR',
    'replace' => 'hp_swimming',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 2;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_group_accomodations-hp_swimming-find_replace_outdoor'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_tearoom_bar-find_replace';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_tearoom_bar';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_tearoom_bar',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_group_accomodations-hp_tearoom_bar-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_television-array_filter';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_television';
  $feeds_tamper->plugin_id = 'array_filter';
  $feeds_tamper->settings = array();
  $feeds_tamper->weight = 3;
  $feeds_tamper->description = 'Filter empty items';
  $export['padvak_group_accomodations-hp_television-array_filter'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_television-explode';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_television';
  $feeds_tamper->plugin_id = 'explode';
  $feeds_tamper->settings = array(
    'separator' => ',',
    'limit' => '',
    'real_separator' => ',',
  );
  $feeds_tamper->weight = 2;
  $feeds_tamper->description = 'Explode';
  $export['padvak_group_accomodations-hp_television-explode'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_television-find_replace';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_television';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_television',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_group_accomodations-hp_television-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_television-rewrite';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_television';
  $feeds_tamper->plugin_id = 'rewrite';
  $feeds_tamper->settings = array(
    'text' => '[hp_bikes_available],[hp_extra_baby_bed_possible],[hp_extra_baby_chair_possible],[hp_internet_connection],[hp_indoor_playground],[hp_outdoor_playground],[hp_parking],[hp_pets_allowed],[hp_picknick_possible],[hp_restaurant_snackbar],[hp_shop],[hp_swimming],[hp_tearoom_bar],[hp_television]',
  );
  $feeds_tamper->weight = 1;
  $feeds_tamper->description = 'Rewrite';
  $export['padvak_group_accomodations-hp_television-rewrite'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_twitter-find_replace';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_twitter';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => 'http://',
    'replace' => '',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 1,
    'regex' => TRUE,
    'regex_find' => '/^http\\:\\/\\/$/ui',
  );
  $feeds_tamper->weight = 1;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_group_accomodations-hp_twitter-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_twitter-rewrite';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_twitter';
  $feeds_tamper->plugin_id = 'rewrite';
  $feeds_tamper->settings = array(
    'text' => 'http://[hp_twitter]',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Rewrite';
  $export['padvak_group_accomodations-hp_twitter-rewrite'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_website-find_replace';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_website';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => 'http://',
    'replace' => '',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 1,
    'regex' => TRUE,
    'regex_find' => '/^http\\:\\/\\/$/ui',
  );
  $feeds_tamper->weight = 1;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_group_accomodations-hp_website-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_website-rewrite';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_website';
  $feeds_tamper->plugin_id = 'rewrite';
  $feeds_tamper->settings = array(
    'text' => 'http://[hp_website]',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Rewrite';
  $export['padvak_group_accomodations-hp_website-rewrite'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_youtube-find_replace';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_youtube';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => 'http://',
    'replace' => '',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 1,
    'regex' => TRUE,
    'regex_find' => '/^http\\:\\/\\/$/ui',
  );
  $feeds_tamper->weight = 1;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_group_accomodations-hp_youtube-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_group_accomodations-hp_youtube-rewrite';
  $feeds_tamper->importer = 'padvak_group_accomodations';
  $feeds_tamper->source = 'hp_youtube';
  $feeds_tamper->plugin_id = 'rewrite';
  $feeds_tamper->settings = array(
    'text' => 'http://[hp_youtube]',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Rewrite';
  $export['padvak_group_accomodations-hp_youtube-rewrite'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-blank_source_1-rewrite';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'Blank source 1';
  $feeds_tamper->plugin_id = 'rewrite';
  $feeds_tamper->settings = array(
    'text' => '3[hp_svp_contract_id]',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Rewrite';
  $export['padvak_holiday_accomodations-blank_source_1-rewrite'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_accessibility_label-find_replace';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_accessibility_label';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => 'BASIC',
    'replace' => 'hp_accessibility_label_a',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_holiday_accomodations-hp_accessibility_label-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_accessibility_label-find_replace_none';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_accessibility_label';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => 'INFORMATION_AVAILABLE',
    'replace' => 'hp_accessibility_label_i',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 1;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_holiday_accomodations-hp_accessibility_label-find_replace_none'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_accessibility_label-find_replace_plus';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_accessibility_label';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => 'COMFORT',
    'replace' => 'hp_accessibility_label_a_plus',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 2;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_holiday_accomodations-hp_accessibility_label-find_replace_plus'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_bed_and_breakfast-find_replace';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_bed_and_breakfast';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_bed_and_breakfast',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_holiday_accomodations-hp_bed_and_breakfast-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_bikes_available-find_replace';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_bikes_available';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_bikes_available',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_holiday_accomodations-hp_bikes_available-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_contract_type-convert_case';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_contract_type';
  $feeds_tamper->plugin_id = 'convert_case';
  $feeds_tamper->settings = array(
    'mode' => '1',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Convert case';
  $export['padvak_holiday_accomodations-hp_contract_type-convert_case'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_contract_type-rewrite';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_contract_type';
  $feeds_tamper->plugin_id = 'rewrite';
  $feeds_tamper->settings = array(
    'text' => 'hp_[hp_contract_type]',
  );
  $feeds_tamper->weight = 1;
  $feeds_tamper->description = 'Rewrite';
  $export['padvak_holiday_accomodations-hp_contract_type-rewrite'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_country-default_value';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_country';
  $feeds_tamper->plugin_id = 'default_value';
  $feeds_tamper->settings = array(
    'default_value' => 'be',
    'only_if_empty' => 0,
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Set value or default value';
  $export['padvak_holiday_accomodations-hp_country-default_value'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_cycling_label-find_replace';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_cycling_label';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_cycling_label',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_holiday_accomodations-hp_cycling_label-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_extra_baby_bed_possible-find_replace';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_extra_baby_bed_possible';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_extra_baby_bed_possible',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_holiday_accomodations-hp_extra_baby_bed_possible-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_extra_baby_chair_possible-find_replace';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_extra_baby_chair_possible';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_extra_baby_chair_possible',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_holiday_accomodations-hp_extra_baby_chair_possible-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_facebook-find_replace';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_facebook';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => 'http://',
    'replace' => '',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 1,
    'regex' => TRUE,
    'regex_find' => '/^http\\:\\/\\/$/ui',
  );
  $feeds_tamper->weight = 1;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_holiday_accomodations-hp_facebook-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_facebook-rewrite';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_facebook';
  $feeds_tamper->plugin_id = 'rewrite';
  $feeds_tamper->settings = array(
    'text' => 'http://[hp_facebook]',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Rewrite';
  $export['padvak_holiday_accomodations-hp_facebook-rewrite'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_full_board-find_replace';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_full_board';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_full_board',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_holiday_accomodations-hp_full_board-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_green_key_label-array_filter';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_green_key_label';
  $feeds_tamper->plugin_id = 'array_filter';
  $feeds_tamper->settings = array();
  $feeds_tamper->weight = 3;
  $feeds_tamper->description = 'Filter empty items';
  $export['padvak_holiday_accomodations-hp_green_key_label-array_filter'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_green_key_label-explode';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_green_key_label';
  $feeds_tamper->plugin_id = 'explode';
  $feeds_tamper->settings = array(
    'separator' => ',',
    'limit' => '',
    'real_separator' => ',',
  );
  $feeds_tamper->weight = 2;
  $feeds_tamper->description = 'Explode';
  $export['padvak_holiday_accomodations-hp_green_key_label-explode'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_green_key_label-find_replace';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_green_key_label';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_green_key_label',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_holiday_accomodations-hp_green_key_label-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_green_key_label-rewrite';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_green_key_label';
  $feeds_tamper->plugin_id = 'rewrite';
  $feeds_tamper->settings = array(
    'text' => '[hp_accessibility_label],[hp_cycling_label],[hp_green_key_label]',
  );
  $feeds_tamper->weight = 1;
  $feeds_tamper->description = 'Rewrite';
  $export['padvak_holiday_accomodations-hp_green_key_label-rewrite'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_half_board-find_replace';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_half_board';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_half_board',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_holiday_accomodations-hp_half_board-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_house_number-rewrite';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_house_number';
  $feeds_tamper->plugin_id = 'rewrite';
  $feeds_tamper->settings = array(
    'text' => '[hp_street]  [hp_house_number]',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Rewrite';
  $export['padvak_holiday_accomodations-hp_house_number-rewrite'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_indoor_playground-find_replace';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_indoor_playground';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_indoor_playground',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_holiday_accomodations-hp_indoor_playground-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_internet_connection-find_replace';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_internet_connection';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_internet_connection',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_holiday_accomodations-hp_internet_connection-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_outdoor_playground-find_replace';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_outdoor_playground';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_outdoor_playground',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_holiday_accomodations-hp_outdoor_playground-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_parking-find_replace';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_parking';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_parking',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_holiday_accomodations-hp_parking-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_pets_allowed-find_replace';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_pets_allowed';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_pets_allowed',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_holiday_accomodations-hp_pets_allowed-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_picknick_possible-find_replace';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_picknick_possible';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_picknick_possible',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_holiday_accomodations-hp_picknick_possible-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_restaurant_snackbar-find_replace';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_restaurant_snackbar';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_restaurant_snackbar',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_holiday_accomodations-hp_restaurant_snackbar-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_self_cooking-array_filter';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_self_cooking';
  $feeds_tamper->plugin_id = 'array_filter';
  $feeds_tamper->settings = array();
  $feeds_tamper->weight = 3;
  $feeds_tamper->description = 'Filter empty items';
  $export['padvak_holiday_accomodations-hp_self_cooking-array_filter'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_self_cooking-explode';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_self_cooking';
  $feeds_tamper->plugin_id = 'explode';
  $feeds_tamper->settings = array(
    'separator' => ',',
    'limit' => '',
    'real_separator' => ',',
  );
  $feeds_tamper->weight = 2;
  $feeds_tamper->description = 'Explode';
  $export['padvak_holiday_accomodations-hp_self_cooking-explode'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_self_cooking-find_replace';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_self_cooking';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_self_cooking',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_holiday_accomodations-hp_self_cooking-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_self_cooking-rewrite';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_self_cooking';
  $feeds_tamper->plugin_id = 'rewrite';
  $feeds_tamper->settings = array(
    'text' => '[hp_full_board],[hp_half_board],[hp_bed_and_breakfast],[hp_self_cooking]',
  );
  $feeds_tamper->weight = 1;
  $feeds_tamper->description = 'Rewrite';
  $export['padvak_holiday_accomodations-hp_self_cooking-rewrite'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_shop-find_replace';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_shop';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_shop',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_holiday_accomodations-hp_shop-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_swimming-find_replace';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_swimming';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => 'INDOOR',
    'replace' => 'hp_swimming',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_holiday_accomodations-hp_swimming-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_swimming-find_replace_none';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_swimming';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => 'NONE',
    'replace' => '0',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 1;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_holiday_accomodations-hp_swimming-find_replace_none'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_swimming-find_replace_outdoor';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_swimming';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => 'OUTDOOR',
    'replace' => 'hp_swimming',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 2;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_holiday_accomodations-hp_swimming-find_replace_outdoor'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_tearoom_bar-find_replace';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_tearoom_bar';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_tearoom_bar',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_holiday_accomodations-hp_tearoom_bar-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_television-array_filter';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_television';
  $feeds_tamper->plugin_id = 'array_filter';
  $feeds_tamper->settings = array();
  $feeds_tamper->weight = 3;
  $feeds_tamper->description = 'Filter empty items';
  $export['padvak_holiday_accomodations-hp_television-array_filter'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_television-explode';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_television';
  $feeds_tamper->plugin_id = 'explode';
  $feeds_tamper->settings = array(
    'separator' => ',',
    'limit' => '',
    'real_separator' => ',',
  );
  $feeds_tamper->weight = 2;
  $feeds_tamper->description = 'Explode';
  $export['padvak_holiday_accomodations-hp_television-explode'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_television-find_replace';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_television';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => '1',
    'replace' => 'hp_television',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 0,
    'regex' => FALSE,
    'func' => 'str_ireplace',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_holiday_accomodations-hp_television-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_television-rewrite';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_television';
  $feeds_tamper->plugin_id = 'rewrite';
  $feeds_tamper->settings = array(
    'text' => '[hp_bikes_available],[hp_extra_baby_bed_possible],[hp_extra_baby_chair_possible],[hp_internet_connection],[hp_indoor_playground],[hp_outdoor_playground],[hp_parking],[hp_pets_allowed],[hp_picknick_possible],[hp_restaurant_snackbar],[hp_shop],[hp_swimming],[hp_tearoom_bar],[hp_television]',
  );
  $feeds_tamper->weight = 1;
  $feeds_tamper->description = 'Rewrite';
  $export['padvak_holiday_accomodations-hp_television-rewrite'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_twitter-find_replace';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_twitter';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => 'http://',
    'replace' => '',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 1,
    'regex' => TRUE,
    'regex_find' => '/^http\\:\\/\\/$/ui',
  );
  $feeds_tamper->weight = 1;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_holiday_accomodations-hp_twitter-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_twitter-rewrite';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_twitter';
  $feeds_tamper->plugin_id = 'rewrite';
  $feeds_tamper->settings = array(
    'text' => 'http://[hp_twitter]',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Rewrite';
  $export['padvak_holiday_accomodations-hp_twitter-rewrite'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_website-find_replace';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_website';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => 'http://',
    'replace' => '',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 1,
    'regex' => TRUE,
    'regex_find' => '/^http\\:\\/\\/$/ui',
  );
  $feeds_tamper->weight = 1;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_holiday_accomodations-hp_website-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_website-rewrite';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_website';
  $feeds_tamper->plugin_id = 'rewrite';
  $feeds_tamper->settings = array(
    'text' => 'http://[hp_website]',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Rewrite';
  $export['padvak_holiday_accomodations-hp_website-rewrite'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_youtube-find_replace';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_youtube';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => 'http://',
    'replace' => '',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 1,
    'regex' => TRUE,
    'regex_find' => '/^http\\:\\/\\/$/ui',
  );
  $feeds_tamper->weight = 1;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_holiday_accomodations-hp_youtube-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_holiday_accomodations-hp_youtube-rewrite';
  $feeds_tamper->importer = 'padvak_holiday_accomodations';
  $feeds_tamper->source = 'hp_youtube';
  $feeds_tamper->plugin_id = 'rewrite';
  $feeds_tamper->settings = array(
    'text' => 'http://[hp_youtube]',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Rewrite';
  $export['padvak_holiday_accomodations-hp_youtube-rewrite'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_organised_holidays-hp_contract_type-convert_case';
  $feeds_tamper->importer = 'padvak_organised_holidays';
  $feeds_tamper->source = 'hp_contract_type';
  $feeds_tamper->plugin_id = 'convert_case';
  $feeds_tamper->settings = array(
    'mode' => '1',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Convert case';
  $export['padvak_organised_holidays-hp_contract_type-convert_case'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_organised_holidays-hp_contract_type-rewrite';
  $feeds_tamper->importer = 'padvak_organised_holidays';
  $feeds_tamper->source = 'hp_contract_type';
  $feeds_tamper->plugin_id = 'rewrite';
  $feeds_tamper->settings = array(
    'text' => '4[hp_svp_contract_id]',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Rewrite';
  $export['padvak_organised_holidays-hp_contract_type-rewrite'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_organised_holidays-hp_country-default_value';
  $feeds_tamper->importer = 'padvak_organised_holidays';
  $feeds_tamper->source = 'hp_country';
  $feeds_tamper->plugin_id = 'default_value';
  $feeds_tamper->settings = array(
    'default_value' => 'be',
    'only_if_empty' => 0,
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Set value or default value';
  $export['padvak_organised_holidays-hp_country-default_value'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_organised_holidays-hp_facebook-find_replace';
  $feeds_tamper->importer = 'padvak_organised_holidays';
  $feeds_tamper->source = 'hp_facebook';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => 'http://',
    'replace' => '',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 1,
    'regex' => TRUE,
    'regex_find' => '/^http\\:\\/\\/$/ui',
  );
  $feeds_tamper->weight = 1;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_organised_holidays-hp_facebook-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_organised_holidays-hp_facebook-rewrite';
  $feeds_tamper->importer = 'padvak_organised_holidays';
  $feeds_tamper->source = 'hp_facebook';
  $feeds_tamper->plugin_id = 'rewrite';
  $feeds_tamper->settings = array(
    'text' => 'http://[hp_facebook]',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Rewrite';
  $export['padvak_organised_holidays-hp_facebook-rewrite'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_organised_holidays-hp_house_number-rewrite';
  $feeds_tamper->importer = 'padvak_organised_holidays';
  $feeds_tamper->source = 'hp_house_number';
  $feeds_tamper->plugin_id = 'rewrite';
  $feeds_tamper->settings = array(
    'text' => '[hp_street]  [hp_house_number]',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Rewrite';
  $export['padvak_organised_holidays-hp_house_number-rewrite'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_organised_holidays-hp_twitter-find_replace';
  $feeds_tamper->importer = 'padvak_organised_holidays';
  $feeds_tamper->source = 'hp_twitter';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => 'http://',
    'replace' => '',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 1,
    'regex' => TRUE,
    'regex_find' => '/^http\\:\\/\\/$/ui',
  );
  $feeds_tamper->weight = 1;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_organised_holidays-hp_twitter-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_organised_holidays-hp_twitter-rewrite';
  $feeds_tamper->importer = 'padvak_organised_holidays';
  $feeds_tamper->source = 'hp_twitter';
  $feeds_tamper->plugin_id = 'rewrite';
  $feeds_tamper->settings = array(
    'text' => 'http://[hp_twitter]',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Rewrite';
  $export['padvak_organised_holidays-hp_twitter-rewrite'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_organised_holidays-hp_website-find_replace';
  $feeds_tamper->importer = 'padvak_organised_holidays';
  $feeds_tamper->source = 'hp_website';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => 'http://',
    'replace' => '',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 1,
    'regex' => TRUE,
    'regex_find' => '/^http\\:\\/\\/$/ui',
  );
  $feeds_tamper->weight = 1;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_organised_holidays-hp_website-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_organised_holidays-hp_website-rewrite';
  $feeds_tamper->importer = 'padvak_organised_holidays';
  $feeds_tamper->source = 'hp_website';
  $feeds_tamper->plugin_id = 'rewrite';
  $feeds_tamper->settings = array(
    'text' => 'http://[hp_website]',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Rewrite';
  $export['padvak_organised_holidays-hp_website-rewrite'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_organised_holidays-hp_youtube-find_replace';
  $feeds_tamper->importer = 'padvak_organised_holidays';
  $feeds_tamper->source = 'hp_youtube';
  $feeds_tamper->plugin_id = 'find_replace';
  $feeds_tamper->settings = array(
    'find' => 'http://',
    'replace' => '',
    'case_sensitive' => 0,
    'word_boundaries' => 0,
    'whole' => 1,
    'regex' => TRUE,
    'regex_find' => '/^http\\:\\/\\/$/ui',
  );
  $feeds_tamper->weight = 1;
  $feeds_tamper->description = 'Find replace';
  $export['padvak_organised_holidays-hp_youtube-find_replace'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'padvak_organised_holidays-hp_youtube-rewrite';
  $feeds_tamper->importer = 'padvak_organised_holidays';
  $feeds_tamper->source = 'hp_youtube';
  $feeds_tamper->plugin_id = 'rewrite';
  $feeds_tamper->settings = array(
    'text' => 'http://[hp_youtube]',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Rewrite';
  $export['padvak_organised_holidays-hp_youtube-rewrite'] = $feeds_tamper;

  return $export;
}
