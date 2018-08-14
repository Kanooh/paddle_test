<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Utilities\DrupalApi\DrupalSearchApiApi.
 */

namespace Kanooh\Paddle\Utilities\DrupalApi;

use Kanooh\Paddle\Utilities\HttpRequest\HttpRequest;

/**
 * Utility class to perform actions on the Search API.
 */
class DrupalSearchApiApi extends DrupalApi
{
    /**
     * Forces the Solr server to commit its index.
     *
     * @param string $index_id
     *   The ID of the index to commit.
     *
     * @throws \Exception
     *   Thrown when an error occurred during the commit.
     */
    public function commitIndex($index_id)
    {
        $index = search_api_index_load($index_id);
        $server = search_api_server_load($index->server);

        // There's no reasonable way to access the protected $proxy property of
        // $server here, so let's built up the object ourselves.
        // See SearchApiSolrService::ensureProxy().
        $class = search_api_get_service_info($server->class);
        if ($class && class_exists($class['class'])) {
            if (empty($server->options)) {
                // We always have to provide the options.
                $server->options = array();
            }
            $service = new $class['class']($server);
            $wait_searcher = true;
            $response = $service->getSolrConnection()->commit($wait_searcher);
        }

        // Reload the current solr proxy.
        search_api_server_load($index->server, true);

        $result = !empty($response) && $response->status_message == 'OK';
        if (!$result) {
            throw new \Exception('The search index "' . $index_id . '" could not be committed.');
        }
    }

    /**
     * Instructs Search API to index a number of items on a given search index.
     *
     * @param string $index_id
     *   The ID of the search index to index.
     * @param int $limit
     *   The maximum number of items to index. Pass -1 to index all items.
     *   Defaults to -1.
     *
     * @return int
     *   The number of items that were indexed.
     *
     * @throws \Exception
     *   Thrown when an error occurred during the indexing.
     */
    public function indexItems($index_id, $limit = -1)
    {
        $index = search_api_index_load($index_id);
        try {
            $number = search_api_index_items($index, $limit);
        } catch (\SearchApiException $e) {
            throw new \Exception('An error occurred while indexing search index "' . $index_id . '".');
        }

        return $number;
    }

    /**
     * Retrieve the status from the Search API index.
     *
     * @param string $index_id
     *   The ID of the search index to index.
     *
     * @return array
     *   An associative array containing two keys (in this order):
     *   - indexed: The number of items already indexed in their latest version.
     *   - total: The total number of items that have to be indexed for this index.
     *
     * @throws \Exception
     *   Thrown when an error occurred fetching the status.
     */
    public function getStatus($index_id)
    {
        $index = search_api_index_load($index_id);

        try {
            $status = search_api_index_status($index);
        } catch (\SearchApiException $e) {
            throw new \Exception('An error occurred while fetching status for search index "' . $index_id . '".');
        }

        return $status;
    }

    /**
     * Does a GET request to the given URL.
     *
     * @param string $url
     *   The URL to visit.
     *
     * @return array
     *   An array with response data.
     */
    protected function performRequest($url)
    {
        $request = new HttpRequest($this->webdriver);
        $request->setMethod(HttpRequest::GET);
        $request->setUrl($url);

        return $request->send();
    }
}
