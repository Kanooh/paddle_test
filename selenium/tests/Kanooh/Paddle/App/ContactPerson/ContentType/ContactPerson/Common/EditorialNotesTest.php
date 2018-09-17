<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\ContactPerson\ContentType\ContactPerson\Common\EditorialNotesTest.
 */

namespace Kanooh\Paddle\App\ContactPerson\ContentType\ContactPerson\Common;

use Kanooh\Paddle\Apps\ContactPerson;
use Kanooh\Paddle\Core\ContentType\Base\EditorialNotesTestBase;

/**
 * EditorialNotesTest class.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class EditorialNotesTest extends EditorialNotesTestBase
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
