<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\News\NewsTest.
 */

namespace Kanooh\Paddle\App\News;

use Drupal\Component\Utility\Random;
use Kanooh\Paddle\Apps\News;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage as AddContentPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Admin\Structure\ContentRegionPage\ContentRegionPage;
use Kanooh\Paddle\Pages\Admin\Structure\ContentRegionPage\ContentRegionUtility;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CustomContentPanelsContentType;
use Kanooh\Paddle\Pages\Node\EditPage\NewsPage as EditNewsPage;
use Kanooh\Paddle\Pages\Node\EditPage\NewsRandomFiller;
use Kanooh\Paddle\Pages\Node\ViewPage\NewsViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs tests on the Paddle News Paddlet.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NewsTest extends WebDriverTestCase
{

    /**
     * The administrative node view page.
     *
     * @var AdministrativeNodeViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * @var AssetCreationService
     */
    protected $assetCreationService;

    /**
    * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * The news edit page.
     *
     * @var EditNewsPage
     */
    protected $editNewsPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * Test content
     *
     * @var array
     */
    protected $testContent;

    /**
     * The random data generation class.
     *
     * @var Random $random
     */
    protected $random;

    /**
     * The content region configuration page.
     *
     * @var ContentRegionPage
     */
    protected $contentRegionConfigurationPage;

    /**
     * The utility class for common function for content regions.
     *
     * @var ContentRegionUtility
     */
    protected $contentRegionUtility;

    /**
     * The 'Add content' page.
     *
     * @var AddContentPage
     */
    protected $addContentPage;

    /**
     * The frontend news page.
     *
     * @var NewsViewPage
     */
    protected $newsViewPage;

    /**
     * The panels display of a contact person.
     *
     * @var LayoutPage
     */
    protected $layoutPage;

     /**
     * The form filler for the news item edit form.
     *
     * @var NewsRandomFiller
     */
    protected $newsRandomFiller;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Create some instances to use later on.
        $this->addContentPage = new AddContentPage($this);
        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->assetCreationService = new AssetCreationService($this);
        $this->contentRegionConfigurationPage = new ContentRegionPage($this);
        $this->contentRegionUtility = new ContentRegionUtility($this);
        $this->editNewsPage = new EditNewsPage($this);
        $this->layoutPage = new LayoutPage($this);
        $this->newsRandomFiller = new NewsRandomFiller();
        $this->newsViewPage = new NewsViewPage($this);
        $this->random = new Random();

        // Set up test data.
        $this->testContent['all_pages']['right'] = $this->random->name(64);
        $this->testContent['all_pages']['bottom'] = $this->random->name(64);
        $this->testContent['news_item']['right'] = $this->random->name(64);
        $this->testContent['news_item']['bottom'] = $this->random->name(64);

        // Go to the login page and log in as Chief Editor.
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new News);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        // Log out.
        $this->userSessionService = new UserSessionService($this);
        $this->userSessionService->logout();

        parent::tearDown();
    }

    /**
     * Tests the creation of a news item.
     *
     * @group editing
     * @group news
     */
    public function testCreate()
    {
        $title = $this->random->name(8);
        // Go to admin/content_manager/add.
        $this->addContentPage->go();
        // Click on "Contact person".
        $this->addContentPage->links->linkNewsItem->click();
        $this->addContentPage->createNodeModal->waitUntilOpened();
        $this->assertTextPresent('information modal dialog');
        // Fill in required fields.
        $this->addContentPage->createNodeModal->title->fill($title);
        $this->addContentPage->createNodeModal->submit();
        $this->addContentPage->createNodeModal->waitUntilClosed();

        $this->administrativeNodeViewPage->checkArrival();
        // Wait until we see confirmation that the node has been created.
        $this->waitUntilElementIsPresent('//div[@id="messages"]');
        $this->assertTextPresent(
            'News item ' . $title . ' has been created.'
        );

        $this->administrativeNodeViewPage->contextualToolbar->buttonPageProperties->click();
        // Then I see the edit form of the news item.
        $this->editNewsPage->checkArrival();

        // Fill in all other fields.
        $this->newsRandomFiller->randomize();
        $this->newsRandomFiller->fill($this->editNewsPage);
        // Set the image field.
        $this->editNewsPage->newsForm->leadImage->chooseImage();
        $this->editNewsPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPreviewRevision->click();

        // Verify that in the front end the fields are shown.
        $this->newsViewPage->checkArrival();
        $this->newsViewPage->assertLayoutMarkup();
        $this->assertTextPresent($this->newsRandomFiller->title);
        $this->assertTextPresent($this->newsRandomFiller->body);
        // Verify the default image position value is 'floating left (50%)'.
        $this->byCssSelector('img.news-item-featured-image.half-width.float-left');
    }

    /**
     * Tests the lead image of a news item.
     *
     * @group editing
     * @group news
     * @group scald
     */
    public function testLeadImage()
    {
        $nid = $this->contentCreationService->createNewsItem();

        // Upload an image.
        $this->editNewsPage->go($nid);
        $this->editNewsPage->newsForm->leadImage->chooseImage();

        // Save.
        $this->editNewsPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Verify the default image position value is 'floating left (50%)'.
        $this->byCssSelector('img.news-item-featured-image.half-width.float-left');

        // Change to 'full width top'.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageProperties->click();
        $this->editNewsPage->checkArrival();
        $this->editNewsPage->newsForm->leadImagePosition->fullTop->select();
        $this->editNewsPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Verify the image CSS classes match the chosen position.
        $this->byCssSelector('img.news-item-featured-image.full-width');

        // Change to 'floating right (50%)'.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageProperties->click();
        $this->editNewsPage->checkArrival();
        $this->editNewsPage->newsForm->leadImagePosition->halfRight->select();
        $this->editNewsPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Verify the image CSS classes match the chosen position.
        $this->byCssSelector('img.news-item-featured-image.half-width.float-right');
    }

    /**
     * Tests the creation date field on the news edit page.
     *
     * @group editing
     * @group news
     */
    public function testCreationDate()
    {
        $nid = $this->contentCreationService->createNewsItem();

        // Get the creation date.
        $this->administrativeNodeViewPage->go($nid);
        $this->administrativeNodeViewPage->nodeSummary->showAllMetadata();
        $created_item = $this->administrativeNodeViewPage->nodeSummary->getMetadata('general', 'created');
        $created = $created_item['value_raw'];

        // Go to the edit page and make sure the creation date is the same in
        // the creation date field.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageProperties->click();
        $this->editNewsPage->checkArrival();

        $this->assertEquals($this->editNewsPage->creationDate->getContent(), date('d/m/Y', $created));
        $this->assertEquals($this->editNewsPage->creationTime->getContent(), date('H:i', $created));

        // Pick a date a week ago and also change the hours and seconds.
        $date = strtotime('-1 week 6 hours 12 seconds');
        $this->editNewsPage->creationDate->fill(date('d/m/Y', $date));
        $this->editNewsPage->creationTime->fill(date('H:i', $date));
        $this->editNewsPage->contextualToolbar->buttonSave->click();

        // Check in the node summary that the creation date was changed
        // correctly.
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->nodeSummary->showAllMetadata();
        $created_item = $this->administrativeNodeViewPage->nodeSummary->getMetadata('general', 'created');
        $created = $created_item['value'];
        $this->assertEquals($created, date('d/m/Y - H:i', $date));

        // Check that the creation date is shown on the front end.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->newsViewPage->checkArrival();
        $this->assertTextPresent(date('j F Y', $date));
    }

    /**
     * Tests the content region panes on news pages.
     *
     * @group news
     * @group panes
     */
    public function testContentRegionsOnNews()
    {
        // Go to the content region configuration page.
        $this->contentRegionConfigurationPage->go();

        // Click 'Edit content for all pages' and add two panes: one to the
        // right region, and one to the bottom region.
        $this->contentRegionUtility->addCustomContentPanes(
            $this->contentRegionConfigurationPage->links->linkEditContentForAllPages,
            $this->testContent['all_pages']['right'],
            $this->testContent['all_pages']['bottom']
        );

        // Override the setting for the simple contact pages.
        $this->contentRegionConfigurationPage->getOverride('news_item')->enable();
        $this->contentRegionConfigurationPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved');
        $checkbox = $this->contentRegionConfigurationPage->getOverride('news_item')->checkbox;
        $this->assertTrue($checkbox->selected());

        // Click 'Edit content for every news page' and add two panes: one to
        // the right region, and one to the bottom region.
        $this->contentRegionUtility->addCustomContentPanes(
            $this->contentRegionConfigurationPage->getOverride('news_item')->editLink,
            $this->testContent['news_item']['right'],
            $this->testContent['news_item']['bottom']
        );

        // Create an a news item. We end up on the administrative node view.
        $nid = $this->contentCreationService->createNewsItem();

        // Go to the front-end view and check that the chosen panes for the news
        // content regions are shown.
        $this->newsViewPage->go($nid);
        $this->assertTextPresent($this->testContent['news_item']['right']);
        $this->assertTextPresent($this->testContent['news_item']['bottom']);

        // Go to the page layout.
        $this->layoutPage->go($nid);

        // Add two custom content panes, one to the right region, and one to the
        // bottom region.
        $custom_pane_content = array(
            'right' => $this->random->name(64),
            'bottom' => $this->random->name(64),
        );
        $regions = $this->layoutPage->display->getRegions();
        $custom_content_pane = new CustomContentPanelsContentType($this);

        // Add a custom content pane to the right region.
        $custom_content_pane->body = $custom_pane_content['right'];
        $regions['right']->addPane($custom_content_pane);

        // Add a custom content pane to the bottom region.
        $custom_content_pane->body = $custom_pane_content['bottom'];
        $regions['bottom']->addPane($custom_content_pane);

        // Save the page. We arrive on the administrative node view.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->assertTextPresent('The changes have been saved.');

        // Go to the frontend view and check that the new panes are shown in
        // addition to the chosen panes for the news content regions.
        $this->newsViewPage->go($nid);
        $this->assertTextPresent($this->testContent['news_item']['right']);
        $this->assertTextPresent($this->testContent['news_item']['bottom']);
        $this->assertTextPresent($custom_pane_content['right']);
        $this->assertTextPresent($custom_pane_content['bottom']);

        // Go back to the global content region configuration page.
        $this->contentRegionConfigurationPage->go();

        // Set the checkbox to use global content settings and click 'Save.
        $this->contentRegionConfigurationPage->getOverride('news_item')->disable();
        $this->contentRegionConfigurationPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved');
        $checkbox = $this->contentRegionConfigurationPage->getOverride('news_item')->checkbox;
        $this->assertFalse($checkbox->selected());

        // Go to the front page of the news. Check that the global panes are now
        // shown.
        $this->newsViewPage->go($nid);
        $this->assertTextPresent($this->testContent['all_pages']['right']);
        $this->assertTextPresent($this->testContent['all_pages']['bottom']);

        // Check that the custom panes are still shown.
        $this->assertTextPresent($custom_pane_content['right']);
        $this->assertTextPresent($custom_pane_content['bottom']);

        // Check that the news panes are not shown.
        $this->assertTextNotPresent($this->testContent['news_item']['right']);
        $this->assertTextNotPresent($this->testContent['news_item']['bottom']);
    }

    /**
     * Tests that the lead image field is tracked as reference.
     *
     * @group linkChecker
     */
    public function testNewsLeadImageFieldReference()
    {
        // Create a image atom use as lead image.
        $atom = $this->assetCreationService->createImage();

        // Create a news item to test with.
        $nid = $this->contentCreationService->createNewsItem();

        // Add the atom as a lead image.
        $this->editNewsPage->go($nid);
        $this->editNewsPage->newsForm->leadImage->selectAtom($atom['id']);
        $this->editNewsPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Ensure there is a reference record for this.
        $references = reference_tracker_get_inbound_references('scald_atom', $atom['id']);
        $this->assertEquals($nid, $references['node'][0]);
    }
}
