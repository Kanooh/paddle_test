<?php
/**
 * @file
 * Contains \Drupal\paddle_web_service\Plugin\resource\Pages__1_0.
 */

namespace Drupal\paddle_web_service\Plugin\resource;

use Drupal\restful\Plugin\resource\DataInterpreter\DataInterpreterInterface;
use Drupal\restful\Plugin\resource\ResourceEntity;
use Drupal\restful\Plugin\resource\ResourceInterface;

/**
 * Class Pages__1_0.
 *
 * @package Drupal\paddle_web_service\Plugin\resource
 *
 * @Resource(
 *   name = "pages:1.0",
 *   resource = "pages",
 *   label = "Pages",
 *   description = "Paddle pages.",
 *   authenticationTypes = FALSE,
 *   authenticationOptional = TRUE,
 *   dataProvider = {
 *     "entityType": "node"
 *   },
 *   majorVersion = 1,
 *   minorVersion = 0,
 *   allowOrigin = "*"
 * )
 */
class Pages__1_0 extends ResourceEntity implements ResourceInterface {

  /**
   * {@inheritdoc}
   */
  public function publicFields() {
    $public_fields = parent::publicFields();

    $public_fields['url'] = array(
      'callback' => array($this, 'getAbsoluteAliasedUrl'),
      'discovery' => array(
        // Information about the field for human consumption.
        'info' => array(
          'label' => t('URL'),
          'description' => t('Absolute URL with the aliased path to the page.'),
        ),
        // Describe the data.
        'data' => array(
          'cardinality' => 1,
          'type' => 'string',
        ),
      ),
    );

    // Return the label as title.
    $public_fields['title'] = $public_fields['label'];
    unset($public_fields['label']);

    $public_fields['general-vocabulary'] = array(
      'property' => 'field_paddle_general_tags',
      'resource' => array(
        'name' => 'tags',
        'majorVersion' => 1,
        'minorVersion' => 0,
      ),
    );

    return $public_fields;
  }

  /**
   * Get the aliased, absolute URL to the page.
   *
   * @param DataInterpreterInterface $interpreter
   *   The data interpreter containing the wrapper.
   *
   * @return string
   *   A string containing a URL to the given path.
   */
  public static function getAbsoluteAliasedUrl(DataInterpreterInterface $interpreter) {
    /** @var \EntityDrupalWrapper $wrapper */
    $wrapper = $interpreter->getWrapper();
    $uri = entity_uri($wrapper->type(), $wrapper->value());
    $uri['options']['absolute'] = TRUE;
    return url($uri['path'], $uri['options']);
  }

  /**
   * {@inheritdoc}
   */
  protected function dataProviderClassName() {
    return '\Drupal\paddle_web_service\Plugin\resource\DataProvider\DataProviderPage';
  }

}
