<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\SocialMedia\SocialMediaTest.
 */

namespace Kanooh\Paddle\App\SocialMedia;

use Drupal\Component\Utility\Random;
use Kanooh\Paddle\Apps\News;
use Kanooh\Paddle\Apps\SocialMedia;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleSocialMedia\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Element\SocialMedia\ShareWidget\ShareWidgetButton;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditLandingPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as FrontEndViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\DrupalService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs tests on the Paddle Social Media Paddlet.
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class SocialMediaTest extends WebDriverTestCase
{

    /**
     * The administrative node view.
     *
     * @var ViewPage
     */
    protected $adminViewPage;

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
     * Node front-end view page.
     *
     * @var FrontEndViewPage
     */
    protected $frontEndViewPage;

    /**
     * The node edit page.
     *
     * @var EditPage
     */
    protected $nodeEditPage;

    /**
     * The random data generation class.
     *
     * @var Random $random
     */
    protected $random;

    /**
     * The configuration page the Social Media paddlet.
     *
     * @var ConfigurePage
     */
    protected $configurationPage;

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
        $this->adminViewPage = new ViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->frontEndViewPage = new FrontEndViewPage($this);
        $this->nodeEditPage = new EditPage($this);
        $this->configurationPage = new ConfigurePage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Go to the login page and log in as Chief Editor.
        $this->userSessionService->login('ChiefEditor');

        // Bootstrap Drupal.
        $drupal = new DrupalService();
        $drupal->bootstrap($this);

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new SocialMedia);
    }

    /**
     * Tests the configuration form of the Social Media widget.
     *
     * @group socialMedia
     */
    public function testConfigurationFormDefaultSettings()
    {
        // Reset the form by deleting the variables holding the settings.
        variable_del('paddle_social_media_content_types');
        variable_del('paddle_social_media_networks');

        $this->configurationPage->go();

        // Check that the default settings are used.
        $configureForm = $this->configurationPage->configureForm;
        $this->assertTrue($configureForm->getContentTypeCheckboxByName('basic_page')->isChecked());
        $this->assertFalse($configureForm->getContentTypeCheckboxByName('landing_page')->isChecked());
        $this->assertFalse($configureForm->getContentTypeCheckboxByName('paddle_overview_page')->isChecked());
        $this->assertTrue($configureForm->getSocialCheckboxByName('facebook')->isChecked());
        $this->assertTrue($configureForm->getSocialCheckboxByName('twitter')->isChecked());
        $networks = paddle_social_media_available_networks();
        unset($networks['facebook']);
        unset($networks['twitter']);
        foreach (array_keys($networks) as $name) {
            $this->assertFalse($configureForm->getSocialCheckboxByName($name)->isChecked());
        }
    }

    /**
     * Tests that the default settings are handled when enabling the paddlet.
     *
     * @group socialMedia
     */
    public function testDefaultSettings()
    {
        // Delete the current configuration.
        variable_del('paddle_social_media_content_types');
        variable_del('paddle_social_media_networks');

        // Disable and re-enable the app.
        $app = new SocialMedia;
        $this->appService->disableAppsByMachineNames(array($app->getModuleName()));
        $this->appService->enableApp($app);

        // Create a basic page.
        $nid = $this->contentCreationService->createBasicPage();
        $this->addNodeBody($nid);

        // Go to the front-end page of the node.
        $this->frontEndViewPage->go($nid);

        // Verify that only the expected buttons are shown.
        $expected = array('facebook', 'twitter');
        $this->assertShareWidgetNetworkButtons($expected);

        // Create a landing page.
        $nid = $this->contentCreationService->createLandingPage();
        $this->addNodeBody($nid);

        // Go to the front-end page of the node.
        $this->frontEndViewPage->go($nid);

        // Verify that the widget is not shown.
        try {
            $this->frontEndViewPage->shareWidget;
            $this->fail('The share widget should not be shown.');
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            // Everything is fine.
        }
    }

    /**
     * Tests the buttons of the Social Media widget.
     *
     * @group socialMedia
     */
    public function testShareButtons()
    {
        // Create a node.
        $nid = $this->contentCreationService->createBasicPage();
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->body->setBodyText($this->alphanumericTestDataProvider->getValidValue());
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();

        // Go to the configuration page and enable the checkbox for this content
        // type.
        $this->configurationPage->go();
        $this->configurationPage->configureForm->getContentTypeCheckboxByName('basic_page')->check();
        $this->configurationPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The configuration options have been saved.');

        $networks = array_keys(paddle_social_media_available_networks());

        // First disable all the networks to make sure the widget is not visible.
        foreach ($networks as $name) {
            $this->configurationPage->configureForm->getSocialCheckboxByName($name)->uncheck();
        }
        $this->configurationPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The configuration options have been saved.');

        // Go to the front-end page of the node.
        $this->frontEndViewPage->go($nid);

        // Check that the share buttons are not there.
        try {
            $this->frontEndViewPage->shareWidget;
            $this->fail('The share widget should not be shown.');
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            // Everything is fine.
        }

        // Check visibility one by one.
        foreach ($networks as $name) {
            $this->configurationPage->go();

            // Enable the current network.
            $this->configurationPage->configureForm->getSocialCheckboxByName($name)->check();
            $this->configurationPage->contextualToolbar->buttonSave->click();
            $this->waitUntilTextIsPresent('The configuration options have been saved.');

            // Go to the frontpage.
            $this->frontEndViewPage->go($nid);

            // Verify that only the expected buttons are shown.
            $this->assertShareWidgetNetworkButtons(array($name));

            // Disable the current network.
            $this->configurationPage->go();
            $this->configurationPage->configureForm->getSocialCheckboxByName($name)->uncheck();
            $this->configurationPage->contextualToolbar->buttonSave->click();
            $this->waitUntilTextIsPresent('The configuration options have been saved.');
        }

        // Now enable 3 networks.
        $this->configurationPage->go();
        $visible = array('facebook', 'twitter', 'google_plusone_share');
        foreach ($visible as $name) {
            $this->configurationPage->configureForm->getSocialCheckboxByName($name)->check();
        }
        $this->configurationPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The configuration options have been saved.');

        // Go to the frontpage.
        $this->frontEndViewPage->go($nid);

        // Verify that only the expected buttons are shown.
        $this->assertShareWidgetNetworkButtons($visible);

        // Enable one additional network. Picking Linkedin so the Google+ one
        // will be rendered in the dropdown.
        $this->configurationPage->go();
        $this->configurationPage->configureForm->getSocialCheckboxByName('linkedin')->check();
        $this->configurationPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The configuration options have been saved.');

        // Go to the frontpage.
        $this->frontEndViewPage->go($nid);

        // Verify that the expected buttons are there.
        $visible = array('facebook', 'twitter');
        $in_dropdown = array('linkedin', 'google_plusone_share');
        $this->assertShareWidgetNetworkButtons($visible, $in_dropdown);

        // Enable all the networks now.
        $this->configurationPage->go();
        foreach ($networks as $name) {
            $this->configurationPage->configureForm->getSocialCheckboxByName($name)->check();
        }
        $this->configurationPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The configuration options have been saved.');

        // Go to the frontpage again.
        $this->frontEndViewPage->go($nid);

        //Verify that the buttons are on the correct location on the Basic Page
        $xpath = '//div[contains(@class, "node-basic-page")]/div[@class="content"]/div[@class="paddle-social-media-share"]';
        $this->waitUntilElementIsPresent($xpath);

        // Verify that all the networks are in place.
        $in_dropdown = array_values(array_diff($networks, $visible));
        $this->assertShareWidgetNetworkButtons($visible, $in_dropdown);

        // Open the dropdown and verify that all the labels are shown.
        $this->moveto($this->frontEndViewPage->shareWidget->dropdown->toggle);
        $dropdown_buttons = $this->frontEndViewPage->shareWidget->dropdown->shareButtons;
        $network_labels = paddle_social_media_available_networks();
        foreach ($dropdown_buttons as $key => $button) {
            /* @var ShareWidgetButton $button */
            // Verify that the element is visible.
            $this->assertTrue(
                $button->getWebdriverElement()->displayed(),
                "The button {$button->name} is not visible."
            );
            // Verify that the text is there.
            $this->assertEquals($network_labels[$button->name], $button->text);
        }
    }

    /**
     * Tests the location of the buttons of the Social Media widget on
     * an Overview page.
     *
     * @group socialMedia
     */
    public function testShareButtonsLocationOnOverviewPage()
    {
        // Create a custom Overview page.
        $nid = $this->contentCreationService->createOverviewPage();
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->body->setBodyText($this->alphanumericTestDataProvider->getValidValue());
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();

        // Go to the configuration page and enable the checkbox for this content
        // type.
        $this->configurationPage->go();
        $this->configurationPage->configureForm->getContentTypeCheckboxByName('paddle_overview_page')->check();
        $this->configurationPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The configuration options have been saved.');

        // Go to the frontpage.
        $this->frontEndViewPage->go($nid);

        //Verify that the buttons are secluded from the content section
        $xpath_old = '//div[contains(@class, "node-paddle-overview-page")]/div[@class="content"]/div[@class="paddle-social-media-share"]';
        $this->waitUntilElementIsNoLongerPresent($xpath_old);

        //Verify the new location of the buttons
        $xpath_new = '//div[contains(@class, "region-content")]/div[@class="paddle-social-media-share"]';
        $this->waitUntilElementIsPresent($xpath_new);
    }

    /**
     * Verifies that the expected list of buttons is shown.
     *
     * @param array $visible
     *   The list of network names for which the buttons should be visible.
     * @param null|array $in_dropdown
     *   An optional array of networks that should be rendered in the dropdown.
     *   If null, it will be asserted that the dropdown is not there.
     */
    protected function assertShareWidgetNetworkButtons($visible = array(), $in_dropdown = null)
    {
        // Retrieve the list of available buttons.
        $buttons = $this->frontEndViewPage->shareWidget->shareButtons;
        // Verify that only the expected buttons are shown.
        $expected = array_merge(array('email', 'print'), $visible);
        $this->assertEquals($expected, array_keys($buttons));

        if ($in_dropdown) {
            $dropdown_buttons = $this->frontEndViewPage->shareWidget->dropdown->shareButtons;
            // This comparison asserts also that the buttons are shown in the
            // same order.
            $this->assertEquals($in_dropdown, array_keys($dropdown_buttons));
        } else {
            try {
                $this->frontEndViewPage->shareWidget->dropdown;
                $this->fail('The dropdown should not be rendered.');
            } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
                // Everything is fine.
            }
        }
    }

    /**
     * Add some body text to a node.
     *
     * @param int $nid
     *   The node id of the node to add the body to.
     */
    protected function addNodeBody($nid)
    {
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->body->setBodyText($this->alphanumericTestDataProvider->getValidValue());
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();
    }
}
