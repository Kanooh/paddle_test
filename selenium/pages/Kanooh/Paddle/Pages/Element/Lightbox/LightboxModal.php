<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Lightbox\LightboxModal.
 */

namespace Kanooh\Paddle\Pages\Element\Lightbox;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Element\Modal\Modal;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class representing the Lightbox modal.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $mainImage
 *   The image shown in the Lightbox modal.
 */
class LightboxModal extends Modal
{
    /**
     * {@inheritdoc}
     */
    protected $xpathSelector = '//div[@id="colorbox"]';

    /**
     * {@inheritdoc}
     */
    protected $overlayXPathSelector = '//div[@id="cboxOverlay"]';

    /**
     * {@inheritdoc}
     */
    protected $closeButtonXPathSelector = '//button[@id="cboxClose"]';

    /**
     * {@inheritdoc}
     */
    protected $submitButtonXPathSelector = '';

    /**
     * The webdriver element representing the modal.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Constructs an LightboxImage object.
     *
     * @param \Kanooh\WebDriver\WebDriverTestCase $webdriver
     *   The Selenium web driver test case.
     */
    public function __construct(WebDriverTestCase $webdriver)
    {
        parent::__construct($webdriver);
        $this->element = $this->webdriver->byXPath($this->xpathSelector);
    }

    /**
     * {@inheritdoc}
     */
    public function waitUntilOpened()
    {
        $content_xpath = '//div[@id="cboxLoadedContent"]';
        $this->webdriver->waitUntilElementIsDisplayed($this->xpathSelector . $content_xpath);
    }

    /**
     * Magically provides all known elements of the modal as properties.
     *
     * @param string $name
     *   A element machine name.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'mainImage':
                return $this->element->byXPath('.//img[contains(@class, "cboxPhoto")]');
        }
        throw new \Exception("The property $name is undefined.");
    }

    /**
     * @return mixed
     */
    public function waitUntilClosed()
    {
        $webdriver = $this->webdriver;
        $content_xpath = '//div[@id="cboxLoadedContent"]';

        $callable = new SerializableClosure(
            function () use ($webdriver, $content_xpath) {
                try {
                    $webdriver->byXPath($content_xpath);
                } catch (\Exception $e) {
                    // Modal content not present.
                    return true;
                }
            }
        );
        $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());
    }
}
