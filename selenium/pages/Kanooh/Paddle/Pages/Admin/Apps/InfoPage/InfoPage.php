<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\InfoPage\InfoPage.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\InfoPage;

use Kanooh\Paddle\Apps\AppInterface;
use Kanooh\Paddle\Pages\Element\Links\TopLevelAdminMenuLinks;
use Kanooh\Paddle\Pages\PaddlePage;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * The info page for the paddlets.
 *
 * @property InfoPageContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 * @property TopLevelAdminMenuLinks $adminMenuLinks
 *   The admin menu links.
 */
class InfoPage extends PaddlePage
{
    /**
     * @var AppInterface
     */
    protected $app;

    /**
     * Constructs an InfoPage.
     *
     * @param \Kanooh\WebDriver\WebDriverTestCase $webdriver
     *   The interface to the Selenium webdriver.
     * @param AppInterface $app
     *   The app that is being shown on the infopage.
     */
    public function __construct(WebDriverTestCase $webdriver, AppInterface $app)
    {
        parent::__construct($webdriver);

        $this->app = $app;
        $this->path = 'admin/paddlet_store/app/' . $app->getModuleName();
    }

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'adminMenuLinks':
                return new TopLevelAdminMenuLinks($this->webdriver);
            case 'contextualToolbar':
                return new InfoPageContextualToolbar($this->webdriver);
        }
        return parent::__get($property);
    }

    /**
     * Checks if the detailed description is shown correctly.
     *
     * @return bool
     *   Returns true if the detailed description has been found,
     *   false otherwise.
     *
     * @todo for now we only return true unless we hit the strpos check. This
     * is because at this point in time, the apps don't have the detailed
     * description yet.
     */
    public function checkDetailedDescription()
    {
        // Get the content of the app.
        $content = paddle_apps_get_detailed_description($this->app->getModuleName());

        if (!empty($content)) {
            $criteria = $this->webdriver->using('xpath')->value('//div[contains(@class, "detailed-description")]');
            $elements = $this->webdriver->elements($criteria);
            if (!empty($elements)) {
                /* @var \PHPUnit_Extensions_Selenium2TestCase_Element $element */
                $element = $elements[0];
                // For some reason text() tends to return an empty string
                // here, but only sometimes.
                $html = $element->attribute('innerHTML');

                // Check if the HTML contains the content in the detailed
                // description.
                return strpos($html, $content) !== false;
            }
        }
        // @todo: once all the apps have the detailed description implemented,
        // this should return false.
        return true;
    }

    /**
     * Get the FAQs.
     *
     * @return array
     *   Array of FAQ where the key is the title and the value is the link.
     */
    public function getFAQ()
    {
        $faq = array();

        $xpath = '//div[contains(@class, "faq")]//a';
        $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));
        foreach ($elements as $element) {
            $faq[$element->text()] = $element->attribute('href');
        }

        return $faq;
    }

    /**
     * Get the vendor info.
     *
     * @return array
     *   Array with two elements - the vendor name and vendor link.
     */
    public function getVendorInfo()
    {
        $info = array();

        $xpath = '//div[contains(@class, "statistics")]/div[contains(@class, "vendor")]/a';
        $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));
        if (!empty($elements[0])) {
            $info = array(
                'vendor' => $elements[0]->text(),
                'link' => $elements[0]->attribute('href'),
            );
        }

        return $info;
    }
}
