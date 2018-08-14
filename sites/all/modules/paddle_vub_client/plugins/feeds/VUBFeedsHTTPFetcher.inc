<?php

/**
 * @file
 * Home of the VUBFeedsHTTPFetcher and related classes.
 */

feeds_include_library('PuSHSubscriber.inc', 'PuSHSubscriber');
feeds_include_library('http_request.inc', 'http_request');

/**
 * Result set which contains a list of VUB entities.
 */
class VUBFeedsHTTPFetcherResult extends FeedsHTTPFetcherResult {

  /**
   * Overrides FeedsHTTPFetcherResult::getRaw().
   */
  public function getRaw() {
    if (!isset($this->raw)) {
      feeds_include_library('http_request.inc', 'http_request');

      $result = $this->getHttpRequest($this->url);
      if (!in_array($result->code, array(200, 201, 202, 203, 204, 205, 206))) {
        throw new Exception(t('Download of @url failed with code !code.', array('@url' => $this->url, '!code' => $result->code)));
      }
      $this->raw = $result->data;
    }

    return $this->sanitizeRaw($this->raw);
  }

  /**
   * Writes a custom HTTP request to connect to the VUB server.
   *
   * @param string $url
   *   The URL provided by the Feeds source.
   *
   * @return mixed|string
   *   The result from the HTTP request.
   */
  protected function getHttpRequest($url) {
    $result = new stdClass();
    $server_url = variable_get('paddle_vub_client_server_site', '');
    $ch = curl_init();
    // Retrieve the X-CSRF-token.
    $http_x_csrf_token = paddle_vub_client_retrieve_http_x_crsf_token($ch, $server_url);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'X-CSRF-Token: ' . $http_x_csrf_token));
    curl_setopt($ch, CURLOPT_URL, $url);


    if ($this->acceptInvalidCert) {
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    }

    $data = curl_exec($ch);

    if (!paddle_vub_client_check_for_curl_errors($ch, 'cURL error (@code) @error for importing data from @url')) {
      $result->data = $data;
    }

    $result->code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    return $result;
  }

}

/**
 * Fetches VUB entities via HTTP.
 */
class VUBFeedsHTTPFetcher extends FeedsHTTPFetcher {

  /**
   * Overrides FeedsHTTPFetcher::fetch().
   */
  public function fetch(FeedsSource $source) {
    $source_config = $source->getConfigFor($this);
    if ($this->config['use_pubsubhubbub'] && ($raw = $this->subscriber($source->feed_nid)->receive())) {
      return new FeedsFetcherResult($raw);
    }
    $fetcher_result = new VUBFeedsHTTPFetcherResult($source_config['source']);
    // When request_timeout is empty, the global value is used.
    $fetcher_result->setTimeout($this->config['request_timeout']);
    $fetcher_result->setAcceptInvalidCert($this->config['accept_invalid_cert']);

    // Retrieve the GUIDs of the items and use them
    // to fetch each item individually.
    return $fetcher_result;
  }

}
