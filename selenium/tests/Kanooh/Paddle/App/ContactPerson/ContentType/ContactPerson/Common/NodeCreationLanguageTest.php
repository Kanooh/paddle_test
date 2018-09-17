<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\ContactPerson\ContentType\ContactPerson\Common\NodeCreationLanguageTest.
 */

namespace Kanooh\Paddle\App\ContactPerson\ContentType\ContactPerson\Common;

use Kanooh\Paddle\Apps\ContactPerson;
use Kanooh\Paddle\App\Multilingual\ContentType\Base\NodeCreationLanguageTestBase;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\CreateContactPersonModal;

/**
 * Class NodeDeleteButtonTest
 * @package Kanooh\Paddle\App\ContactPerson\ContentType\ContactPerson\Common
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeCreationLanguageTest extends NodeCreationLanguageTestBase
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
    public function getModalClassName()
    {
        return '\Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\CreateContactPersonModal';
    }

    /**
     * {@inheritdoc}
     */
    public function getContentTypeName()
    {
        return 'contact_person';
    }

    /**
     * {@inheritdoc}
     */
    public function fillInAddModalForm($modal)
    {
        /** @var CreateContactPersonModal $modal */
        $modal->firstName->fill($this->alphanumericTestDataProvider->getValidValue());
        $modal->lastName->fill($this->alphanumericTestDataProvider->getValidValue());
    }
}
