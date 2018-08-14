<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\OrganizationalUnit\ContentType\OrganizationalUnit\Common\NodeDetailTest.
 */

namespace Kanooh\Paddle\App\OrganizationalUnit\ContentType\OrganizationalUnit\Common;

use Kanooh\Paddle\Core\ContentType\Base\NodeDetailTestBase;
use Kanooh\Paddle\Utilities\ContentCreationService;

/**
 * Class NodeDetailTest
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeDetailTest extends NodeDetailTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createOrganizationalUnit($title);
    }
}
