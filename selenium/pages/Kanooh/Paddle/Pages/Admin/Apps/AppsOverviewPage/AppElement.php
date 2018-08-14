<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\AppsOverviewPage\AppElement.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\AppsOverviewPage;

/**
 * Class App
 * @package Kanooh\Paddle\Pages\Admin\Apps\AppsOverviewPage
 *
 * @property string $machineName
 * @property boolean $status
 * @property string $statusText
 * @property PHPUnit_Extensions_Selenium2TestCase_Element $activationButton
 * @property string $isPaid
 * @property PHPUnit_Extensions_Selenium2TestCase_Element $configureButton
 */
class AppElement
{
    /**
     * The webdriver element representing this AppElement.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * The links in the app. These are styled as buttons.
     *
     * @var AppElementLinks
     */
    public $links;

    /**
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     */
    public function __construct(\PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        $this->element = $element;
        $this->links = new AppElementLinks(null, $element);
    }

    /**
     * Dynamic getter.
     */
    public function __get($property)
    {
        switch ($property) {
            case 'machineName':
                return $this->element->attribute('data-machine-name');
                break;
            case 'status':
                return (bool) $this->element->attribute('data-status');
                break;
            case 'statusText':
                $status = $this->element->byXPath('.//div[contains(@class, "paddle-apps-paddlet-status")]');
                return $status->text();
                break;
            case 'activationButton':
                return $this->element->byXPath('.//div[contains(@class, "paddle-apps-paddlet-status")]/a');
                break;
            case 'isPaid':
                try {
                    $level = $this->element->byXPath('.//span[contains(@class, "paddle-apps-paddlet-level")]');
                    return trim($level->text()) == 'paying';
                } catch (\Exception $e) {
                    // Do nothing - there is just no level specified.
                }
                return false;
            case 'configureButton':
                return $this->element->byXPath('.//div[@class="paddle-apps-paddlet-configure"]/a');
                break;
        }
    }

    /**
     * Check if the configure button is present for a given paddlet.
     *
     * @return bool
     *   TRUE if the configure button is present for the paddlet, FALSE otherwise.
     */
    public function checkConfigureButton()
    {
        $xpath = './/div[@class="paddle-apps-paddlet-configure"]/a';
        $elements = $this->element->elements($this->element->using('xpath')->value($xpath));
        return (bool) count($elements);
    }

    /**
     * Checks if the install button for a certain app has been disabled.
     *
     * @return bool
     *   True if the install button is disabled, false otherwise.
     */
    public function checkDisabledInstallButton()
    {
        $xpath = './/span[contains(@class, "disabled") and @data-title="You\'ve reached the maximum number of free and/or extra paddlets. Please upgrade first."]';
        $elements = $this->element->elements($this->element->using('xpath')->value($xpath));
        return (bool) count($elements);
    }
}
