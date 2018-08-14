<?php

/**
 * @file
 * Contains PaddleTestingMailSystem.
 */

/**
 * A mail sending implementation that captures sent messages to a variable.
 *
 * This class avoids issues caused by serialized instances of classes that
 * might need modules to be already included to run their __wakeup() method.
 * An example of this is the Entity class.
 *
 * @see https://www.drupal.org/node/2279851
 */
class PaddleTestingMailSystem extends TestingMailSystem implements MailSystemInterface {

  /**
   * {@inheritdoc}
   */
  public function mail(array $message) {
    $captured_emails = variable_get('drupal_test_email_collector', array());
    foreach ($message['params'] as $key => $param) {
      if (is_object($param) && get_class($param) !== 'stdClass') {
        unset($message['params'][$key]);
      }
    }

    $captured_emails[] = $message;
    variable_set('drupal_test_email_collector', $captured_emails);
    return TRUE;
  }

}
