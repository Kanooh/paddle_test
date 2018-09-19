<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\ViewPage\ContactPersonViewPage.
 */

namespace Kanooh\Paddle\Pages\Node\ViewPage;

/**
 * A contact person page in the frontend view.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $showContactInfoLink
 */
class ContactPersonViewPage extends ViewPage
{
    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'showContactInfoLink':
                $xpath = '//div[contains(@class, "paddle-oup paddle-cp-ou")]/span/a[contains(@class, "paddle-other-organizations")]';
                return $this->webdriver->byXPath($xpath);
                break;
        }
        return parent::__get($property);
    }

    /**
     * Checks if the fields are in the correct div on the front end page view.
     */
    public function assertLayoutMarkup()
    {
        $this->webdriver->byCssSelector('div.paddle-cp-photo');
        $this->webdriver->byCssSelector('h2.paddle-cp-page-title');
        $this->webdriver->byCssSelector('div.paddle-cp-function');
        $this->webdriver->byCssSelector('div.paddle-cp-manager');
        $this->webdriver->byCssSelector('div.pane-node-body');
        $this->webdriver->byCssSelector('div.paddle-cp.paddle-cp-address');
        $this->webdriver->byCssSelector('div.paddle-cp-phone-office');
        $this->webdriver->byCssSelector('div.paddle-cp-mobile-office');
        $this->webdriver->byCssSelector('div.paddle-cp-email');
        $this->webdriver->byCssSelector('div.paddle-cp-website');
        $this->webdriver->byCssSelector('div.paddle-cp-linkedin');
        $this->webdriver->byCssSelector('div.paddle-cp-twitter');
        $this->webdriver->byCssSelector('div.paddle-cp-yammer');
        $this->webdriver->byCssSelector('div.paddle-cp-skype');
    }

    /**
     * Checks if the icons are not rendered on the frontend.
     */
    public function assertIconsRendered()
    {
        $this->webdriver->byClassName('fa-envelope');
        $this->webdriver->byClassName('fa-home');
        $this->webdriver->byClassName('fa-phone');
        $this->webdriver->byClassName('fa-link');
        $this->webdriver->byClassName('fa-linkedin');
        $this->webdriver->byClassName('fa-twitter');
        $this->webdriver->byClassName('fa-skype');
        $this->webdriver->byClassName('fa-mobile');
        $this->webdriver->byClassName('icon-yammer');
    }

    /**
     * Checks if the icons are not rendered.
     */
    public function assertNoIconsRendered()
    {
        $elements = $this->webdriver->elements(
            $this->webdriver->using('xpath')->value('//i[contains(@class, "fa-envelope")]')
        );
        $this->webdriver->assertEquals(0, count($elements));
        $elements = $this->webdriver->elements(
            $this->webdriver->using('xpath')->value('//i[contains(@class, "fa-home")]')
        );
        $this->webdriver->assertEquals(0, count($elements));
        $elements = $this->webdriver->elements(
            $this->webdriver->using('xpath')->value('//i[contains(@class, "fa-phone")]')
        );
        $this->webdriver->assertEquals(0, count($elements));
        $elements = $this->webdriver->elements(
            $this->webdriver->using('xpath')->value('//i[contains(@class, "fa-print")]')
        );
        $this->webdriver->assertEquals(0, count($elements));
        $elements = $this->webdriver->elements(
            $this->webdriver->using('xpath')->value('//i[contains(@class, "fa-link")]')
        );
        $this->webdriver->assertEquals(0, count($elements));
        $elements = $this->webdriver->elements(
            $this->webdriver->using('xpath')->value('//i[contains(@class, "fa-linkedin")]')
        );
        $this->webdriver->assertEquals(0, count($elements));
        $elements = $this->webdriver->elements(
            $this->webdriver->using('xpath')->value('//i[contains(@class, "fa-twitter")]')
        );
        $this->webdriver->assertEquals(0, count($elements));
        $elements = $this->webdriver->elements(
            $this->webdriver->using('xpath')->value('//i[contains(@class, "fa-skype")]')
        );
        $this->webdriver->assertEquals(0, count($elements));
        $elements = $this->webdriver->elements(
            $this->webdriver->using('xpath')->value('//i[contains(@class, "fa-mobile")]')
        );
        $this->webdriver->assertEquals(0, count($elements));
        $elements = $this->webdriver->elements(
            $this->webdriver->using('xpath')->value('//i[contains(@class, "icon-yammer")]')
        );
        $this->webdriver->assertEquals(0, count($elements));
    }

    /**
     * Checks if the manager link is present on the page.
     *
     * @param string $manager
     *   The manager name.
     *
     * @return bool
     *   True if a manager link has been found, false otherwise.
     */
    public function checkManagerLink($manager, $nid)
    {
        $alias = drupal_lookup_path('alias', 'node/' . $nid);
        $xpath = '//div[contains(@class, "paddle-cp-manager")]//a[text()="' . $manager . '" and contains(@href, "' . $alias . '")]';
        $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));
        return (bool) count($elements);
    }

    /**
     * Gets the organization link.
     *
     * @param string $title
     *   The title of the organizational unit
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function getOrganizationByTitle($title)
    {
        $xpath = '//div[contains(@class, "paddle-cp-fc-ou-parents")]/div/a[contains(text(), "' . $title . '")]';
        return $this->webdriver->byXPath($xpath);
    }
}
