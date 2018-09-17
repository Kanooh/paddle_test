<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\ContactPerson\ContentType\ContactPerson\Common\SocialMediaTest.
 */

namespace Kanooh\Paddle\App\ContactPerson\ContentType\ContactPerson\Common;

use Kanooh\Paddle\Apps\ContactPerson;
use Kanooh\Paddle\App\SocialMedia\ContentType\Base\SocialMediaTestBase;

/**
 * SocialMediaTest class.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class SocialMediaTest extends SocialMediaTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->appService->enableApp(new ContactPerson);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($first_name = null, $last_name = null)
    {
        return $this->contentCreationService->createContactPerson($first_name, $last_name);
    }
}
