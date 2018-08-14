<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\GlossaryOverviewPane.
 */

namespace Kanooh\Paddle\Pages\Element\Pane;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Element\Glossary\GlossaryDefinition;
use Kanooh\Paddle\Pages\Element\Pager\Pager;

/**
 * The glossary overview pane on the glossary overview page.
 *
 * @property GlossaryDefinition[] $definitions
 *   An array containing the definitions on the pane.
 * @property Pager $pager
 *   The pager element.
 */
class GlossaryOverviewPane extends Pane
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'definitions':
                $definitions = array();

                foreach ($this->webdriver->elements($this->webdriver->using('css selector')->value('.pane-paddle-glossary-glossary-overview-pane .glossary-definition')) as $definition) {
                    $definitions[] = new GlossaryDefinition($this->webdriver, $definition);
                }

                return $definitions;
                break;
            case 'pager':
                $element = $this->webdriver->byCss('.pane-paddle-glossary-glossary-overview-pane .pager');
                return new Pager($this->webdriver, $element);
                break;
        }
        throw new \Exception("The property $name is undefined.");
    }

    /**
     * Clicks on a letter and waits until the definitions for it are loaded.
     *
     * @param $letter
     *   The letter which to click.
     */
    public function showDefinitionsForLetter($letter)
    {
        $xpath = '//div[contains(@class, "views-summary")]/a[contains(@href, "views-glossary/' . strtolower($letter) . '")]';
        $element = $this->webdriver->byXPath($xpath);
        $element->click();
        $webdriver = $this->webdriver;

        $callable = new SerializableClosure(
            function () use ($xpath, $webdriver) {
                $element = $webdriver->byXPath($xpath);
                $classes = explode(' ', $element->attribute('class'));
                return in_array('active', $classes);
            }
        );
        $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());
    }
}
