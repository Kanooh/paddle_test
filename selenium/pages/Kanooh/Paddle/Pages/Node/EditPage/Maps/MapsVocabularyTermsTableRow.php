<?php

/**
 * @file
 * Contains Kanooh\Paddle\Pages\Node\EditPage\Maps\MapsVocabularyTermsTableRow.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage\Maps;

use Kanooh\Paddle\Pages\Element\Form\Checkbox;
use Kanooh\Paddle\Pages\Element\Table\Row;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class representing a single row in the maps vocabulary terms table.
 *
 * @property string $termId
 *   The id of the taxonomy term the row relates to.
 * @property string $name
 *   The name of the taxonomy term the row relates to.
 * @property Checkbox $enabled
 *   The checkbox to enable this taxonomy term row.
 * @property MapsVocabularyTermDisplayModeRadios $mode
 *   The radios to configure the display mode for the term in the frontend.
 */
class MapsVocabularyTermsTableRow extends Row
{
    /**
     * The Selenium webdriver element representing the row.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Constructs an MapsVocabularyTermsTableRow object.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The Selenium element representing the row.
     */
    public function __construct(WebDriverTestCase $webdriver, $element)
    {
        parent::__construct($webdriver);

        $this->element = $element;
    }

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'termId':
                return $this->element->attribute('data-term-id');
            case 'name':
                return $this->element->byXPath('.//input[@type="checkbox"]/../label')->text();
            case 'enabled':
                return new Checkbox($this->webdriver, $this->element->byXPath('.//input[@type="checkbox"]'));
            case 'mode':
                return new MapsVocabularyTermDisplayModeRadios($this->webdriver, $this->element);
        }

        throw new \RuntimeException("The property with the name $name is not defined.");
    }
}
