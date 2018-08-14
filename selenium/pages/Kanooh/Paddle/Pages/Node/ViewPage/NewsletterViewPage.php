<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\ViewPage\NewsletterViewPage.
 */

namespace Kanooh\Paddle\Pages\Node\ViewPage;

use Kanooh\Paddle\Pages\Element\Display\PanelsDisplay;

/**
 * A newsletter node in the frontend view.
 *
 * @property PanelsDisplay $display
 *   The panels display.
 */
class NewsletterViewPage extends ViewPage
{
    /**
     * {@inheritdoc}
     */
    public function waitUntilPageIsLoaded()
    {
        $this->webdriver->waitUntilElementIsDisplayed(
            '//body[contains(concat(" ", normalize-space(@class), " "), " node-type-newsletter ")]'
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
