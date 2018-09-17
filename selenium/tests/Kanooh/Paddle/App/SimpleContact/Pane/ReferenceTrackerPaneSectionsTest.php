<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\SimpleContact\Pane\ReferenceTrackerPaneSectionsTest.
 */

namespace Kanooh\Paddle\App\SimpleContact\Pane;

use Kanooh\Paddle\Apps\SimpleContact;
use Kanooh\Paddle\Core\Pane\Base\ReferenceTrackerPaneSectionsTestBase;
use Kanooh\Paddle\Pages\Element\PanelsContentType\SimpleContactFormPanelsContentType;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ReferenceTrackerPaneSectionsTest extends ReferenceTrackerPaneSectionsTestBase
{

    /**
     * The nid of the simple contact node created for this test.
     *
     * @var int
     */
    protected $simpleContactNid;

    /**
     * {@inheritDoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->appService->enableApp(new SimpleContact);
    }

    /**
     * {@inheritDoc}
     */
    protected function additionalTestSetUp()
    {
        // We have to create the simple contact node before opening the modal,
        // as the simple contact selection is done through a select instead of
        // an autocomplete.
        $nid = $this->contentCreationService->createSimpleContact();

        // Add this node to the expected references.
        $this->additionalReferences['node'][] = $nid;

        // Save the nid for later.
        $this->simpleContactNid = $nid;
    }


    /**
     * {@inheritDoc}
     */
    protected function getPaneContentTypeInstance()
    {
        return new SimpleContactFormPanelsContentType($this);
    }

    /**
     * {@inheritDoc}
     */
    protected function configurePaneContentType($content_type, $referenced_ids)
    {
        /* @var SimpleContactFormPanelsContentType $content_type */
        $content_type->form->node->selectOptionByValue($this->simpleContactNid);
    }
}
