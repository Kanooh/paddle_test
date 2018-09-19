<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Product\PollChartTest.
 */

namespace Kanooh\Paddle\App\Poll;

use Kanooh\Paddle\Apps\Poll;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Node\EditPage\Poll\PollPage;
use Kanooh\Paddle\Pages\Node\ViewPage\Poll\PollViewPage as FrontEndViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Test chart visualizations of poll data.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PollChartTest extends WebDriverTestCase
{
    /**
     * @var AdministrativeNodeViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var PollPage
     */
    protected $editPage;

    /**
     * @var FrontEndViewPage
     */
    protected $frontendPage;

    /**
     *
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
        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->editPage = new PollPage($this);
        $this->frontendPage = new FrontEndViewPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Log in as site manager.
        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not enabled yet.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new Poll);
    }

    /**
     * Tests the poll results displayed as chart.
     */
    public function testChartTypes()
    {
        // Define the votes.
        $choices = array(
            array(
                'text' => $this->alphanumericTestDataProvider->getValidValue(),
                'votes' => 42,
            ),
            array(
                'text' => $this->alphanumericTestDataProvider->getValidValue(),
                'votes' => 31,
            ),
            array(
                'text' => $this->alphanumericTestDataProvider->getValidValue(),
                'votes' => 3,
            ),
        );

        // Create a poll.
        $nid = $this->contentCreationService->createPollPage(null, $choices);

        // Close the poll so we can access results only.
        $this->editPage->go($nid);
        $this->editPage->pollForm->pollStatusRadioButtons->closed->select();
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Verify that by default the standard bars are shown.
        $this->frontendPage->go($nid);
        $this->assertEquals(42, $this->frontendPage->pollView->results[0]['votes']);

        // Change the chart type.
        $this->editPage->go($nid);
        $this->editPage->pollForm->chartType->selectOptionByValue('pie');
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Verify that the pie chart is being rendered in the front.
        $this->frontendPage->go($nid);
        $this->assertEquals('PieChart', $this->frontendPage->pollView->chart->getType());

        // Check that the legend is there and all the labels are rendered in
        // the proper order.
        $expected_labels = array_column($choices, 'text');
        $this->assertEquals($expected_labels, $this->frontendPage->pollView->chart->legend->labels);

        // Assert that the accessible table is shown there.
        $this->assertNotNull($this->frontendPage->pollView->chart->table);

        // Change the type.
        $this->editPage->go($nid);
        $this->editPage->pollForm->chartType->selectOptionByValue('column');
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Test it again.
        $this->frontendPage->go($nid);
        $this->assertEquals('ColumnChart', $this->frontendPage->pollView->chart->getType());

        // Verify again the legend..
        $this->assertEquals($expected_labels, $this->frontendPage->pollView->chart->legend->labels);

        // Assert that the accessible table is shown there.
        $this->assertNotNull($this->frontendPage->pollView->chart->table);
    }
}
