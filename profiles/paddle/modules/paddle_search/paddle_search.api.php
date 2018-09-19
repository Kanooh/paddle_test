<?php
/**
 * @file
 * Hooks provided by Paddle Search.
 */

/**
 * @addtogroup hooks
 * @{
 */

/**
 * Alters the publication date timestamp which will be sorted on.
 *
 * This hook is invoked by
 * paddle_search_api_publication_date_getter_callback().
 *
 * @param int $publication_date
 *   The UNIX timestamp of the publication date.
 * @param object $node
 *   The to be indexed node.
 */
function hook_paddle_search_publication_date_alter($publication_date, $node) {
  $publication_date = '1505743254';
}

/**
 * @} End of "addtogroup hooks"
 */
