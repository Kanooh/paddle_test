<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Utilities\DrupalApi\DrupalNodeApi.
 */

namespace Kanooh\Paddle\Utilities\DrupalApi;

use Kanooh\Paddle\Utilities\HttpRequest\HttpRequest;

class DrupalNodeApi extends DrupalApi
{
    /**
     * Gets a node object from the Drupal site.
     *
     * @param int $nid
     *   Nid of the node that has to be retreived.
     *
     * @return object
     *   The node object.
     */
    public function get($nid)
    {
        $request = new HttpRequest($this->webdriver);
        $request->setMethod(HttpRequest::GET);
        $request->setUrl($this->baseUrl . '/webdriver/node/' . $nid);
        $response = $request->send();

        return json_decode(isset($response->responseText) ? $response->responseText : '');
    }

    /**
     * Create a node in the Drupal site.
     *
     * @param string $type
     *   The content type.
     * @param string $title
     *   The title of the node.
     * @param string $user_name
     *   The name of the user creating the node.
     *
     * @return object
     *   The node object.
     */
    public function create($type, $title, $user_name)
    {
        $request = new HttpRequest($this->webdriver);
        $request->setMethod(HttpRequest::GET);
        // Remove '%' because it confuses the server.
        $url = $this->baseUrl . '/webdriver/create_node/' . $type . '/' . str_replace('%', '', urlencode($title));
        // Provide a default user.
        if ($user_name) {
             $url .= '/' . $user_name;
        } else {
             $url .= '/admin';
        }
        $request->setUrl($url);
        $response = $request->send();

        return json_decode(isset($response->responseText) ? $response->responseText : '');
    }
}
