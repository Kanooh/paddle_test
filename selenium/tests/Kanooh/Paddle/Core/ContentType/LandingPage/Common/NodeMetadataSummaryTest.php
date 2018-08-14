<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\LandingPage\Common\NodeMetadataSummaryTest.
 */

namespace Kanooh\Paddle\Core\ContentType\LandingPage\Common;

use Kanooh\Paddle\Core\ContentType\Base\NodeMetadataSummaryTestBase;

/**
 * Class NodeMetadataSummaryTest
 * @package Kanooh\Paddle\Core\ContentType\LandingPage\Common
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeMetadataSummaryTest extends NodeMetadataSummaryTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createLandingPage(null, $title);
    }

    /**
     * {@inheritdoc}
     */
    public function checkSummaryOnLayoutPage($node)
    {
        $this->landingPageLayoutPage->checkArrival();
        $this->landingPageLayoutPage->nodeSummary->showAllMetadata();
        $this->assertNodeSummary($this->landingPageLayoutPage, $node);
        $this->landingPageLayoutPage->contextualToolbar->buttonBack->click();
        $this->acceptAlert();
    }
}
