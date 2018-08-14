<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Cultuurnet\Pane\PaneLinkTest.
 */

namespace Kanooh\Paddle\App\Cultuurnet\Pane;

use Kanooh\Paddle\Apps\Cultuurnet;
use Kanooh\Paddle\Core\Pane\Base\PaneLinkTestBase;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleCultuurnet\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Element\PanelsContentType\UiTDatabankPanelsContentType;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PaneLinkTest extends PaneLinkTestBase
{
    /**
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Enable the Cultuurnet module and fill in the configuration form of the paddlet.
        $this->appService->enableApp(new Cultuurnet);
        $this->configurePage = new ConfigurePage($this);

        $this->configurePage->go();
        $this->configurePage->checkArrival();
        $this->configurePage->form->applicationKey->fill('03417fc4-300a-4e5f-a45a-21c443d53079');
        $this->configurePage->form->sharedSecret->fill('200b82e247eaee802efebd0b6d8c88e9');
        $this->configurePage->contextualToolbar->buttonSave->click();
    }

    /**
     * {@inheritDoc}
     */
    protected function getPaneContentTypeInstance()
    {
        return new UiTDatabankPanelsContentType($this);
    }

    /**
     * {@inheritDoc}
     */
    protected function configurePaneContentType($content_type, $referenced_ids)
    {
        // No additional configuration needed.
    }
}
