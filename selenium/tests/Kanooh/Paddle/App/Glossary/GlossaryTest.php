<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Glossary\GlossaryTest.
 */

namespace Kanooh\Paddle\App\Glossary;

use Kanooh\Paddle\Apps\Glossary;
use Kanooh\Paddle\Apps\Multilingual;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleGlossary\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminNodeViewPage;
use Kanooh\Paddle\Pages\Element\Glossary\GlossaryDefinitionDeleteModal;
use Kanooh\Paddle\Pages\Element\Glossary\GlossaryDefinitionModal;
use Kanooh\Paddle\Pages\Element\Scald\LibraryModal;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Pages\Node\ViewPage\GlossaryOverviewPageViewPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as FrontEndNodeViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\CleanUpService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class GlossaryTest
 * @package Kanooh\Paddle\App\Glossary
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class GlossaryTest extends WebDriverTestCase
{
    /**
     * @var AdminNodeViewPage
     */
    protected $adminNodeViewPage;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var AssetCreationService
     */
    protected $assetCreationService;

    /**
     * @var CleanUpService
     */
    protected $cleanUpService;

    /**
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var EditPage
     */
    protected $editPage;

    /**
     * @var FrontEndNodeViewPage
     */
    protected $frontEndNodeViewPage;

    /**
     * @var GlossaryOverviewPageViewPage
     */
    protected $glossaryOverviewPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Instantiate some classes to use in the test.
        $this->adminNodeViewPage = new AdminNodeViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->assetCreationService = new AssetCreationService($this);
        $this->configurePage = new ConfigurePage($this);
        $this->editPage = new EditPage($this);
        $this->frontEndNodeViewPage = new FrontEndNodeViewPage($this);
        $this->glossaryOverviewPage = new GlossaryOverviewPageViewPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new Glossary);
    }

    /**
     * Tests the Add/Edit/Delete functionality.
     */
    public function testGlossaryDefinition()
    {
        $this->configurePage->go();
        $this->configurePage->contextualToolbar->buttonAdd->click();

        $modal = new GlossaryDefinitionModal($this);
        $modal->waitUntilOpened();

        // Verify that the elements are required.
        $modal->form->saveButton->click();
        $this->waitUntilTextIsPresent('Definition field is required.');

        // Create a new definition.
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $description = $this->alphanumericTestDataProvider->getValidValue();
        $modal->form->definition->fill($title);
        $modal->form->description->setBodyText($description);
        $modal->form->saveButton->click();
        $modal->waitUntilClosed();
        $this->configurePage->checkArrival();
        $this->assertTextPresent('Definition saved.');
        $this->waitUntilTextIsPresent($title);

        // Verify that the definition has been created and that the description
        // has been saved.
        $definition = paddle_glossary_load_by_definition($title);
        $this->assertTrue(!empty($definition));
        $loaded_description = paddle_glossary_get_definition_description($definition);
        $this->assertContains($description, $loaded_description);

        $row = $this->configurePage->glossaryDefinitionTable->getRowByDefinition($definition->definition);
        $this->assertEquals($definition->definition, $row->definition);

        // Test the edit link.
        $row = $this->configurePage->glossaryDefinitionTable->getRowByDefinition($title);
        $row->linkEdit->click();
        $modal = new GlossaryDefinitionModal($this);
        $modal->waitUntilOpened();

        // Edit the description.
        // @todo: We should test the editing of the description as well but this
        // is blocked by KANWEBS-3872.
        $new_title = $this->alphanumericTestDataProvider->getValidValue();

        $modal->form->definition->waitUntilDisplayed();
        $modal->form->definition->clear();
        $modal->form->definition->waitUntilDisplayed();
        $modal->form->definition->fill($new_title);
        $modal->form->saveButton->click();
        $modal->waitUntilClosed();

        $this->configurePage->checkArrival();
        $this->assertTextPresent('Definition saved.');

        // Reload the definition and verify the description and the gdid are
        // still the same.
        $new_definition = paddle_glossary_load_by_definition($new_title);
        $new_loaded_description = paddle_glossary_get_definition_description($new_definition);
        $this->assertEquals($new_loaded_description, $loaded_description);
        $this->assertEquals($definition->gdid, $new_definition->gdid);

        // Now add a definition starting with 'a' so we are sure it starts with
        // the first letter and test that the page will be loaded by default.
        $this->configurePage->contextualToolbar->buttonAdd->click();
        $modal = new GlossaryDefinitionModal($this);
        $modal->waitUntilOpened();
        $title = 'a' . $this->alphanumericTestDataProvider->getValidValue();
        $modal->form->definition->fill($title);
        $modal->form->saveButton->click();
        $modal->waitUntilClosed();
        $this->configurePage->checkArrival();
        $this->assertTextPresent('Definition saved.');

        // The definition should be immediately visible.
        $this->waitUntilTextIsPresent($title);

        // Test the delete link.
        $row = $this->configurePage->glossaryDefinitionTable->getRowByDefinition($title);
        $row->linkDelete->click();
        $modal = new GlossaryDefinitionDeleteModal($this);
        $modal->waitUntilOpened();

        // Delete the definition.
        $modal->buttonConfirm->click();
        $this->waitUntilTextIsPresent('Definition deleted.');

        // Try to load the definition and verify there is none.
        $this->assertFalse(paddle_glossary_load_by_definition($title));
    }

    /**
     * Tests validation against the glossary definition.
     */
    public function testGlossaryDefinitionValidation()
    {
        $this->configurePage->go();
        $this->configurePage->contextualToolbar->buttonAdd->click();

        $modal = new GlossaryDefinitionModal($this);
        $modal->waitUntilOpened();

        // Prepare a valid title, appending a non-alphanumeric character
        // at the end.
        $title = $this->alphanumericTestDataProvider->getValidValue() . '!';

        // Verify that the definition can only start with alphanumeric
        // characters.
        $non_alphanumeric = ',';
        $modal->form->definition->fill($non_alphanumeric . $title);
        $modal->form->saveButton->click();
        $this->waitUntilTextIsPresent('A definition should start with a letter or a number.');

        // Create the definition.
        $modal->form->definition->fill($title);
        $modal->submit();
        $modal->waitUntilClosed();
        $this->configurePage->checkArrival();
        $this->waitUntilTextIsPresent($title);

        // Reload the page to get rid of the confirmation message.
        $this->configurePage->reloadPage();
        // Try to edit the definition.
        $row = $this->configurePage->glossaryDefinitionTable->getRowByDefinition($title);
        $row->linkEdit->click();
        $modal = new GlossaryDefinitionModal($this);
        $modal->waitUntilOpened();
        $description = $this->alphanumericTestDataProvider->getValidValue();
        $modal->form->description->setBodyText($description);
        $modal->submit();
        $this->waitUntilTextIsPresent('Definition saved.');

        // Try to add again the same definition.
        $this->configurePage->contextualToolbar->buttonAdd->click();
        $modal = new GlossaryDefinitionModal($this);
        $modal->waitUntilOpened();
        $modal->form->definition->fill($title);
        $modal->submit();
        $this->waitUntilTextIsPresent("The definition $title already exists.");

        // Test that a definition starting with a number is accepted.
        $title = '5' . $title;
        $modal->form->definition->fill($title);
        $modal->submit();
        $modal->waitUntilClosed();
        $this->configurePage->checkArrival();
        $this->waitUntilTextIsPresent($title);
    }

    /**
     * Tests the glossary overview page.
     */
    public function testGlossaryOverviewPage()
    {
        // Create a definition.
        $definition = $this->alphanumericTestDataProvider->getValidValue();
        $description = $this->alphanumericTestDataProvider->getValidValue();
        $this->contentCreationService->createGlossaryDefinition($definition, $description);

        $definition_object = paddle_glossary_load_by_definition($definition);

        $this->glossaryOverviewPage->go();
        $this->glossaryOverviewPage->glossaryOverviewPane->showDefinitionsForLetter($definition[0]);
        $this->waitUntilTextIsPresent($definition_object->definition);
        $definitions = $this->glossaryOverviewPage->glossaryOverviewPane->definitions;

        $not_present = true;
        foreach ($definitions as $def) {
            if ($def->definition->text() == $definition_object->definition && $def->description->text() == $description) {
                $not_present = false;
            }
        }

        $this->assertFalse($not_present);
    }

    /**
     * Tests the glossary definitions inside a body text.
     */
    public function testGlossaryDefinitionHighlighting()
    {
        // Create a definition.
        $definition = $this->alphanumericTestDataProvider->getValidValue();
        $description = $this->alphanumericTestDataProvider->getValidValue();
        $this->contentCreationService->createGlossaryDefinition($definition, $description);

        // Create a basic page and fill out the body.
        $nid = $this->contentCreationService->createBasicPage();
        $this->editPage->go($nid);
        $body_text = $this->alphanumericTestDataProvider->getValidValue(32) . ' ' . $definition . ' ' . $this->alphanumericTestDataProvider->getValidValue(32);
        $this->editPage->body->setBodyText($body_text);
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Now check if the title attribute is set.
        $this->frontEndNodeViewPage->go($nid);
        $this->assertDefinitionShown($definition, $description);

        // Add different cases of the definition.
        $this->editPage->go($nid);
        $body_text =  ' ' .  strtoupper($definition) . ' ' . $definition . $definition . ' ' . $definition . 'something' . ' (' . $definition . ') ' . strtoupper($definition) . ' ' . ucfirst($definition);
        $this->editPage->body->setBodyText($body_text);
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Now check if the title attribute is set.
        $this->frontEndNodeViewPage->go($nid);
        $this->assertDefinitionCount($definition, $description, 2);
    }

    /**
     * Test proper escaping of special characters inside definitions.
     */
    public function testGlossaryDefinitionEscaping()
    {
        // Create definitions that contain a regular expression character.
        $definitions = array();
        foreach (array('/', '$1', '|') as $character) {
            $definition = $this->alphanumericTestDataProvider->getValidValue()
                . $character . $this->alphanumericTestDataProvider->getValidValue();
            $description = $this->alphanumericTestDataProvider->getValidValue();

            // Create the definition.
            $this->contentCreationService->createGlossaryDefinition($definition, $description);

            // Save the definition title for later.
            $definitions[$definition] = $description;
        }

        // Create a basic page and edit it.
        $nid = $this->contentCreationService->createBasicPage();
        $this->editPage->go($nid);

        // Generate a body containing all the definitions and add it to the page.
        $body = '';
        foreach ($definitions as $definition => $description) {
            $body .= $definition . ' ' . $this->alphanumericTestDataProvider->getValidValue() . ' ';
        }
        // Whitespaces at the end of the body in a wysiwyg are deleted.
        $body = rtrim($body);
        $this->editPage->body->setBodyText($body);
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go on the front page and assert that the body is there and visible.
        $this->frontEndNodeViewPage->go($nid);
        $this->assertTextPresent($body);

        // Verify that the definitions are there.
        foreach ($definitions as $definition => $description) {
            $this->assertDefinitionShown($definition, $description);
        }
    }

    /**
     * Test that definitions don't break HTML tags.
     */
    public function testGlossaryDefinitionInHTMLTags()
    {
        // Create a definition.
        $definition = $this->alphanumericTestDataProvider->getValidValue();
        $description = $this->alphanumericTestDataProvider->getValidValue();
        $this->contentCreationService->createGlossaryDefinition($definition, $description);

        // Add an asset with the definition as alt tag.
        $data = $this->assetCreationService->createImage(array(
            'alternative_text' => $definition,
        ));

        // Create a basic page and set the body.
        $nid = $this->contentCreationService->createBasicPage();
        $this->editPage->go($nid);
        $body = $definition . ' ' . $this->alphanumericTestDataProvider->getValidValue();
        $this->editPage->body->setBodyText($body);

        // Maximize the editor to avoid problems clicking elements.
        $this->editPage->body->waitUntilReady();
        $this->editPage->body->maximizeWindow();

        // Insert the atom in the editor.
        $this->editPage->body->buttonOpenScaldLibraryModal->click();
        $library_modal = new LibraryModal($this);
        $library_modal->waitUntilOpened();
        $atom = $library_modal->library->getAtomById($data['id']);
        $atom->insertLink->click();
        $library_modal->waitUntilClosed();

        // Save the page and go to the frontend view.
        $this->editPage->body->normalizeWindow();
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
        $this->frontEndNodeViewPage->go($nid);

        // Assert that the image is present.
        $this->assertNotEmpty($this->byClassName("atom-id-{$data['id']}"));

        // Verify that the body contains only the inserted text.
        $this->assertEquals($body, $this->frontEndNodeViewPage->body->text());

        // Verify that the definition is there.
        $this->assertDefinitionShown($definition, $description);
    }

    /**
     * Tests the views attachment on the glossary overview page when Paddle i18n
     * is enabled. See https://one-agency.atlassian.net/browse/KANWEBS-4728.
     *
     * @group regression
     */
    public function testGlossaryViewAttachment()
    {
        // Enable the Paddle i18n as the problem appears when it is enabled.
        $this->appService->enableApp(new Multilingual());

        // Create 3 definition all starting with different letter.
        $letters = array('g', 'k', 's');
        $created_definitions = array();
        foreach ($letters as $letter) {
            $definition = $letter . $this->alphanumericTestDataProvider->getValidValue();
            $this->contentCreationService->createGlossaryDefinition($definition, '');
            $created_definitions[$letter] = $definition;
        }

        $this->glossaryOverviewPage->go();
        // All 3 definitions should be present.
        $definitions = $this->glossaryOverviewPage->glossaryOverviewPane->definitions;
        foreach ($letters as $letter) {
            $this->assertTextPresent($created_definitions[$letter]);
        }

        // Check that the view attachment works properly.
        $this->glossaryOverviewPage->glossaryOverviewPane->showDefinitionsForLetter($letters[0]);
        $this->waitUntilTextIsPresent($created_definitions[$letters[0]]);
        $definitions = $this->glossaryOverviewPage->glossaryOverviewPane->definitions;

        foreach ($definitions as $def) {
            if ($def->definition->text() == $created_definitions[$letters[1]] ||
              $def->definition->text() == $created_definitions[$letters[2]]) {
                $this->fail('Only definitions for the selected letter should appear.');
            }
        }
    }

    /**
     * {@inheritdoc}
    */
    public function tearDown()
    {
        $this->cleanUpService = new CleanUpService($this);
        $this->cleanUpService->deleteEntities('paddle_glossary_definition');

        parent::tearDown();
    }

    /**
     * Asserts that a glossary definition is being shown.
     *
     * @param string $definition
     *   The definition to check for.
     * @param string $description
     *   The description for the definition.
     */
    public function assertDefinitionShown($definition, $description)
    {
        $xpath = '//div[contains(@class, "field-name-body")]//span[contains(@data-original-title, "' . $description . '") and contains(text(), "' . $definition . '")]';
        $elements = $this->elements($this->using('xpath')->value($xpath));
        $this->assertTrue((bool) count($elements), "Definition $definition has not been found.");

        $def = $this->byClassName('glossary-definition');
        $this->moveto($def);
        $this->waitUntilElementIsPresent('//div[contains(@class, "tooltip")]');
    }

    /**
     * Asserts the number of highlights of a glossary definition.
     *
     * @param string $definition
     *   The definition to create an entry for.
     * @param string $description
     *   The description for the definition.
     * @param string $count
     *   The number of times a definition should be highlighted.
     */
    public function assertDefinitionCount($definition, $description, $count)
    {
        $xpath = '//div[contains(@class, "field-name-body")]//span[contains(@title, ' . $description . ') and contains(text(), ' . $definition . ')]';
        $elements = $this->elements($this->using('xpath')->value($xpath));
        $this->assertEquals($count, count($elements));
    }
}
