<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleEmbed\ConfigurePage\ConfigurePage.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleEmbed\ConfigurePage;

use Kanooh\Paddle\Apps\Embed;
use Kanooh\Paddle\Pages\Admin\Apps\ConfigurePage\ConfigurePageBase;
use Kanooh\Paddle\Pages\Element\Embed\WidgetTable;
use Kanooh\Paddle\Pages\Element\Links\ContentAdminMenuLinks;
use Kanooh\Paddle\Pages\PaddlePage;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * The configuration page for the Embed paddlet.
 *
 * @property ConfigurePageContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 * @property WidgetTable $widgetTable
 *   The table of widgets.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $createButton
 *   The button to create a new widget.
 * @property ContentAdminMenuLinks $adminMenuLinks
 *   The admin menu links.
 */
class ConfigurePage extends ConfigurePageBase
{

    /**
     * Constructs a ConfigurePage.
     *
     * @param \Kanooh\WebDriver\WebDriverTestCase $webdriver
     *   The interface to the Selenium webdriver.
     */
    public function __construct(WebDriverTestCase $webdriver)
    {
        parent::__construct($webdriver, new Embed);
    }

    /**
     * Checks if the widget table is present on the page.
     *
     * @return boolean
     *   TRUE if present, FALSE if not.
     */
    public function widgetTablePresent()
    {
        $criteria = $this->webdriver->using('xpath')->value('//table[@id="widget-list"]');
        $elements = $this->webdriver->elements($criteria);
        if (count($elements) > 0) {
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'contextualToolbar':
                return new ConfigurePageContextualToolbar($this->webdriver);
            case 'widgetTable':
                return new WidgetTable($this->webdriver, '//table[@id="widget-list"]');
            case 'adminMenuLinks':
                return new ContentAdminMenuLinks($this->webdriver);
                break;
        }
        return parent::__get($property);
    }
}
