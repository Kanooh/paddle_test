<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\Base\SchedulingTestBase.
 */

namespace Kanooh\Paddle\Core\ContentType\Base;

use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditPageRandomFiller;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage as NodeEditPage;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Base class for scheduling tests.
 */
abstract class SchedulingTestBase extends WebDriverTestCase
{
    /**
     * The 'Add' page of the Paddle Content Manager module.
     *
     * @var AddPage
     */
    protected $addContentPage;

    /**
     * The administrative node view of a landing page.
     *
     * @var AdministrativeNodeViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * The node edit page.
     *
     * @var NodeEditPage
     */
    protected $nodeEditPage;

    /**
     * The user session manager.
     *
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * Creates a node of the content type that is being tested.
     *
     * @param string $title
     *   Optional title for the node. If omitted a random title will be used.
     *
     * @return int
     *   The node ID of the node that was created.
     */
    abstract public function setupNode($title = null);

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Instantiate some objects for later use.
        $this->addContentPage = new AddPage($this);
        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->nodeEditPage = new NodeEditPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        $this->contentCreationService->cleanUp($this);
        parent::tearDown();
    }

    /**
     * Tests scheduling.
     *
     * @group editing
     * @group scheduling
     * @group schedulingTestBase
     * @group workflow
     */
    public function testScheduling()
    {
        // Go to the login page and log in as chief editor.
        $this->userSessionService->login('ChiefEditor');

        // Create a new page, with both scheduling options set.
        $nid = $this->setupNode();

        $this->nodeEditPage->go($nid);
        $filler = new EditPageRandomFiller($this->nodeEditPage);
        $filler->setTargetFields($filler::PUBLISH_ON_FIELD | $filler::UNPUBLISH_ON_FIELD);
        $filler->randomize()->fill();

        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->waitUntilTextIsPresent('has been updated.');

        // Check that the node is scheduled for publication and unpublication at
        // the right date and time.
        $this->administrativeNodeViewPage->nodeSummary->showAllMetadata();
        $publish = $this->administrativeNodeViewPage->nodeSummary->getMetaData('publication', 'publish');
        $publish_date = $filler->publishOnDate;
        // Strip off the seconds from the time representation, this is not
        // displayed in the node metadata.
        $publish_time = substr($filler->publishOnTime, 0, 5);
        $this->assertEquals($publish_date . ' - ' . $publish_time, $publish['value']);

        $unpublish = $this->administrativeNodeViewPage->nodeSummary->getMetaData('publication', 'unpublish');
        $unpublish_date = $filler->unpublishOnDate;
        $unpublish_time = substr($filler->unpublishOnTime, 0, 5);
        $this->assertEquals($unpublish_date . ' - ' . $unpublish_time, $unpublish['value']);

        // Edit the node, remove the unpublication date and check that the node
        // is no longer scheduled for unpublication.
        $this->nodeEditPage->go($nid);

        $filler = new EditPageRandomFiller($this->nodeEditPage);
        $filler->setTargetFields($filler::UNPUBLISH_ON_FIELD);
        $filler->unpublishOnDate = '';
        $filler->unpublishOnTime = '';
        $filler->fill();

        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->waitUntilTextIsPresent('has been updated.');

        $this->administrativeNodeViewPage->nodeSummary->showAllMetadata();
        $publish = $this->administrativeNodeViewPage->nodeSummary->getMetaData('publication', 'publish');
        $this->assertEquals($publish_date . ' - ' . $publish_time, $publish['value']);
        $unpublish = $this->administrativeNodeViewPage->nodeSummary->getMetaData('publication', 'unpublish');
        $this->assertEquals('-', $unpublish['value']);

        // Edit the node, remove the publication date and check that the node
        // is no longer scheduled.
        $this->nodeEditPage->go($nid);

        $filler->setTargetFields($filler::PUBLISH_ON_FIELD);
        $filler->publishOnDate = '';
        $filler->publishOnTime = '';
        $filler->fill();

        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->waitUntilTextIsPresent('has been updated.');

        $this->administrativeNodeViewPage->nodeSummary->showAllMetadata();
        $publish = $this->administrativeNodeViewPage->nodeSummary->getMetaData('publication', 'publish');
        $unpublish = $this->administrativeNodeViewPage->nodeSummary->getMetaData('publication', 'unpublish');
        $this->assertEquals('-', $publish['value']);
        $this->assertEquals('-', $unpublish['value']);

        // Schedule the node and verify the correct message is shown.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageProperties->click();
        $this->nodeEditPage->checkArrival();
        $filler->setTargetFields($filler::PUBLISH_ON_FIELD | $filler::UNPUBLISH_ON_FIELD);
        $filler->randomize()->fill();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->waitUntilTextIsPresent('has been updated.');
        $this->administrativeNodeViewPage->contextualToolbar->buttonSchedule->click();
        $this->waitUntilTextIsPresent('This post is unpublished and will be published');
    }

    /**
     * Test editing a scheduled revision.
     *
     * @group editing
     * @group scheduling
     * @group schedulingTestBase
     * @group workflow
     */
    public function testEditScheduledRevision()
    {
        // Login as editor.
        $this->userSessionService->login('Editor');

        // Create a node.
        $nid = $this->setupNode();
        $this->administrativeNodeViewPage->go($nid);
        $this->administrativeNodeViewPage->nodeSummary->showAllMetadata();
        $status = $this->administrativeNodeViewPage->nodeSummary->getMetadata('workflow', 'status');
        $this->assertEquals('Concept', $status['value']);

        // Schedule it for publication.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageProperties->click();
        $this->nodeEditPage->checkArrival();
        $filler = new EditPageRandomFiller($this->nodeEditPage);
        $filler->setTargetFields($filler::PUBLISH_ON_FIELD);
        $filler->randomize()->fill();
        $original_publication_date = $filler->publishOnDate;
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Login as chief editor.
        $this->userSessionService->switchUser('ChiefEditor');

        // Publish the page the editor just created.
        $this->administrativeNodeViewPage->go($nid);
        $this->administrativeNodeViewPage->contextualToolbar->buttonSchedule->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->nodeSummary->showAllMetadata();
        $status = $this->administrativeNodeViewPage->nodeSummary->getMetadata('workflow', 'status');
        $this->assertEquals('Scheduled', $status['value']);

        // Edit the page as chief editor.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageProperties->click();
        $this->nodeEditPage->checkArrival();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();

        // The status should go back to Concept no matter the user that is
        // logged in.
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->nodeSummary->showAllMetadata();
        $status = $this->administrativeNodeViewPage->nodeSummary->getMetadata('workflow', 'status');
        $this->assertEquals('Concept', $status['value']);
        $this->assertTrue($this->administrativeNodeViewPage->contextualToolbar->buttonSchedule->displayed());
    }
}
