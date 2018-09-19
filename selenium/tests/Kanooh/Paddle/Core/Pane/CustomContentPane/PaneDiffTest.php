<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Pane\CustomContentPane\PaneDiffTest.
 */

namespace Kanooh\Paddle\Core\Pane\CustomContentPane;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Core\Pane\Base\PaneDiffTestBase;
use Kanooh\Paddle\Pages\Element\Pane\CustomContentPane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CustomContentPanelsContentType;
use Kanooh\Paddle\Pages\Element\Pane\Pane;
use Kanooh\Paddle\Pages\Element\Region\Region;

/**
 * Class PaneDiffTest.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PaneDiffTest extends PaneDiffTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->config['old'][] = $this->alphanumericTestDataProvider->getValidValue();
        $this->config['new'][] = $this->alphanumericTestDataProvider->getValidValue();
    }

    /**
     * {@inheritdoc}
     */
    public function addPaneToRegion(Region $region)
    {
        $content_type = new CustomContentPanelsContentType($this);
        $config = $this->config;

        $callable = new SerializableClosure(
            function () use ($content_type, $config) {
                $content_type->getForm()->body->waitUntilReady();
                $content_type->getForm()->body->setBodyText($config['old'][0]);
            }
        );

        return $region->addPane($content_type, $callable);
    }

    /**
     * {@inheritdoc}
     */
    public function editPane(Pane $pane)
    {
        $custom_content_pane = new CustomContentPane($this, $pane->getUuid(), $pane->getXPathSelector());
        $config = $this->config;

        $callable = new SerializableClosure(
            function () use ($custom_content_pane, $config) {
                $custom_content_pane->toolbar->buttonEdit->click();
                $custom_content_pane->editPaneModal->waitUntilOpened();
                $form = $custom_content_pane->contentType->getForm();

                $form->body->waitUntilReady();
                $form->body->setBodyText($config['new'][0]);

                $custom_content_pane->editPaneModal->submit();
                $custom_content_pane->editPaneModal->waitUntilClosed();
            }
        );
        $custom_content_pane->executeAndWaitUntilReloaded($callable);
    }

    /**
     * {@inheritdoc}
     */
    public function removePane(Pane $pane)
    {
        $custom_content_pane = new CustomContentPane($this, $pane->getUuid(), $pane->getXPathSelector());

        $callable = new SerializableClosure(
            function () use ($custom_content_pane) {
                $custom_content_pane->delete();
            }
        );
        $custom_content_pane->executeAndWaitUntilReloaded($callable);
    }
}
