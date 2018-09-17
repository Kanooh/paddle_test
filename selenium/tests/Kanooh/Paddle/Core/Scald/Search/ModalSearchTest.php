<?php
/**
 * @file
 */

namespace Kanooh\Paddle\Core\Scald\Search;

use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPage;
use Kanooh\Paddle\Pages\Element\Layout\Paddle2Col3to9Layout;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\PanelsContentType\ImagePanelsContentType;
use Kanooh\Paddle\Pages\Element\Scald\LibraryModal;

/**
 * Test searches on atom entities in the modal.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ModalSearchTest extends SearchTestBase
{
    /**
     * @var AddPage
     */
    protected $addPage;

    /**
     * @var PanelsContentPage
     */
    protected $editLayoutPage;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();
        $this->addPage = new AddPage($this);
        $this->editLayoutPage = new PanelsContentPage($this);
    }
    /**
     * {@inheritdoc}
     */
    protected function executeStepsTowardsLibrary()
    {
        $this->addPage->go();
        $layout = new Paddle2Col3to9Layout();
        $nid = $this->addPage->createLandingPage($layout->id());

        $this->editLayoutPage->go($nid);

        $this->editLayoutPage->display->getRandomRegion();

        $this->editLayoutPage->display->region(Paddle2Col3to9Layout::REGIONA)->buttonAddPane->click();

        $content_type = new ImagePanelsContentType($this);

        // Select the pane type in the modal dialog.
        $modal = new AddPaneModal($this);
        $modal->waitUntilOpened();
        $this->waitUntilTextIsPresent('Add new pane');

        $modal->selectContentType($content_type);

        $form = $content_type->getForm();
        $form->image->selectButton->click();

        $modal = new LibraryModal($this);
        $modal->waitUntilOpened();
    }

    /**
     * {@inheritDoc}
     */
    protected function getLibrary()
    {
        $modal = new LibraryModal($this);
        $modal->waitUntilOpened();

        return $modal->library;
    }
}
