<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Scald\Library.
 */

namespace Kanooh\Paddle\Pages\Element\Scald;

use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\Paddle\Pages\Element\Form\AutoCompletedText;
use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Utilities\DrupalApi\DrupalAjaxApi;

/**
 * Class Library
 *
 * @property LibraryItem[] $items
 *   List of all items displayed in the library.
 * @property Text $searchText
 *   The free search text input field.
 * @property AutoCompletedText $tagsAutocompleteField
 *   The autocomplete field to filter by tags.
 * @property AutoCompletedText $generalTermsAutocompleteField
 *   The autocomplete field to filter by general terms.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $searchButton
 *   The search submit button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $showMoreLink
 *   The 'Show more' link.
 */
class Library extends Element
{
    /**
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     *   The webdriver element.
     */
    protected $element;

    /**
     * @var bool
     *   Indicates whether the library is shown in a modal or on a page.
     */
    protected $libraryShownInModal;

    /**
     * Constructs a new Library object.
     *
     * @param \Kanooh\WebDriver\WebDriverTestCase $webdriver
     *   The Selenium web driver test case.
     * @param string $xpath_selector
     *   XPath selector of the library.
     * @param bool $shown_in_modal
     *   Indicates whether the library is shown in a modal or on a page.
     */
    public function __construct($webdriver, $xpath_selector, $shown_in_modal = true)
    {
        parent::__construct($webdriver);
        $this->xpathSelector = $xpath_selector;
        $this->libraryShownInModal = $shown_in_modal;
        $this->element = $this->getWebdriverElement();
    }

    /**
     * Return the atom with a specific id.
     *
     * @param int $id
     *   Atom ID.
     *
     * @return LibraryItem
     *   The atom corresponding to the specified id, or false if the atom was
     *   not found.
     */
    public function getAtomById($id)
    {
        $atom = false;

        while ($atom == false) {
            // Look for the atom in the currently visible atoms.
            $xpath = $this->getXPathSelector() . '//div[@data-atom-id="' . $id . '"]';
            $criteria = $this->webdriver->using('xpath')->value($xpath);
            $elements = $this->webdriver->elements($criteria);

            if (!empty($elements)) {
                // If the atom was found, store it in the variable.
                $atom = new LibraryItem($elements[0]);
            } elseif ($this->showMoreLinkDisplayed()) {
                // Otherwise click the show more link and retry to find the atom
                // if the show more link is visible.
                $this->showMore();
            } else {
                // Otherwise stop looking.
                break;
            }
        }

        return $atom;
    }

    /**
     * Returns a list of visible library items.
     *
     * @return LibraryItem[]
     */
    protected function getItems()
    {
        $elements = $this->webdriver->elements($this->webdriver->using('css selector')->value('div.paddle-library-item'));

        $items = array();

        foreach ($elements as $element) {
            $items[] = new LibraryItem($element);
        }

        return $items;
    }

    /**
     * Returns the number of visible items in the library.
     *
     * @return int
     *   The number of items.
     */
    public function getItemCount()
    {
        return count($this->getItems());
    }

    /**
     * Clicks on the "Show more" link and waits until the items are loaded.
     */
    public function showMore()
    {
        $this->webdriver->moveto($this->showMoreLink);
        $this->showMoreLink->click();
        $drupalAjaxApi = new DrupalAjaxApi($this->webdriver);
        // Pass the id since this element disapears when the last page is loaded.
        $drupalAjaxApi->waitUntilElementFinishedAjaxing('paddle-scald-library-show-more', $this->webdriver->getTimeout());
    }

    /**
     * Checks if the show more link is displayed.
     */
    public function showMoreLinkDisplayed()
    {
        $xpath = $this->getXPathSelector() . '//div[contains(@class, "show-more")]/a';
        $criteria = $this->webdriver->using('xpath')->value($xpath);
        $elements = $this->webdriver->elements($criteria);

        return !empty($elements);
    }

    /**
     * Waits until the library is reloaded.
     *
     * The library can be either in a page or in a modal. In the page the ajax
     * is off, so the whole page is reloaded. To keep just one method and also
     * to avoid hard-coding the media library page in this class (that, in case
     * of the non modal mode, it's a view element) we always wait for the
     * element to be stale.
     */
    public function waitUntilReloaded()
    {
        // Wait for the element to become stale.
        $element = $this->element;
        $this->webdriver->waitUntil(
            function () use ($element) {
                try {
                    /* @var \PHPUnit_Extensions_Selenium2TestCase_Element $element */
                    $element->displayed();

                    return null;
                } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
                    // The element is stale.
                    return true;
                }
            },
            $this->webdriver->getTimeout()
        );

        $library = $this;
        // Now wait until the library element is back in place.
        $this->webdriver->waitUntil(
            function () use ($library) {
                /* @var Library $library */
                return $library->getWebdriverElement();
            },
            $this->webdriver->getTimeout()
        );

        // Update the webdriver element value in case the element needs to
        // be reloaded again.
        $this->element = $this->getWebdriverElement();
    }

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'items':
                return $this->getItems();
            case 'searchText':
                $xpath = $this->libraryShownInModal ? '//input[@name="search"]' : '//input[@name="search_api_views_fulltext"]';
                return new Text($this->webdriver, $this->webdriver->byXPath($this->xpathSelector . $xpath));
            case 'tagsAutocompleteField':
                return new AutoCompletedText(
                    $this->webdriver,
                    $this->webdriver->byXPath($this->xpathSelector . '//input[@name="tags"]')
                );
            case 'generalTermsAutocompleteField':
                return new AutoCompletedText(
                    $this->webdriver,
                    $this->webdriver->byXPath($this->xpathSelector . '//input[@name="general_tags"]')
                );
            case 'searchButton':
                $xpath = $this->libraryShownInModal ? '//input[@name="op"][@value="Search"]' : '//input[@value="Apply"]';
                return $this->webdriver->byXPath($this->xpathSelector . $xpath);
            case 'showMoreLink':
                return $this->webdriver->byCssSelector('.paddle-scald-library-form .show-more a.button');
            default:
        }
    }
}
