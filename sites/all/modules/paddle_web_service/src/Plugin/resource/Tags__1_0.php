<?php
/**
 * @file
 * Contains \Drupal\paddle_web_service\Plugin\resource\Tags__1_0.
 */

namespace Drupal\paddle_web_service\Plugin\resource;

use Drupal\restful\Plugin\resource\ResourceEntity;
use Drupal\restful\Plugin\resource\ResourceInterface;

/**
 * Class Tags.
 *
 * @package Drupal\restful\Plugin\resource
 *
 * @Resource(
 *   name = "tags:1.0",
 *   resource = "tags",
 *   label = "Tags",
 *   description = "Paddle tags.",
 *   authenticationTypes = FALSE,
 *   authenticationOptional = TRUE,
 *   dataProvider = {
 *     "entityType": "taxonomy_term",
 *     "bundles": {
 *       "paddle_general"
 *     },
 *   },
 *   majorVersion = 1,
 *   minorVersion = 0,
 *   allowOrigin = "*"
 * )
 */
class Tags__1_0 extends ResourceEntity implements ResourceInterface {

  /**
   * {@inheritdoc}
   */
  public function publicFields() {
    $public_fields = parent::publicFields();

    // Return the label as term.
    $public_fields['term'] = $public_fields['label'];
    unset($public_fields['label']);

    return $public_fields;
  }

}
