<?php
/**
 * @file
 * Contains \Drupal\paddle_web_service\Plugin\resource\DataProvider\DataProviderPage.
 */

namespace Drupal\paddle_web_service\Plugin\resource\DataProvider;

use Drupal\restful\Plugin\resource\DataProvider\DataProviderEntity;

/**
 * Class DataProviderPage.
 *
 * @package Drupal\restful\Plugin\resource\DataProvider
 */
class DataProviderPage extends DataProviderEntity {

  /**
   * {@inheritdoc}
   */
  protected function addExtraInfoToQuery($query) {
    parent::addExtraInfoToQuery($query);
    // Enable to use a very specific hook_query_TAG_alter implementation.
    $query->addTag('node_with_term_reference');
  }

}
