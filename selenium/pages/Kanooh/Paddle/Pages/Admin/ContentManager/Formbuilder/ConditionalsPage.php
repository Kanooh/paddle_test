<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Formbuilder\ConditionalsPage.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Formbuilder;

use Kanooh\Paddle\Pages\PaddlePage;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * The 'Conditionals' page of the Paddle Formbuilder module.
 *
 * @property FormBuilderContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $addConditionalButton
 *   The button which adds new conditional.
 */
class ConditionalsPage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'node/%/webform/conditionals';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'contextualToolbar':
                return new FormBuilderContextualToolbar($this->webdriver);
            case 'addConditionalButton':
                return $this->webdriver->byId('edit-conditionals-new-new');
        }

        return parent::__get($property);
    }
}
