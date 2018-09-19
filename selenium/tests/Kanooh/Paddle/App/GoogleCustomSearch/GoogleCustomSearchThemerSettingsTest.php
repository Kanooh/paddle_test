<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\GoogleCustomSearch\GoogleCustomSearchThemerSettingsTest.
 */

namespace Kanooh\Paddle\App\GoogleCustomSearch;

use Drupal\Component\Utility\Random;
use Kanooh\Paddle\Apps\GoogleCustomSearch;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleGoogleCustomSearch\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerAddPage\ThemerAddPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\ThemerEditPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerOverviewPage\ThemerOverviewPage;
use Kanooh\Paddle\Pages\Element\ElementNotPresentException;
use Kanooh\Paddle\Pages\FrontPage\FrontPage;
use Kanooh\Paddle\Pages\SearchPage\GoogleCustomSearchPage;
use Kanooh\Paddle\Pages\SearchPage\PaddleSearchPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs tests on the themer settings for the Paddle Google Custom Search
 * Paddlet.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class GoogleCustomSearchThemerSettingsTest extends WebDriverTestCase
{
    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * The configuration page the Google Custom Search paddlet.
     *
     * @var ConfigurePage
     */
    protected $googleCustomSearchConfigPage;

    /**
     * The random data generator.
     *
     * @var Random
     */
    protected $random;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var string
     */
    protected $theme_name;

    /**
     * The 'Add' page of the Paddle Themer module.
     *
     * @var ThemerAddPage
     */
    protected $themerAddPage;

    /**
     * The 'Edit' page of the Paddle Themer module.
     *
     * @var ThemerEditPage
     */
    protected $themerEditPage;

    /**
     * The 'Overview' page of the Paddle Themer module.
     *
     * @var ThemerOverviewPage
     */
    protected $themerOverviewPage;

    /**
     * The homepage.
     *
     * @var FrontPage
     */
    protected $frontPage;

    /**
     * The google custom search page.
     *
     * @var GoogleCustomSearchPage
     */
    protected $googleCustomSearchPage;

    /**
     * The paddle search api page.
     *
     * @var PaddleSearchPage
     */
    protected $paddleSearchPage;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Create some instances to use later on.
        $this->frontPage = new FrontPage($this);
        $this->googleCustomSearchConfigPage = new ConfigurePage($this);
        $this->googleCustomSearchPage = new GoogleCustomSearchPage($this);
        $this->paddleSearchPage = new PaddleSearchPage($this);
        $this->random = new Random();
        $this->themerOverviewPage = new ThemerOverviewPage($this);
        $this->themerAddPage = new ThemerAddPage($this);
        $this->themerEditPage = new ThemerEditPage($this);
        $this->userSessionService = new UserSessionService($this);

        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new GoogleCustomSearch);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        // Disable the Google custom Search.
        $this->themerOverviewPage->go();
        $theme = $this->themerOverviewPage->theme($this->theme_name);
        $theme->edit->click();
        $this->themerEditPage->checkArrival();
        $this->themerEditPage->header->header->click();
        $this->themerEditPage->header->googleCustomSearchEnabled->uncheck();
        $this->themerEditPage->header->paddleSearchEnabled->check();
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();
        // Log out.
        $this->userSessionService = new UserSessionService($this);
        $this->userSessionService->logout();

        parent::tearDown();
    }

    /**
     * Tests the themer settings for the google custom search paddlet.
     *
     * @todo This needs to be adjusted when the actual themer classes are in place.
     *
     * @group frontend
     * @group search
     * @group themer
     */
    public function testThemerSettings()
    {
        $this->userSessionService->login('SiteManager');

        // Go to the configuration page.
        $this->googleCustomSearchConfigPage->go();
        // Build an array with a value for each form field.
        $values = array(
            'cse_id' => '002208523045865629998:-5f9imrvx-g',
            'api_key' => 'AIzaSyD1IRykPL_z9vPbAR6i_PHzyXRIQFO1cfE',
        );

        // Fill in the form with the values specified previously.
        $this->googleCustomSearchConfigPage->cseID->fill($values['cse_id']);
        $this->googleCustomSearchConfigPage->apiKey->fill($values['api_key']);

        // Save the form.
        $this->googleCustomSearchConfigPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The configuration options have been saved.');

        // Add a new and test each search setting seperatly.
        $this->themerOverviewPage->go();

        $this->themerOverviewPage->contextualToolbar->buttonCreateTheme->click();
        $this->themerAddPage->checkArrival();

        // Create a new theme.
        $human_theme_name = $this->random->name(8);
        $this->themerAddPage->name->clear();
        $this->themerAddPage->name->value($human_theme_name);
        $this->themerAddPage->baseTheme->selectOptionByValue('vo_standard');
        $this->themerAddPage->buttonSubmit->click();
        $this->themerEditPage->checkArrival();
        $this->theme_name = $this->themerEditPage->getThemeName();

        // Unfold the header section.
        $this->themerEditPage->header->header->click();

        // Check if the checkbox for the google custom search setting is
        // disabled by default. For the paddle search, this should be enabled by
        // default.
        $this->assertTrue($this->themerEditPage->header->paddleSearchEnabled->isChecked());
        $this->assertFalse($this->themerEditPage->header->googleCustomSearchEnabled->isChecked());

        // Check if the correct search texts have been set.
        $this->assertEquals(
            'On this website',
            $this->themerEditPage->header->paddleSearchTitle->getContent()
        );
        $this->assertEquals(
            'On all websites',
            $this->themerEditPage->header->googleCustomSearchTitle->getContent()
        );

        // Save the theme.
        $this->moveto($this->themerEditPage->buttonSubmit);
        $this->themerEditPage->buttonSubmit->click();

        // Enable the theme.
        $this->themerOverviewPage->checkArrival();
        $theme = $this->themerOverviewPage->theme($this->theme_name);
        $this->assertEquals($human_theme_name, $theme->title->text());

        $theme->enable->click();
        $this->themerOverviewPage->checkArrival();

        // Resave the theme to work around a bug that causes the search options
        // to be accepted only if they are saved in the currently active theme.
        // @todo Remove this workaround once KANWEBS-2100 is in.
        // @see https://one-agency.atlassian.net/browse/KANWEBS-2100
        $theme = $this->themerOverviewPage->theme($this->theme_name);
        $theme->edit->click();
        $this->themerEditPage->checkArrival();
        $this->moveto($this->themerEditPage->buttonSubmit);
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();

        // Verify in the front end that the search radio buttons are not shown.
        $this->frontPage->go();
        try {
            $radio_buttons = $this->frontPage->searchBox->searchMethod;
            $this->fail('The radio buttons to select the search engine should not be shown.');
        } catch (ElementNotPresentException $e) {
            // This exception is expected (a.k.a ElementNotPresentExpection).
        }

        // When searching, you arrive on the paddle search page.
        $this->frontPage->searchBox->searchField->fill($this->getRandomSearchString());
        $this->frontPage->searchBox->searchButton->click();
        $this->waitUntilTextIsPresent('Search results');
        $this->paddleSearchPage->checkArrival();

        // Enable the google custom search and set the texts.
        $text = array(
          'paddle_search' => $this->random->name(8),
          'google_custom_search' => $this->random->name(8),
        );
        $this->themerEditPage->go($this->theme_name);
        // Unfold the header section.
        $this->themerEditPage->header->header->click();
        $this->waitUntilTextIsPresent('Google custom search');
        $this->moveto($this->themerEditPage->header->googleCustomSearchEnabled->getWebdriverElement());
        $this->themerEditPage->header->googleCustomSearchEnabled->check();
        $this->themerEditPage->header->googleCustomSearchTitle->fill($text['google_custom_search']);
        $this->themerEditPage->header->paddleSearchTitle->fill($text['paddle_search']);
        $this->moveto($this->themerEditPage->buttonSubmit);
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();

        // Test if you can choose between the paddle search and the google
        // custom search.
        $this->frontPage->go();
        $this->assertTrue($this->frontPage->searchBox->searchMethod->isDisplayed());

        // Check that the default search is selected by default.
        $this->assertEquals('default_search', $this->frontPage->searchBox->searchMethod->getSelected()->getValue());

        // Select the paddle search.
        $this->frontPage->searchBox->searchMethod->paddleSearch->select();
        $this->frontPage->searchBox->searchField->clear();
        $this->frontPage->searchBox->searchField->fill($this->getRandomSearchString());
        $this->frontPage->searchBox->searchButton->click();
        $this->paddleSearchPage->checkArrival();

        // Select the google custom search.
        $this->frontPage->searchBox->searchMethod->googleCustomSearch->select();
        $this->frontPage->searchBox->searchField->clear();
        $this->frontPage->searchBox->searchField->fill($this->getRandomSearchString());
        $this->frontPage->searchBox->searchButton->click();
        $this->googleCustomSearchPage->checkArrival();

        // Check if the texts next to the radio buttons are correct.
        $this->assertTextPresent($text['paddle_search'], $this->frontPage->searchBox->getWebdriverElement());
        $this->assertTextPresent($text['google_custom_search'], $this->frontPage->searchBox->getWebdriverElement());

        // Only enable the google custom search in the themer.
        $this->themerEditPage->go($this->theme_name);
        // Unfold the header section.
        $this->themerEditPage->header->header->click();
        $this->waitUntilTextIsPresent('Google custom search');
        $this->themerEditPage->header->paddleSearchEnabled->uncheck();
        $this->moveto($this->themerEditPage->buttonSubmit);
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();

        // Verify in the front end that the search radio buttons are not shown.
        $this->frontPage->go();
        try {
            $radio_buttons = $this->frontPage->searchBox->searchMethod;
            $this->fail('The radio buttons to select the search engine should not be shown.');
        } catch (ElementNotPresentException $e) {
            // This exception is expected (a.k.a ElementNotPresentExpection).
        }

        // When searching, you arrive on the google custom search page.
        $this->frontPage->searchBox->searchField->clear();
        $this->frontPage->searchBox->searchField->fill($this->getRandomSearchString());
        $this->frontPage->searchBox->searchButton->click();
        $this->googleCustomSearchPage->checkArrival();

        // Verify in the the themer that the paddle search checkbox is disabled.
        $this->themerEditPage->go($this->theme_name);
        // Unfold the header section.
        $this->themerEditPage->header->header->click();
        $this->waitUntilTextIsPresent('Google custom search');
        $this->assertFalse($this->themerEditPage->header->paddleSearchEnabled->isChecked());
    }

    protected function getRandomSearchString($length = 6)
    {
        return $this->random->name($length);
    }
}
