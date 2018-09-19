<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\TranslatePage\TranslatePage.
 */

namespace Kanooh\Paddle\Pages\Node\TranslatePage;

use Kanooh\Paddle\Pages\AdminPage;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\Form\AutoCompletedText;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * The node translate page.
 *
 * @property AutocompletedText autocompleteNodeNl
 *   The autocomplete to associate a node as Dutch translation.
 * @property AutocompletedText autocompleteNodeEn
 *   The autocomplete to associate a node as English translation.
 * @property AutocompletedText autocompleteNodeFr
 *   The autocomplete to associate a node as French translation.
 * @property AutocompletedText autocompleteNodeDe
 *   The autocomplete to associate a node as German translation.
 * @property TranslationPageTable $translationTable
 *   The translation table on the page.
 */
class TranslatePage extends AdminPage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'node/%/translate';

    /**
     * The contextual toolbar.
     *
     * @var TranslatePageContextualToolbar
     */
    public $contextualToolbar;

    /**
     * {@inheritdoc}
     */
    public function __construct(WebDriverTestCase $webdriver)
    {
        parent::__construct($webdriver);
        $this->contextualToolbar = new TranslatePageContextualToolbar($this->webdriver);
    }

    /**
     * @inheritDoc
     */
    public function __get($property)
    {
        if (strpos($property, 'autocompleteNode') === 0) {
            $language = strtolower(substr($property, 16));
            $element = $this->webdriver->byXpath('//input[@name="translations[node][' . $language . ']"]');
            return new AutoCompletedText($this->webdriver, $element);
        }

        switch ($property) {
            case 'translationTable':
                return new TranslationPageTable($this->webdriver);
                break;
        }

        return parent::__get($property);
    }

    /**
     * Select an existing node to be used as translation of the current node.
     *
     * @param string $language
     *   The language ISO code.
     * @param string $title
     *   The title of the node to use as translation.
     */
    public function selectExistingTranslation($language, $title)
    {
        $field_name = 'autocompleteNode' . ucfirst($language);
        $this->{$field_name}->fill($title);
        $autocomplete = new AutoComplete($this->webdriver);
        $autocomplete->pickSuggestionByValue($title);
    }
}
