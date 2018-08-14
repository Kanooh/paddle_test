<?php
/**
 * @file
 * Hooks provided by Paddle Codex Flanders.
 */

/**
 * @addtogroup hooks
 * @{
 */

/**
 * Alter the data being used to send the request.
 *
 * This hook is invoked by
 * paddle_codex_flanders_codex_flanders_content_type_edit_form_submit().
 *
 * @param string $url
 *   The url for the request.
 * @param int $aid
 *   The article ID.
 */
function hook_paddle_codex_flanders_request_url(&$url, &$aid) {
  $aid = 5;
  $url = 'http://randomurl.com?AID=' . $aid;
}

/**
 * @} End of "addtogroup hooks"
 */
