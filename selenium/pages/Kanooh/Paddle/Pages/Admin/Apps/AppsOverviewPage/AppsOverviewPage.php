<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\AppsOverviewPage\AppsOverviewPage.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\AppsOverviewPage;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Apps\AppInterface;
use Kanooh\Paddle\Pages\Element\Links\PaddleStoreAdminMenuLinks;
use Kanooh\Paddle\Pages\PaddlePage;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * The Paddle Store overview page base class.
 *
 * @property PaddleStoreAdminMenuLinks $adminMenuLinks
 *   The admin menu links.
 * @property AppElement[] $apps
 *   Associative list of visible apps on the page, keyed by machine name
 * @property AppsOverviewPageFilterForm $filterForm
 *   Search form with faceted filters.
 */
class AppsOverviewPage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/paddlet_store';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'adminMenuLinks':
                return new PaddleStoreAdminMenuLinks($this->webdriver);
                break;
            case 'apps':
                return $this->getApps();
                break;
            case 'filterForm':
                $element = $this->webdriver->byXPath('//form[contains(@id, "paddle-apps-paddlets-form")]');
                return new AppsOverviewPageFilterForm($this->webdriver, $element);
                break;
        }
        return parent::__get($property);
    }

    /**
     * Gets all apps currently visible on the page.
     *
     * @return AppElement[]
     *   Associative list of app elements, keyed by machine name.
     */
    public function getApps()
    {
        $xpath = '//div[@class="paddle_apps_paddlets_overview"]//div[contains(concat(" ", normalize-space(@class), " "), " paddle-apps-paddlet ")]';
        $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));
        $apps = array();

        foreach ($elements as $element) {
            $app = new AppElement($element);
            $apps[$app->machineName] = $app;
        }

        return $apps;
    }

    /**
     * Waits until the overview page is updated after changing search filters.
     */
    public function waitUntilUpdated()
    {
        $page = $this;
        $this->webdriver->waitUntil(
            new SerializableClosure(
                function () use ($page) {
                    try {
                        $page->webdriver->byXPath('//div[contains(@class, "ajax-progress")]');
                    } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
                        return true;
                    }
                }
            ),
            $this->webdriver->getTimeout()
        );
    }

    /**
     * Gets an app element from the overview page.
     *
     * @param AppInterface $app
     *   The App that the element represents.
     *
     * @return AppElement
     *   The app element.
     *
     * @throws \PHPUnit_Extensions_Selenium2TestCase_WebDriverException
     */
    public function appElement(AppInterface $app)
    {
        $xpath = '//div[@class="paddle_apps_paddlets_overview"]//div[contains(@class, "app-' . $app->getId() . '")]';
        $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));

        if (count($elements)) {
            return new AppElement($elements[0]);
        } else {
            throw new \PHPUnit_Extensions_Selenium2TestCase_WebDriverException(
                "App {$app->getId()} not found on " . $this->path,
                // @codingStandardsIgnoreStart
                \PHPUnit_Extensions_Selenium2TestCase_WebDriverException::NoSuchElement
                // @codingStandardsIgnoreEnd
            );
        }
    }

    /**
     * Enables the app and wait until it is enabled.
     *
     * @param AppInterface $app
     *   The App to enable.
     */
    public function enableApp(AppInterface $app)
    {
        $app_element = $this->appElement($app);
        $app_element->links->linkEnable->click();

        // Confirm the activation in the dialog.
        $confirmation_modal = new ConfirmAppActivationModal($this->webdriver);
        $confirmation_modal->waitUntilOpened();
        $confirmation_modal->submit();
        $confirmation_modal->waitUntilClosed();

        $this->checkArrival();
    }
}
