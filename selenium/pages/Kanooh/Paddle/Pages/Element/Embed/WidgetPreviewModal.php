<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Embed\WidgetPreviewModal.
 */

namespace Kanooh\Paddle\Pages\Element\Embed;

use Kanooh\Paddle\Pages\Element\Modal\Modal;

/**
 * Class WidgetPreviewModal
 */
class WidgetPreviewModal extends Modal
{
    /**
     * {@inheritdoc}
     */
    public function waitUntilOpened()
    {
        // By default we wait for the submit button, but this modal doesn't have
        // any submit button. Instead wait for widget preview div.
        $this->webdriver->waitUntilElementIsDisplayed($this->xpathSelector . '//div[@id="widget-preview"]');
        $this->getUniqueIds();
    }
}
