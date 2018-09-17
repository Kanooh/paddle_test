<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\News\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\News;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\News;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Utilities\DrupalApi\DrupalVariableApi;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\News
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class EnableDisableTest extends EnableDisableTestBase
{
    /**
     * {@inheritdoc}
     */
    public function getApp()
    {
        return new News;
    }

    /**
     * Test that after the paddlet has been installed a Overview page has been
     * created and its nid is saved in the appropriate variable.
     */
    public function testNewsOverviewPage()
    {
        // Install the paddlet.
        $this->appService->enableApp(new News);

        // Check that the variable is set.
        $service = new DrupalVariableApi($this);
        $overview_page_nid = $service->get('paddle_news_overview_page_nid');
        $this->assertNotNull($overview_page_nid);

        // Check if the page is there.
        $administrative_node_view_page = new ViewPage($this);
        $administrative_node_view_page->go($overview_page_nid);
    }
}
