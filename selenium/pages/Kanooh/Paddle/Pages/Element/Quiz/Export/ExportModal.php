<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Quiz\Export\ExportModal.
 */

namespace Kanooh\Paddle\Pages\Element\Quiz\Export;

use Kanooh\Paddle\Pages\Element\Modal\Modal;

/**
 * The export modal, used to confirm the export of a quiz's results.
 *
 * @property ExportForm $form
 *   Export form inside the modal.
 */
class ExportModal extends Modal
{
    /**
     * XPath selector of the form element.
     *
     * @var string
     */
    protected $formXPathSelector = '//form[@id="paddle-quiz-export-form"]';

    /**
     * Makes the browser wait until the modal is fully loaded.
     *
     * This is determined by the fact that the form is displayed.
     */
    public function waitUntilOpened()
    {
        $this->webdriver->waitUntilElementIsDisplayed($this->formXPathSelector);

        // Store the modal's unique ids for later use.
        $this->getUniqueIds();
    }

    /**
     * {inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'form':
                return new ExportForm($this->webdriver, $this->webdriver->byXPath($this->formXPathSelector));
        }
    }
}
