<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Scald\PluploadFileList.
 */

namespace Kanooh\Paddle\Pages\Element\Scald;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * The box holding the list of files to upload provided by Plupload module.
 *
 * @package Kanooh\Paddle\Pages\Element\Scald
 *
 * @property PluploadFile[] $files
 *   Array containing the objects of the files in the Plupload file list.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $uploadButton
 *   The button to upload all added file(s).
 * @property string $uploadProgress
 *   The progress of the upload of the added files.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $inputField
 *   The hidden file upload field.
 */
class PluploadFileList
{
    /**
     * The Selenium webdriver.
     *
     * @var WebDriverTestCase
     */
    protected $webdriver;

    /**
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     *   The Webdriver element.
     */
    protected $element;

    /**
     * Constructs a new Form object.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The Selenium webdriver element representing the form.
     */
    public function __construct(WebDriverTestCase $webdriver, \PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        $this->webdriver = $webdriver;
        $this->element = $element;
    }

    /**
     * Magic property getter.
     *
     * @param string $name
     *   The name of the property we are looking for.
     *
     * @return mixed
     *   The property.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'files':
                if (!$this->listIdEmpty()) {
                    $files = array();

                    $elements = $this->element->elements($this->element->using('xpath')->value('./li'));
                    foreach ($elements as $element) {
                        $files[] = new PluploadFile($element);
                    }

                    return $files;
                }
                break;
            case 'uploadButton':
                return $this->element->byXPath('.//a[contains(@class, "plupload_start")]');
            case 'inputField':
                $xpath = './../../div[contains(@class, "plupload")]/input[@multiple = "multiple"]';

                return $this->element->byXPath($xpath);
            case 'uploadProgress':
                $element = $this->element->byXPath('.//div/span[contains(@class, "plupload_total_status")]');

                return trim($element->text());
        }

        return null;
    }

    /**
     * Adds the files and uploads them.
     *
     * @param array|string $files
     *   Array of path to files to upload.
     */
    public function uploadFiles($files)
    {
        if (!is_array($files)) {
            $files = array($files);
        }
        $this->inputField->value(implode("\n", $files));
    }

    /**
     * Waits until all added files are uploaded.
     */
    public function waitUntilFilesUploaded()
    {
        $plupload = $this;
        $callable = new SerializableClosure(
            function () use ($plupload) {
                return $plupload->uploadProgress == '100%';
            }
        );
        $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());
    }
}
