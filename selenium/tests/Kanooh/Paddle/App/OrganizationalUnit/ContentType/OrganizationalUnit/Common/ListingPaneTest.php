<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\ContentType\OrganizationalUnit\Common\ListingPaneTest.
 */

namespace Kanooh\Paddle\App\OrganizationalUnit\ContentType\OrganizationalUnit\Common;

use Kanooh\Paddle\Core\ContentType\Base\ListingPaneTestBase;

/**
 * Class ListingPaneTest
 * @package Kanooh\Paddle\App\OrganizationalUnit\ContentType\OrganizationalUnit\Common
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ListingPaneTest extends ListingPaneTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createOrganizationalUnit($title);
    }
}
