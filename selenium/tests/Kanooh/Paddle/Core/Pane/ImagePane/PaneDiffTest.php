<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Pane\ImagePane\PaneDiffTest.
 */

namespace Kanooh\Paddle\Core\Pane\ImagePane;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Core\Pane\Base\PaneDiffTestBase;
use Kanooh\Paddle\Pages\Element\Pane\ImagePane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\ImagePanelsContentType;
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

        // Create 2 image atoms with a crop style.
        $data = array(
          'title' => $atom_1,
          'path' => dirname(__FILE__) . '/../../../assets/budapest.jpg',
        );
        $this->data['old'] = $this->assetCreationService->createImage($data);

        $data = array(
          'title' => $atom_2,
          'path' => dirname(__FILE__) . '/../../../assets/sample_image.jpg',
        );
        $this->data['new'] = $this->assetCreationService->createImage($data);

        $this->config['old'][] = $atom_1;
        $this->config['new'][] = $atom_2;
    }

    /**
     * {@inheritdoc}
     */
    public function addPaneToRegion(Region $region)
    {
        $content_type = new ImagePanelsContentType($this);
        $data = $this->data;
        $scald_service = $this->scaldService;

        $callable = new SerializableClosure(
            function () use ($content_type, $data, $scald_service) {
                // Select an image.
                $content_type->getForm()->image->selectButton->click();
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
        $image_pane = new ImagePane($this, $pane->getUuid(), $pane->getXPathSelector());
        $data = $this->data;
        $scald_service = $this->scaldService;

        $callable = new SerializableClosure(
            function () use ($image_pane, $data, $scald_service) {
                $image_pane->toolbar->buttonEdit->click();
                $image_pane->editPaneModal->waitUntilOpened();
                $form = $image_pane->contentType->getForm();

                $form->image->clear();
                $form->image->selectButton->click();
                $scald_service->insertAtom($data['new']['id']);

                $image_pane->editPaneModal->submit();
                $image_pane->editPaneModal->waitUntilClosed();
            }
        );
        $image_pane->executeAndWaitUntilReloaded($callable);
    }

    /**
     * {@inheritdoc}
     */
    public function removePane(Pane $pane)
    {
        $image_pane = new ImagePane($this, $pane->getUuid(), $pane->getXPathSelector());

        $callable = new SerializableClosure(
            function () use ($image_pane) {
                $image_pane->delete();
            }
        );
        $image_pane->executeAndWaitUntilReloaded($callable);
    }
}
