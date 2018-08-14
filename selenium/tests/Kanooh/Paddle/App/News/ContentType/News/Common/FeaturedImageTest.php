<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\News\ContentType\News\Common\FeaturedImageTest.
 */

namespace Kanooh\Paddle\App\News\ContentType\News\Common;

use Kanooh\Paddle\Apps\News;
use Kanooh\Paddle\Core\ContentType\Base\FeaturedImageTestBase;

/**
 * FeaturedImageTest class.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class FeaturedImageTest extends FeaturedImageTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->appService->enableApp(new News);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createNewsItem($title);
    }

    /**
     * Asserts the news featured image with a style shows correctly.
     *
     * @group featuredImage
     * @group manualCrop
     * @group scald
     */
    public function testImageField()
    {
        // Create a node.
        $nid = $this->setupNode();

        // Create an image atom with a crop style.
        $data = array('image_style' => '16_9');
        $atom = $this->assetCreationService->createImage($data);

        // Go to the edit page and add an image with a cropping ratio.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->featuredImage->selectAtom($atom['id']);
        $this->nodeEditPage->featuredImage->style->selectOptionByValue($data['image_style']);
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Generate the path to the style image.
        $scald_atom = scald_atom_load($data['id']);
        $expected_src = file_create_url(image_style_path($data['image_style'], $scald_atom->file_source));
        $image = $this->byXPath('//div[contains(@class, "field-type-paddle-scald-atom")]//img');

        // The images may the 'itok' query parameter appended to the url.
        // Assert that the string starts with the expected path.
        $this->assertStringStartsWith($expected_src, $image->attribute('src'));
    }
}
