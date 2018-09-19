<?php

/**
 * @file
 * Contains controller classes for Paddle Incoming RSS.
 */

/**
 * Controller class to handle Incoming RSS feed item entities.
 */
class PaddleIncomingRSSFeedItemEntityController extends EntityAPIController {

  /**
   * {@inheritdoc}
   */
  public function save($entity, DatabaseTransaction $transaction = NULL) {
    $entity->is_new = !empty($entity->is_new) || empty($entity->{$this->idKey});

    // Set the created date if not yet configured.
    if (isset($entity->is_new) && empty($entity->created)) {
      $entity->created = REQUEST_TIME;
    }

    // Set/update the changed date.
    $entity->changed = REQUEST_TIME;

    return parent::save($entity, $transaction);
  }

  /**
   * {@inheritdoc}
   */
  protected function renderEntityProperty($wrapper, $name, $property, $view_mode, $langcode, &$content) {
    // If there is a specific render callback for this property, use that.
    $property_render_callback = 'render' . ucfirst($name) . 'Property';
    if (method_exists($this, $property_render_callback)) {
      $this->{$property_render_callback}($wrapper, $name, $property, $view_mode, $langcode, $content);
    }
    else {
      // Fallback to default implementation.
      parent::renderEntityProperty($wrapper, $name, $property, $view_mode, $langcode, $content);
    }
  }

  /**
   * Renders the thumbnail property.
   *
   * We cannot use the entity_property theme function as due to
   * https://drupal.org/node/2109825 the 'content' key is ignored.
   */
  protected function renderThumbnailProperty($wrapper, $name, $property, $view_mode, $langcode, &$content) {
    // Do not render empty entity properties.
    if ($property->value() == NULL) {
      return;
    }

    $content[$name] = array(
      '#theme' => 'html_tag',
      '#tag' => 'div',
      '#attributes' => array('class' => array(drupal_html_class('entity-property-' . $name))),
      '#value' => theme('image', array(
        'path' => $property->value(),
        'alt' => $wrapper->label(),
      )),
    );
  }

  /**
   * Renders the created property.
   */
  protected function renderCreatedProperty($wrapper, $name, $property, $view_mode, $langcode, &$content) {
    // Do not render empty entity properties.
    if ($property->value() == NULL) {
      return;
    }

    $content[$name] = array(
      '#theme' => 'html_tag',
      '#tag' => 'div',
      '#attributes' => array('class' => array(drupal_html_class('entity-property-' . $name))),
      '#value' => format_date($property->value(), 'custom', 'd/m/Y'),
    );
  }

}

/**
 * Controller class to handle extra fields for the RSS feed item entity.
 */
class PaddleIncomingRSSFeedItemExtraFieldsController extends EntityDefaultExtraFieldsController {

  /**
   * {@inheritdoc}
   */
  public function fieldExtraFields() {
    $extra = array();

    // Handle only thumbnail and changed fields.
    foreach (array('thumbnail', 'created', 'changed') as $name) {
      if (!empty($this->propertyInfo['properties'][$name])) {
        $extra[$this->entityType][$this->entityType]['display'][$name] = $this->generateExtraFieldInfo($name, $this->propertyInfo['properties'][$name]);
      }
    }

    return $extra;
  }

}
