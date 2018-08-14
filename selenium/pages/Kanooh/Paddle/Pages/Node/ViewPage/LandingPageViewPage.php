<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\ViewPage\LandingPageViewPage.
 */

namespace Kanooh\Paddle\Pages\Node\ViewPage;

use Kanooh\Paddle\Pages\Element\Display\PanelsDisplay;

/**
 * A landing page node in the frontend view.
 *
 * @property PanelsDisplay $display
 *   The panels display.
 */
class LandingPageViewPage extends ViewPage
{
    /**
     * {@inheritdoc}
     */
    public function waitUntilPageIsLoaded()
    {
        $this->webdriver->waitUntilElementIsDisplayed(
            '//body[contains(concat(" ", normalize-space(@class), " "), " node-type-landing-page ")]'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'display':
                // @todo - when we have more specific selector for the display
                // when on the front-end replace this generic selector.
                return new PanelsDisplay(
                    $this->webdriver,
                    '//div[@id="block-system-main"]/div[contains(@class, "content")]'
                );
        }
        return parent::__get($property);
    }
}
