<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Glossary\GlossaryPagerTest.
 */

namespace Kanooh\Paddle\App\Glossary;

use Kanooh\Paddle\Apps\Glossary;
use Kanooh\Paddle\Pages\Node\ViewPage\GlossaryOverviewPageViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\CleanUpService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class GlossaryPagerTest
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class GlossaryPagerTest extends WebDriverTestCase
{
    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var CleanUpService
     */
    protected $cleanUpService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var GlossaryOverviewPageViewPage
     */
    protected $glossaryOverviewPage;

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

        // Instantiate some classes to use in the test.
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->cleanUpService = new CleanUpService($this);
        $this->glossaryOverviewPage = new GlossaryOverviewPageViewPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new Glossary);
    }

    /**
     * Test the appearance of the pager in the Glossary overview page.
     *
     * @group regression
     */
    public function testOverviewPagePager()
    {
        // Create enough definitions to fill two pages.
        for ($i = 0; $i < 80; $i++) {
            $this->contentCreationService->createGlossaryDefinition(
                $this->alphanumericTestDataProvider->getValidValue(),
                $this->alphanumericTestDataProvider->getValidValue()
            );
        }

        // Go to the overview page.
        $this->glossaryOverviewPage->go();
        // Verify that we have the pager shown.
        $this->assertNotNull($this->glossaryOverviewPage->glossaryOverviewPane->pager->nextLink);

        // Go to the second page, so all the pager links will be there.
        $link = $this->glossaryOverviewPage->glossaryOverviewPane->pager->nextLink;
        $link->click();
        // The pane is reloaded through Ajax, including the pager.
        $this->waitUntilElementIsStale($link);

        // Verify that first, previous, next and last links have no text shown.
        $links = array('first', 'previous', 'next', 'last');
        foreach ($links as $name) {
            $this->assertEmpty(
                $this->glossaryOverviewPage->glossaryOverviewPane->pager->{$name . 'Link'}->text(),
                "The link for the $name page is not empty."
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        $this->cleanUpService->deleteEntities('paddle_glossary_definition');

        parent::tearDown();
    }
}
