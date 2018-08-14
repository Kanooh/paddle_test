<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\News\ContentType\News\Common\NodeRevisionsTest.
 */

namespace Kanooh\Paddle\App\News\ContentType\News\Common;

use Kanooh\Paddle\Apps\News;
use Kanooh\Paddle\Core\ContentType\Base\NodeRevisionsTestBase;
use Kanooh\Paddle\Pages\Node\EditPage\NewsPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\AssetCreationService;

/**
 * Class NodeRevisionsTest
 * @package Kanooh\Paddle\App\News\ContentType\News\Common
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeRevisionsTest extends NodeRevisionsTestBase
{

    /**
     * @var AssetCreationService
     */
    protected $assetCreationService;

    /**
     * @var NewsPage
     */
    protected $editNewsPage;
    /**
     * {@inheritdoc}
     */

    public function setupPage()
    {
        parent::setUpPage();

        $this->assetCreationService = new AssetCreationService($this);
        $this->editNewsPage = new NewsPage($this);

        $service = new AppService($this, $this->userSessionService);
        $service->enableApp(new News);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createNewsItemViaUI($title);
    }

    /**
     * Tests the news specific fields.
     *
     * @group revisions
     */
    public function testNewsRevision()
    {
        // Create an image.
        $atom = $this->assetCreationService->createImage();

        // Create a node, fill out the edit page and save.
        $nid = $this->contentCreationService->createNewsItem();
        $this->editNewsPage->go($nid);
        $this->editNewsPage->newsForm->leadImage->selectAtom($atom['id']);
        $this->editNewsPage->contextualToolbar->buttonSave->click();

        // Get the revisions for this node.
        $revisions = node_revision_list(node_load($nid));
        $revisions = array_slice($revisions, 0, 2, true);
        $vids = array_keys($revisions);

        // Go to the diff page.
        $this->diffPage->go(array($nid, $vids[1], $vids[0]));

        // Get an identifier to check for in the src of the rendered image.
        $parts = explode('/', $atom['path']);
        $file = array_pop($parts);
        $file = explode('.', $file);
        $this->assertTrue($this->diffPage->checkImagePresent($file[0]));
    }
}
