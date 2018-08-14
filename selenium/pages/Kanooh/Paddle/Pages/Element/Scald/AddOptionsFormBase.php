<?php
/**
 * @file
 * \Kanooh\Paddle\Pages\Element\Scald\AddOptionsFormBase.
 */

namespace Kanooh\Paddle\Pages\Element\Scald;

use Kanooh\Paddle\Pages\Element\Form\Form;

/**
 * Class AddOptionsFormBase
 * @package Kanooh\Paddle\Pages\Element\Scald
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $finishButton
 * @property AddOptionsFormGeneralVocabularyTermReferenceTree $generalVocabularyTermReferenceTree
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $tags
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $tagsAddButton
 */
class AddOptionsFormBase extends Form
{
    public function __get($name)
    {
        switch ($name) {
            case 'finishButton':
                return $this->element->byXPath('.//input[@value="Finish"]');
            case 'generalVocabularyTermReferenceTree':
                return new AddOptionsFormGeneralVocabularyTermReferenceTree($this->webdriver);
            case 'tags':
                return $this->webdriver->byName('atom0[field_paddle_tags][und][term_entry]');
            case 'tagsAddButton':
                return $this->webdriver->byXPath('//div[contains(@class, "field-name-field-paddle-tags")]//input[@type = "submit" and @name = "op"]');
        }
    }

    /**
     * Waits until the given tag is displayed.
     *
     * Call this after adding a new tag to the page.
     *
     * @param string $tag
     *   The tag that was added.
     */
    public function waitUntilTagIsDisplayed($tag)
    {
        $xpath = '//div[contains(@class, "field-name-field-paddle-tags")]//div[contains(@class, "at-term-list")]//span[text() = "' . $tag . '"]';
        $this->webdriver->waitUntilElementIsDisplayed($xpath);
    }
}
