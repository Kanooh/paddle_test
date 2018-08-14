<?php
/**
 * @file
 * Filter based on proximity.
 */

/**
 * Class paddle_holiday_participation_handler_filter_proximity.
 */
class paddle_holiday_participation_handler_filter_proximity extends geofield_handler_filter {
  function query() {
    $proximityPlugin = geofield_proximity_load_plugin($this->options['source']);
    $options = $proximityPlugin->getSourceValue($this);

    if ($options) {
      $lat_alias = 'field_paddle_geofield_lat';
      $lon_alias = 'field_paddle_geofield_lon';
      $this->ensure_my_table();

      $info = $this->operators();
      if (!empty($info[$this->operator]['method'])) {
        $haversine_options = array(
          'origin_latitude' => $options['latitude'],
          'origin_longitude' => $options['longitude'],
          'destination_latitude' => $this->table_alias . '.' . $lat_alias,
          'destination_longitude' => $this->table_alias . '.' . $lon_alias,
          'earth_radius' => $this->value['unit'],
        );
        $this->{$info[$this->operator]['method']}($haversine_options);
      }
    }
  }
}
