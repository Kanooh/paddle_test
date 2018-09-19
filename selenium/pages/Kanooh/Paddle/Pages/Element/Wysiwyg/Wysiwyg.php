<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Wysiwyg\Wysiwyg.
 */

namespace Kanooh\Paddle\Pages\Element\Wysiwyg;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\Paddle\Pages\Element\Modal\Modal;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * A page element representing a wysiwyg editor.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonMaximize
 *   The maximize button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonSource
 *   The source button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonUndo
 *   The undo button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonRedo
 *   The redo button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonBold
 *   The bold button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonItalic
 *   The italic button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonUnderline
 *   The underline button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonStrike
 *   The strike button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonSubscript
 *   The subscript button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonSuperscript
 *   The superscript button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonBulletedlist
 *   The bulletedlist button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonNumberedlist
 *   The numberedlist button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonIndent
 *   The indent button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonOutdent
 *   The outdent button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonJustifyleft
 *   The justifyleft button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonJustifycenter
 *   The justifycenter button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonJustifyright
 *   The justifyright button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonJustifyblock
 *   The justifyblock button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonPastetext
 *   The pastetext button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonOpenScaldLibraryModal
 *   The Scald library button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonMediaembed
 *   The mediaembed button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonHorizontalrule
 *   The horizontalrule button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonTable
 *   The table button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonAnchor
 *   The anchor button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonLink
 *   The link button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonUnlink
 *   The unlink button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonScayt
 *   The spell-checking button.
 * @property ImagePropertiesModal $modalImageProperties
 *   The Image Properties modal.
 * @property LinkModal $modalLink
 *   The Link modal.
 * @property TablePropertiesModal $modalTableProperties
 *   The Table Properties modal.
 */
class Wysiwyg extends Element
{
    /**
     * The editor id.
     *
     * @var string
     */
    protected $editorId;

    /**
     * The webdriver element representing the wysiwyg.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $editor;

    /**
     * The XPath to the CKEditor element that holds maximized status class.
     *
     * @var string
     */
    protected $xpathSelectorMaximizedCKEditor;

    /**
     * The XPath selector for the body when the CKEditor is maximized.
     *
     * @var string
     */
    protected $xpathSelectorBodyMaximizedCKEditor = '//body[contains(concat(" ", @class, " "), " ckeditor-fullscreen ")]';

    /**
     * Constructs a Wysiwyg element.
     *
     * @param WebDriverTestCase $webdriver
     * @param string $editor_id
     *   The editor id. This usually matches the field name (for example
     *   'edit-body-und-0-value'). You can also inspect the CKEDITOR.instances
     *   variable in the javascript console to find it.
     */
    public function __construct(WebDriverTestCase $webdriver, $editor_id)
    {
        parent::__construct($webdriver);

        // Find the actual id of the active WYSIWYG editor.
        $element = $this->webdriver->byXPath('//textarea[contains(@id, "' . $editor_id . '")]');

        $this->editorId = $element->attribute('id');
        $this->xpathSelector = '//div[contains(concat(" ", normalize-space(@class), " "), " cke_editor_' . $this->editorId . '")]';
        $this->xpathSelectorMaximizedCKEditor = $this->xpathSelector . '/div[contains(concat(" ", @class, " "), " cke_maximized ")]';

        // Wait until the wysiwyg is fully loaded.
        $this->waitUntilReady();
        $this->editor = $this->getWebdriverElement();
    }

    /**
     * Magic getter for wysiwyg elements.
     *
     * @param string $property
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     *   The requested element.
     *
     * @throws \Exception
     *   Thrown when the requested property does not exist.
     */
    public function __get($property)
    {
        // Return buttons.
        if (strpos($property, 'button') === 0) {
            $button = strtolower(substr($property, 6));
            return $this->editor->byXPath('.//a[contains(concat(" ", @class, " "), " cke_button__' . $button . ' ")]');
        }

        // Return modal forms. These are instantiated here on demand rather than
        // in the constructor to save time and memory as these modals are rarely
        // interacted with.
        if (strpos($property, 'modal') === 0) {
            $modal = substr($property, 5);
            return $this->getModal($modal);
        }

        throw new \Exception('Property does not exist: ' . $property);
    }

    /**
     * Returns information about the modals that are used in the wysiwyg.
     *
     * @return array
     *   An associative array, keyed by modal name, with the modal class as
     *   value.
     */
    public function getModalInfo()
    {
        // @todo Implement the remaining modals.
        return array(
            'ImageProperties' => 'ImagePropertiesModal',
            'Link' => 'LinkModal',
            'TableProperties' => 'TablePropertiesModal',
        );
    }

    /**
     * Returns the modal with the given title.
     *
     * @param string $name
     *   The title of the modal. This corresponds with a camelcased version of
     *   the modal window title. Eg. 'EmbedMediaDialog'.
     *
     * @return Modal
     *   The requested modal.
     *
     * @throws \Exception
     *   Thrown when the requested modal is not defined.
     */
    public function getModal($name)
    {
        $modals = $this->getModalInfo();
        if (array_key_exists($name, $modals)) {
            $class = __NAMESPACE__ . '\\' . $modals[$name];
            return new $class($this->webdriver, $this->editorId);
        }
        throw new \Exception('Modal is not defined: ' . $name);
    }

    /**
     * Waits until the wysiwyg is ready to accept input.
     */
    public function waitUntilReady()
    {
        $variable = 'CKEDITOR.instances["' . $this->editorId . '"]';
        $webdriver = $this->webdriver;
        $callable = new SerializableClosure(
            function () use ($webdriver, $variable) {
                if ($webdriver->execute(
                    array(
                        'script' => "return 'undefined' !== typeof {$variable} && {$variable}.status == 'ready';",
                        'args' => array(),
                    )
                )) {
                    return true;
                }
            }
        );
        $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());
    }

    /**
     * Sets the body text.
     *
     * @param string $text
     *   The text to enter in the body.
     */
    public function setBodyText($text)
    {
        $this->webdriver->byId('cke_' . $this->editorId);
        $this->webdriver->execute(
            array(
                'script' => "CKEDITOR.instances['{$this->editorId}'].setData('" . $text . "');",
                'args' => array(),
            )
        );
    }

    /**
     * Gets the body text.
     *
     * @return string
     *   The text of the body.
     */
    public function getBodyText()
    {
        $this->webdriver->byId('cke_' . $this->editorId);
        return $this->webdriver->execute(
            array(
                'script' => "return CKEDITOR.instances['{$this->editorId}'].getData();",
                'args' => array(),
            )
        );
    }

    /**
     * Checks if an element with the given XPath is present in the body.
     *
     * This will execute PHPUnit_Extensions_Selenium2TestCase::byXPath() in the
     * iframe that contains the body text.
     *
     * @param string $xpath
     *   The XPath expression.
     */
    public function checkBodyByXPath($xpath)
    {
        $test_case = $this->webdriver;
        $callable = new SerializableClosure(
            function () use ($test_case, $xpath) {
                // Execute the XPath expression. The PHPUnit Selenium test case will
                // throw an exception if it is not found.
                $test_case->waitUntilElementIsPresent($xpath);
            }
        );
        $this->inIframe($callable);
    }

    /**
     * Switches to the WYSIWYG iframe, allowing you to interact with it.
     */
    public function inIframe($callback)
    {
        // Switch to the iframe that contains the body for this editor instance.
        $iframe = $this->webdriver->byXPath($this->xpathSelector . '//div[contains(concat(" ", @class, " "), " cke_contents ")]/iframe');
        $this->webdriver->frame($iframe);

        call_user_func($callback);

        // Switch back to the root frame.
        $this->webdriver->frame(null);
    }

    /**
     * Checks if the WYSIWYG is maximized.
     *
     * @return bool
     */
    public function isMaximized()
    {
        $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($this->xpathSelectorMaximizedCKEditor));
        return (bool) count($elements);
    }

    /**
     * Maximizes the WYSIWYG, if needed, and tests the maximization.
     */
    public function maximizeWindow()
    {
        // Maximize only if not already maximized.
        if (!$this->isMaximized()) {
            $this->buttonMaximize->click();
        }

        $this->webdriver->waitUntilElementIsDisplayed($this->xpathSelectorMaximizedCKEditor);
        $this->webdriver->waitUntilElementIsDisplayed($this->xpathSelectorBodyMaximizedCKEditor);
    }

    /**
     * Remove maximized status from the WYSIWYG, if needed, and tests the process.
     */
    public function normalizeWindow()
    {
        // Remove maximized status only if the WYSIWYG is maximized.
        if ($this->isMaximized()) {
            $this->buttonMaximize->click();
        }

        $this->webdriver->waitUntilElementIsNoLongerPresent($this->xpathSelectorMaximizedCKEditor);
        $this->webdriver->waitUntilElementIsNoLongerPresent($this->xpathSelectorBodyMaximizedCKEditor);
    }

    /**
     * Turns on or off the Scayt spell-checker depending on its previous condition.
     */
    public function toggleSpellChecking()
    {
        // Switch to the iframe that contains the Scayt button menu.
        $xpath = '//iframe[contains(@class, "cke_panel_frame")]';
        $this->webdriver->waitUntilElementIsDisplayed($xpath);
        $iframe = $this->webdriver->byXPath($xpath);
        $this->webdriver->frame($iframe);

        // Press the enable/disable button.
        $toggle_button = $this->webdriver->byXPath('//a[contains(@class, "cke_menubutton__scaytToggle")]');
        $toggle_button->click();

        // Switch back to the root frame.
        $this->webdriver->frame(null);
    }

    /**
     * Opens the image properties modal for an image atom.
     *
     * @param string $atom_id
     *   The atom id.
     */
    public function openImagePropertiesModal($atom_id)
    {
        // Double-click the image in the CKEditor.
        $test_case = $this->webdriver;
        $callable = new SerializableClosure(
            function () use ($test_case, $atom_id) {
                $xpath = '//img[contains(@class, "atom-id-' . $atom_id . '")]';
                $test_case->waitUntilElementIsPresent($xpath);
                $img = $test_case->byXPath($xpath);
                $test_case->moveto($img);
                $test_case->doubleclick();
            }
        );
        $this->inIframe($callable);

        // Wait for the image properties modal to open.
        $this->modalImageProperties->waitUntilOpened();
    }
}
