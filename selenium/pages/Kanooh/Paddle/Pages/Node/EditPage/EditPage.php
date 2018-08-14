<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\EditPage.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\EditorialNote\EditorialNote;
use Kanooh\Paddle\Pages\Element\Form\AutoCompletedText;
use Kanooh\Paddle\Pages\Element\Form\Checkbox;
use Kanooh\Paddle\Pages\Element\Form\Checkboxes;
use Kanooh\Paddle\Pages\Element\Form\Select;
use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Pages\Element\NodeMenuItemList\NodeMenuItemList;
use Kanooh\Paddle\Pages\Element\NodeMetadataSummary\NodeMetadataSummary;
use Kanooh\Paddle\Pages\Element\Scald\ImageAtomField;
use Kanooh\Paddle\Pages\Element\Wysiwyg\Wysiwyg;
use Kanooh\Paddle\Pages\PaddlePage;
use Kanooh\Paddle\Pages\Admin\Structure\MenuManager\DeleteMenuItemModal;
use Kanooh\Paddle\Pages\Element\NodeMenuItemList\NodeMenuItem;
use Kanooh\Paddle\Pages\Element\MenuManager\OverviewForm\OverviewForm;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;
use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;

/**
 * The node edit page
 *
 * @property EditPageGeneralVocabularyTermReferenceTree $generalVocabularyTermReferenceTree
 *   The General Vocabulary term reference tree.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $menuLinkTitle
 * @property NodeMenuItemList $nodeMenuItemList
 *   The node menu item list.
 * @property NodeMetadataSummary $nodeSummary
 *   The node summary.
 * @property AutoCompletedText $responsibleAuthor
 *   The responsible author auto complete text field.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $title
 *   The title textfield.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $schedulerOptionsContainer
 *   The fieldset containing the scheduling options.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $schedulerOptionsTitle
 *   Title of the scheduler options.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $publishOnDate
 *   The textfield to set the date on which the node will be published.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $publishOnTime
 *   The textfield to set the time on which the node will be published.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $unpublishOnDate
 *   The textfield to set the date on which the node will be unpublished.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $unpublishOnTime
 *   The textfield to set the time on which the node will be unpublished.
 * @property Checkbox $enableRatingCheckbox
 *   Checkbox to enable/disable rating.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $showBreadcrumbCheckbox
 *   Checkbox to show/hide the breadcrumb.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $seoTitleField
 *   SEO title input field.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $seoDescriptionField
 *   SEO description textarea field.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $revisionHistory
 *   Revision history pane.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $navigationContainer
 *   Navigation container.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $showNextLevelCheckbox
 *   Checkbox to show/hide the next level menu items.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $taxonomyContainer
 *   The fieldset containing the taxonomy options.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $taxonomyContainerTitle
 *   The title of the fieldset containing the taxonomy options.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $addToMenuLink
 *   Link to open the menu item modal.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $tags
 *   The textfield to set tags linked to the page.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $tagsAddButton
 *   The button to add a tag linked to the page.
 * @property Text $teaser
 *   The teaser text area.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $teaserToggleLink
 *   The link to toggle the teaser (summary) field.
 * @property Wysiwyg $body
 *   The body text in a wysiwyg.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $pathAlias
 *   Path alias input field.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $pathAuto
 *   Checkbox to deactivate the path alias input field.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $creationDate
 *   The textfield to set the date on which the node was created.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $creationTime
 *   The textfield to set the time on which the node was created.
 * @property Text $editorialNoteText
 *   The textarea used to enter the text of an editorial note.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $editorialNoteSave
 *   The button used to save an editorial note.
 * @property EditorialNote[] $editorialNotes
 *   All the editorial notes.
 * @property CommentRadioButtons $commentRadioButtons
 *   The settings for comments.
 * @property Select $language
 *   The language of the node.
 * @property TranslationTable $translationTable
 *   The translation table on the page.
 * @property ImageAtomField $featuredImage
 * @property ProtectedPageRadioButtons $protectedPageRadioButtons
 *   The settings to limit visibility of published pages.
 * @property Checkboxes $protectedPageUserRolesCheckBoxes
 *   The settings to limit visibility of published pages to certain user roles.
 * @property Checkbox $generateTimestamp
 * @property Text $timestampDate
 * @property Text $timestampHour
 */
class EditPage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'node/%/edit';

    /**
     * The contextual toolbar.
     *
     * @var EditPageContextualToolbar
     */
    public $contextualToolbar;

    /**
     * The XPath selector of the scheduler options pane title.
     *
     * @var string
     */
    protected $xpathSchedulerOptionsTitle = '//div[contains(@class, "panel-pane pane-scheduler-form-pane")]/h2';

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * {@inheritdoc}
     */
    public function __construct(WebDriverTestCase $webdriver)
    {
        parent::__construct($webdriver);
        $this->contextualToolbar = new EditPageContextualToolbar($this->webdriver);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
    }

    /**
     * Populate the form fields.
     *
     * @param array $edit
     *    The values to enter in the edit form.
     *
     * @deprecated Use the specific properties representing the input fields
     *   instead.
     *
     * @deprecated Remove this and attach a Form class instead.
     */
    public function populateFields($edit)
    {
        // Set the title.
        if (!empty($edit['title'])) {
            // We need to clear otherwise Selenium appends the value at the end.
            $this->webdriver->byId('edit-title')->clear();
            $this->webdriver->byId('edit-title')->value($edit['title']);
        }

        // Enter the body.
        if (!empty($edit['body[und][0][value]'])) {
            $body = $edit['body[und][0][value]'];
            $this->body->setBodyText($body);
        }
        // Enable/disable pathauto.
        // If url alias has to be set, disable pathauto automatically.
        // Only click the checkbox if the value should be 0, and the checkbox is selected,
        // or if the value should be 1 and the checkbox is not selected.
        if (isset($edit['path[alias]'])) {
            $edit['path[pathauto]'] = 0;
        }

        $pathauto = $this->webdriver->byId('edit-path-pathauto');
        if (isset($edit['path[pathauto]']) &&
            (
                empty($edit['path[pathauto]']) &&
                $pathauto->selected() ||
                !empty($edit['path[pathauto]']) &&
                !$pathauto->selected()
            )
        ) {
            // On the edit screen of landing pages, the browser does not scroll
            // down. That is why tests fail on this part.
            $xpath = '//input[@id="edit-path-alias"]';
            $element = $this->webdriver->element($this->webdriver->using('xpath')->value($xpath));

            $this->webdriver->moveto($element);

            $pathauto->click();
        }

        // Set the url alias.
        if (isset($edit['path[alias]'])) {
            // We need to clear otherwise Selenium appends the value at the end.
            $this->webdriver->byId('edit-path-alias')->clear();
            $this->webdriver->byId('edit-path-alias')->value($edit['path[alias]']);
        }

        // Set the publish on date.
        if (!empty($edit['publish_on[date]'])) {
            $this->webdriver->byId('edit-publish-on-datepicker-popup-0')->clear();
            $this->webdriver->byId('edit-publish-on-datepicker-popup-0')->value($edit['publish_on[date]']);
        }

        // Set the publish on time.
        if (!empty($edit['publish_on[time]'])) {
            $this->webdriver->byId('edit-publish-on-timeEntry-popup-1')->clear();
            $this->webdriver->byId('edit-publish-on-timeEntry-popup-1')->value($edit['publish_on[time]']);
        }

        // Set the unpublish on date.
        if (!empty($edit['unpublish_on[date]'])) {
            $this->webdriver->byId('edit-unpublish-on-datepicker-popup-0')->clear();
            $this->webdriver->byId('edit-unpublish-on-datepicker-popup-0')->value($edit['unpublish_on[date]']);
        }

        // Set the unpublish on time.
        if (!empty($edit['unpublish_on[time]'])) {
            $this->webdriver->byId('edit-unpublish-on-timeEntry-popup-1')->clear();
            $this->webdriver->byId('edit-unpublish-on-timeEntry-popup-1')->value($edit['unpublish_on[time]']);
        }
    }

    /**
     * Check if this page has scheduler options.
     *
     * @return bool
     *   True if the scheduler options pane title is found.
     */
    public function supportsSchedulerOptions()
    {
        $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($this->xpathSchedulerOptionsTitle));
        return (bool) count($elements);
    }

    /**
     * Toggle the scheduler options open or closed.
     */
    public function toggleSchedulerOptions()
    {
        $title = $this->webdriver->byXPath($this->xpathSchedulerOptionsTitle);
        $this->moveto($title);
        $title->click();
        $this->webdriver->waitUntilElementIsDisplayed('//div[@id="edit-publish-on"]');
    }

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'generalVocabularyTermReferenceTree':
                return new EditPageGeneralVocabularyTermReferenceTree($this->webdriver);
            case 'addToMenuLink':
                return $this->webdriver->byXPath('//div[contains(@class, "pane-node-menu-items")]//a[contains(@class, "add-button")]');
            case 'navigationContainer':
                return $this->webdriver->byXPath('//div[contains(@class, "pane-basic-options")]');
            case 'nodeMenuItemList':
                return new NodeMenuItemList($this->webdriver);
            case 'nodeSummary':
                return new NodeMetadataSummary($this->webdriver);
            case 'pathAuto':
                return $this->webdriver->byName('path[pathauto]');
            case 'pathAlias':
                return $this->webdriver->byName('path[alias]');
            case 'title':
                return $this->webdriver->byName('title');
            case 'taxonomyContainer':
                return $this->webdriver->byXPath('//div[contains(@class, "pane-taxonomy-vocabularies")]');
            case 'taxonomyContainerTitle':
                return $this->webdriver->byXPath('//div[contains(@class, "pane-taxonomy-vocabularies")]//h2');
            case 'tags':
                return $this->webdriver->byName('field_paddle_tags[und][term_entry]');
            case 'tagsAddButton':
                return $this->webdriver->byXPath('//div[contains(@class, "field-name-field-paddle-tags")]//input[@type = "submit" and @name = "op"]');
            case 'teaser':
                $element = $this->webdriver->byName('body[und][0][summary]');
                return new Text($this->webdriver, $element);
                break;
            case 'teaserToggleLink':
                return $this->webdriver->byXPath('//div[contains(@class, "form-item-body-und-0-value")]//a[contains(@class, "link-edit-summary")]');
            case 'revisionHistory':
                return $this->webdriver->byClassName('pane-moderation-history-pane');
            case 'seoDescriptionField':
                return $this->webdriver->byXPath('//textarea[@id="edit-field-paddle-seo-description-und-0-value"]');
            case 'seoTitleField':
                return $this->webdriver->byXPath('//input[@id="edit-field-paddle-seo-title-und-0-value"]');
            case 'enableRatingCheckbox':
                return new Checkbox($this->webdriver, $this->webdriver->byId("edit-field-paddle-enable-rating-und"));
            case 'showBreadcrumbCheckbox':
                return $this->webdriver->byXPath('//input[@id="edit-field-show-breadcrumb-und"]');
            case 'showNextLevelCheckbox':
                return $this->webdriver->byXPath('//input[@id="edit-field-paddle-show-next-level-und"]');
            case 'responsibleAuthor':
                return new AutoCompletedText($this->webdriver, $this->webdriver->byName('field_page_responsible_author[und][0][target_id]'));
            case 'schedulerOptionsContainer':
                return $this->webdriver->byXPath('//div[contains(@class, "pane-scheduler-form-pane")]');
            case 'schedulerOptionsTitle':
                return $this->webdriver->byXPath('//div[contains(@class, "pane-scheduler-form-pane")]/h2');
            case 'publishOnDate':
                return $this->webdriver->byName('publish_on[date]');
            case 'publishOnTime':
                return $this->webdriver->byName('publish_on[time]');
            case 'unpublishOnDate':
                return $this->webdriver->byName('unpublish_on[date]');
            case 'unpublishOnTime':
                return $this->webdriver->byName('unpublish_on[time]');
            case 'body':
                return new Wysiwyg($this->webdriver, 'edit-body-und-0-value');
            case 'creationDate':
                return $this->webdriver->byName('creation_date[date]');
            case 'creationTime':
                return $this->webdriver->byName('creation_date[time]');
            case 'editorialNoteText':
                return new Text($this->webdriver, $this->webdriver->byName('editorial_note'));
            case 'editorialNoteSave':
                return $this->webdriver->byId('edit-editorial-note-submit');
            case 'editorialNotes':
                $criteria = $this->webdriver->using('xpath')->value('.//div[contains(@class, "paddle-editorial-note")]');
                $notes = $this->webdriver->elements($criteria);

                $items = array();
                foreach ($notes as $note) {
                    $items[] = new EditorialNote($this->webdriver, $note);
                }
                return $items;
            case 'commentRadioButtons':
                return new CommentRadioButtons($this->webdriver, $this->webdriver->byClassName('pane-node-form-comment'));
            case 'language':
                return new Select($this->webdriver, $this->webdriver->byName('language'));
            case 'translationTable':
                return new TranslationTable($this->webdriver);
            case 'featuredImage':
                return new ImageAtomField(
                    $this->webdriver,
                    $this->webdriver->byXPath('.//div/input[@name="field_paddle_featured_image[und][0][sid]"]/..')
                );
                break;
            case 'protectedPageRadioButtons':
                return new ProtectedPageRadioButtons($this->webdriver, $this->webdriver->byId('edit-field-paddle-prot-pg-visibility'));
            case 'protectedPageUserRolesCheckBoxes':
                return new Checkboxes($this->webdriver, $this->webdriver->byId('edit-field-paddle-prot-pg-user-roles'));
            case 'generateTimestamp':
                return new Checkbox($this->webdriver, $this->webdriver->byId("edit-field-paddle-overwrite-timestamp-und"));
            case 'timestampDate':
                return new Text($this->webdriver, $this->webdriver->byName('field_paddle_timestamp[und][0][value][date]'));
            case 'timestampHour':
                return new Text($this->webdriver, $this->webdriver->byName('field_paddle_timestamp[und][0][value][time]'));
        }
        return parent::__get($property);
    }

    /**
     * Moves the browser to the given element.
     *
     * This wrapper is needed because our current RandomFiller implementation
     * does not have access to the webdriver, so it cannot scroll to some
     * elements it needs to set.
     *
     * @todo Remove this once we have a more solid FormFiller implementation.
     * @see https://one-agency.atlassian.net/browse/KANWEBS-1643
     *
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The element to scroll to.
     */
    public function moveto(\PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        $this->webdriver->moveto($element);
    }

    /**
     * Waits until the given fieldset has slid open.
     *
     * This wrapper is needed because our current RandomFiller implementation
     * does not have access to the webdriver, so it cannot call the waitUntil()
     * method on it.
     *
     * @todo Remove this once we have a more solid FormFiller implementation.
     * @see https://one-agency.atlassian.net/browse/KANWEBS-1643
     *
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $fieldset
     *   The fieldset to wait on.
     */
    public function waitUntilFieldsetIsOpen(\PHPUnit_Extensions_Selenium2TestCase_Element $fieldset)
    {
        $callable = new SerializableClosure(
            function () use ($fieldset) {
                return !in_array('folded', explode(' ', $fieldset->attribute('class'))) ? true : null;
            }
        );
        $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());
    }

    /**
     * Returns the webdriver element of the general tag with the given tid.
     *
     * This wrapper is provided to help the EditSimpleContactPagePageRandomFiller
     * to target these dynamically generated elements. The random filler class
     * does not have access to the webdriver.
     *
     * @param int $tid
     *   The tid of the general vocabulary term of which to return the webdriver
     *   element.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     *   The webdriver element.
     *
     * @deprecated
     *   Use the TermReferenceTree class instead.
     */
    public function getGeneralVocabularyTermElement($tid)
    {
        return $this->webdriver->byName("field_paddle_general_tags[und][0][$tid][$tid]");
    }

    /**
     * Returns the webdriver elements of the general vocabulary tags.
     *
     * This wrapper is provided to help the EditSimpleContactPagePageRandomFiller
     * to target these dynamically generated elements. The random filler class
     * does not have access to the webdriver.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element[]
     *   The webdriver elements representing the general vocabulary tags.
     *
     * @deprecated
     *   Use the TermReferenceTree class instead.
     */
    public function getGeneralVocabularyTermElements()
    {
        $xpath = '//div[contains(@class, "field-name-field-paddle-general-tags")]//ul[contains(@class, "term-reference-tree-level")]//input[@type="checkbox"]';
        return $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));
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

    /**
     * Add the current node to a menu or edit existing menu item for it.
     *
     * @param NodeMenuItem $menu_item
     *   The menu item we are working on. If this is passed the operation is edit.
     * @param string $menu_item_title
     *   The menu menu_item_title to use when creating the menu item.
     * @param string $menu
     *   Machine name of the menu.
     * @param int $target
     *   Mlid of the target menu item. The item will be positioned relative to
     *   this item. If set to 0 the item will not be repositioned.
     * @param string $position
     *   How to position the item relative to the target item:
     *   - 'child': the item will be placed as a child of the target item.
     *   - 'after': the item will be placed next in line after the target item.
     *
     * @return int
     *   The mlid of the item created/edited.
     */
    public function addOrEditNodeMenuItem($menu_item = null, $menu_item_title = null, $menu = 'main_menu_nl', $target = 0, $position = 'child')
    {
        $item_mlid = null;
        if (!empty($menu_item)) {
            $menu_item->editIcon->click();
            $item_mlid = $menu_item->mlid;
        } else {
            $this->addToMenuLink->click();
        }
        $modal = new MenuItemModal($this->webdriver);
        $modal->waitUntilOpened();
        if (!empty($menu_item_title)) {
            $modal->title->fill($menu_item_title);
        }
        if ($menu) {
            $modal->navigation->selectOptionByValue($menu);
        }
        $modal->submit();
        // The next modal is the placement modal.
        $placement_modal = new MenuItemPositionModal($this->webdriver);
        $placement_modal->waitUntilOpened();

        // Try to get the menu item if we don't have it and we have the menu item title.
        $form_xpath = $placement_modal->getXPathSelector() .
            '//form[@id="paddle-menu-manager-node-menu-item-menu-placement-form"]';
        $menu_placement_form = new OverviewForm($this->webdriver, $this->webdriver->byXPath($form_xpath));
        if ($item_mlid == null && $menu_item_title) {
            $item_mlid = $menu_placement_form->overviewFormTable->getMenuItemRowByTitle($menu_item_title)->getMlid();
        }

        // Place it in the menu if the target mlid has been passed.
        if ($target) {
            // Find the new item and the target item in the list.
            $item_position = $menu_placement_form->overviewFormTable->getMenuItemPositionByMlid($item_mlid);
            $target_position = $menu_placement_form->overviewFormTable->getMenuItemPositionByMlid($target);

            // Focus the tabledrag handle so we can control it using the
            // keyboard.
            // @todo FOCUS - avoid using xpath by adding properties to OverviewFormTable.
            $xpath = '//table[@id = "menu-overview"]/tbody/tr[' . ($item_position + 1) . ']//a[contains(@class, "tabledrag-handle")]';
            $this->focusElementByXPath($xpath);

            // Move the item to the target position using the arrow keys.
            $key = $item_position < $target_position ? Keys::DOWN : Keys::UP;
            $keys = str_repeat($key, abs($target_position - $item_position));
            if ($position == 'child') {
                $keys .= Keys::RIGHT;
            }
            $this->webdriver->keys($keys);

            // The message about the changes to the table doesn't appear
            // consistently on both Chrome and Firefox. It only seems to work
            // reliably if the browser window itself has focus, but this cannot
            // be guaranteed on all test environments. Our end users of course
            // always have their browser windows focused while they are
            // interacting with the site, but the webdriver might be working
            // headless or in an unfocused window. Let's make sure the event
            // triggers. It listens to blurs and clicks.
            $xpath = '//table[@id = "menu-overview"]/tbody/tr[' . ($target_position + 1) . ']//a[contains(@class, "tabledrag-handle")]';
            $this->blurElementByXPath($xpath);
            $this->webdriver->byXPath('//div[contains(concat(" ", @class, " "), " modal-content ")]')->click();

            $this->webdriver->waitForText('Changes made in this table will not be saved until the form is submitted');
        }
        $placement_modal->submit();
        $modal->waitUntilClosed();

        return $item_mlid;
    }

    /**
     * Focuses the element identified by the given XPath.
     *
     * @see https://gist.github.com/yckart/6351935
     *
     * @param string $xpath
     *   The XPath query that represents the element that is in need of focus.
     *
     * @deprecated
     *   This method exists in the Element class. Once the tabledrag handles are
     *   objects of a class which extends Element we should use Element::focus().
     *   See "@todo FOCUS" above.
     *
     * @return string
     *   The Javascript code.
     */
    protected function focusElementByXPath($xpath)
    {
        $script = "
            var getElementByXPath = function(xpath) {
                return document.evaluate(xpath, document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;
            }
            getElementByXPath(arguments[0]).focus();
        ";
        $this->webdriver->execute(
            array(
                'script' => $script,
                'args' => array($xpath),
            )
        );
    }

    /**
     * Blurs the element identified by the given XPath.
     *
     * @see https://gist.github.com/yckart/6351935
     *
     * @param string $xpath
     *   The XPath query that represents the element that is in excess of focus.
     *
     * @deprecated
     *   This method exists in the Element class. Once the tabledrag handles are
     *   objects of a class which extends Element we should use Element::blur().
     *   See "@todo FOCUS" above.
     *
     * @return string
     *   The Javascript code.
     */
    protected function blurElementByXPath($xpath)
    {
        $script = "
            var getElementByXPath = function(xpath) {
                return document.evaluate(xpath, document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;
            }
            getElementByXPath(arguments[0]).blur();
        ";
        $this->webdriver->execute(
            array(
                'script' => $script,
                'args' => array($xpath),
            )
        );
    }

    /**
     * Delete a menu item for the current node.
     * @param NodeMenuItem $menu_item
     *   The <li> element in the list of menu items which we are deleting.
     */
    public function deleteMenuItem($menu_item)
    {
        $menu_item->deleteIcon->click();
        $modal = new DeleteMenuItemModal($this->webdriver);
        $modal->waitUntilOpened();
        $modal->submit();
        $modal->waitUntilClosed();
    }

    /**
     * Waits until the teaser has been toggled to visibility.
     */
    public function waitUntilTeaserIsDisplayed()
    {
        $teaser = $this->teaser;
        $callable = new SerializableClosure(
            function () use ($teaser) {
                return $teaser->isDisplayed() ? true : null;
            }
        );
        $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());
    }

    /**
     * Helper method to add an editorial note to the current node.
     *
     * @param string $body
     *   The text of the note.
     */
    public function addEditorialNote($body = '')
    {
        $body = !empty($body) ? $body : $this->alphanumericTestDataProvider->getValidValue();
        $this->editorialNoteText->fill($body);

        // We need to wait until the save button is visible.
        $element = $this->editorialNoteSave;
        $this->webdriver->waitUntilElementIsDisplayed(function () use ($element) {
            return $element;
        });

        $this->editorialNoteSave->click();
        $this->webdriver->waitUntilTextIsPresent('Your editorial note has been added.');
    }

    /**
     * Set the responsible author field in a node.
     *
     * @param string $username
     *   The username of the user we want to set as responsible.
     */
    public function setNodeResponsibleAuthor($username)
    {
        $this->responsibleAuthor->fill($username);
        /** @var \PHPUnit_Extensions_Selenium2TestCase_Element $element */
        $autocomplete = new AutoComplete($this->webdriver);
        $autocomplete->waitUntilDisplayed();
        $autocomplete->pickSuggestionByValue($username);
    }

    /**
     * Enables commenting for this node.
     */
    public function enableCommenting()
    {
        $this->webdriver->moveto($this->commentRadioButtons->open->getWebdriverElement());
        $this->webdriver->moveto($this->tagsAddButton);
        $this->commentRadioButtons->open->select();
        $this->contextualToolbar->buttonSave->click();
    }

    /**
     * Deletes the tag with the passed id.
     *
     * @param int $tid
     *   The id of the term to delete.
     */
    public function deleteTag($tid)
    {
        $xpath = '//div[@id = "at-term-' . $tid . '"]/span[contains(@class, "at-term-action-remove")]';
        $this->webdriver->byXPath($xpath)->click();
    }
}
