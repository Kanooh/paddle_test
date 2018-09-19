<?php
/**
 * @file
 * paddle_holiday_participation.feeds_importer_default.inc
 */

/**
 * Implements hook_feeds_importer_default().
 */
function paddle_holiday_participation_feeds_importer_default() {
  $export = array();
  $feeds_importer = new stdClass();
  $feeds_importer->disabled = FALSE; /* Edit this to true to make a default feeds_importer disabled initially */
  $feeds_importer->api_version = 1;
  $feeds_importer->id = 'padvak_day_trips';
  $feeds_importer->config = array(
    'name' => 'Day trips',
    'description' => '',
    'fetcher' => array(
      'plugin_key' => 'FeedsHTTPFetcher',
      'config' => array(
        'auto_detect_feeds' => 1,
        'use_pubsubhubbub' => 0,
        'designated_hub' => '',
        'request_timeout' => '',
        'auto_scheme' => 'http://test-opendata.visitflanders.org/holidayparticipation/activities/daytrips',
        'accept_invalid_cert' => 0,
      ),
    ),
    'parser' => array(
      'plugin_key' => 'FeedsExJsonPath',
      'config' => array(
        'sources' => array(
          'hp_svp_contract_id' => array(
            'name' => 'SVP contract ID',
            'value' => 'svp_contract_id',
            'debug' => 0,
            'weight' => '0',
          ),
          'hp_name' => array(
            'name' => 'Name',
            'value' => 'name',
            'debug' => 0,
            'weight' => '1',
          ),
          'hp_picknick_possible' => array(
            'name' => 'Picknick possible',
            'value' => 'picknick_possible',
            'debug' => 0,
            'weight' => '2',
          ),
          'hp_restaurant_snackbar' => array(
            'name' => 'Restaurant/snackbar',
            'value' => 'restaurant',
            'debug' => 0,
            'weight' => '3',
          ),
          'hp_indoor_playground' => array(
            'name' => 'Indoor playground',
            'value' => 'indoor_play_ground',
            'debug' => 0,
            'weight' => '4',
          ),
          'hp_outdoor_playground' => array(
            'name' => 'Outdoor playground',
            'value' => 'play_ground',
            'debug' => 0,
            'weight' => '5',
          ),
          'hp_parking' => array(
            'name' => 'Parking',
            'value' => 'parking',
            'debug' => 0,
            'weight' => '6',
          ),
          'hp_pets_allowed' => array(
            'name' => 'Pets Allowed',
            'value' => 'pets_allowed',
            'debug' => 0,
            'weight' => '7',
          ),
          'hp_facebook' => array(
            'name' => 'Facebook',
            'value' => 'facebook',
            'debug' => 0,
            'weight' => '8',
          ),
          'hp_product_description' => array(
            'name' => 'Product description',
            'value' => 'product_description',
            'debug' => 0,
            'weight' => '9',
          ),
          'hp_twitter' => array(
            'name' => 'Twitter',
            'value' => 'twitter',
            'debug' => 0,
            'weight' => '10',
          ),
          'hp_youtube' => array(
            'name' => 'YouTube',
            'value' => 'youtube',
            'debug' => 0,
            'weight' => '11',
          ),
          'hp_website' => array(
            'name' => 'website',
            'value' => 'website',
            'debug' => 0,
            'weight' => '12',
          ),
          'hp_private_transport_description' => array(
            'name' => 'private transport description',
            'value' => 'private_transport_description',
            'debug' => 0,
            'weight' => '13',
          ),
          'hp_public_transport_description' => array(
            'name' => 'public transport description',
            'value' => 'public_transport_description',
            'debug' => 0,
            'weight' => '14',
          ),
          'hp_validity_period_description' => array(
            'name' => 'validity period description',
            'value' => 'validity_period_description',
            'debug' => 0,
            'weight' => '15',
          ),
          'hp_opening_hours' => array(
            'name' => 'opening hours',
            'value' => 'opening_hours',
            'debug' => 0,
            'weight' => '16',
          ),
          'hp_imagesurlmain' => array(
            'name' => 'Featured image',
            'value' => 'imagesurlmain',
            'debug' => 0,
            'weight' => '17',
          ),
          'hp_street' => array(
            'name' => 'Street',
            'value' => 'street',
            'debug' => 0,
            'weight' => '18',
          ),
          'hp_house_number' => array(
            'name' => 'House number',
            'value' => 'house_number',
            'debug' => 0,
            'weight' => '19',
          ),
          'hp_box_number' => array(
            'name' => 'Box number',
            'value' => 'box_number',
            'debug' => 0,
            'weight' => '20',
          ),
          'hp_postal_code' => array(
            'name' => 'Postal code',
            'value' => 'postal_code',
            'debug' => 0,
            'weight' => '21',
          ),
          'hp_city_name' => array(
            'name' => 'City name',
            'value' => 'city_name',
            'debug' => 0,
            'weight' => '22',
          ),
          'hp_country' => array(
            'name' => 'Country',
            'value' => 'country',
            'debug' => 0,
            'weight' => '23',
          ),
          'hp_contract_type' => array(
            'name' => 'Contract type',
            'value' => 'contract_type',
            'debug' => 0,
            'weight' => '24',
          ),
          'hp_is_deleted' => array(
            'name' => 'Is deleted',
            'value' => 'deleted',
            'debug' => 0,
            'weight' => '25',
          ),
          'hp_province' => array(
            'name' => 'Province',
            'value' => 'province',
            'debug' => 0,
            'weight' => '26',
          ),
          'hp_resv_period_individual' => array(
            'name' => 'Reservation period individual',
            'value' => 'reservation_period_individual',
            'debug' => 0,
            'weight' => '27',
          ),
          'hp_resv_period_group' => array(
            'name' => 'Reservation period group ',
            'value' => 'reservation_period_group',
            'debug' => 0,
            'weight' => '28',
          ),
          'hp_resv_comments' => array(
            'name' => 'Reservation comments',
            'value' => 'reservation_comments',
            'debug' => 0,
            'weight' => '29',
          ),
          'hp_base_contract_id' => array(
            'name' => 'Base contract id',
            'value' => 'base_contract_id',
            'debug' => 0,
            'weight' => '30',
          ),
          'hp_imagesurl' => array(
            'name' => 'Imagesurl',
            'value' => 'imagesurl',
            'debug' => 0,
            'weight' => '31',
          ),
          'hp_validity_period' => array(
            'name' => 'Validity period',
            'value' => 'validity_period',
            'debug' => 0,
            'weight' => '32',
          ),
          'hp_contract_start_year' => array(
            'name' => 'Contract start year',
            'value' => 'contract_start_year',
            'debug' => 0,
            'weight' => '33',
          ),
          'hp_contract_end_year' => array(
            'name' => 'Contract end year',
            'value' => 'contract_end_year',
            'debug' => 0,
            'weight' => '34',
          ),
        ),
        'context' => array(
          'value' => '$.*',
        ),
        'display_errors' => 0,
        'source_encoding' => array(
          0 => 'auto',
        ),
        'debug_mode' => 0,
      ),
    ),
    'processor' => array(
      'plugin_key' => 'FeedsNodeProcessor',
      'config' => array(
        'expire' => '-1',
        'author' => 0,
        'authorize' => 0,
        'mappings' => array(
          0 => array(
            'source' => 'hp_svp_contract_id',
            'target' => 'field_hp_svp_contract_id',
            'unique' => TRUE,
          ),
          1 => array(
            'source' => 'hp_name',
            'target' => 'title',
            'unique' => FALSE,
          ),
          2 => array(
            'source' => 'hp_opening_hours',
            'target' => 'field_hp_opening_hours',
            'unique' => FALSE,
          ),
          3 => array(
            'source' => 'hp_private_transport_description',
            'target' => 'field_hp_private_transport_desc',
            'unique' => FALSE,
          ),
          4 => array(
            'source' => 'hp_public_transport_description',
            'target' => 'field_hp_public_transport_desc',
            'unique' => FALSE,
          ),
          5 => array(
            'source' => 'hp_validity_period_description',
            'target' => 'field_hp_validity_period_desc',
            'unique' => FALSE,
          ),
          6 => array(
            'source' => 'hp_indoor_playground',
            'target' => 'field_hp_facilities',
            'unique' => FALSE,
          ),
          7 => array(
            'source' => 'hp_outdoor_playground',
            'target' => 'field_hp_facilities',
            'unique' => FALSE,
          ),
          8 => array(
            'source' => 'hp_parking',
            'target' => 'field_hp_facilities',
            'unique' => FALSE,
          ),
          9 => array(
            'source' => 'hp_pets_allowed',
            'target' => 'field_hp_facilities',
            'unique' => FALSE,
          ),
          10 => array(
            'source' => 'hp_picknick_possible',
            'target' => 'field_hp_facilities',
            'unique' => FALSE,
          ),
          11 => array(
            'source' => 'hp_restaurant_snackbar',
            'target' => 'field_hp_facilities',
            'unique' => FALSE,
          ),
          12 => array(
            'source' => 'hp_facebook',
            'target' => 'field_hp_facebook:value',
            'unique' => FALSE,
          ),
          13 => array(
            'source' => 'hp_twitter',
            'target' => 'field_hp_twitter:value',
            'unique' => FALSE,
          ),
          14 => array(
            'source' => 'hp_youtube',
            'target' => 'field_hp_youtube:value',
            'unique' => FALSE,
          ),
          15 => array(
            'source' => 'hp_website',
            'target' => 'field_hp_website:value',
            'unique' => FALSE,
          ),
          16 => array(
            'source' => 'hp_imagesurlmain',
            'target' => 'field_hp_main_image_url:value',
            'unique' => FALSE,
          ),
          17 => array(
            'source' => 'hp_imagesurlmain',
            'target' => 'field_paddle_featured_image',
            'unique' => FALSE,
          ),
          18 => array(
            'source' => 'hp_product_description',
            'target' => 'body',
            'format' => 'full_html',
          ),
          19 => array(
            'source' => 'hp_name',
            'target' => 'field_hp_address:name_line',
            'unique' => FALSE,
          ),
          20 => array(
            'source' => 'hp_street',
            'target' => 'field_hp_address:thoroughfare',
            'unique' => FALSE,
          ),
          21 => array(
            'source' => 'hp_house_number',
            'target' => 'field_hp_address:thoroughfare',
            'unique' => FALSE,
          ),
          22 => array(
            'source' => 'hp_box_number',
            'target' => 'field_hp_address:premise',
            'unique' => FALSE,
          ),
          23 => array(
            'source' => 'hp_postal_code',
            'target' => 'field_hp_address:postal_code',
            'unique' => FALSE,
          ),
          24 => array(
            'source' => 'hp_city_name',
            'target' => 'field_hp_address:locality',
            'unique' => FALSE,
          ),
          25 => array(
            'source' => 'hp_country',
            'target' => 'field_hp_address:country',
            'unique' => FALSE,
          ),
          26 => array(
            'source' => 'Blank source 1',
            'target' => 'guid',
            'unique' => TRUE,
          ),
          27 => array(
            'source' => 'hp_contract_type',
            'target' => 'field_hp_contract_type',
            'unique' => FALSE,
          ),
          28 => array(
            'source' => 'hp_resv_period_individual',
            'target' => 'field_hp_resv_period_individual',
            'unique' => FALSE,
          ),
          29 => array(
            'source' => 'hp_resv_period_group',
            'target' => 'field_hp_resv_period_group',
            'unique' => FALSE,
          ),
          30 => array(
            'source' => 'hp_resv_comments',
            'target' => 'field_hp_resv_comments',
            'unique' => FALSE,
          ),
          31 => array(
            'source' => 'hp_base_contract_id',
            'target' => 'field_hp_base_contract_id',
            'unique' => FALSE,
          ),
          32 => array(
            'source' => 'hp_imagesurl',
            'target' => 'imagesurl',
            'unique' => FALSE,
          ),
          33 => array(
            'source' => 'hp_validity_period',
            'target' => 'validity_period',
            'unique' => FALSE,
          ),
          34 => array(
            'source' => 'hp_contract_start_year',
            'target' => 'field_hp_contract_start_year',
            'unique' => FALSE,
          ),
          35 => array(
            'source' => 'hp_contract_end_year',
            'target' => 'field_hp_contract_end_year',
            'unique' => FALSE,
          ),
        ),
        'update_existing' => '2',
        'update_non_existent' => 'delete',
        'input_format' => 'plain_text',
        'skip_hash_check' => 1,
        'bundle' => 'offer',
      ),
    ),
    'content_type' => '',
    'update' => 0,
    'import_period' => '86400',
    'expire_period' => 3600,
    'import_on_create' => 0,
    'process_in_background' => 0,
  );
  $export['padvak_day_trips'] = $feeds_importer;

  $feeds_importer = new stdClass();
  $feeds_importer->disabled = FALSE; /* Edit this to true to make a default feeds_importer disabled initially */
  $feeds_importer->api_version = 1;
  $feeds_importer->id = 'padvak_group_accomodations';
  $feeds_importer->config = array(
    'name' => 'Group Accomodations',
    'description' => '',
    'fetcher' => array(
      'plugin_key' => 'FeedsHTTPFetcher',
      'config' => array(
        'auto_detect_feeds' => 1,
        'use_pubsubhubbub' => 0,
        'designated_hub' => '',
        'request_timeout' => '',
        'auto_scheme' => 'http://test-opendata.visitflanders.org/holidayparticipation/accommodation/groupaccommodations',
        'accept_invalid_cert' => 0,
      ),
    ),
    'parser' => array(
      'plugin_key' => 'FeedsExJsonPath',
      'config' => array(
        'sources' => array(
          'hp_svp_contract_id' => array(
            'name' => 'SVP contract ID',
            'value' => 'svp_contract_id',
            'debug' => 0,
            'weight' => '0',
          ),
          'hp_name' => array(
            'name' => 'Name',
            'value' => 'name',
            'debug' => 0,
            'weight' => '1',
          ),
          'hp_facebook' => array(
            'name' => 'Facebook',
            'value' => 'facebook',
            'debug' => 0,
            'weight' => '2',
          ),
          'hp_twitter' => array(
            'name' => 'Twitter',
            'value' => 'twitter',
            'debug' => 0,
            'weight' => '3',
          ),
          'hp_youtube' => array(
            'name' => 'YouTube',
            'value' => 'youtube',
            'debug' => 0,
            'weight' => '4',
          ),
          'hp_website' => array(
            'name' => 'Website',
            'value' => 'website',
            'debug' => 0,
            'weight' => '5',
          ),
          'hp_picknick_possible' => array(
            'name' => 'Picknick possible',
            'value' => 'picknick_possible',
            'debug' => 0,
            'weight' => '6',
          ),
          'hp_restaurant_snackbar' => array(
            'name' => 'Restaurant/snackbar',
            'value' => 'restaurant',
            'debug' => 0,
            'weight' => '7',
          ),
          'hp_indoor_playground' => array(
            'name' => 'Indoor playground',
            'value' => 'indoor_play_ground',
            'debug' => 0,
            'weight' => '8',
          ),
          'hp_outdoor_playground' => array(
            'name' => 'Outdoor playground',
            'value' => 'play_ground',
            'debug' => 0,
            'weight' => '9',
          ),
          'hp_parking' => array(
            'name' => 'Parking',
            'value' => 'parking',
            'debug' => 0,
            'weight' => '10',
          ),
          'hp_pets_allowed' => array(
            'name' => 'Pets Allowed',
            'value' => 'pets_allowed',
            'debug' => 0,
            'weight' => '11',
          ),
          'hp_tearoom_bar' => array(
            'name' => 'Tearoom/bar',
            'value' => 'bar_cafe_cafetaria',
            'debug' => 0,
            'weight' => '12',
          ),
          'hp_shop' => array(
            'name' => 'Shop',
            'value' => 'shop',
            'debug' => 0,
            'weight' => '13',
          ),
          'hp_swimming' => array(
            'name' => 'Swimming',
            'value' => 'swimming',
            'debug' => 0,
            'weight' => '14',
          ),
          'hp_television' => array(
            'name' => 'Television',
            'value' => 'television',
            'debug' => 0,
            'weight' => '15',
          ),
          'hp_internet_connection' => array(
            'name' => 'Internet connection',
            'value' => 'internet_connection',
            'debug' => 0,
            'weight' => '16',
          ),
          'hp_extra_baby_bed_possible' => array(
            'name' => 'Extra baby bed possible',
            'value' => 'extra_baby_bed_possible',
            'debug' => 0,
            'weight' => '17',
          ),
          'hp_extra_baby_chair_possible' => array(
            'name' => 'Extra baby chair possible',
            'value' => 'extra_baby_chair_possible',
            'debug' => 0,
            'weight' => '18',
          ),
          'hp_bikes_available' => array(
            'name' => 'Bikes Available',
            'value' => 'bikes_available',
            'debug' => 0,
            'weight' => '19',
          ),
          'hp_self_cooking' => array(
            'name' => 'Self cooking',
            'value' => 'self_cooking',
            'debug' => 0,
            'weight' => '20',
          ),
          'hp_bed_and_breakfast' => array(
            'name' => 'Bed and breakfast',
            'value' => 'bed_breakfast',
            'debug' => 0,
            'weight' => '21',
          ),
          'hp_half_board' => array(
            'name' => 'Half Board',
            'value' => 'half_board',
            'debug' => 0,
            'weight' => '22',
          ),
          'hp_full_board' => array(
            'name' => 'Full Board',
            'value' => 'full_board',
            'debug' => 0,
            'weight' => '23',
          ),
          'hp_green_key_label' => array(
            'name' => 'Green key',
            'value' => 'green_key_labeled',
            'debug' => 0,
            'weight' => '24',
          ),
          'hp_cycling_label' => array(
            'name' => 'Bicycle-friendly accommodation',
            'value' => 'cycling_label',
            'debug' => 0,
            'weight' => '25',
          ),
          'hp_accessibility_label' => array(
            'name' => 'Accessibility Label',
            'value' => 'accessibility_label',
            'debug' => 0,
            'weight' => '26',
          ),
          'hp_surroundings_description' => array(
            'name' => 'Surroundings description',
            'value' => 'surroundings_description',
            'debug' => 0,
            'weight' => '27',
          ),
          'hp_residence_description' => array(
            'name' => 'Residence description',
            'value' => 'residence_description',
            'debug' => 0,
            'weight' => '28',
          ),
          'hp_on_the_spot_description' => array(
            'name' => 'On the spot description',
            'value' => 'on_the_spot_description',
            'debug' => 0,
            'weight' => '29',
          ),
          'hp_public_transport_description' => array(
            'name' => 'public transport description',
            'value' => 'public_transport_description',
            'debug' => 0,
            'weight' => '30',
          ),
          'hp_private_transport_description' => array(
            'name' => 'private transport description',
            'value' => 'private_transport_description',
            'debug' => 0,
            'weight' => '31',
          ),
          'hp_validity_period_description' => array(
            'name' => 'validity period description',
            'value' => 'validity_period_description',
            'debug' => 0,
            'weight' => '32',
          ),
          'hp_opening_hours' => array(
            'name' => 'opening hours',
            'value' => 'opening_hours',
            'debug' => 0,
            'weight' => '33',
          ),
          'hp_imagesurlmain' => array(
            'name' => 'Featured image',
            'value' => 'imagesurlmain',
            'debug' => 0,
            'weight' => '34',
          ),
          'hp_product_description' => array(
            'name' => 'Product description',
            'value' => 'product_description',
            'debug' => 0,
            'weight' => '35',
          ),
          'hp_street' => array(
            'name' => 'Street',
            'value' => 'street',
            'debug' => 0,
            'weight' => '36',
          ),
          'hp_house_number' => array(
            'name' => 'House number',
            'value' => 'house_number',
            'debug' => 0,
            'weight' => '37',
          ),
          'hp_box_number' => array(
            'name' => 'Box number',
            'value' => 'box_number',
            'debug' => 0,
            'weight' => '38',
          ),
          'hp_postal_code' => array(
            'name' => 'Postal code',
            'value' => 'postal_code',
            'debug' => 0,
            'weight' => '39',
          ),
          'hp_city_name' => array(
            'name' => 'City name',
            'value' => 'city_name',
            'debug' => 0,
            'weight' => '40',
          ),
          'hp_country' => array(
            'name' => 'Country',
            'value' => 'country',
            'debug' => 0,
            'weight' => '41',
          ),
          'hp_contract_type' => array(
            'name' => 'Contract type',
            'value' => 'contract_type',
            'debug' => 0,
            'weight' => '42',
          ),
          'hp_is_deleted' => array(
            'name' => 'Is deleted',
            'value' => 'deleted',
            'debug' => 0,
            'weight' => '43',
          ),
          'hp_province' => array(
            'name' => 'hp_province',
            'value' => 'province',
            'debug' => 0,
            'weight' => '44',
          ),
          'hp_base_contract_id' => array(
            'name' => 'Base contract id',
            'value' => 'base_contract_id',
            'debug' => 0,
            'weight' => '45',
          ),
          'hp_imagesurl' => array(
            'name' => 'Imagesurl',
            'value' => 'imagesurl',
            'debug' => 0,
            'weight' => '45',
          ),
          'hp_max_capacity' => array(
            'name' => 'Max capacity',
            'value' => 'max_capacity',
            'debug' => 0,
            'weight' => '46',
          ),
          'hp_min_capacity' => array(
            'name' => 'Min capacity',
            'value' => 'min_capacity',
            'debug' => 0,
            'weight' => '47',
          ),
          'hp_validity_period' => array(
            'name' => 'Validity period',
            'value' => 'validity_period',
            'debug' => 0,
            'weight' => '48',
          ),
          'contract_start_year' => array(
            'name' => 'Contract start year',
            'value' => 'contract_start_year',
            'debug' => 0,
            'weight' => '49',
          ),
          'hp_contract_end_year' => array(
            'name' => 'Contract end year',
            'value' => 'contract_end_year',
            'debug' => 0,
            'weight' => '50',
          ),
        ),
        'context' => array(
          'value' => '$.*',
        ),
        'display_errors' => 0,
        'source_encoding' => array(
          0 => 'auto',
        ),
        'debug_mode' => 0,
      ),
    ),
    'processor' => array(
      'plugin_key' => 'FeedsNodeProcessor',
      'config' => array(
        'expire' => '-1',
        'author' => 0,
        'authorize' => 0,
        'mappings' => array(
          0 => array(
            'source' => 'hp_svp_contract_id',
            'target' => 'field_hp_svp_contract_id',
            'unique' => TRUE,
          ),
          1 => array(
            'source' => 'hp_name',
            'target' => 'title',
            'unique' => FALSE,
          ),
          2 => array(
            'source' => 'hp_bikes_available',
            'target' => 'field_hp_facilities',
            'unique' => FALSE,
          ),
          3 => array(
            'source' => 'hp_extra_baby_bed_possible',
            'target' => 'field_hp_facilities',
            'unique' => FALSE,
          ),
          4 => array(
            'source' => 'hp_extra_baby_chair_possible',
            'target' => 'field_hp_facilities',
            'unique' => FALSE,
          ),
          5 => array(
            'source' => 'hp_internet_connection',
            'target' => 'field_hp_facilities',
            'unique' => FALSE,
          ),
          6 => array(
            'source' => 'hp_indoor_playground',
            'target' => 'field_hp_facilities',
            'unique' => FALSE,
          ),
          7 => array(
            'source' => 'hp_outdoor_playground',
            'target' => 'field_hp_facilities',
            'unique' => FALSE,
          ),
          8 => array(
            'source' => 'hp_parking',
            'target' => 'field_hp_facilities',
            'unique' => FALSE,
          ),
          9 => array(
            'source' => 'hp_pets_allowed',
            'target' => 'field_hp_facilities',
            'unique' => FALSE,
          ),
          10 => array(
            'source' => 'hp_picknick_possible',
            'target' => 'field_hp_facilities',
            'unique' => FALSE,
          ),
          11 => array(
            'source' => 'hp_restaurant_snackbar',
            'target' => 'field_hp_facilities',
            'unique' => FALSE,
          ),
          12 => array(
            'source' => 'hp_shop',
            'target' => 'field_hp_facilities',
            'unique' => FALSE,
          ),
          13 => array(
            'source' => 'hp_swimming',
            'target' => 'field_hp_facilities',
            'unique' => FALSE,
          ),
          14 => array(
            'source' => 'hp_tearoom_bar',
            'target' => 'field_hp_facilities',
            'unique' => FALSE,
          ),
          15 => array(
            'source' => 'hp_television',
            'target' => 'field_hp_facilities',
            'unique' => FALSE,
          ),
          16 => array(
            'source' => 'hp_full_board',
            'target' => 'field_hp_room_and_board',
            'unique' => FALSE,
          ),
          17 => array(
            'source' => 'hp_half_board',
            'target' => 'field_hp_room_and_board',
            'unique' => FALSE,
          ),
          18 => array(
            'source' => 'hp_bed_and_breakfast',
            'target' => 'field_hp_room_and_board',
            'unique' => FALSE,
          ),
          19 => array(
            'source' => 'hp_self_cooking',
            'target' => 'field_hp_room_and_board',
            'unique' => FALSE,
          ),
          20 => array(
            'source' => 'hp_accessibility_label',
            'target' => 'field_hp_labels',
            'unique' => FALSE,
          ),
          21 => array(
            'source' => 'hp_cycling_label',
            'target' => 'field_hp_labels',
            'unique' => FALSE,
          ),
          22 => array(
            'source' => 'hp_green_key_label',
            'target' => 'field_hp_labels',
            'unique' => FALSE,
          ),
          23 => array(
            'source' => 'hp_facebook',
            'target' => 'field_hp_facebook:value',
            'unique' => FALSE,
          ),
          24 => array(
            'source' => 'hp_surroundings_description',
            'target' => 'field_hp_surroundings_desc',
            'unique' => FALSE,
          ),
          25 => array(
            'source' => 'hp_residence_description',
            'target' => 'field_hp_residence_desc',
            'unique' => FALSE,
          ),
          26 => array(
            'source' => 'hp_on_the_spot_description',
            'target' => 'field_hp_on_the_spot_desc',
          ),
          27 => array(
            'source' => 'hp_opening_hours',
            'target' => 'field_hp_opening_hours',
            'unique' => FALSE,
          ),
          28 => array(
            'source' => 'hp_private_transport_description',
            'target' => 'field_hp_private_transport_desc',
            'unique' => FALSE,
          ),
          29 => array(
            'source' => 'hp_public_transport_description',
            'target' => 'field_hp_public_transport_desc',
            'unique' => FALSE,
          ),
          30 => array(
            'source' => 'hp_validity_period_description',
            'target' => 'field_hp_validity_period_desc',
            'unique' => FALSE,
          ),
          31 => array(
            'source' => 'hp_twitter',
            'target' => 'field_hp_twitter:value',
            'unique' => FALSE,
          ),
          32 => array(
            'source' => 'hp_youtube',
            'target' => 'field_hp_youtube:value',
            'unique' => FALSE,
          ),
          33 => array(
            'source' => 'hp_website',
            'target' => 'field_hp_website:value',
            'unique' => FALSE,
          ),
          34 => array(
            'source' => 'hp_imagesurlmain',
            'target' => 'field_hp_main_image_url:value',
            'unique' => FALSE,
          ),
          35 => array(
            'source' => 'hp_imagesurlmain',
            'target' => 'field_paddle_featured_image',
            'unique' => FALSE,
          ),
          36 => array(
            'source' => 'hp_product_description',
            'target' => 'body',
            'format' => 'full_html',
          ),
          37 => array(
            'source' => 'hp_name',
            'target' => 'field_hp_address:name_line',
            'unique' => FALSE,
          ),
          38 => array(
            'source' => 'hp_street',
            'target' => 'field_hp_address:thoroughfare',
            'unique' => FALSE,
          ),
          39 => array(
            'source' => 'hp_house_number',
            'target' => 'field_hp_address:thoroughfare',
            'unique' => FALSE,
          ),
          40 => array(
            'source' => 'hp_box_number',
            'target' => 'field_hp_address:premise',
            'unique' => FALSE,
          ),
          41 => array(
            'source' => 'hp_postal_code',
            'target' => 'field_hp_address:postal_code',
            'unique' => FALSE,
          ),
          42 => array(
            'source' => 'hp_city_name',
            'target' => 'field_hp_address:locality',
            'unique' => FALSE,
          ),
          43 => array(
            'source' => 'hp_country',
            'target' => 'field_hp_address:country',
            'unique' => FALSE,
          ),
          44 => array(
            'source' => 'Blank source 1',
            'target' => 'guid',
            'unique' => TRUE,
          ),
          45 => array(
            'source' => 'hp_contract_type',
            'target' => 'field_hp_contract_type',
            'unique' => FALSE,
          ),
          46 => array(
            'source' => 'hp_base_contract_id',
            'target' => 'field_hp_base_contract_id',
            'unique' => FALSE,
          ),
          47 => array(
            'source' => 'hp_imagesurl',
            'target' => 'imagesurl',
            'unique' => FALSE,
          ),
          48 => array(
            'source' => 'hp_min_capacity',
            'target' => 'field_hp_min_capacity',
            'unique' => FALSE,
          ),
          49 => array(
            'source' => 'hp_max_capacity',
            'target' => 'field_hp_max_capacity',
            'unique' => FALSE,
          ),
          50 => array(
            'source' => 'hp_validity_period',
            'target' => 'validity_period',
            'unique' => FALSE,
          ),
          51 => array(
            'source' => 'hp_contract_end_year',
            'target' => 'field_hp_contract_end_year',
            'unique' => FALSE,
          ),
          52 => array(
            'source' => 'contract_start_year',
            'target' => 'field_hp_contract_start_year',
            'unique' => FALSE,
          ),
        ),
        'update_existing' => '2',
        'update_non_existent' => 'delete',
        'input_format' => 'plain_text',
        'skip_hash_check' => 1,
        'bundle' => 'offer',
      ),
    ),
    'content_type' => '',
    'update' => 0,
    'import_period' => '86400',
    'expire_period' => 3600,
    'import_on_create' => 0,
    'process_in_background' => 0,
  );
  $export['padvak_group_accomodations'] = $feeds_importer;

  $feeds_importer = new stdClass();
  $feeds_importer->disabled = FALSE; /* Edit this to true to make a default feeds_importer disabled initially */
  $feeds_importer->api_version = 1;
  $feeds_importer->id = 'padvak_holiday_accomodations';
  $feeds_importer->config = array(
    'name' => 'Holiday accomodations',
    'description' => '',
    'fetcher' => array(
      'plugin_key' => 'FeedsHTTPFetcher',
      'config' => array(
        'auto_detect_feeds' => 1,
        'use_pubsubhubbub' => 0,
        'designated_hub' => '',
        'request_timeout' => '',
        'auto_scheme' => 'http://test-opendata.visitflanders.org/holidayparticipation/accommodation/holidayaccommodations',
        'accept_invalid_cert' => 0,
      ),
    ),
    'parser' => array(
      'plugin_key' => 'FeedsExJsonPath',
      'config' => array(
        'sources' => array(
          'hp_svp_contract_id' => array(
            'name' => 'SVP contract ID',
            'value' => 'svp_contract_id',
            'debug' => 0,
            'weight' => '0',
          ),
          'hp_name' => array(
            'name' => 'Name',
            'value' => 'name',
            'debug' => 0,
            'weight' => '1',
          ),
          'hp_facebook' => array(
            'name' => 'Facebook',
            'value' => 'facebook',
            'debug' => 0,
            'weight' => '2',
          ),
          'hp_twitter' => array(
            'name' => 'Twitter',
            'value' => 'twitter',
            'debug' => 0,
            'weight' => '3',
          ),
          'hp_youtube' => array(
            'name' => 'YouTube',
            'value' => 'youtube',
            'debug' => 0,
            'weight' => '4',
          ),
          'hp_website' => array(
            'name' => 'Website',
            'value' => 'website',
            'debug' => 0,
            'weight' => '5',
          ),
          'hp_surroundings_description' => array(
            'name' => 'Surroundings description',
            'value' => 'surroundings_description',
            'debug' => 0,
            'weight' => '6',
          ),
          'hp_residence_description' => array(
            'name' => 'Residence description',
            'value' => 'residence_description',
            'debug' => 0,
            'weight' => '7',
          ),
          'hp_on_the_spot_description' => array(
            'name' => 'On the spot description',
            'value' => 'on_the_spot_description',
            'debug' => 0,
            'weight' => '8',
          ),
          'hp_private_transport_description' => array(
            'name' => 'private transport description',
            'value' => 'private_transport_description',
            'debug' => 0,
            'weight' => '9',
          ),
          'hp_public_transport_description' => array(
            'name' => 'public transport description',
            'value' => 'public_transport_description',
            'debug' => 0,
            'weight' => '10',
          ),
          'hp_validity_period_description' => array(
            'name' => 'validity period description',
            'value' => 'validity_period_description',
            'debug' => 0,
            'weight' => '11',
          ),
          'hp_opening_hours' => array(
            'name' => 'opening hours',
            'value' => 'opening_hours',
            'debug' => 0,
            'weight' => '12',
          ),
          'hp_imagesurlmain' => array(
            'name' => 'Featured image',
            'value' => 'imagesurlmain',
            'debug' => 0,
            'weight' => '13',
          ),
          'hp_picknick_possible' => array(
            'name' => 'Picknick possible',
            'value' => 'picknick_possible',
            'debug' => 0,
            'weight' => '14',
          ),
          'hp_restaurant_snackbar' => array(
            'name' => 'Restaurant/snackbar',
            'value' => 'restaurant',
            'debug' => 0,
            'weight' => '15',
          ),
          'hp_indoor_playground' => array(
            'name' => 'Indoor playground',
            'value' => 'indoor_play_ground',
            'debug' => 0,
            'weight' => '16',
          ),
          'hp_outdoor_playground' => array(
            'name' => 'Outdoor playground',
            'value' => 'play_ground',
            'debug' => 0,
            'weight' => '17',
          ),
          'hp_parking' => array(
            'name' => 'Parking',
            'value' => 'parking',
            'debug' => 0,
            'weight' => '18',
          ),
          'hp_pets_allowed' => array(
            'name' => 'Pets Allowed',
            'value' => 'pets_allowed',
            'debug' => 0,
            'weight' => '19',
          ),
          'hp_tearoom_bar' => array(
            'name' => 'Tearoom/bar',
            'value' => 'bar_cafe_cafetaria',
            'debug' => 0,
            'weight' => '20',
          ),
          'hp_shop' => array(
            'name' => 'Shop',
            'value' => 'shop',
            'debug' => 0,
            'weight' => '21',
          ),
          'hp_swimming' => array(
            'name' => 'Swimming',
            'value' => 'swimming',
            'debug' => 0,
            'weight' => '22',
          ),
          'hp_television' => array(
            'name' => 'Television',
            'value' => 'television',
            'debug' => 0,
            'weight' => '23',
          ),
          'hp_internet_connection' => array(
            'name' => 'Internet connection',
            'value' => 'internet_connection',
            'debug' => 0,
            'weight' => '24',
          ),
          'hp_extra_baby_bed_possible' => array(
            'name' => 'Extra baby bed possible',
            'value' => 'extra_baby_bed_possible',
            'debug' => 0,
            'weight' => '25',
          ),
          'hp_extra_baby_chair_possible' => array(
            'name' => 'Extra baby chair possible',
            'value' => 'extra_baby_chair_possible',
            'debug' => 0,
            'weight' => '26',
          ),
          'hp_bikes_available' => array(
            'name' => 'Bikes Available',
            'value' => 'bikes_available',
            'debug' => 0,
            'weight' => '27',
          ),
          'hp_self_cooking' => array(
            'name' => 'Self cooking',
            'value' => 'self_cooking',
            'debug' => 0,
            'weight' => '28',
          ),
          'hp_bed_and_breakfast' => array(
            'name' => 'Bed and breakfast',
            'value' => 'bed_breakfast',
            'debug' => 0,
            'weight' => '29',
          ),
          'hp_half_board' => array(
            'name' => 'Half Board',
            'value' => 'half_board',
            'debug' => 0,
            'weight' => '30',
          ),
          'hp_full_board' => array(
            'name' => 'Full Board',
            'value' => 'full_board',
            'debug' => 0,
            'weight' => '31',
          ),
          'hp_green_key_label' => array(
            'name' => 'Green key',
            'value' => 'green_key_labeled',
            'debug' => 0,
            'weight' => '32',
          ),
          'hp_cycling_label' => array(
            'name' => 'Bicycle-friendly accommodation',
            'value' => 'cycling_label',
            'debug' => 0,
            'weight' => '33',
          ),
          'hp_accessibility_label' => array(
            'name' => 'Accessibility Label',
            'value' => 'accessibility_label',
            'debug' => 0,
            'weight' => '34',
          ),
          'hp_product_description' => array(
            'name' => 'Product description',
            'value' => 'product_description',
            'debug' => 0,
            'weight' => '35',
          ),
          'hp_street' => array(
            'name' => 'Street',
            'value' => 'street',
            'debug' => 0,
            'weight' => '36',
          ),
          'hp_house_number' => array(
            'name' => 'House number',
            'value' => 'house_number',
            'debug' => 0,
            'weight' => '37',
          ),
          'hp_box_number' => array(
            'name' => 'Box number',
            'value' => 'box_number',
            'debug' => 0,
            'weight' => '38',
          ),
          'hp_postal_code' => array(
            'name' => 'Postal code',
            'value' => 'postal_code',
            'debug' => 0,
            'weight' => '39',
          ),
          'hp_city_name' => array(
            'name' => 'City name',
            'value' => 'city_name',
            'debug' => 0,
            'weight' => '40',
          ),
          'hp_country' => array(
            'name' => 'Country',
            'value' => 'country',
            'debug' => 0,
            'weight' => '41',
          ),
          'hp_contract_type' => array(
            'name' => 'Contract type',
            'value' => 'contract_type',
            'debug' => 0,
            'weight' => '42',
          ),
          'hp_is_deleted' => array(
            'name' => 'Is deleted',
            'value' => 'deleted',
            'debug' => 0,
            'weight' => '43',
          ),
          'hp_province' => array(
            'name' => 'Province',
            'value' => 'province',
            'debug' => 0,
            'weight' => '44',
          ),
          'hp_base_contract_id' => array(
            'name' => 'Base contract id',
            'value' => 'base_contract_id',
            'debug' => 0,
            'weight' => '45',
          ),
          'hp_imagesurl' => array(
            'name' => 'Imagesurl',
            'value' => 'imagesurl',
            'debug' => 0,
            'weight' => '45',
          ),
          'hp_validity_period' => array(
            'name' => 'Validity period',
            'value' => 'validity_period',
            'debug' => 0,
            'weight' => '46',
          ),
          'hp_contract_start_year' => array(
            'name' => 'Contract start year',
            'value' => 'contract_start_year',
            'debug' => 0,
            'weight' => '47',
          ),
          'hp_contract_end_year' => array(
            'name' => 'Contract end year',
            'value' => 'contract_end_year',
            'debug' => 0,
            'weight' => '48',
          ),
        ),
        'context' => array(
          'value' => '$.*',
        ),
        'display_errors' => 0,
        'source_encoding' => array(
          0 => 'auto',
        ),
        'debug_mode' => 0,
      ),
    ),
    'processor' => array(
      'plugin_key' => 'FeedsNodeProcessor',
      'config' => array(
        'expire' => '-1',
        'author' => 0,
        'authorize' => 0,
        'mappings' => array(
          0 => array(
            'source' => 'hp_svp_contract_id',
            'target' => 'field_hp_svp_contract_id',
            'unique' => TRUE,
          ),
          1 => array(
            'source' => 'hp_name',
            'target' => 'title',
            'unique' => FALSE,
          ),
          2 => array(
            'source' => 'hp_bikes_available',
            'target' => 'field_hp_facilities',
            'unique' => FALSE,
          ),
          3 => array(
            'source' => 'hp_extra_baby_bed_possible',
            'target' => 'field_hp_facilities',
            'unique' => FALSE,
          ),
          4 => array(
            'source' => 'hp_extra_baby_chair_possible',
            'target' => 'field_hp_facilities',
            'unique' => FALSE,
          ),
          5 => array(
            'source' => 'hp_indoor_playground',
            'target' => 'field_hp_facilities',
            'unique' => FALSE,
          ),
          6 => array(
            'source' => 'hp_internet_connection',
            'target' => 'field_hp_facilities',
            'unique' => FALSE,
          ),
          7 => array(
            'source' => 'hp_outdoor_playground',
            'target' => 'field_hp_facilities',
            'unique' => FALSE,
          ),
          8 => array(
            'source' => 'hp_parking',
            'target' => 'field_hp_facilities',
            'unique' => FALSE,
          ),
          9 => array(
            'source' => 'hp_pets_allowed',
            'target' => 'field_hp_facilities',
            'unique' => FALSE,
          ),
          10 => array(
            'source' => 'hp_picknick_possible',
            'target' => 'field_hp_facilities',
            'unique' => FALSE,
          ),
          11 => array(
            'source' => 'hp_restaurant_snackbar',
            'target' => 'field_hp_facilities',
            'unique' => FALSE,
          ),
          12 => array(
            'source' => 'hp_shop',
            'target' => 'field_hp_facilities',
            'unique' => FALSE,
          ),
          13 => array(
            'source' => 'hp_swimming',
            'target' => 'field_hp_facilities',
            'unique' => FALSE,
          ),
          14 => array(
            'source' => 'hp_tearoom_bar',
            'target' => 'field_hp_facilities',
            'unique' => FALSE,
          ),
          15 => array(
            'source' => 'hp_television',
            'target' => 'field_hp_facilities',
            'unique' => FALSE,
          ),
          16 => array(
            'source' => 'hp_full_board',
            'target' => 'field_hp_room_and_board',
            'unique' => FALSE,
          ),
          17 => array(
            'source' => 'hp_half_board',
            'target' => 'field_hp_room_and_board',
            'unique' => FALSE,
          ),
          18 => array(
            'source' => 'hp_bed_and_breakfast',
            'target' => 'field_hp_room_and_board',
            'unique' => FALSE,
          ),
          19 => array(
            'source' => 'hp_self_cooking',
            'target' => 'field_hp_room_and_board',
            'unique' => FALSE,
          ),
          20 => array(
            'source' => 'hp_accessibility_label',
            'target' => 'field_hp_labels',
            'unique' => FALSE,
          ),
          21 => array(
            'source' => 'hp_cycling_label',
            'target' => 'field_hp_labels',
            'unique' => FALSE,
          ),
          22 => array(
            'source' => 'hp_green_key_label',
            'target' => 'field_hp_labels',
            'unique' => FALSE,
          ),
          23 => array(
            'source' => 'hp_facebook',
            'target' => 'field_hp_facebook:value',
            'unique' => FALSE,
          ),
          24 => array(
            'source' => 'hp_twitter',
            'target' => 'field_hp_twitter:value',
            'unique' => FALSE,
          ),
          25 => array(
            'source' => 'hp_website',
            'target' => 'field_hp_website:value',
            'unique' => FALSE,
          ),
          26 => array(
            'source' => 'hp_youtube',
            'target' => 'field_hp_youtube:value',
            'unique' => FALSE,
          ),
          27 => array(
            'source' => 'hp_surroundings_description',
            'target' => 'field_hp_surroundings_desc',
            'unique' => FALSE,
          ),
          28 => array(
            'source' => 'hp_residence_description',
            'target' => 'field_hp_residence_desc',
            'unique' => FALSE,
          ),
          29 => array(
            'source' => 'hp_on_the_spot_description',
            'target' => 'field_hp_on_the_spot_desc',
            'unique' => FALSE,
          ),
          30 => array(
            'source' => 'hp_opening_hours',
            'target' => 'field_hp_opening_hours',
            'unique' => FALSE,
          ),
          31 => array(
            'source' => 'hp_private_transport_description',
            'target' => 'field_hp_private_transport_desc',
            'unique' => FALSE,
          ),
          32 => array(
            'source' => 'hp_public_transport_description',
            'target' => 'field_hp_public_transport_desc',
            'unique' => FALSE,
          ),
          33 => array(
            'source' => 'hp_validity_period_description',
            'target' => 'field_hp_validity_period_desc',
            'unique' => FALSE,
          ),
          34 => array(
            'source' => 'hp_imagesurlmain',
            'target' => 'field_hp_main_image_url:value',
            'unique' => FALSE,
          ),
          35 => array(
            'source' => 'hp_imagesurlmain',
            'target' => 'field_paddle_featured_image',
            'unique' => FALSE,
          ),
          36 => array(
            'source' => 'hp_product_description',
            'target' => 'body',
            'format' => 'full_html',
          ),
          37 => array(
            'source' => 'hp_name',
            'target' => 'field_hp_address:name_line',
            'unique' => FALSE,
          ),
          38 => array(
            'source' => 'hp_street',
            'target' => 'field_hp_address:thoroughfare',
            'unique' => FALSE,
          ),
          39 => array(
            'source' => 'hp_house_number',
            'target' => 'field_hp_address:thoroughfare',
            'unique' => FALSE,
          ),
          40 => array(
            'source' => 'hp_box_number',
            'target' => 'field_hp_address:premise',
            'unique' => FALSE,
          ),
          41 => array(
            'source' => 'hp_postal_code',
            'target' => 'field_hp_address:postal_code',
            'unique' => FALSE,
          ),
          42 => array(
            'source' => 'hp_city_name',
            'target' => 'field_hp_address:locality',
            'unique' => FALSE,
          ),
          43 => array(
            'source' => 'hp_country',
            'target' => 'field_hp_address:country',
            'unique' => FALSE,
          ),
          44 => array(
            'source' => 'Blank source 1',
            'target' => 'guid',
            'unique' => TRUE,
          ),
          45 => array(
            'source' => 'hp_contract_type',
            'target' => 'field_hp_contract_type',
            'unique' => FALSE,
          ),
          46 => array(
            'source' => 'hp_base_contract_id',
            'target' => 'field_hp_base_contract_id',
            'unique' => FALSE,
          ),
          47 => array(
            'source' => 'hp_imagesurl',
            'target' => 'imagesurl',
            'unique' => FALSE,
          ),
          48 => array(
            'source' => 'hp_validity_period',
            'target' => 'validity_period',
            'unique' => FALSE,
          ),
          49 => array(
            'source' => 'hp_contract_end_year',
            'target' => 'field_hp_contract_end_year',
            'unique' => FALSE,
          ),
          50 => array(
            'source' => 'hp_contract_start_year',
            'target' => 'field_hp_contract_start_year',
            'unique' => FALSE,
          ),
        ),
        'update_existing' => '2',
        'update_non_existent' => 'delete',
        'input_format' => 'plain_text',
        'skip_hash_check' => 1,
        'bundle' => 'offer',
      ),
    ),
    'content_type' => '',
    'update' => 0,
    'import_period' => '86400',
    'expire_period' => 3600,
    'import_on_create' => 0,
    'process_in_background' => 0,
  );
  $export['padvak_holiday_accomodations'] = $feeds_importer;

  $feeds_importer = new stdClass();
  $feeds_importer->disabled = FALSE; /* Edit this to true to make a default feeds_importer disabled initially */
  $feeds_importer->api_version = 1;
  $feeds_importer->id = 'padvak_organised_holidays';
  $feeds_importer->config = array(
    'name' => 'Organised Holidays',
    'description' => '',
    'fetcher' => array(
      'plugin_key' => 'FeedsHTTPFetcher',
      'config' => array(
        'auto_detect_feeds' => 1,
        'use_pubsubhubbub' => 0,
        'designated_hub' => '',
        'request_timeout' => '',
        'auto_scheme' => 'http://test-opendata.visitflanders.org/holidayparticipation/activities/organisedholidays',
        'accept_invalid_cert' => 0,
      ),
    ),
    'parser' => array(
      'plugin_key' => 'FeedsExJsonPath',
      'config' => array(
        'sources' => array(
          'hp_svp_contract_id' => array(
            'name' => 'SVP contract ID',
            'value' => 'svp_contract_id',
            'debug' => 0,
            'weight' => '0',
          ),
          'hp_name' => array(
            'name' => 'Name',
            'value' => 'name',
            'debug' => 0,
            'weight' => '1',
          ),
          'hp_facebook' => array(
            'name' => 'Facebook',
            'value' => 'facebook',
            'debug' => 0,
            'weight' => '2',
          ),
          'hp_imagesurlmain' => array(
            'name' => 'Featured image',
            'value' => 'imagesurlmain',
            'debug' => 0,
            'weight' => '2',
          ),
          'hp_street' => array(
            'name' => 'street',
            'value' => 'street',
            'debug' => 0,
            'weight' => '3',
          ),
          'hp_contract_type' => array(
            'name' => 'Contract type',
            'value' => 'contract_type',
            'debug' => 0,
            'weight' => '3',
          ),
          'hp_product_description' => array(
            'name' => 'Product description',
            'value' => 'product_description',
            'debug' => 0,
            'weight' => '3',
          ),
          'hp_twitter' => array(
            'name' => 'Twitter',
            'value' => 'twitter',
            'debug' => 0,
            'weight' => '3',
          ),
          'hp_youtube' => array(
            'name' => 'YouTube',
            'value' => 'youtube',
            'debug' => 0,
            'weight' => '4',
          ),
          'hp_website' => array(
            'name' => 'Website',
            'value' => 'website',
            'debug' => 0,
            'weight' => '5',
          ),
          'hp_is_deleted' => array(
            'name' => 'Is deleted',
            'value' => 'deleted',
            'debug' => 0,
            'weight' => '6',
          ),
          'hp_house_number' => array(
            'name' => 'House number',
            'value' => 'house_number',
            'debug' => 0,
            'weight' => '6',
          ),
          'hp_box_number' => array(
            'name' => 'Box number',
            'value' => 'box_number',
            'debug' => 0,
            'weight' => '7',
          ),
          'hp_base_contract_id' => array(
            'name' => 'Base contract id',
            'value' => 'base_contract_id',
            'debug' => 0,
            'weight' => '7',
          ),
          'hp_imagesurl' => array(
            'name' => 'Imagesurl',
            'value' => 'imagesurl',
            'debug' => 0,
            'weight' => '7',
          ),
          'hp_postal_code' => array(
            'name' => 'Postal code',
            'value' => 'postal_code',
            'debug' => 0,
            'weight' => '8',
          ),
          'hp_city_name' => array(
            'name' => 'City name',
            'value' => 'city_name',
            'debug' => 0,
            'weight' => '9',
          ),
          'hp_country' => array(
            'name' => 'Country',
            'value' => 'country',
            'debug' => 0,
            'weight' => '10',
          ),
          'hp_internal' => array(
            'name' => 'Internal',
            'value' => 'internal',
            'debug' => 0,
            'weight' => '11',
          ),
          'hp_min_capacity' => array(
            'name' => 'Min capacity',
            'value' => 'min_capacity',
            'debug' => 0,
            'weight' => '12',
          ),
          'hp_max_capacity' => array(
            'name' => 'Max capacity',
            'value' => 'max_capacity',
            'debug' => 0,
            'weight' => '13',
          ),
          'hp_validity_period' => array(
            'name' => 'Validity period',
            'value' => 'validity_period',
            'debug' => 0,
            'weight' => '14',
          ),
          'hp_contract_start_year' => array(
            'name' => 'Contract start year',
            'value' => 'contract_start_year',
            'debug' => 0,
            'weight' => '15',
          ),
          'hp_contract_end_year' => array(
            'name' => 'Contract end year',
            'value' => 'contract_end_year',
            'debug' => 0,
            'weight' => '16',
          ),
        ),
        'context' => array(
          'value' => '$.*',
        ),
        'display_errors' => 0,
        'source_encoding' => array(
          0 => 'auto',
        ),
        'debug_mode' => 0,
      ),
    ),
    'processor' => array(
      'plugin_key' => 'FeedsNodeProcessor',
      'config' => array(
        'expire' => '-1',
        'author' => 0,
        'authorize' => 0,
        'mappings' => array(
          0 => array(
            'source' => 'hp_svp_contract_id',
            'target' => 'field_hp_svp_contract_id',
            'unique' => TRUE,
          ),
          1 => array(
            'source' => 'hp_name',
            'target' => 'title',
            'unique' => FALSE,
          ),
          2 => array(
            'source' => 'hp_facebook',
            'target' => 'field_hp_facebook:value',
            'unique' => FALSE,
          ),
          3 => array(
            'source' => 'hp_twitter',
            'target' => 'field_hp_twitter:value',
            'unique' => FALSE,
          ),
          4 => array(
            'source' => 'hp_website',
            'target' => 'field_hp_website:value',
            'unique' => FALSE,
          ),
          5 => array(
            'source' => 'hp_youtube',
            'target' => 'field_hp_youtube:value',
            'unique' => FALSE,
          ),
          6 => array(
            'source' => 'hp_imagesurlmain',
            'target' => 'field_hp_main_image_url:value',
            'unique' => FALSE,
          ),
          7 => array(
            'source' => 'hp_imagesurlmain',
            'target' => 'field_paddle_featured_image',
            'unique' => FALSE,
          ),
          8 => array(
            'source' => 'hp_product_description',
            'target' => 'body',
            'format' => 'full_html',
          ),
          9 => array(
            'source' => 'hp_name',
            'target' => 'field_hp_address:name_line',
            'unique' => FALSE,
          ),
          10 => array(
            'source' => 'hp_street',
            'target' => 'field_hp_address:thoroughfare',
            'unique' => FALSE,
          ),
          11 => array(
            'source' => 'hp_house_number',
            'target' => 'field_hp_address:thoroughfare',
            'unique' => FALSE,
          ),
          12 => array(
            'source' => 'hp_box_number',
            'target' => 'field_hp_address:premise',
            'unique' => FALSE,
          ),
          13 => array(
            'source' => 'hp_postal_code',
            'target' => 'field_hp_address:postal_code',
            'unique' => FALSE,
          ),
          14 => array(
            'source' => 'hp_city_name',
            'target' => 'field_hp_address:locality',
            'unique' => FALSE,
          ),
          15 => array(
            'source' => 'hp_country',
            'target' => 'field_hp_address:country',
            'unique' => FALSE,
          ),
          16 => array(
            'source' => 'Blank source 1',
            'target' => 'guid',
            'unique' => TRUE,
          ),
          17 => array(
            'source' => 'hp_contract_type',
            'target' => 'field_hp_contract_type',
            'unique' => FALSE,
          ),
          18 => array(
            'source' => 'hp_base_contract_id',
            'target' => 'field_hp_base_contract_id',
            'unique' => FALSE,
          ),
          19 => array(
            'source' => 'hp_imagesurl',
            'target' => 'imagesurl',
            'unique' => FALSE,
          ),
          20 => array(
            'source' => 'hp_internal',
            'target' => 'field_hp_formula_oh',
            'unique' => FALSE,
          ),
          21 => array(
            'source' => 'hp_min_capacity',
            'target' => 'field_hp_min_capacity',
            'unique' => FALSE,
          ),
          22 => array(
            'source' => 'hp_max_capacity',
            'target' => 'field_hp_max_capacity',
            'unique' => FALSE,
          ),
          23 => array(
            'source' => 'hp_validity_period',
            'target' => 'validity_period',
            'unique' => FALSE,
          ),
          24 => array(
            'source' => 'hp_contract_end_year',
            'target' => 'field_hp_contract_end_year',
            'unique' => FALSE,
          ),
          25 => array(
            'source' => 'hp_contract_start_year',
            'target' => 'field_hp_contract_start_year',
            'unique' => FALSE,
          ),
        ),
        'update_existing' => '2',
        'update_non_existent' => 'delete',
        'input_format' => 'plain_text',
        'skip_hash_check' => 1,
        'bundle' => 'offer',
      ),
    ),
    'content_type' => '',
    'update' => 0,
    'import_period' => '86400',
    'expire_period' => 3600,
    'import_on_create' => 0,
    'process_in_background' => 0,
  );
  $export['padvak_organised_holidays'] = $feeds_importer;

  return $export;
}
