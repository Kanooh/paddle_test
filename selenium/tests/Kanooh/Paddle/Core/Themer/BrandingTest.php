<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Core\Themer\BrandingTest.
 */

namespace Kanooh\Paddle\Core\Themer;

use Kanooh\Paddle\Apps\Multilingual;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerAddPage\ThemerAddPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\ThemerEditPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerOverviewPage\ThemerOverviewPage;
use Kanooh\Paddle\Pages\FrontPage\FrontPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class BrandingTest extends WebDriverTestCase
{
    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var FrontPage
     */
    protected $frontPage;

    /**
     * @var ThemerAddPage
     */
    protected $themerAddPage;

    /**
     * @var ThemerEditPage
     */
    protected $themerEditPage;

    /**
     * @var ThemerOverviewPage
     */
    protected $themerOverviewPage;

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

        $this->frontPage = new FrontPage($this);
        $this->themerAddPage = new ThemerAddPage($this);
        $this->themerEditPage = new ThemerEditPage($this);
        $this->themerOverviewPage = new ThemerOverviewPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->appService = new AppService($this, $this->userSessionService);

        // Log in as site manager.
        $this->userSessionService->login('SiteManager');
    }

    /**
     * Tests the global header and footer.
     *
     * Attention!
     * We have no guarantee that the used tokens (92, 97, 100) will continue
     * to exist and that token 99999 will not. Neither do we have guarantee
     * that the widget server of the Flemish Government will be reachable every
     * time this test runs.
     * You can easily manually test if the server is still reachable and a
     * token still exists by surfing to - to check for token 97 -
     * http://widgets.vlaanderen.be/widget/live/97 and ensuring you get
     * Javascript code.
     *
     * @group themer
     */
    public function testGlobalHeaderAndFooter()
    {
        // Create a new theme.
        $this->themerOverviewPage->go();
        $this->themerOverviewPage->contextualToolbar->buttonCreateTheme->click();
        $this->themerAddPage->checkArrival();
        $this->themerAddPage->baseTheme->selectOptionByValue('vo_standard');
        $this->themerAddPage->buttonSubmit->click();
        $this->themerEditPage->checkArrival();
        $theme_name = $this->themerEditPage->getThemeName();

        // Check that the VO branding is selected by default.
        $this->assertEquals(
            $this->themerEditPage->brandingOptions->getSelected()->getValue(),
            $this->themerEditPage->brandingOptions->yesVoBranding->getValue()
        );

        // Save the theme.
        $this->moveto($this->themerEditPage->buttonSubmit);
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();

        // Enable the theme.
        $this->themerOverviewPage->theme($theme_name)->enable->click();
        $this->themerOverviewPage->checkArrival();

        // Go to the front page.
        $this->frontPage->go();

        // Verify the default global header block is present.
        $this->byClassName('vo-global-header')->byId('block-widget-block-7308abf9e3634a2e90011e6629c04d36');
        // Verify the default global footer block is present.
        $this->byClassName('vo-global-footer')->byId('block-widget-block-92bb34889dd34f71b6088af0fba9156c');

        // Fill in invalid tokens. These are strings with non hexadecimal
        // characters.
        $this->themerOverviewPage->go();
        $this->themerOverviewPage->getActiveTheme()->edit->click();
        $this->themerEditPage->checkArrival();
        $this->themerEditPage->voHeaderToken->fill('&"/(');
        $this->themerEditPage->voFooterToken->fill('%^¨');

        // Save the theme.
        $this->moveto($this->themerEditPage->buttonSubmit);
        $this->themerEditPage->buttonSubmit->click();
        $this->themerEditPage->checkArrival();

        // Verify there are 2 error messages.
        $this->assertCount(2, $this->elements($this->using('css selector')->value('#messages .messages.error li')));

        // Fill in valid non default tokens.
        $this->themerEditPage->voHeaderToken->fill('92');
        $this->themerEditPage->voFooterToken->fill('99999');

        // Save the theme.
        $this->moveto($this->themerEditPage->buttonSubmit);
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();

        // Go to the front page.
        $this->frontPage->go();

        // Verify the non default global header is present.
        $this->byClassName('vo-global-header')->byId('block-widget-block-92');
        // Verify the non existing, non default global footer is not present.
        $this->assertCount(0, $this->elements($this->using('class name')->value('vo-global-footer')));

        // Fill in valid non default hexadecimal tokens and verify no errors are
        // being thrown.
        $this->themerOverviewPage->go();
        $this->themerOverviewPage->getActiveTheme()->edit->click();
        $this->themerEditPage->checkArrival();
        $this->themerEditPage->voHeaderToken->fill('4F3A03858C8ADDEFDEB8CFACF67A1971');
        $this->themerEditPage->voFooterToken->fill('755EC94D3158B247FB387CA48FD963A7');

        // Save the theme.
        $this->moveto($this->themerEditPage->buttonSubmit);
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();

        // Go to the front page.
        $this->frontPage->go();

        // Verify the non existing, non default global header is not present.
        $this->assertCount(0, $this->elements($this->using('class name')->value('vo-global-header')));
        // Verify the non existing, non default global footer is not present.
        $this->assertCount(0, $this->elements($this->using('class name')->value('vo-global-footer')));

        // Select no branding.
        $this->themerOverviewPage->go();
        $this->themerOverviewPage->getActiveTheme()->edit->click();
        $this->themerEditPage->checkArrival();
        $this->themerEditPage->brandingOptions->noVoBranding->select();

        // Save the theme.
        $this->moveto($this->themerEditPage->buttonSubmit);
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();

        // Go to the front page.
        $this->frontPage->go();

        // Verify the global header and footer blocks are not present.
        $this->assertCount(0, $this->elements($this->using('class name')->value('vo-global-header')));
        $this->assertCount(0, $this->elements($this->using('class name')->value('vo-global-footer')));
    }

    /**
     * Tests the federal header.
     *
     * @group themer
     */
    public function testFederalHeader()
    {
        // Create a new theme and set the federal header.
        $this->themerOverviewPage->go();
        $this->themerOverviewPage->contextualToolbar->buttonCreateTheme->click();
        $this->themerAddPage->checkArrival();
        $this->themerAddPage->baseTheme->selectOptionByValue('vo_standard');
        $this->themerAddPage->buttonSubmit->click();
        $this->themerEditPage->checkArrival();
        $theme_name = $this->themerEditPage->getThemeName();
        $this->themerEditPage->brandingOptions->federalBranding->select();

        // Save the theme.
        $this->moveto($this->themerEditPage->buttonSubmit);
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();

        // Enable the theme.
        $this->themerOverviewPage->theme($theme_name)->enable->click();
        $this->themerOverviewPage->checkArrival();

        // Go to the front page.
        $this->frontPage->go();

        // Verify the federal header is present.
        try {
            $this->byCssSelector('.federal-header');
        } catch (\Exception $e) {
            $this->fail('The federal header should be shown.');
        }

        $this->assertTextPresent('Other official information and services');

        $this->appService->enableApp(new Multilingual);
        $this->frontPage->reloadPage();
        try {
            $this->byCssSelector('.federal-header .language-switcher-locale-url');
        } catch (\Exception $e) {
            $this->fail('The federal header should contian the language switcher buttons.');
        }
    }
}
