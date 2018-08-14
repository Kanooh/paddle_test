<?php
/**
 * @file
 * Google Api Custom Search wrapper functions.
 */

/**
 * Wrapper function to query google custom search and give back results.
 *
 * @param string $api_key
 *   The api key for google custom search.
 * @param string $search_machine_id
 *   The ID of the search machine.
 * @param array $keywords
 *   The keywords to build up our search query from.
 * @param string $start_index
 *   The start index for the results we want on the next page.
 *
 * @return Google_Service_Customsearch_Search
 *   An object containing all info about the search.
 */
function paddle_google_custom_search_get_custom_search_results($api_key, $search_machine_id, $keywords = array(), $start_index = 1) {
  // Query google with the correct parameters so we get search results back.
  $client = new Google_Client();
  $client->setClassConfig('Google_Cache_File', array('directory' => variable_get('file_temporary_path')));
  $client->setDeveloperKey($api_key);
  $service = new Google_Service_Customsearch($client);
  $search_query = implode('+', $keywords);
  $options = array(
    'cx' => $search_machine_id,
    'start' => $start_index,
  );
  // Get facets passed as URL params.
  if (!empty($_GET['label'])) {
    $search_query .= ' ' . $_GET['label'];
  }

  $result = $service->cse->listCse($search_query, $options);

  return $result;
}
