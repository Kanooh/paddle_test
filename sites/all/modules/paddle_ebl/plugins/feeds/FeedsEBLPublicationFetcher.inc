<?php

/**
 * @file
 * Home of the FeedsCirroResourceFetcher and related classes.
 */

feeds_include_library('PuSHSubscriber.inc', 'PuSHSubscriber');
feeds_include_library('http_request.inc', 'http_request');

/**
 * Result set which contains a list of EBL publications.
 */
class FeedsEBLPublicationFetcherResult extends FeedsHTTPFetcherResult {

  /**
   * Overrides FeedsHTTPFetcherResult::getRaw().
   */
  public function getRaw() {
    if (!isset($this->raw)) {
      // The group of JSON strings starts with a '['.
      $items = '[';

      $amount_of_pages = $this->getPageAmount();

      for ($i = 1; $i <= $amount_of_pages; $i++) {
        $item_url = $this->url . '/pagina=' . $i;
        $item = http_request_get($item_url, NULL, NULL,
          $this->acceptInvalidCert, $this->timeout);

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
      $items = rtrim($items, ',') . ']';

      $this->raw = $this->prepareResultsForParsing($items);
    }

    return $this->sanitizeRaw($this->raw);
  }

  /**
   * Calculates the amount of page requests we need to perform.
   *
   * @return int
   *   The amount of page requests we need to do to get all items.
   *
   * @throws \Exception
   */
  public function getPageAmount() {
    $amount_of_pages = 0;
    // Get only the resources which are not deleted.
    $result = http_request_get($this->url, NULL, NULL, $this->acceptInvalidCert,
      $this->timeout);
    if (!in_array($result->code, array(200, 201, 202, 203, 204, 205, 206))) {
      throw new Exception(t('Download of @url failed with code !code.',
        array('@url' => $this->url, '!code' => $result->code)));
    }

    $decoded_json = drupal_json_decode($result->data);

    if (!empty($decoded_json['collection']['total_count'])) {
      $total_count = $decoded_json['collection']['total_count'];
      $amount_of_pages = ($total_count / 10);

      // Round up one page when there are still items remaining.
      if ($total_count % 10 != 0) {
        $amount_of_pages = ceil($amount_of_pages);
      }
    }

    return $amount_of_pages;
  }

  /**
   * Prepares the results so that the parser can work natively with them.
   *
   * @param string $items
   *   The JSON string received from the accumulated pages.
   *
   * @return string
   *   The encoded JSON string of the results which the parsers can work with.
   *
   * @throws \Exception
   */
  public function prepareResultsForParsing($items) {
    $publications = '';

    if (!empty($items)) {
      $results = array();
      $decoded_items = drupal_json_decode($items);

      foreach ($decoded_items as $decoded_item) {
        $results = array_merge($decoded_item['collection']['results'],
          $results);
      }

      $publications = '[';
      if (!empty($results)) {
        foreach ($results as $result) {
          $alias = $result['alias'];
          $item_url = paddle_ebl_build_url('detail') . 'alias=' . $alias;

          $publication = http_request_get($item_url, NULL, NULL,
            $this->acceptInvalidCert, $this->timeout);

          if (!in_array($publication->code,
            array(200, 201, 202, 203, 204, 205, 206))) {
            throw new Exception(t('Download of @url failed with code !code.',
              array('@url' => $item_url, '!code' => $publication->code)));
          }
          // We append a comma to the item since it is the delimiter between
          // items in a JSON string.
          $decoded_publication = drupal_json_decode($publication->data);
          // The items are wrapped under the 'publication' array key.
          $publications .= drupal_json_encode($decoded_publication['publication']) . ',';
        }
      }

      // Remove the last comma in the appended JSON string and close the group
      // of JSON elements with a '].
      $publications = rtrim($publications, ',') . ']';
    }

    return $publications;
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
 * Fetches EBL publications via HTTP.
 */
class FeedsEBLPublicationFetcher extends FeedsHTTPFetcher {

  /**
   * Overrides FeedsHTTPFetcher::fetch().
   */
  public function fetch(FeedsSource $source) {
    $source_config = $source->getConfigFor($this);
    if ($this->config['use_pubsubhubbub'] && ($raw = $this->subscriber($source->feed_nid)->receive())) {
      return new FeedsFetcherResult($raw);
    }
    $fetcher_result = new FeedsEBLPublicationFetcherResult($source_config['source']);
    // When request_timeout is empty, the global value is used.
    $fetcher_result->setTimeout($this->config['request_timeout']);
    $fetcher_result->setAcceptInvalidCert($this->config['accept_invalid_cert']);

    return $fetcher_result;
  }

}
