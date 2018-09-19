<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Pane\Sections\PaneSectionsTest.
 */

namespace Kanooh\Paddle\Core\Pane\Sections;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\LandingPageViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPage;
use Kanooh\Paddle\Pages\Element\Pane\CustomContentPane;
use Kanooh\Paddle\Pages\Node\ViewPage\LandingPageViewPage as FrontEndLandingPage;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CustomContentPanelsContentType;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\TestDataProvider\UrlTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;
use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;

/**
 * Tests for the pane sections of different panes.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PaneSectionsTest extends WebDriverTestCase
{
    /**
    * @var LandingPageViewPage
    */
    protected $administrativeNodeViewPage;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AssetCreationService
     */
    protected $assetCreationService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var FrontEndLandingPage
     */
    protected $frontendLandingPage;

    /**
     * @var PanelsContentPage
     */
    protected $layoutPage;

    /**
     * @var UrlTestDataProvider
     */
    protected $urlTestDataProvider;

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
        $this->administrativeNodeViewPage = new LandingPageViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->assetCreationService = new AssetCreationService($this);
        $this->frontendLandingPage = new FrontEndLandingPage($this);
        $this->layoutPage = new PanelsContentPage($this);
        $this->urlTestDataProvider = new UrlTestDataProvider();
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Login to the application first.
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * Tests if the chevron is shown in the bottom section of the pane.
     *
     * @group panes
     * @group sections
     */
    public function testBottomSectionChevron()
    {
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $basic_nid = $this->contentCreationService->createBasicPage($title);

        // Create a random landing page.
        $landing_page_nid = $this->contentCreationService->createLandingPage();
        $this->layoutPage->go($landing_page_nid);

        $content_type = new CustomContentPanelsContentType($this);
        $webdriver = $this;
        $callable = new SerializableClosure(
            function () use ($content_type, $webdriver) {
                $webdriver->moveto($content_type->bottomSection->enable->getWebdriverElement());
                $content_type->bottomSection->enable->check();
                $webdriver->waitUntilTextIsPresent('Type of the URL');
                $webdriver->moveto($content_type->bottomSection->text->getWebdriverElement());
                $content_type->bottomSection->text->fill('Bottom section text');
            }
        );

        $region = $this->layoutPage->display->getRandomRegion();
        $pane = $region->addPane($content_type, $callable);
        $pane_uuid = $pane->getUuid();

        $this->assertBottomSectionChevronNotPresent($pane_uuid);
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->frontendLandingPage->go($landing_page_nid);
        $this->assertBottomSectionChevronNotPresent($pane_uuid);

        // Check for an external url.
        $this->layoutPage->go($landing_page_nid);
        $pane->toolbar->buttonEdit->click();
        $pane->editPaneModal->waitUntilOpened();

        // Go by id. Otherwise you get "Element cannot be scrolled into
        // view".
        $this->byId('edit-bottom-section-wrapper-section-url-type-external')->click();
        $this->waitUntilTextIsPresent('External URL');
        $content_type->bottomSection->externalUrl->fill($this->urlTestDataProvider->getValidValue());
        $pane->editPaneModal->submit();
        $pane->editPaneModal->waitUntilClosed();

        // Save and preview to check in frontend if bottom chevron is present.
        $this->assertBottomSectionChevronPresent($pane_uuid);
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->frontendLandingPage->go($landing_page_nid);
        $this->assertBottomSectionChevronPresent($pane_uuid);

        // Check for an internal url.
        $this->layoutPage->go($landing_page_nid);
        $pane->toolbar->buttonEdit->click();
        $pane->editPaneModal->waitUntilOpened();

        // Go by id. Otherwise you get "Element cannot be scrolled into
        // view".
        $this->byId('edit-bottom-section-wrapper-section-url-type-internal')->click();
        $this->waitUntilTextIsPresent('Node');
        $content_type->bottomSection->internalUrl->fill($title . ' (node/' . $basic_nid . ')');
        $pane->editPaneModal->submit();
        $pane->editPaneModal->waitUntilClosed();

        $this->assertBottomSectionChevronPresent($pane_uuid);
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->frontendLandingPage->go($landing_page_nid);
        $this->assertBottomSectionChevronPresent($pane_uuid);
    }

    /**
     * Tests if the text in a section is not being double escaped.
     *
     * @group panes
     * @group sections
     */
    public function testSectionTextEscaping()
    {
        // Create a landing page.
        $landing_page_nid = $this->contentCreationService->createLandingPage();
        $this->layoutPage->go($landing_page_nid);

        $content_type = new CustomContentPanelsContentType($this);
        // String for the escaping.
        $text = 'Test & ; "';
        $callable = new SerializableClosure(
            function () use ($content_type, $text) {
                $content_type->topSection->enable->check();
                $content_type->topSection->text->fill($text);
            }
        );

        $region = $this->layoutPage->display->getRandomRegion();
        $pane = $region->addPane($content_type, $callable);

        // Check if the text is properly shown.
        $this->assertEquals($text, $pane->topSection->getText());

        // Save and preview to check in frontend if text is shown correctly.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->frontendLandingPage->go($landing_page_nid);
        $this->assertTextPresent($text);
    }

    /**
     * Tests if invalid urls are detected in the sections.
     *
     * @group panes
     */
    public function testInvalidExternalUrlInputSection()
    {
        // Create a landing page and go to the layout page.
        $nid = $this->contentCreationService->createLandingPage();
        $this->layoutPage->go($nid);

        // Add the pane to a region.
        $region = $this->layoutPage->display->getRandomRegion();

        $data_set = $this->urlTestDataProvider->getInvalidDataSet();
        foreach (array('top', 'bottom') as $section) {
            $other_section = $section == 'bottom' ? 'top' : 'bottom';
            foreach ($data_set as $url) {
                // Prepare a custom content pane.
                $content_type = new CustomContentPanelsContentType($this);
                $webdriver = $this;
                $callable = new SerializableClosure(
                    function () use ($content_type, $webdriver, $section, $other_section, $url) {
                        $other_section_name = $other_section . 'Section';
                        $webdriver->moveto($content_type->{$other_section_name}->enable->getWebdriverElement());
                        $content_type->{$other_section_name}->enable->uncheck();

                        $section_name = $section . 'Section';
                        $webdriver->moveto($content_type->{$section_name}->enable->getWebdriverElement());
                        $content_type->{$section_name}->enable->check();
                        $webdriver->waitUntilTextIsPresent('Type of the URL');
                        $webdriver->moveto($content_type->{$section_name}->text->getWebdriverElement());
                        $content_type->{$section_name}->text->fill($webdriver->alphanumericTestDataProvider->getValidValue());

                        // Go by id. Otherwise you get "Element cannot be scrolled into
                        // view".
                        $id = 'edit-' . $section .  '-section-wrapper-section-url-type-external';
                        $webdriver->byId($id)->click();
                        $webdriver->waitUntilTextIsPresent('External URL');
                        $webdriver->moveto($content_type->{$section_name}->externalUrl->getWebdriverElement());
                        $content_type->{$section_name}->externalUrl->fill($url);
                    }
                );

                // Open the Add Pane dialog.
                $region->buttonAddPane->click();
                $modal = new AddPaneModal($this);
                $modal->waitUntilOpened();

                // Select the pane type in the modal dialog.
                $modal->selectContentType($content_type);
                call_user_func($callable, $modal);
                $modal->submit();

                $this->keys(Keys::PAGEUP);


                $this->assertTextPresent('Please enter a valid URL for ' . ucfirst($section));

                $modal->close();
                $modal->waitUntilClosed();
            }
        }
    }

    /**
     * Tests if valid urls are detected in the sections.
     *
     * @group panes
     */
    public function testValidExternalUrlInputSection()
    {
        // Create a landing page and go to the layout page.
        $nid = $this->contentCreationService->createLandingPage();
        $this->layoutPage->go($nid);

        // Add the pane to a region.
        $region = $this->layoutPage->display->getRandomRegion();

        $data_set = $this->urlTestDataProvider->getValidDataSet();
        foreach (array('top', 'bottom') as $section) {
            $other_section = $section == 'bottom' ? 'top' : 'bottom';
            foreach ($data_set as $url) {
                // Prepare a custom content pane.
                $content_type = new CustomContentPanelsContentType($this);
                $webdriver = $this;
                $callable = new SerializableClosure(
                    function () use ($content_type, $webdriver, $section, $other_section, $url) {
                        $other_section_name = $other_section . 'Section';
                        $webdriver->moveto($content_type->{$other_section_name}->enable->getWebdriverElement());
                        $content_type->{$other_section_name}->enable->uncheck();

                        $section_name = $section . 'Section';
                        $webdriver->moveto($content_type->{$section_name}->enable->getWebdriverElement());
                        $content_type->{$section_name}->enable->check();
                        $webdriver->waitUntilTextIsPresent('Type of the URL');
                        $webdriver->moveto($content_type->{$section_name}->text->getWebdriverElement());
                        $content_type->{$section_name}->text->fill($webdriver->alphanumericTestDataProvider->getValidValue());

                        // Go by id. Otherwise you get "Element cannot be scrolled into
                        // view".
                        $id = 'edit-' . $section .  '-section-wrapper-section-url-type-external';
                        $webdriver->byId($id)->click();
                        $webdriver->waitUntilTextIsPresent('External URL');
                        $webdriver->moveto($content_type->{$section_name}->externalUrl->getWebdriverElement());
                        $content_type->{$section_name}->externalUrl->fill($url);
                    }
                );

                // Open the Add Pane dialog.
                $region->buttonAddPane->click();
                $modal = new AddPaneModal($this);
                $modal->waitUntilOpened();

                // Select the pane type in the modal dialog.
                $modal->selectContentType($content_type);
                call_user_func($callable, $modal);
                $modal->submit();
                $modal->waitUntilClosed();
            }
        }
    }

    /**
     * Tests the functioning of the top section icon.
     *
     * @group panes
     */
    public function testTopSectionIcon()
    {
        // Create an image atom to use in the test.
        $data = array(
          'path' => dirname(__FILE__) . '/../../../assets/sample_image.jpg'
        );
        $atom = $this->assetCreationService->createImage($data);
        $atom_id = $atom['id'];

        // Create a landing page and go to the layout page.
        $nid = $this->contentCreationService->createLandingPage();
        $this->layoutPage->go($nid);

        // Add the pane to a region.
        $region = $this->layoutPage->display->getRandomRegion();
        $content_type = new CustomContentPanelsContentType($this);

        $top_text = $this->alphanumericTestDataProvider->getValidValue();
        $callable = new SerializableClosure(
            function () use ($content_type, $top_text, $atom_id) {
                $content_type->topSection->enable->check();
                $content_type->topSection->text->fill($top_text);
                $content_type->topSection->icon->selectAtom($atom_id);
            }
        );
        $pane = $region->addPane($content_type, $callable);
        $pane_uuid = $pane->getUuid();

        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Make sure that both icon and title are displayed.
        $pane = new CustomContentPane($this, $pane_uuid, '//div[@data-pane-uuid="' . $pane_uuid . '"]');
        $this->assertEquals($top_text, $pane->topSection->text);
        $this->assertNotNull($pane->topSection->icon);

        //Check if the image has been resized
        $this->assertEquals($pane->topSection->icon->attribute('width'), 45);
    }

    /**
     * Asserts if the chevron is present in the bottom section of a pane.
     *
     * @param string $uuid
     *   The uuid of the pane.
     */
    protected function assertBottomSectionChevronPresent($uuid)
    {
        // Make sure the pane is there before looking for the chevron.
        $xpath = '//div[@data-pane-uuid="' . $uuid . '"]';
        $this->waitUntilElementIsPresent($xpath);

        $xpath .= '//div[@class="pane-section-bottom"]//a//i[@class="fa fa-chevron-right"]';
        $chevron = $this->element($this->using('xpath')->value($xpath));
        $this->assertTrue($chevron->displayed());
    }

    /**
     * Asserts if the chevron is not present in the bottom section of a pane.
     *
     * @param string $uuid
     *   The uuid of the pane.
     */
    protected function assertBottomSectionChevronNotPresent($uuid)
    {
        $elements = $this->elements($this->using('xpath')->value('//div[@data-pane-uuid="' . $uuid . '"]//div[@class="pane-section-bottom"]//a//i[@class="fa fa-chevron-right"]'));
        $this->assertTrue(count($elements) == 0);
    }
}
