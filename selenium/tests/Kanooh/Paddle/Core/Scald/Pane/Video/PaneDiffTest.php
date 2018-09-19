<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Scald\Pane\Video\PaneDiffTest.
 */

namespace Kanooh\Paddle\Core\Scald\Pane\Video;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Core\Pane\Base\PaneDiffTestBase;
use Kanooh\Paddle\Pages\Element\Pane\VideoPane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\VideoPanelsContentType;
use Kanooh\Paddle\Pages\Element\Pane\Pane;
use Kanooh\Paddle\Pages\Element\Region\Region;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\ScaldService;

/**
 * Class PaneDiffTest.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PaneDiffTest extends PaneDiffTestBase
{
    /**
     * @var AssetCreationService
     */
    protected $assetCreationService;

    /**
     * Array containing the atom id's for later use.
     *
     * @var array
     */
    protected $data;

    /**
     * @var ScaldService
     */
    protected $scaldService;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();
        $this->assetCreationService = new AssetCreationService($this);
        $this->scaldService = new ScaldService($this);

        $atom_1 = $this->alphanumericTestDataProvider->getValidValue();
        $atom_2 = $this->alphanumericTestDataProvider->getValidValue();

        // Create 2 video atoms with a crop style.
        $this->data['old'] = $this->assetCreationService->createVideo(array('title' => $atom_1));
        $this->data['new'] = $this->assetCreationService->createYoutubeVideo(array('title' => $atom_2));

        $this->config['old'][] = $atom_1;
        $this->config['new'][] = $atom_2;
    }

    /**
     * {@inheritdoc}
     */
    public function addPaneToRegion(Region $region)
    {
        $content_type = new VideoPanelsContentType($this);
        $data = $this->data;
        $scald_service = $this->scaldService;

        $callable = new SerializableClosure(
            function () use ($content_type, $data, $scald_service) {
                // Select a video.
                $content_type->getForm()->video->selectButton->click();
                $scald_service->insertAtom($data['old']['id']);
            }
        );

        return $region->addPane($content_type, $callable);
    }

    /**
     * {@inheritdoc}
     */
    public function editPane(Pane $pane)
    {
        $video_pane = new VideoPane($this, $pane->getUuid(), $pane->getXPathSelector());
        $data = $this->data;
        $scald_service = $this->scaldService;

        $callable = new SerializableClosure(
            function () use ($video_pane, $data, $scald_service) {
                $video_pane->toolbar->buttonEdit->click();
                $video_pane->editPaneModal->waitUntilOpened();
                $form = $video_pane->contentType->getForm();

                $form->video->clear();
                $form->video->selectButton->click();
                $scald_service->insertAtom($data['new']['id']);

                $video_pane->editPaneModal->submit();
                $video_pane->editPaneModal->waitUntilClosed();
            }
        );
        $video_pane->executeAndWaitUntilReloaded($callable);
    }

    /**
     * {@inheritdoc}
     */
    public function removePane(Pane $pane)
    {
        $video_pane = new VideoPane($this, $pane->getUuid(), $pane->getXPathSelector());

        $callable = new SerializableClosure(
            function () use ($video_pane) {
                $video_pane->delete();
            }
        );
        $video_pane->executeAndWaitUntilReloaded($callable);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        // Remove all atoms created during the test. Because we used the
        // AssetCreationService to create our atoms, the class knows which ones
        // need to be deleted.
        AssetCreationService::cleanUp($this);
        parent::tearDown();
    }
}
