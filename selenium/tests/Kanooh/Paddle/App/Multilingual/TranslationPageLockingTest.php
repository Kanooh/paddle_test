<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Multilingual\TranslationPageLockingTest
 */

namespace Kanooh\Paddle\App\Multilingual;

use Kanooh\Paddle\Apps\Multilingual;
use Kanooh\Paddle\Core\ContentType\Base\NodeLockingTestBase;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\CreateNodeModal;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Pages\Node\TranslatePage\TranslatePage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\MultilingualService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;

/**
 * Checks the locking of the translation node page.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class TranslationPageLockingTest extends NodeLockingTestBase
{

    /**
     * Test data provider for alphanumeric data.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * The node edit page.
     *
     * @var EditPage
     */
    protected $editPage;

    /**
     * @var TranslatePage
     */
    protected $translatePage;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Instantiate some classes to use in the test.
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->editPage = new EditPage($this);
        $this->translatePage = new TranslatePage($this);

        // Add the node translation page to the pages to be checked.
        $this->testPages['Translations'] = new TranslatePage($this);

        // Enable the app if it is not enabled yet.
        $app_service = new AppService($this, $this->userSessionService);
        $app_service->enableApp(new Multilingual);

        // Make sure the tests have the expected multilingual configuration.
        $this->userSessionService->login('ChiefEditor');
        MultilingualService::setPaddleTestDefaults($this);
        $this->userSessionService->logout();
    }

    /**
     * @inheritdoc
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createBasicPage($title);
    }

    /**
     * Tests that the translation source node lock is released after creating a new translation.
     *
     * @group contentType
     * @group editing
     * @group nodeLockingTestBase
     * @group contentLocking
     */
    public function testTranslationSourceLockRelease()
    {
        $this->userSessionService->login('ChiefEditor');

        // Enable two languages to translate the node to.
        MultilingualService::enableLanguages($this, array('Bulgarian', 'Italian'));

        // Create a node to translate.
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $nid = $this->setupNode($title);

        // Go to the translation page to trigger the locking.
        $this->translatePage->go($nid);

        // Add a new translation.
        $this->translatePage->translationTable->getRowByLanguage('it')->translationLink->click();
        $italian_title = $this->alphanumericTestDataProvider->getValidValue();
        $italian_nid = $this->fillTranslationModal($italian_title);

        // Verify that the original node is not locked anymore.
        $this->assertNodeNotLocked($title);

        // Go to the edit page of the new translation node.
        // By adding a translation in this new node, we can test that the
        // lock release is working on the correct source node.
        // We go to the edit page through the admin page toolbar so proper locking
        // can be triggered.
        $this->administrativeNodeViewPage->go($italian_nid);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageProperties->click();
        $this->editPage->checkArrival();

        // Add a translation for this node.
        $this->editPage->translationTable->getRowByLanguage('bg')->translationLink->click();
        $this->fillTranslationModal($this->alphanumericTestDataProvider->getValidValue());

        // Assert that the Italian node is not locked anymore.
        $this->assertNodeNotLocked($italian_title);
        // Check that the original is still unlocked.
        $this->assertNodeNotLocked($title);
    }

    /**
     * Fills the create node translation modal.
     *
     * @param string $title
     *   The title to use for the node.
     *
     * @return int
     *   The nid of the newly created node.
     */
    protected function fillTranslationModal($title)
    {
        $modal = new CreateNodeModal($this);
        $modal->waitUntilOpened();
        $modal->title->fill($title);
        $modal->submit();
        $modal->waitUntilClosed();
        $this->administrativeNodeViewPage->checkArrival();

        return $this->administrativeNodeViewPage->getNodeIDFromUrl();
    }
}
