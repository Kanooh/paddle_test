<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Pane\Base\ReferenceTrackerPaneEntityReferenceTestBase.
 */

namespace Kanooh\Paddle\Core\Pane\Base;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Element\Pane\Pane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\PanelsContentType;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\ScaldService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

abstract class ReferenceTrackerPaneEntityReferenceTestBase extends WebDriverTestCase
{
    /**
     * @var AdministrativeNodeViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var AssetCreationService
     */
    protected $assetCreationService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var LayoutPage
     */
    protected $layoutPage;

    /**
     * @var ScaldService
     */
    protected $scaldService;

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

        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->assetCreationService = new AssetCreationService($this);
        $this->layoutPage = new LayoutPage($this);
        $this->scaldService = new ScaldService($this);
        $this->userSessionService = new UserSessionService($this);
        $this->appService = new AppService($this, $this->userSessionService);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Go to the login page and log in as chief editor.
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * Creates an instance of the pane content type needed for the test.
     *
     * @return PanelsContentType
     *   A content type instance for the pane we need to test.
     */
    abstract protected function getPaneContentTypeInstance();

    /**
     * Callback to configure the pane content type.
     *
     * @param PanelsContentType $content_type
     *   The pane content type that has been added.
     * @param array $references
     *   The id of the entity that has to be referenced.
     */
    abstract protected function configurePaneContentType($content_type, $references);

    /**
     * Creates entities that will be referenced in the test.
     *
     * By default creates a basic page. Can be overridden to create other
     * entities.
     *
     * @return array
     *   An array with the entity type and ids.
     */
    protected function setUpReferencedEntities()
    {
        $nid = $this->contentCreationService->createBasicPage();

        return array('node' => array($nid));
    }

    /**
     * Tests tracking of entity references in panes.
     *
     * @group linkChecker
     */
    public function testPaneEntityReference()
    {
        // Create the entity that is going to be referenced.
        $referenced_entities = $this->setUpReferencedEntities();

        // Create a basic page to hold a pane.
        $referencing_nid = $this->contentCreationService->createBasicPage();

        // Add a pane to the page.
        $pane = $this->addPane($referencing_nid, $referenced_entities);

        // Verify that the node is referencing the entity.
        $this->assertEquals(
            $referenced_entities,
            reference_tracker_get_outbound_references('node', $referencing_nid)
        );

        // Now delete the pane.
        $this->deletePane($referencing_nid, $pane->getUuid(), $pane->getXPathSelectorByUuid());

        // Verify that the reference has been removed.
        $this->assertEquals(
            array(),
            reference_tracker_get_outbound_references('node', $referencing_nid)
        );
    }

    /**
     * Add a pane to a node, runs the configuration of the pane and saves the page.
     *
     * @param int $referencing_nid
     *   The nid of the page where to add the pane.
     * @param array $references
     *   An array of entities that needs to be referenced.
     * @return Pane
     *   The created pane.
     */
    protected function addPane($referencing_nid, $references)
    {
        // Add a custom content pane in a region.
        $this->layoutPage->go($referencing_nid);
        $region = $this->layoutPage->display->getRandomRegion();

        // Create an instance of the pane.
        $content_type = $this->getPaneContentTypeInstance();

        $test_case = $this;
        $callable = new SerializableClosure(
            function () use ($test_case, $content_type, $references) {
                $test_case->configurePaneContentType($content_type, $references);
            }
        );
        $pane = $region->addPane($content_type, $callable);

        // Save the layout page.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        return $pane;
    }

    /**
     * Deletes a pane from a node.
     *
     * @param int $nid
     *   The nid of the page where to delete the pane.
     * @param string $pane_uuid
     *   The UUID of the pane to delete.
     * @param string $xpath_selector
     *   The XPath of the pane.
     */
    protected function deletePane($nid, $pane_uuid, $xpath_selector)
    {
        $this->layoutPage->go($nid);

        $pane = new Pane($this, $pane_uuid, $xpath_selector);
        $pane->delete();

        // Save the layout page.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
    }
}
