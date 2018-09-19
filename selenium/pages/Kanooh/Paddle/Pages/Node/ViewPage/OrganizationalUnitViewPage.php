<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\ViewPage\OrganizationalUnitViewPage.
 */

namespace Kanooh\Paddle\Pages\Node\ViewPage;

use Kanooh\Paddle\Pages\Node\ViewPage\OpeningHours\ExceptionalClosingDaysFieldset;

/**
 * An organizational unit page node in the frontend view.
 *
 * @property ExceptionalClosingDaysFieldset $exceptionalClosingDays
 */
class OrganizationalUnitViewPage extends ViewPage
{
    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'exceptionalClosingDays':
                return new ExceptionalClosingDaysFieldset($this->webdriver, $this->webdriver->byCssSelector('fieldset.exceptional-closing-days'));
        }
        return parent::__get($property);
    }

    /**
     * Checks if the fields are in the correct div on the front end page view.
     */
    public function assertLayoutMarkup()
    {
        $this->webdriver->byCssSelector('div.field-name-field-paddle-featured-image');
        $this->webdriver->byCssSelector('h2.paddle-oup-page-title');
        $this->webdriver->byCssSelector('div.pane-node-body');
        $this->webdriver->byCssSelector('div.paddle-oup.paddle-oup-address');
        $this->webdriver->byCssSelector('div.paddle-oup-phone');
        $this->webdriver->byCssSelector('div.paddle-oup-fax');
        $this->webdriver->byCssSelector('div.paddle-oup-email');
        $this->webdriver->byCssSelector('div.paddle-oup-website');
        $this->webdriver->byCssSelector('div.paddle-oup-linkedin');
        $this->webdriver->byCssSelector('div.paddle-oup-facebook');
        $this->webdriver->byCssSelector('div.paddle-oup-twitter');
        $this->webdriver->byCssSelector('div.paddle-oup-head-unit');
        $this->webdriver->byCssSelector('div.paddle-oup-vat-number');
        $this->webdriver->byCssSelector('div.paddle-oup-parent-units');
    }

    /**
     * Checks if the list of parent entities is visible on frontend.
     *
     * @param int $nodeId
     * @param string $nodeTitle
     */
    public function assertParentEntities($nodeId, $nodeTitle)
    {
        $this->webdriver->byCssSelector('div.paddle-oup-parent-units.parent-'.$nodeId);
        $xpath = '//div[contains(@class, "parent-' . $nodeId . '")]/a';
        $this->webdriver->assertContains(strtolower($nodeTitle), $this->webdriver->byXPath($xpath)->attribute('href'));
    }

    /**
     * Checks if the icons are not rendered on the frontend.
     */
    public function assertIconsRendered()
    {
        $this->webdriver->byClassName('fa-envelope');
        $this->webdriver->byClassName('fa-home');
        $this->webdriver->byClassName('fa-phone');
        $this->webdriver->byClassName('fa-print');
        $this->webdriver->byClassName('fa-link');
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
    }
}
