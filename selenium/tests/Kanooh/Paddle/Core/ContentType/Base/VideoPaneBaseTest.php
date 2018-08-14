<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\Base\VideoPaneBaseTest.
 */

namespace Kanooh\Paddle\Core\ContentType\Base;

use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Element\Pane\VideoPane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\VideoPanelsContentType;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests functionalities specific to the Video pane.
 */
abstract class VideoPaneBaseTest extends WebDriverTestCase
{

    /**
     * The administrative node view of a page.
     *
     * @var ViewPage
     */
    protected $adminNodeViewPage;

    /**
     * @var AssetCreationService
     */
    protected $assetCreationService;

    /**
     * The service to create content of several types.
     *
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * Test videos.
     *
     * @var array
     */
    protected $videos;

    /**
     * Creates a node of the content type that is being tested.
     *
     * @param string $title
     *   Optional title for the node. If omitted a random title will be used.
     *
     * @return int
     *   The node ID of the node that was created.
     */
    abstract public function setupNode($title = null);

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Create some instances to use later on.
        $this->adminNodeViewPage = new ViewPage($this);
        $this->assetCreationService = new AssetCreationService($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Go to the login page and log in as chief editor.
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        // Remove all atoms created during the test. Because we used the
        // AssetCreationService to create our atoms, the class knows which ones
        // need to be deleted.
        AssetCreationService::cleanUp($this);
        parent::tearDown();
    }

    /**
     * Get the 'Page layout' page belonging to a certain node type.
     *
     * @return LayoutPage
     *   The 'Page layout' page.
     */
    protected function getLayoutPage()
    {
        return new LayoutPage($this);
    }

    /**
     * Data provider for testVideoPaneEditForm().
     *
     * Returns a list of values to create videos with.
     *
     * @return array
     *   An array containing video type and array of video data.
     */
    public function videoSetupProvider()
    {
        return array(
            array(
                array(
                    'type' => 'file',
                ),
            ),
            array(
                array(
                    'type' => 'youtube',
                    'identifier' => 'https://www.youtube.com/watch?v=aTMbHEoAktM',
                    'thumbnail' => false,
                ),
            ),
        );
    }

    /**
     * Check the edit form for the video pane.
     *
     * @dataProvider videoSetupProvider
     *
     * @group modals
     * @group panes
     * @group scald
     * @group videoPane
     */
    public function testVideoPaneEditForm($video_data)
    {
        // Create the video atom to use.
        switch ($video_data['type']) {
            case 'file':
                $video_data += $this->assetCreationService->createVideo();
                break;
            case 'youtube':
                $video_data += $this->assetCreationService->createYoutubeVideo($video_data);
                break;
        }

        // Create the node.
        $nid = $this->setupNode();
        $node = node_load($nid);

        $this->adminNodeViewPage->go($nid);

        // Go to the layout page.
        $this->adminNodeViewPage->contextualToolbar->buttonPageLayout->click();
        $layout_page = $this->getLayoutPage();
        $layout_page->checkArrival();
        $region = $layout_page->display->getRandomRegion();

        // Add a pane to the page.
        $video_type = new VideoPanelsContentType($this);
        $pane = $region->addPane(
            $video_type,
            function ($modal) use ($video_type, $video_data) {
                $video_type->getForm($modal)->video->selectAtom($video_data['id']);
            }
        );
        $pane_uuid = $pane->getUuid();
        $pane_xpath = $pane->getXPathSelector();
        $video_pane = new VideoPane($this, $pane_uuid, $pane_xpath);

        // Check that the video is there and then save.
        $test_data = $video_data;
        if ($video_data['type'] == 'file') {
            $test_data['video_location'] = 'sample_video';
            $test_data['poster'] = 'sample_video';
            $test_data['type'] = 'video/mp4';
        } else {
            $test_data['video_location'] = $video_data['identifier'];
            $test_data['poster'] = str_replace('https://www.youtube.com/watch?v=', '', $video_data['identifier']);
            $test_data['type'] = 'video/youtube';
        }
        $test_data['subtitles'] = 'sample_subtitles';

        $this->assertTrue($video_pane->checkVideoDisplayedInPane($test_data));
        $layout_page->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
    }
}
