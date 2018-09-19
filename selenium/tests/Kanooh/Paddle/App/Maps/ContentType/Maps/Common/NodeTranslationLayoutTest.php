<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Maps\ContentType\Maps\Common\NodeTranslationLayoutTestBase.
 */

namespace Kanooh\Paddle\App\Maps\ContentType\Maps\Common;

use Kanooh\Paddle\App\Multilingual\ContentType\Base\NodeTranslationLayoutTestBase;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\CreateNodeModal;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\MapsLayoutPage;

/**
 * {@inheritdoc}
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeTranslationLayoutTest extends NodeTranslationLayoutTestBase
{
    /**
     * @inheritDoc
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->layoutPage = new MapsLayoutPage($this);
    }

    /**
     * {@inheritDoc}
     */
    public function setUpNode()
    {
        return $this->contentCreationService->createMapsPage();
    }

    /**
     * {@inheritDoc}
     */
    public function fillTranslationModal($title = null)
    {
        $title = !empty($title) ? $title : $this->alphanumericTestDataProvider->getValidValue();

        $modal = new CreateNodeModal($this);
        $modal->waitUntilOpened();
        $modal->title->fill($title);
        $modal->submit();
        $modal->waitUntilClosed();
    }
}
