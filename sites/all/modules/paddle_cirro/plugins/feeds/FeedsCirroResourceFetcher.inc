<?php

/**
 * @file
 * Home of the FeedsCirroResourceFetcher and related classes.
 */

feeds_include_library('PuSHSubscriber.inc', 'PuSHSubscriber');
feeds_include_library('http_request.inc', 'http_request');

/**
 * Result set which contains a list of CIRRO resources.
 */
class FeedsCirroResourceFetcherResult extends FeedsHTTPFetcherResult {

  /**
   * Overrides FeedsHTTPFetcherResult::getRaw().
   */
  public function getRaw() {
    if (!isset($this->raw)) {
      // The group of JSON strings starts with a '['.
      $items = '[';

      $resources = $this->getResources();

      // Append each resource result to the JSON string.
      foreach ($resources as $resource) {
        $guid = $resource['Guid'];
        $item_url = $this->url . '/' . $guid;
        $item = http_request_get($item_url, NULL, NULL, $this->acceptInvalidCert, $this->timeout);

        if (!in_array($item->code, array(200, 201, 202, 203, 204, 205, 206))) {
          throw new Exception(t('Download of @url failed with code !code.',
            array('@url' => $item_url, '!code' => $item->code)));
        }
        // We append a comma to the item since it is the delimiter between items
        // in a JSON string.
        $items .= $item->data . ',';
      }

      // Remove the last comma in the appended JSON string and close the group
      // of JSON elements with a '].
      $this->raw = rtrim($items, ',') . ']';
    }

    return $this->sanitizeRaw($this->raw);
  }

  /**
   * Retrieves a list of GUIDs which is used to import the separate items.
   */
  public function getResources() {
    // Get only the resources which are not deleted.
    $result = http_request_get($this->url . '?verwijderd=false', NULL, NULL, $this->acceptInvalidCert, $this->timeout);
    if (!in_array($result->code, array(200, 201, 202, 203, 204, 205, 206))) {
      throw new Exception(t('Download of @url failed with code !code.',
        array('@url' => $this->url, '!code' => $result->code)));
    }

    $results = drupal_json_decode($result->data);

    return $results;
  }

  /**
   * Returns the URL of the Feeds Source.
   *
   * @return null|string
   *   If there is a feed URL specified, return the URL.
   */
  public function getUrl() {
    return $this->url;
  }

}

/**
 * Fetches CIRRO resources via HTTP.
 */
class FeedsCirroResourceFetcher extends FeedsHTTPFetcher {

  /**
   * Overrides FeedsHTTPFetcher::fetch().
   */
  public function fetch(FeedsSource $source) {
    $source_config = $source->getConfigFor($this);
    if ($this->config['use_pubsubhubbub'] && ($raw = $this->subscriber($source->feed_nid)->receive())) {
      return new FeedsFetcherResult($raw);
    }
    $fetcher_result = new FeedsCirroResourceFetcherResult($source_config['source']);
    // When request_timeout is empty, the global value is used.
    $fetcher_result->setTimeout($this->config['request_timeout']);
    $fetcher_result->setAcceptInvalidCert($this->config['accept_invalid_cert']);

    // Retrieve the GUIDs of the items and use them
    // to fetch each item individually.
    return $fetcher_result;
  }

}
