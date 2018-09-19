<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Core\SharedImageTest.
 */

namespace Kanooh\Paddle\Core;

use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminNodeViewPage;
use Kanooh\Paddle\Pages\Admin\DashboardPage\DashboardPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as FrontEndNodeViewPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage as NodeEditPage;
use Kanooh\Paddle\Pages\Admin\SiteSettings\SiteSettingsPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\TestDataProvider\EmailTestDataProvider;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests the Social Media shared images.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class SharedImageTest extends WebDriverTestCase
{
    /**
     * @var AdminNodeViewPage
     */
    protected $adminNodeViewPage;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var AssetCreationService
     */
    protected $assetCreationService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var DashboardPage
     */
    protected $dashboardPage;

    /**
     * @var FrontEndNodeViewPage
     */
    protected $frontEndNodeViewPage;

    /**
     * @var NodeEditPage
     */
    protected $nodeEditPage;

    /**
     * @var SiteSettingsPage
     */
    protected $siteSettingsPage;

    /**
     * @var EmailTestDataProvider
     */
    protected $emailTestDataProvider;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Create some instances to use later on.
        $this->frontEndNodeViewPage = new FrontEndNodeViewPage($this);
        $this->nodeEditPage = new NodeEditPage($this);
        $this->adminNodeViewPage = new AdminNodeViewPage($this);
        $this->dashboardPage = new DashboardPage($this);
        $this->siteSettingsPage = new SiteSettingsPage($this);
        $this->assetCreationService = new AssetCreationService($this);
        $this->userSessionService = new UserSessionService($this);
        $this->appService = new AppService($this, $this->userSessionService);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->emailTestDataProvider = new EmailTestDataProvider($this);

        $this->userSessionService->login('SiteManager');
    }

    /**
     * Tests if the correct meta tags are added to the page after adding
     * a Default Shared Image.
     *
     * @group sharedImage
     */
    public function testDefaultSharedImageMetaTags()
    {
        // Create an image atom to test with. The Default image dimensions
        // of the created image is 960 x 640 pixels (See sample image).
        $image = $this->assetCreationService->createImage();
        $atom = scald_atom_load($image['id']);
        $atom_path = $atom->thumbnail_source;

        // The path of the displayed image.
        $styled_path = image_style_path('large', $atom_path);
        $image_url = file_create_url($styled_path);

        $this->initDefaultSharedImage();

        // Go to the Site settings page.
        $this->dashboardPage->go();
        $this->dashboardPage->siteSettingsMenuBlock->links->linkSiteSettings->click();
        $this->siteSettingsPage->checkArrival();

        // Add the Image to the Site settings and save the page.
        $this->siteSettingsPage->defaultSharedImage->selectAtom($image['id']);
        $email = $this->emailTestDataProvider->getValidValue();
        $this->siteSettingsPage->siteEmail->fill($email);
        $this->siteSettingsPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The configuration options have been saved.');

        // Check if the correct image has been selected.
        $this->assertEquals($image['id'], $this->siteSettingsPage->defaultSharedImage->valueField->value());

        // Create the node which you want to share.
        $nid = $this->contentCreationService->createBasicPage();

        // Verify that the correct meta tags are added to the page.
        $this->frontEndNodeViewPage->go($nid);
        $this->verifySEOTags($image_url);
    }

    /**
     * Tests if the correct meta tags are added to the page after adding
     * a Featured Image.
     *
     * @group sharedImage
     */
    public function testFeaturedImageMetaTags()
    {
        // Create an image atom to test with. The Default image dimensions
        // of the created image is 960 x 640 pixels (See sample image).
        $image = $this->assetCreationService->createImage();
        $atom = scald_atom_load($image['id']);
        $atom_path = $atom->thumbnail_source;

        // The path of the displayed image.
        $styled_path = image_style_path('large', $atom_path);
        $image_url = file_create_url($styled_path);

        // Create the node which you want to share.
        $nid = $this->contentCreationService->createBasicPage();

        $this->nodeEditPage->go($nid);

        $this->nodeEditPage->featuredImage->selectAtom($image['id']);
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Verify that the correct meta tags are added to the page.
        $this->frontEndNodeViewPage->go($nid);
        $this->verifySEOTags($image_url);
    }

    /**
     * Tests which image is being shared.
     *
     * This test will add/remove Featured Images and Default Shared images
     * to verify which images will be shared in each use case.
     *
     * @group sharedImage
     */
    public function testAddRemoveImages()
    {
        // Create the Default Image.
        $default_image = $this->assetCreationService->createImage();
        $default_atom = scald_atom_load($default_image['id']);
        $default_atom_path = $default_atom->thumbnail_source;
        $default_styled_path = image_style_path('large', $default_atom_path);
        $default_image_url = file_create_url($default_styled_path);

        // Created the Featured Image.
        $featured_image = $this->assetCreationService->createImage();
        $featured_atom = scald_atom_load($featured_image['id']);
        $featured_atom_path = $featured_atom->thumbnail_source;
        $featured_styled_path = image_style_path('large', $featured_atom_path);
        $featured_image_url = file_create_url($featured_styled_path);

        // Add the Image to the Site settings and save the page.
        $this->initDefaultSharedImage();
        $this->siteSettingsPage->go();
        $this->siteSettingsPage->defaultSharedImage->selectAtom($default_image['id']);
        $this->siteSettingsPage->contextualToolbar->buttonSave->click();
        $this->siteSettingsPage->checkArrival();

        // Create the node which you want to share and add a featured image.
        $nid = $this->contentCreationService->createBasicPage();
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->featuredImage->selectAtom($featured_image['id']);
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
        $this->adminNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->adminNodeViewPage->checkArrival();

        // Verify the shared image location which is from the Featured Image.
        $this->frontEndNodeViewPage->go($nid);
        $xpath_image_og_metatag = '//meta[contains(@property, "og:image") and contains(@content, "' . $featured_image_url . '")]';
        $this->waitUntilElementIsPresent($xpath_image_og_metatag);
        $xpath_image_tc_metatag = '//meta[contains(@name, "twitter:image") and contains(@content, "' . $featured_image_url . '")]';
        $this->waitUntilElementIsPresent($xpath_image_tc_metatag);

        // Remove the Featured Image.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->featuredImage->clear();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
        $this->adminNodeViewPage->contextualToolbar->buttonPreviewRevision->click();

        // Verify the shared image location which is from the Default Image.
        $this->frontEndNodeViewPage->checkArrival();
        $xpath_image_og_metatag = '//meta[contains(@property, "og:image") and contains(@content, "' . $default_image_url . '")]';
        $this->waitUntilElementIsPresent($xpath_image_og_metatag);
        $xpath_image_tc_metatag = '//meta[contains(@name, "twitter:image") and contains(@content, "' . $default_image_url . '")]';
        $this->waitUntilElementIsPresent($xpath_image_tc_metatag);

        $this->adminNodeViewPage->go($nid);
        $this->adminNodeViewPage->contextualToolbar->buttonOnlineVersion->click();

        // Verify the shared image location which is from the Default Image.
        $this->frontEndNodeViewPage->checkArrival();
        $xpath_image_og_metatag = '//meta[contains(@property, "og:image") and contains(@content, "' . $featured_image_url . '")]';
        $this->waitUntilElementIsPresent($xpath_image_og_metatag);
        $xpath_image_tc_metatag = '//meta[contains(@name, "twitter:image") and contains(@content, "' . $featured_image_url . '")]';
        $this->waitUntilElementIsPresent($xpath_image_tc_metatag);

        // Remove the Default Image.
        $this->siteSettingsPage->go();
        $this->siteSettingsPage->defaultSharedImage->clear();
        $this->siteSettingsPage->contextualToolbar->buttonSave->click();
        $this->siteSettingsPage->checkArrival();
        // Verify that the meta tags are not displayed.

        $this->adminNodeViewPage->go($nid);
        $this->adminNodeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->frontEndNodeViewPage->checkArrival();
        $xpath_og = '//meta[contains(@property, "og:image")]';
        $this->waitUntilElementIsNoLongerPresent($xpath_og);

        $xpath_tc = '//meta[contains(@name, "twitter:image")]';
        $this->waitUntilElementIsNoLongerPresent($xpath_tc);
    }

    /**
     * Initializes the Default Shared Image (makes it empty).
     */
    protected function initDefaultSharedImage()
    {
        $this->siteSettingsPage->go();
        $this->siteSettingsPage->defaultSharedImage->clear();
        $this->siteSettingsPage->contextualToolbar->buttonSave->click();
        $this->siteSettingsPage->checkArrival();
    }

    /**
     * Verifies if all SEO-related meta tags are correct.
     *
     * @param $image_url
     *   The URL of the shared image.
     */
    protected function verifySEOTags(&$image_url)
    {
        // The image locations
        $xpath_image_og_metatag = '//meta[contains(@property, "og:image") and contains(@content, "' . $image_url . '")]';
        $this->waitUntilElementIsPresent($xpath_image_og_metatag);

        $xpath_image_tc_metatag = '//meta[contains(@name, "twitter:image") and contains(@content, "' . $image_url . '")]';
        $this->waitUntilElementIsPresent($xpath_image_tc_metatag);

        // The image dimensions (The image should be resized to 480 x 320).
        $xpath_image_width_metatag = '//meta[contains(@property, "og:image:width") and contains(@content, "480")]';
        $this->waitUntilElementIsPresent($xpath_image_width_metatag);

        $xpath_image_twitter__width_metatag = '//meta[contains(@name, "twitter:image:width") and contains(@content, "480")]';
        $this->waitUntilElementIsPresent($xpath_image_twitter__width_metatag);

        $xpath_image_height_metatag = '//meta[contains(@property, "og:image:height") and contains(@content, "320")]';
        $this->waitUntilElementIsPresent($xpath_image_height_metatag);

        $xpath_image_twitter_height_metatag = '//meta[contains(@name, "twitter:image:height") and contains(@content, "320")]';
        $this->waitUntilElementIsPresent($xpath_image_twitter_height_metatag);
    }
}
