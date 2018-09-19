<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\SimpleContact\ContentType\SimpleContact\Common\NodeCreationLanguageTest.
 */

namespace Kanooh\Paddle\App\SimpleContact\ContentType\SimpleContact\Common;

use Kanooh\Paddle\Apps\SimpleContact;
use Kanooh\Paddle\App\Multilingual\ContentType\Base\NodeCreationLanguageTestBase;

/**
 * Class NodeDeleteButtonTest
 * @package Kanooh\Paddle\App\SimpleContact\ContentType\SimpleContact\Common
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeCreationLanguageTest extends NodeCreationLanguageTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setupPage()
    {
        parent::setUpPage();

        $this->appService->enableApp(new SimpleContact);
    }

    /**
     * {@inheritdoc}
     */
    public function getContentTypeName()
    {
        return 'simple_contact_page';
    }
}
