<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Scald\PluploadFile.
 */

namespace Kanooh\Paddle\Pages\Element\Scald;

/**
 * An added file to the Plupload box.
 *
 * @package Kanooh\Paddle\Pages\Element\Scald
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $name
 *   The name of the uploaded file.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $size
 *   The size of the uploaded file.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $status
 *   The status of the uploaded file.
 */
class PluploadFile
{
    /**
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     *   The Webdriver element.
     */
    protected $element;

    public function __construct(\PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        $this->element = $element;
    }

    /**
     * Magic property getter.
     *
     * @param string $name
     *   The name of the property we are looking for.
     *
     * @return mixed
     *   The requested form element or null if none found.
     *
     * @throws \Exception
     *   Thrown when the requested element is not defined.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'name':
                $element = $this->element->byXPath('./div[contains(@class, "plupload_file_name")]/span');

                return $element->text();
            case 'size':
                $element = $this->element->byXPath('./div[contains(@class, "plupload_file_size")]');

                return $element->text();
            case 'status':
                $element = $this->element->byXPath('./div[contains(@class, "plupload_file_status")]');

                return $element->text();
        }

        throw new \Exception("The property $name is undefined.");
    }
}
