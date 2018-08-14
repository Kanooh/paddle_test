<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Embed\Pane\PaneSectionsDiffTest.
 */

namespace Kanooh\Paddle\App\Embed\Pane;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Apps\Embed;
use Kanooh\Paddle\Core\Pane\Base\PaneSectionsDiffTestBase;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleEmbed\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Element\Embed\WidgetSettingsModal;
use Kanooh\Paddle\Pages\Element\PanelsContentType\EmbedWidgetPanelsContentType;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PaneSectionsDiffTest extends PaneSectionsDiffTestBase
{

    /**
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * @var int
     */
    protected $wid;

    /**
     * {@inheritDoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->configurePage = new ConfigurePage($this);

        $this->appService->enableApp(new Embed);
    }

    /**
     * {@inheritDoc}
     */
    protected function additionalTestSetUp()
    {
        $test_case = $this;
        $callable = new SerializableClosure(
            function () use ($test_case) {
                // Go to the configuration page and create a widget.
                $test_case->configurePage->go();
                $test_case->configurePage->contextualToolbar->buttonCreate->click();

                $modal = new WidgetSettingsModal($test_case);
                $modal->waitUntilOpened();

                $modal->form->title->fill($test_case->alphanumericTestDataProvider->getValidValue());
                $modal->form->code->fill($test_case->alphanumericTestDataProvider->getValidValue());
                $modal->form->saveButton->click();
                $test_case->waitUntilTextIsPresent('This is what the widget will look like when added to a page.');
                $modal->close();
                $modal->waitUntilClosed();

                // Get the created widget from the table.
                $widgets = $test_case->configurePage->widgetTable->rows;
                $widget = end($widgets);
                $test_case->wid = $widget->wid;
            }
        );
        $this->userSessionService->runAsUser('SiteManager', $callable);
    }

    /**
     * {@inheritDoc}
     */
    protected function getPaneContentTypeInstance()
    {
        return new EmbedWidgetPanelsContentType($this);
    }

    /**
     * {@inheritDoc}
     */
    protected function configurePaneContentType($content_type)
    {
        /* @var EmbedWidgetPanelsContentType $content_type */
        $content_type->getForm()->widgets[$this->wid]->select();
    }
}
