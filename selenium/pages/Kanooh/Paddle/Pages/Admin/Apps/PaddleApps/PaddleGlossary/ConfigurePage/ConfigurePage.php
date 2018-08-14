<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleGlossary\ConfigurePage\ConfigurePage.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleGlossary\ConfigurePage;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Element\Glossary\GlossaryDefinitionTable;
use Kanooh\Paddle\Pages\PaddlePage;

/**
 * The configuration page for the Glossary paddlet.
 *
 * @property ConfigurePageContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 * @property GlossaryDefinitionTable $glossaryDefinitionTable
 *   The table of glossary definitions.
 */
class ConfigurePage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/paddlet_store/app/paddle_glossary/configure';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'contextualToolbar':
                return new ConfigurePageContextualToolbar($this->webdriver);
            case 'glossaryDefinitionTable':
                return new GlossaryDefinitionTable(
                    $this->webdriver,
                    '//form[@id="paddle-glossary-configuration-form"]//table/tbody'
                );
        }

        return parent::__get($property);
    }

    /**
     * Click on the label of a label and waits until the definitions for it are loaded.
     *
     * @param $letter
     *   The letter which to click.
     */
    public function showDefinitionsForLetter($letter)
    {
        $xpath = '//span[contains(@class, "views-summary")]/a[contains(@href, "views-glossary/' . strtolower($letter) . '")]';
        $element = $this->webdriver->byXPath($xpath);
        $element->click();

        $callable = new SerializableClosure(
            function () use ($element) {
                $classes = explode(' ', $element->attribute('class'));
                return in_array('active', $classes);
            }
        );
        $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());
    }
}
