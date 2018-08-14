<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\SimpleContact\ContentType\SimpleContact\Common\NodeArchiveTest.
 */

namespace Kanooh\Paddle\App\SimpleContact\ContentType\SimpleContact\Common;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Apps\SimpleContact;
use Kanooh\Paddle\Core\ContentType\Base\NodeArchiveTestBase;
use Kanooh\Paddle\Pages\Element\PanelsContentType\SimpleContactFormPanelsContentType;

/**
 * NodeArchiveTest class.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeArchiveTest extends NodeArchiveTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->appService->enableApp(new SimpleContact);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createSimpleContact($title);
    }

    /**
     * Tests that the simple contact form pane dropdown do not suggest archived pages.
     *
     * @group workflow
     * @group archive
     */
    public function testPaneAutocompletesExcludeArchivedPages()
    {
        $this->userSessionService->login('ChiefEditor');

        // Create a archived test node.
        $node = array();
        $node['title'] = $this->alphanumericTestDataProvider->getValidValue();
        $node['nid'] = $this->setupNode($node['title']);
        $this->contentCreationService->moderateNode($node['nid'], 'archived');

        // Test that the archived node doesn't appear in the simple contact form
        // pane's dropdown.
        $basic_page_nid = $this->contentCreationService->createBasicPage();
        $this->layoutPage->go($basic_page_nid);

        // Add a simple contact form pane to the basic page.
        $content_type = new SimpleContactFormPanelsContentType($this);
        $webdriver = $this;
        $callable = new SerializableClosure(
            function () use ($webdriver, $content_type, $node) {
                $options = $content_type->form->node->getOptions();
                $webdriver->assertNotContains($node['title'], $options);
            }
        );
        $region = $this->layoutPage->display->getRandomRegion();
        $region->addPane($content_type, $callable);
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
    }
}
