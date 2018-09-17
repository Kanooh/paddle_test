<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\PaneCollection\PaneCollectionTest.
 */

namespace Kanooh\Paddle\App\PaneCollection;

use Kanooh\Paddle\Apps\PaneCollection;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddlePaneCollection\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddlePaneCollection\EditPage\EditPage;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\PaneCollection\PaneCollectionModal;
use Kanooh\Paddle\Pages\Element\PaneCollection\PaneCollectionTableRow;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CustomContentPanelsContentType;
use Kanooh\Paddle\Pages\Element\PanelsContentType\PaneCollection\PaddlePaneCollectionPanelsContentType;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class PaneCollectionTest
 * @package Kanooh\Paddle\App\PaneCollection
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PaneCollectionTest extends WebDriverTestCase
{
    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var EditPage
     */
    protected $editPage;

    /**
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

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
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->configurePage = new ConfigurePage($this);
        $this->editPage = new EditPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new PaneCollection);
    }

    /**
     * Tests the Add/Edit/Delete functionality.
     */
    public function testPaneCollection()
    {
        $this->configurePage->go();
        $this->configurePage->contextualToolbar->buttonAdd->click();

        $modal = new PaneCollectionModal($this);
        $modal->waitUntilOpened();

        // Verify that the elements are required.
        $modal->form->saveButton->click();
        $this->waitUntilTextIsPresent('Title field is required.');

        // Create a new pane collection.
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $modal->form->title->fill($title);
        $modal->form->saveButton->click();
        $modal->waitUntilClosed();
        $this->configurePage->checkArrival();
        $this->assertTextPresent('Pane collection saved.');
        $this->waitUntilTextIsPresent($title);

        // Verify that the pane collection has been created and that the description
        // has been saved.
        $pane_collection = paddle_pane_collection_load_by_title($title);
        $this->assertTrue(!empty($pane_collection));

        /** @var PaneCollectionTableRow $row */
        $row = $this->configurePage->paneCollectionTable->getRowByTitle($pane_collection->title);
        $this->assertEquals($pane_collection->title, $row->title);

        // Go to the pane collection edit page and add a pane.
        $row->actions->linkLayout->click();
        $this->editPage->checkArrival();
        $this->editPage->waitUntilEditorIsLoaded();

        // Verify you cannot add a pane collection.
        $this->editPage->display->getRandomRegion()->buttonAddPane->click();
        $modal = new AddPaneModal($this);
        $modal->waitUntilOpened();

        try {
            $modal->selectContentType(new PaddlePaneCollectionPanelsContentType($this));
            $this->fail('We should not be able to select a pane collection pane on a pane collection edit page.');
        } catch (\Exception $e) {
            // Works like intended, close the modal.
            $modal->close();
        }

        $content_type = new CustomContentPanelsContentType($this);
        $body = $this->alphanumericTestDataProvider->getValidValue();
        $content_type->body = $body;
        $this->editPage->display->getRandomRegion()->addPane($content_type);
        $this->waitUntilTextIsPresent($body);
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->configurePage->waitUntilPageIsLoaded();
        $this->waitUntilTextIsPresent($pane_collection->title);

        // Update the title of a pane collection.
        $row = $this->configurePage->paneCollectionTable->getRowByTitle($pane_collection->title);
        $row->actions->linkEdit->click();
        $modal = new PaneCollectionModal($this);
        $modal->waitUntilOpened();
        $new_title = $this->alphanumericTestDataProvider->getValidValue();
        $modal->form->title->clear();
        $modal->form->title->fill($new_title);
        $modal->form->saveButton->click();
        $modal->waitUntilClosed();
        $this->configurePage->checkArrival();
        $this->waitUntilTextIsPresent($new_title);

        // Go back to the edit page and verify the pane is still there.
        /** @var PaneCollectionTableRow $row */
        $row = $this->configurePage->paneCollectionTable->getRowByTitle($new_title);
        $row->actions->linkLayout->click();
        $this->editPage->checkArrival();
        $this->editPage->display->waitUntilEditorIsLoaded();
        $this->assertEquals(1, count($this->editPage->display->getRandomRegion()->getPanes()));
        $this->assertTextPresent($body);
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->configurePage->waitUntilPageIsLoaded();
    }

    /**
     * Tests validation.
     */
    public function testPaneCollectionValidation()
    {
        $this->configurePage->go();
        $this->configurePage->contextualToolbar->buttonAdd->click();

        $modal = new PaneCollectionModal($this);
        $modal->waitUntilOpened();

        // Prepare a valid title, appending a non-alphanumeric character
        // at the end.
        $title = $this->alphanumericTestDataProvider->getValidValue() . '!';

        // Verify that the definition can only start with alphanumeric
        // characters.
        $non_alphanumeric = ',';
        $modal->form->title->fill($non_alphanumeric . $title);
        $modal->form->saveButton->click();
        $this->waitUntilTextIsPresent('A title should start with a letter or a number.');

        // Create the pane collection.
        $modal->form->title->fill($title);
        $modal->submit();
        $modal->waitUntilClosed();
        $this->configurePage->checkArrival();
        $this->waitUntilTextIsPresent($title);

        // Reload the page to get rid of the confirmation message.
        $this->configurePage->reloadPage();

        // Try to add again the same definition.
        $this->configurePage->contextualToolbar->buttonAdd->click();
        $modal = new PaneCollectionModal($this);
        $modal->waitUntilOpened();
        $modal->form->title->fill($title);
        $modal->submit();
        $this->waitUntilTextIsPresent("The pane collection with title $title already exists.");

        // Test that a definition starting with a number is accepted.
        $title = '5' . $title;
        $modal->form->title->fill($title);
        $modal->submit();
        $modal->waitUntilClosed();
        $this->configurePage->checkArrival();
        $this->waitUntilTextIsPresent($title);
    }
}
