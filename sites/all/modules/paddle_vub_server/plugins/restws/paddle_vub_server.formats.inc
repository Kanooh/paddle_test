<?php

/**
 * @file
 * RESTful web services module formats for the Paddle VUB Server.
 */

/**
 * A formatter to format json.
 */
class PaddleVUBServerFormatJSON extends RestWSBaseFormat {

  /**
   * Forms a JSON string.
   *
   * @inheritdoc
   */
  public function serialize($values) {
    return drupal_json_encode($values);
  }

  /**
   * Split up the JSON string.
   *
   * @inheritdoc
   */
  public function unserialize($properties, $data) {
    $values = drupal_json_decode($data);
    $this->getPropertyValues($values, $properties);
    return $values;
  }

  /**
   * Gets the resource reference value.
   *
   * @param string $type
   *   The data type of the reference property.
   * @param array $reference
   *   The input data specifying the resource reference in one supported way.
   *
   * @return mixed
   *   The value to be set for the reference. Usually this is an entity or
   *   resource id, but for generic entity references it's an
   *   EntityDrupalWrapper.
   *
   * @see RestWSBaseFormat::getResourceReferenceValue()
   *
   * @throws \RestWSException
   */
  protected function getResourceReferenceValue($type, array $reference) {

    if (isset($reference['id']) && $type != 'entity') {
      return $reference['id'];
    }
    // We added paddle_scald_atom support.
    elseif (isset($reference['sid']) && $type == 'paddle_scald_atom') {
      return $reference;
    }
    // Handle setting generic entity references, i.e. of type entity.
    elseif ($type == 'entity' && isset($reference['id']) && isset($reference['resource'])) {
      if (!entity_get_info($reference['resource'])) {
        throw new RestWSException('Invalid resource for entity reference given.', 406);
      }
      return entity_metadata_wrapper($reference['resource'], $reference['id']);
    }
    elseif (isset($reference['uri'])) {
      // @todo: Implement setting references by URI by parsing resource/id from
      // the URI.
    }
    elseif (isset($reference['uuid']) && module_exists('uuid') && $type != 'entity') {
      $ids = entity_get_id_by_uuid($type, array($reference['uuid']));
      if (!$ids) {
        throw new RestWSException('Invalid UUID for resource reference given.', 406);
      }
      return reset($ids);
    }

    throw new RestWSException("Invalid value for resource reference given. type = $type", 406);
  }

  /**
   * We would like to access the actual URL of the resource when it is a file.
   *
   * @inheritdoc
   */
  public function getResourceReference($resource, $id) {
    $return = array(
      'uri' => restws_resource_uri($resource, $id),
      'id' => $id,
      'resource' => $resource,
    );

    // Add the actual URL when the resource is a file.
    if ($resource == 'file') {
      $file = file_load($id);
      $uri = $file->uri;
      $return['url'] = file_create_url($uri);
    }

    if (module_exists('uuid') && $info = entity_get_info($resource)) {
      // Check whether the entity type integrates with UUID module.
      if ($info['base table'] && in_array('uuid', $info['schema_fields_sql']['base table'])) {
        $ids = entity_get_uuid_by_id($resource, array($id));
        if ($id = reset($ids)) {
          $return['uuid'] = $id;
        }
      }
    }
    return $return;
  }

}
