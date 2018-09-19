<?php


/**
 * @file
 * Ctools context type plugin that provides data to Paddle Style plugins.
 */

/**
 * Plugins are described by creating a $plugin array which will be used
 * by the system that includes this file.
 */
$plugin = array(
  'title' => t('Paddle Themer'),
  'description' => t('Provides data to content type plugins.'),
  'context' => 'paddle_themer_context_create',
  'context name' => 'paddle_themer',
  'keyword' => 'paddle_themer',
  'convert list' => 'paddle_themer_convert_list',
  'convert' => 'paddle_themer_convert',
);

/**
 * Create a context.
 *
 * @param bool $empty
 *   If true, just return an empty context.
 * @param mixed $data
 *   If from settings form, an array as from a form. If from argument, a string.
 * @param mixed $conf
 *   TRUE if the $data is coming from admin configuration, FALSE if it's from a
 *   URL arg.
 *
 * @return ctools_context
 *   a Context object.
 */
function paddle_themer_context_create($empty, $data = NULL, $conf = FALSE) {
  $context = new ctools_context('paddle_themer');
  $context->plugin = 'paddle_themer';
  $context->title = t('Paddle themer context plugin');

  if ($empty) {
    return $context;
  }

  if (!empty($data)) {
    $context->data = new stdClass();
    $context->data = $data;
    return $context;
  }
}

/**
 * Provide a list of sub-keywords.
 *
 * This is used to provide keywords from the context for use in a content type,
 * pane, etc.
 */
function paddle_themer_convert_list() {
  return array(
    'instance' => t('Plugin instance'),
    'machine_name' => t('Plugin machine name'),
  );
}

/**
 * Convert a context into a string to be used as a keyword by content types,etc.
 */
function paddle_themer_convert($context, $type) {
  switch ($type) {
    case 'instance':
      return json_encode($context->data['instance']);

    case 'machine_name':
      return $context->data['machine_name'];
  }
}
