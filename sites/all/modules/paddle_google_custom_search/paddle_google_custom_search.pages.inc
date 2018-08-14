<?php
/**
 * @file
 * User page callbacks for the Google Custom Search module.
 */

/**
 * Displays the google custom search page.
 *
 * @param string $keys
 *   The keys to search for.
 */
function paddle_google_custom_search_search_page($keys = NULL) {
  // Load the library.
  google_api_load_library();

  $keys = explode("\\\\", $keys);
  $items = array();
  $output = '';

  // Default values used later on in case an exception shows up.
  $severity = WATCHDOG_ERROR;
  $message = t('An unexpected problem occurred. Please try again.');

  try {
    // Get the google results. Check if we're on a paged page, in that case we
    // need to get the items starting from the page added by 1. This is because
    // the next page in google custom search is starting from the next numbered
    // item. IE: Page 1 starts from 1, page 2 starts from 11, page 3 starts from
    // 21, etc...
    if (isset($_GET['page'])) {
      $start = $_GET['page'] . '1';
      $result = paddle_google_custom_search_get_custom_search_results(variable_get('cse_api_key', ''), variable_get('cse_id', ''), $keys, $start);
    }
    else {
      $result = paddle_google_custom_search_get_custom_search_results(variable_get('cse_api_key', ''), variable_get('cse_id', ''), $keys);
    }
  }
  catch (Google_Service_Exception $e) {
    $exception = $e;

    if ($e->getCode() == 400) {
      $message = t('The API key or Engine ID you entered in the configuration screen of the google custom search paddlet is not correct.');
      $severity = WATCHDOG_NOTICE;
    }
    elseif ($e->getCode() == 403) {
      $message = t('Your limit of 100 requests has been reached, if you want more requests, please upgrade to a paid plan.');
      $severity = WATCHDOG_NOTICE;
    }
  }
  catch (Exception $e) {
    $exception = $e;
  }

  if (isset($exception)) {
    drupal_set_message($message, 'warning', FALSE);
    watchdog_exception('paddle_google_custom_search', $exception, NULL, array(), $severity);
  }

  if (!empty($result)) {
    $total_results = $result->getSearchInformation()->getTotalResults();

    if (module_exists('googleanalytics') && function_exists('googleanalytics_add_search_results_js')) {
      // Push the search result count to the page.
      googleanalytics_add_search_results_js(intval($total_results));
    }

    if ($total_results > 0) {
      foreach ($result->getItems() as $search_item) {
        $items[] = theme('google_search_result', array(
          'title' => $search_item->getHtmlTitle(),
          'url' => $search_item->getLink(),
          'snippet' => $search_item->getSnippet(),
        ));
      }

      // Add the facets(labels) links.
      $context = $result->getContext();
      $facets = array();
      foreach ($context->getFacets() as $facet) {
        if (!empty($facet['modelData'])) {
          $facets[$facet['modelData'][0]['label_with_op']] = $facet['modelData'][0]['anchor'];
        }
        else {
          $facets[$facet['data'][0]['label_with_op']] = $facet['data'][0]['anchor'];
        }
      }
      ksort($facets);

      $facet_links = array();
      foreach ($facets as $label => $anchor) {
        $options = array(
          'absolute' => TRUE,
          'query' => array('label' => $label),
          'attributes' => array('class' => array('label-link')),
        );

        // Add a active class on the active label.
        if (!empty($_GET['label']) && $_GET['label'] == $label) {
          $options['attributes']['class'][] = 'active-facet';
        }
        $facet_links[] = array(
          'data' => l($anchor, $_GET['q'], $options),
        );
      }

      if (count($facet_links)) {
        $element = array(
          '#tag' => 'div',
          '#attributes' => array('class' => 'search-labels-title'),
          '#value' => t('Refine the search results'),
        );
        $output .= theme('html_tag', array('element' => $element));
        $output .= theme('item_list', array(
          'items' => $facet_links,
          'attributes' => array(
            'class' => array(
              'search-labels',
            ),
          ),
        ));
      }

      $search_info = $result->getSearchInformation();
      $number_results = $search_info->getTotalResults() > 100 ? 100 : $search_info->getTotalResults();
      $current_page = pager_default_initialize($number_results, 10);
      $output .= theme('item_list', array(
        'items' => $items,
        'attributes' => array(
          'class' => array(
            'search-results',
          ),
        ),
      ));
      $output .= theme('pager', array('quantity' => 10));
    }
    else {
      $output .= '<p class="empty-resultset-message">' . t('No matching content has been found.') . '</p>';
    }
  }

  return $output;
}
