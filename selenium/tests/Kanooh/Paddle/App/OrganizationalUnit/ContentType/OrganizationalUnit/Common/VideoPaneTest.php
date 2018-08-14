<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\OrganizationalUnit\ContentType\OrganizationalUnit\Common\VideoPaneTest.
 */

namespace Kanooh\Paddle\App\OrganizationalUnit\ContentType\OrganizationalUnit\Common;

use Kanooh\Paddle\Core\ContentType\Base\VideoPaneBaseTest;

/**
 * Class VideoPaneTest
 * @package Kanooh\Paddle\App\OrganizationalUnit\ContentType\OrganizationalUnit\Common
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class VideoPaneTest extends VideoPaneBaseTest
{

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createOrganizationalUnit($title);
    }
}
