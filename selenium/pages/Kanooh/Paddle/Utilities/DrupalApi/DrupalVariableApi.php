<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Utilities\DrupalApi\DrupalVariableApi.
 */

namespace Kanooh\Paddle\Utilities\DrupalApi;

use Kanooh\Paddle\Utilities\HttpRequest\HttpRequest;

class DrupalVariableApi extends DrupalApi
{

    /**
     * Sets a variable to a specific value.
     *
     * @param string $variable_name
     *   Name of the Drupal variable.
     * @param mixed $value
     *   New value for the variable.
     *
     * @return boolean
     *   Returns true when the variable was updated, false otherwise.
     *
     * @throws \Exception
     */
    public function set($variable_name, $value)
    {
        $request = new HttpRequest($this->webdriver);
        $request->setMethod(HttpRequest::POST);
        $request->setUrl($this->baseUrl . '/webdriver/variable/' . $variable_name);
        $request->setData(array($variable_name => $value));
        $response = $request->send();

        if ($response->status != 200) {
            throw new \Exception('Status: ' . $response->status);
        }

        $responseData = json_decode(isset($response->responseText) ? $response->responseText : '');
        if (isset($responseData->$variable_name) && $responseData->$variable_name == $value) {
            return true;
        }
        return false;
    }

    /**
     * Gets a variable from the Drupal site.
     *
     * @param string $variable_name
     *   Name of the Drupal variable.
     *
     * @return mixed
     *   The value of the variable, or NULL if no value was found.
     */
    public function get($variable_name)
    {
        $request = new HttpRequest($this->webdriver);
        $request->setMethod(HttpRequest::GET);
        $request->setUrl($this->baseUrl . '/webdriver/variable/' . $variable_name);
        $response = $request->send();

        $responseData = json_decode(isset($response->responseText) ? $response->responseText : '');
        if (isset($responseData->$variable_name)) {
            return $responseData->$variable_name;
        }
        return null;
    }

    /**
     * Delete a variable from the Drupal site.
     *
     * @param string $variable_name
     *   Name of the Drupal variable.
     */
    public function delete($variable_name)
    {
        $request = new HttpRequest($this->webdriver);
        $request->setMethod(HttpRequest::POST);
        $request->setUrl($this->baseUrl . '/webdriver/variable/' . $variable_name);
        $request->setData(array('delete' => 1));
        $response = $request->send();

        if ($response->status != 200) {
            throw new \Exception('Status: ' . $response->status);
        }
    }
}
