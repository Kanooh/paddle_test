<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\SimpleContact\Pane\ReferenceTrackerPaneEntityReferenceTest.
 */

namespace Kanooh\Paddle\App\SimpleContact\Pane;

use Kanooh\Paddle\Apps\SimpleContact;
use Kanooh\Paddle\Core\Pane\Base\ReferenceTrackerPaneEntityReferenceTestBase;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\PanelsContentType\SimpleContactFormPanelsContentType;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ReferenceTrackerPaneEntityReferenceTest extends ReferenceTrackerPaneEntityReferenceTestBase
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
     * {@inheritDoc}
     */
    protected function setUpReferencedEntities()
    {
        $nid = $this->contentCreationService->createSimpleContact();

        return array('node' => array($nid));
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
    protected function configurePaneContentType($content_type, $references)
    {
        /* @var SimpleContactFormPanelsContentType $content_type */
        $content_type->form->node->selectOptionByValue($references['node'][0]);
    }
}
