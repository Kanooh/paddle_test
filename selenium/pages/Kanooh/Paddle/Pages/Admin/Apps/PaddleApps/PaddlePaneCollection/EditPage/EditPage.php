<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\\Admin\Apps\PaddleApps\PaddlePaneCollection\EditPage\EditPage.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddlePaneCollection\EditPage;

use Kanooh\Paddle\Pages\PaddlePage;

/**
 * EditPage class.
 *
 * @property EditPageDisplay $display
 * @property EditPageContextualToolbar $contextualToolbar
 */
class EditPage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/paddle-pane-collection/edit/%';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'display':
                return new EditPageDisplay($this->webdriver);
            case 'contextualToolbar':
                return new EditPageContextualToolbar($this->webdriver);
        }
        return parent::__get($property);
    }

    /**
     * Waits until the drag and drop editor is displayed.
     */
    public function waitUntilEditorIsLoaded()
    {
        // Wait until the in-place editor is refreshed. First the page is
        // reloaded, then the initIPE ajax command is launched. The class
        // 'panels-ipe-editing' indicates that the editor is fully loaded.
        // @see DrupalPanelsIPE::initEditing()
        $this->webdriver->waitUntilElementIsDisplayed('//div[contains(@class, "panels-ipe-editing")]');
    }
}
