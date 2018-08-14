<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Pane\ImagePane\ReferenceTrackerPaneEntityReferenceTest.
 */

namespace Kanooh\Paddle\Core\Pane\ImagePane;

use Kanooh\Paddle\Core\Pane\Base\ReferenceTrackerPaneEntityReferenceTestBase;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\PanelsContentType\ImagePanelsContentType;

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
    }

    /**
     * {@inheritdoc}
     */
    protected function setUpReferencedEntities()
    {
        // Create an image atom and a basic page.
        $atom = $this->assetCreationService->createImage();
        $nid = $this->contentCreationService->createBasicPage();

        $references = array(
            'node' => array($nid),
            'scald_atom' => array($atom['id']),
        );

        return $references;
    }

    /**
     * {@inheritDoc}
     */
    protected function getPaneContentTypeInstance()
    {
        return new ImagePanelsContentType($this);
    }

    /**
     * {@inheritDoc}
     */
    protected function configurePaneContentType($content_type, $references)
    {
        // Select the created atom.
        /* @var ImagePanelsContentType $content_type */
        $content_type->getForm()->image->selectButton->click();
        $this->scaldService->insertAtom($references['scald_atom'][0]);

        // Add the internal link.
        $content_type->getForm()->internal->select();
        $content_type->getForm()->internalUrl->fill('node/' . $references['node'][0]);

        // Pick the suggestion.
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $autocomplete->pickSuggestionByPosition();
    }
}
