<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\MailChimp\NewsletterTest.
 */

namespace Kanooh\Paddle\App\MailChimp;

use Kanooh\Paddle\Apps\MailChimp;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage as AddContentPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\CreateNewsletterModal;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\NewsletterLayoutPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\NewsletterViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Element\MailChimp\SendNewsletterModal;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CustomContentPanelsContentType;
use Kanooh\Paddle\Pages\Node\EditPage\NewsletterPage as EditNewsletterPage;
use Kanooh\Paddle\Pages\Node\ViewPage\NewsletterViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\MailChimpService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs tests on the Paddle MailChimp Paddlet.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NewsletterTest extends WebDriverTestCase
{
    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * The 'Add content' page.
     *
     * @var AddContentPage
     */
    protected $addContentPage;

    /**
     * The 'Create newsletter' modal.
     *
     * @var CreateNewsletterModal
     */
    protected $createNewsletterModal;

    /**
     * The administrative node view page.
     *
     * @var AdministrativeNodeViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * Front end view page of the newsletter.
     *
     * @var NewsletterViewPage
     */
    protected $newsletterViewPage;

    /**
     * The newsletter edit page.
     *
     * @var EditNewsletterPage
     */
    protected $editNewsletterPage;

    /**
     * Newsletter layout page.
     *
     * @var NewsletterLayoutPage
     */
    protected $layoutPage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var MailChimpService
     */
    protected $mailChimpService;

    /**
     * Test data provider for alphanumeric data.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Create some instances to use later on.
        $this->addContentPage = new AddContentPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->createNewsletterModal = new CreateNewsletterModal($this);
        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->editNewsletterPage = new EditNewsletterPage($this);
        $this->layoutPage = new NewsletterLayoutPage($this);
        $this->newsletterViewPage = new NewsletterViewPage($this);

        // Go to the login page and log in as Chief Editor.
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->mailChimpService = new MailChimpService($this, getenv('mailchimp_api_key'));
        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new MailChimp);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        // Clean up all the content that has been created by this test run.
        $this->contentCreationService->cleanUp($this);
        // Clean up old sent MailChimp campaigns because those couldn't be
        // deleted immediately.
        $this->mailChimpService->cleanupSentCampaigns(false);

        parent::tearDown();
    }

    /**
     * Tests the creation of a newsletter node.
     *
     * @group editing
     */
    public function testCreate()
    {
        // Remove the MailChimp api key so we can test that the node creation
        // is forbidden.
        $this->mailChimpService->deleteMailChimpApiKey();

        // Always work with empty caches, so we force requests to MailChimp for
        // getting lists.
        cache_clear_all('lists', 'cache_mailchimp');

        // Go to admin/content_manager/add.
        $this->addContentPage->go();
        // Click on "Newsletter".
        $this->addContentPage->links->linkNewsletter->click();

        // The modal is meant to be shown without any fields and submit button
        // as the api key is not set. So we cannot use waitUntilOpened() as
        // the waitUntilElementIsDisplayed() method would not work, not even
        // with disabled inputs.
        $this->waitUntilTextIsPresent('Before creating newsletters, you need to enter your MailChimp API key');
        $this->assertModalFields(false);

        $this->createNewsletterModal->close();
        $this->createNewsletterModal->waitUntilClosed();

        // Set back the key and try again.
        $this->mailChimpService->setMailChimpApiKey(getenv('mailchimp_api_key'));
        $this->addContentPage->links->linkNewsletter->click();
        $this->createNewsletterModal->waitUntilOpened();
        $this->assertTextPresent('information modal dialog');
        $this->assertModalFields(true);
        $this->createNewsletterModal->close();
        $this->createNewsletterModal->waitUntilClosed();

        // This part of the test does not work if cache bin cache_mailchimp
        // uses the MemCache caching backend.
        // https://www.drupal.org/files/issues/memcache-n2543030-15.patch fixes
        // this but causes other things to fail.
        // @see https://www.drupal.org/node/2543030
        if (get_class(_cache_get_object('cache_mailchimp')) !== 'MemCacheDrupal') {
            // Fake the case of our MailChimp account not having any lists
            // created.
            // @todo this should be done with a mock class.
            cache_set('lists', array(), 'cache_mailchimp', CACHE_TEMPORARY);

            // Verify that the modal shows the no lists message.
            $this->addContentPage->links->linkNewsletter->click();
            $this->waitUntilTextIsPresent('Before creating newsletters, you need to create at least one list');
            $this->assertModalFields(false);
            $this->createNewsletterModal->close();
            $this->createNewsletterModal->waitUntilClosed();
        }

        // Clear the cache so the api is called to retrieve actual lists.
        cache_clear_all('lists', 'cache_mailchimp');

        // Verify that now all fields are shown.
        $this->addContentPage->links->linkNewsletter->click();
        $this->createNewsletterModal->waitUntilOpened();
        $this->assertTextPresent('information modal dialog');
        $this->assertModalFields(true);

        // Don't fill in required fields.
        $this->createNewsletterModal->submit();
        // Ensure we get 4 error messages.
        $this->waitUntilElementIsPresent('//div[contains(@class, "messages")]');
        $elements = $this->elements(
            $this->using('xpath')->value(
                $this->createNewsletterModal->getXPathSelector() .
                '//div[@class="messages error"]/ul/li'
            )
        );
        $this->assertEquals(4, count($elements));
        $this->createNewsletterModal->close();
        $this->createNewsletterModal->waitUntilClosed();

        // Prepare some default values.
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $from_name = $this->alphanumericTestDataProvider->getValidValue();
        $from_email = 'mytest@kanooh.be';

        // Create a newsletter via UI.
        $nid = $this->contentCreationService->createNewsletterViaUI($title, 'listTwo', $from_name, $from_email);

        // Wait until we see confirmation that the node has been created.
        $this->waitUntilElementIsPresent('//div[@id="messages"]');
        // Verify that the newsletter has been created.
        $this->assertTextPresent("Newsletter $title has been created.");

        // Go to the frontend and check that the campaign id field is not shown.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->newsletterViewPage->checkArrival();
        $this->assertCampaignFieldNotShown();

        // Click the "Page properties" button in the contextual toolbar.
        $this->newsletterViewPage->previewToolbar->closeButton->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageProperties->click();

        // Then I see the edit form of the newsletter.
        $this->editNewsletterPage->checkArrival();

        // Compare the values against MailChimp.
        $this->assertCampaignData($nid, $title, 'listTwo', $from_name, $from_email);

        // Make some changes to our campaign info.
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $from_name = $this->alphanumericTestDataProvider->getValidValue();
        $from_email = 'myothertest@kanooh.be';

        $this->editNewsletterPage->newsletterForm->title->fill($title);
        $this->editNewsletterPage->newsletterForm->fromName->fill($from_name);
        $this->editNewsletterPage->newsletterForm->fromEmail->fill($from_email);
        $this->editNewsletterPage->newsletterForm->listId->listOne->select();

        // Save the content and go back to the edit page as it's the only place
        // where we can have access to the campaign values.
        $this->editNewsletterPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageProperties->click();
        $this->editNewsletterPage->checkArrival();

        // Finally verify the data.
        $this->assertCampaignData($nid, $title, 'listOne', $from_name, $from_email);

        $this->editNewsletterPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
    }

    /**
     * Test that allowed panes are limited.
     */
    public function testAllowedPanes()
    {
        $nid = $this->contentCreationService->createNewsletter();
        $this->administrativeNodeViewPage->go($nid);

        $this->administrativeNodeViewPage->contextualToolbar->buttonPageLayout->click();
        $this->layoutPage->checkArrival();

        // Select a random region to open the add content modal.
        $region = $this->layoutPage->display->getRandomRegion();
        $region->buttonAddPane->click();
        $modal = new AddPaneModal($this);
        $modal->waitUntilOpened();
        $this->waitUntilTextIsPresent('Add new pane');

        // Class list of the panes allowed in a newsletter.
        $allowed_panes = array(
            'node-content',
            'add-image',
            'download-list',
            'listing',
            'free-content',
            'add-menu-structure',
        );

        // Check how many panes are available.
        $base_xpath = '//ul[contains(@class, "paddle-add-pane-list")]//li';
        $elements = $this->elements($this->using('xpath')->value($base_xpath));
        $this->assertEquals(count($allowed_panes), count($elements));

        // Check that those panes are the correct ones.
        foreach ($allowed_panes as $pane_class) {
            $xpath = $base_xpath . '[contains(@class, "' . $pane_class . '")]';
            try {
                $this->element($this->using('xpath')->value($xpath));
            } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
                $this->fail("$pane_class pane not found.");
            }
        }

        // Close modal and exit.
        $modal->close();
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
    }

    /**
     * Test if the "Change layout" functionality works properly.
     *
     * The change is applied and it is visible immediately.
     *
     * @group panes
     */
    public function testChangeLayout()
    {
        // Create a newsletter and go to the page layout.
        $nid = $this->contentCreationService->createNewsletter();
        $this->layoutPage->go($nid);

        // Get the current layout and supported layouts.
        $curr_layout = $this->layoutPage->display->getCurrentLayoutId();
        $allowed_layouts = $this->layoutPage->display->getSupportedLayouts();

        // Unset the current layout.
        unset($allowed_layouts[$curr_layout]);
        $random_layout = array_rand($allowed_layouts);

        // Change the layout.
        $this->layoutPage->changeLayout($random_layout);

        // Check that the correct layout is displayed.
        $ipe_placeholders_xpath = '//div[contains(@class, "panels-ipe-display-container")]' .
          '//div[contains(@class, "panels-ipe-placeholder")]';
        $this->waitUntilElementIsDisplayed($ipe_placeholders_xpath);
        $ipe_placeholders = $this->elements($this->using('xpath')->value($ipe_placeholders_xpath));

        /** @var \PHPUnit_Extensions_Selenium2TestCase_Element $ipe_placeholder */
        foreach ($ipe_placeholders as $ipe_placeholder) {
            $this->assertTrue($ipe_placeholder->displayed());
        }

        $ipe_containers_xpath = '//div[contains(@class, "panels-ipe-display-container")]' .
          '//div[contains(@class, "paddle-layout-' . $random_layout . '")]';
        $this->waitUntilElementIsDisplayed($ipe_containers_xpath);
        $ipe_containers = $this->elements($this->using('xpath')->value($ipe_containers_xpath));

        /** @var \PHPUnit_Extensions_Selenium2TestCase_Element $ipe_container */
        foreach ($ipe_containers as $ipe_container) {
            $this->assertTrue($ipe_container->displayed());
        }

        // Save the page so that subsequent tests are not greeted by an alert.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
    }

    /**
     * Helper method to assert that all fields are either shown or not.
     *
     * @param bool $presence
     *   True to check if elements are present.
     */
    public function assertModalFields($presence = true)
    {
        // As we will use empty(), if we are testing presence of elements
        // we must assert againt false and viceversa.
        $method = $presence ? 'assertFalse' : 'assertTrue';

        $field_xpaths = array(
            '//input[@name="title"]',
            '//label[contains(text(), "List One")]',
            '//label[contains(text(), "List Two")]',
            '//input[@name="field_paddle_mailchimp_cid[campaign_data][from_name]"]',
            '//input[@name="field_paddle_mailchimp_cid[campaign_data][from_email]"]',
        );

        foreach ($field_xpaths as $xpath) {
            $elements = $this->elements($this->using('xpath')->value($xpath));
            $this->{$method}(empty($elements), $xpath);
        }
    }

    /**
     * Assert that the mailchimp campaign id field is not shown on frontend.
     */
    public function assertCampaignFieldNotShown()
    {
        $xpath = '//div[contains(@class, "field field-name-field-paddle-mailchimp-cid")]';
        $elements = $this->elements($this->using('xpath')->value($xpath));
        $this->assertTrue(empty($elements));
    }

    /**
     * Check campaign data against MailChimp.
     *
     * @param int $nid
     *   The node ID to load. Cache will always be bypassed.
     *
     * @param string $title
     *   The title to check.
     *
     * @param string $list_name
     *   The name of the radio button provided by NewsletterRadioButtons class.
     *
     * @param string $from_name
     *   The from name to check against.
     *
     * @param string $from_email
     *   The from email to check against.
     */
    public function assertCampaignData($nid, $title, $list_name, $from_name, $from_email)
    {
        $this->assertEquals($from_name, $this->editNewsletterPage->newsletterForm->fromName->getContent());
        $this->assertEquals($from_email, $this->editNewsletterPage->newsletterForm->fromEmail->getContent());

        // Verify the radios.
        $other_radio_name = ('listOne' === $list_name) ? 'listTwo' : 'listOne';
        $this->assertTrue($this->editNewsletterPage->newsletterForm->listId->{$list_name}->isSelected());
        $this->assertFalse($this->editNewsletterPage->newsletterForm->listId->{$other_radio_name}->isSelected());

        // Load the node, bypassing any cache.
        $node = node_load($nid, null, true);

        // Fetch the field value and the campaign info from MailChimp.
        $campaign_id = $this->mailChimpService->getCampaignIdFromNewsletter($node);
        $campaign = $this->mailChimpService->getCampaignInfo($campaign_id);

        // Verify values.
        $this->assertEquals($title, $campaign['subject']);
        $this->assertEquals($from_name, $campaign['from_name']);
        $this->assertEquals($from_email, $campaign['from_email']);
        $this->assertEquals(
            $this->editNewsletterPage->newsletterForm->listId->{$list_name}->getValue(),
            $campaign['list_id']
        );
    }

    /**
     * Tests the sending of newsletters in test emails.
     *
     * @group sendNewsletter
     * @group mailchimp
     */
    public function testSendingTestEmails()
    {
        // Set the MailChimp API key.
        $this->mailChimpService->setMailChimpApiKey(getenv('mailchimp_api_key'));

        // Create a newsletter node.
        $nid = $this->contentCreationService->createNewsletterViaUI();

        // The relative links will be transformed into absolute when
        // preparing the html content, so prepare the absolute urls.
        $example_url = url('some_url', array('absolute' => true, 'alias' => false));

        // Add some content to the page starting with the body. The html contains
        // some html tags which should be stripped before being send in an email.
        $body_html = '<p><strong><em>SOMETEXT</em></strong></p><ul><li>gsdgs</li><li>sgs</li><li>gsg</li></ul><div class="playable-video"><video class="playable-video" controls="controls" height="480" poster="poster_image.jpg" preload="none" style="width:100%;height:100%;" width="640"><source src="https://www.youtube.com/watch?v=CTmxDQ4y66k" type="video/youtube"/></video></div><p><img alt="ddd" src="some_image.jpg" title=""/></p><p><a href="some_url"><span>LINK</span></a></p><blockquote>QUOTE</blockquote><ol><li>djdk</li><li>dsjd</li></ol><h2>H2</h2><h3>H3</h3><h4>H4</h4><h5>H5</h5>';
        $expected_body_html = '<p><strong><em>SOMETEXT</em></strong></p><ul><li>gsdgs</li><li>sgs</li><li>gsg</li></ul><div class="playable-video"></div><img alt="ddd" src="some_image.jpg" title=""><p><a href="' . $example_url . '"><span>LINK</span></a></p><blockquote>QUOTE</blockquote><ol><li>djdk</li><li>dsjd</li></ol><h2>H2</h2><h3>H3</h3><h4>H4</h4><h5>H5</h5>';
        $this->editNewsletterPage->go($nid);
        $this->editNewsletterPage->body->waitUntilReady();
        $this->editNewsletterPage->body->buttonSource->click();
        $this->editNewsletterPage->body->setBodyText($body_html);
        $this->editNewsletterPage->contextualToolbar->buttonSave->click();

        // Add a custom content pane.
        $pane_html = '<p><strong><em>PANETEXT</em></strong></p><ul><li>aaaa</li><li>bbb</li><li>ccc</li></ul><div class="playable-video"><video class="playable-video" controls="controls" height="480" poster="poster_image.jpg" preload="none" style="width:100%;height:100%;" width="640"><source src="https://www.youtube.com/watch?v=CTmxDQ4y66k" type="video/youtube"/></video></div><p><img alt="ddd" src="some_image.jpg" title=""/></p><p><a href="some_url"><span>LINK</span></a></p><blockquote>QUOTE</blockquote><ol><li>djdk</li><li>lpl</li></ol><h2>J2</h2><h3>J3</h3><h4>J4</h4><h5>J5</h5>';
        $expected_pane_html = '<p><strong><em>PANETEXT</em></strong></p><ul><li>aaaa</li><li>bbb</li><li>ccc</li></ul><div class="playable-video"></div><img alt="ddd" src="some_image.jpg" title=""><p><a href="' . $example_url . '"><span>LINK</span></a></p><blockquote>QUOTE</blockquote><ol><li>djdk</li><li>lpl</li></ol><h2>J2</h2><h3>J3</h3><h4>J4</h4><h5>J5</h5>';
        $this->administrativeNodeViewPage->checkArrival();
        $this->layoutPage->go($nid);
        $content_type = new CustomContentPanelsContentType($this);
        $content_type->body = $pane_html;
        $region = $this->layoutPage->display->getRandomRegion();
        $this->layoutPage->display->waitUntilEditorIsLoaded();
        $region->addPane($content_type);
        $this->waitUntilTextIsPresent('PANETEXT');
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Test what the email HTML generated for this node is. It should
        // contain the expected node body HTML and the pane HTML. Some tags will
        // be stripped on HTMLPurifier level others by
        // paddle_mailchimp_generate_mail_html_for_node(). Avoid the CSS inlining
        // as it will make it very hard to verify the texts are there.
        // Test this before sending tests emails as we might have run out of
        // API calls for the sendTest service and the test might fail there.
        // Also, this prevents wasting sending tests if we have any failure
        // on rendered html.
        $email_html = preg_replace('/\s+/', '', paddle_mailchimp_generate_mail_html_for_node(node_load($nid), false));
        $this->assertTrue(strpos($email_html, preg_replace('/\s+/', '', $expected_body_html)) !== false);
        $this->assertTrue(strpos($email_html, preg_replace('/\s+/', '', $expected_pane_html)) !== false);

        // Define some valid emails. Domains are verified by MailChimp so use
        // 'kanooh.be'.
        $emails = array('first.email@kanooh.be', 'second.email@kanooh.be', 'third.email@kanooh.be');

        // First send the email with one email address only.
        $this->administrativeNodeViewPage->checkArrival();
        $this->sendTestEmail(array($emails[0]));

        // Now send test email to multiple e-mails.
        $this->administrativeNodeViewPage->checkArrival();
        $this->sendTestEmail($emails);
    }

    /**
     * Sends a test email using the interface.
     *
     * @param  array $emails
     *   Array of emails to send the test email to.
     */
    public function sendTestEmail($emails)
    {
        $this->administrativeNodeViewPage->contextualToolbar->buttonSendTestEmail->click();

        $modal = new SendNewsletterModal($this);
        $modal->waitUntilOpened();

        // Enter a valid email(s) but separate them by ";" to test if they will be recognized.
        $value = implode(';', $emails);
        $modal->form->emails->fill($value);
        $modal->form->sendButton->click();
        $modal->waitUntilClosed();
        $this->administrativeNodeViewPage->checkArrival();
        $this->waitUntilTextIsPresent('Test e-mails successfully sent to the following e-mail(s): ' . implode(', ', $emails));
    }


    /**
     * Tests the sending of newsletters as a campaign.
     *
     * @group sendNewsletter
     */
    public function testSendingNewsletterCampaign()
    {
        // Set the MailChimp API key.
        $this->mailChimpService->setMailChimpApiKey(getenv('mailchimp_api_key'));

        // Create a newsletter node.
        $nid = $this->contentCreationService->createNewsletterViaUI();
        $node = node_load($nid);
        $this->assertTrue(!empty($node->field_paddle_mailchimp_cid[LANGUAGE_NONE][0]['value']));
        // Add some content to the page so that the campaign has content.
        $this->editNewsletterPage->go($nid);
        $this->editNewsletterPage->body->waitUntilReady();
        $this->editNewsletterPage->body->setBodyText('abc');
        $this->editNewsletterPage->contextualToolbar->buttonSave->click();

        // Send the campaign.
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonSendCampaign->click();
        $modal = new SendNewsletterModal($this);
        $modal->waitUntilOpened();
        $this->assertTrue($modal->form->sendModeNow->isSelected());
        $modal->form->sendButton->click();
        $modal->waitUntilClosed();

        // Check that the newsletter was sent.
        $this->administrativeNodeViewPage->checkArrival();
        $this->waitUntilTextIsPresent('Newsletter successfully sent!');
        $this->administrativeNodeViewPage->contextualToolbar->checkButtonsNotPresent(array('SendCampaign'));
    }

    /**
     * Tests the scheduling of campaigns.
     *
     * @group sendNewsletter
     */
    public function testSchedulingCampaign()
    {
        // Set the MailChimp API key.
        $this->mailChimpService->setMailChimpApiKey(getenv('mailchimp_api_key'));

        // Create a newsletter node.
        $nid = $this->contentCreationService->createNewsletterViaUI();

        // Add some content to the page so that the campaign has content.
        $this->editNewsletterPage->go($nid);
        $this->editNewsletterPage->body->waitUntilReady();
        $this->editNewsletterPage->body->setBodyText('abc');
        $this->editNewsletterPage->contextualToolbar->buttonSave->click();

        // Schedule the campaign.
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonSendCampaign->click();
        $modal = new SendNewsletterModal($this);
        $modal->waitUntilOpened();
        $this->assertTrue($modal->form->sendModeNow->isSelected());
        $modal->form->sendModeLater->select();
        $timestamp = time() + 60*60*24;
        $modal->form->sendTimeDate->fill(date('d/m/Y', $timestamp));
        // We change only the date and leave the time unchanged. Get the time
        // to avoid timezone differences.
        $time = $modal->form->sendTimeTime->getContent();
        $modal->form->sendButton->click();
        $modal->waitUntilClosed();

        // Check that the newsletter was scheduled.
        $this->administrativeNodeViewPage->checkArrival();
        $date = date(paddle_mailchimp_maichimp_date_format(false, true), $timestamp);
        $this->waitUntilTextIsPresent("Newsletter successfully scheduled for $date $time!");
        // The "Send" button should still be there.
        $this->administrativeNodeViewPage->contextualToolbar->checkButtons(array('SendCampaign'));

        // Check that we can reschedule it.
        $this->administrativeNodeViewPage->contextualToolbar->buttonSendCampaign->click();
        $modal = new SendNewsletterModal($this);
        $modal->waitUntilOpened();
        $this->assertTrue($modal->form->sendModeLater->isSelected());

        $this->assertEquals($modal->form->sendTimeDate->getContent(), date('d/m/Y', $timestamp));
        $new_timestamp = time() + 60*60*48;
        $modal->form->sendTimeDate->fill(date('d/m/Y', $new_timestamp));
        $modal->form->sendButton->click();
        $modal->waitUntilClosed();

        // Check that the newsletter was re-scheduled.
        $this->administrativeNodeViewPage->checkArrival();
        $date = date(paddle_mailchimp_maichimp_date_format(false, true), $new_timestamp);
        $this->waitUntilTextIsPresent("Newsletter successfully scheduled for $date $time!");

        // Now try to send it. It should be possible.
        $this->administrativeNodeViewPage->contextualToolbar->buttonSendCampaign->click();
        $modal = new SendNewsletterModal($this);
        $modal->waitUntilOpened();
        $modal->form->sendModeNow->select();
        $modal->form->sendButton->click();
        $modal->waitUntilClosed();

        // Check that the newsletter was sent.
        $this->administrativeNodeViewPage->checkArrival();
        $this->waitUntilTextIsPresent('Newsletter successfully sent!');
        $this->administrativeNodeViewPage->contextualToolbar->checkButtonsNotPresent(array('SendCampaign'));
    }

    /**
     * Tests newsletter nodes with empty body fields.
     *
     * @see https://one-agency.atlassian.net/browse/KANWEBS-3415
     */
    public function testEmptyBodyNewsletters()
    {
        // Set the MailChimp API key.
        $this->mailChimpService->setMailChimpApiKey(getenv('mailchimp_api_key'));

        // Create a newsletter node.
        $nid = $this->contentCreationService->createNewsletterViaUI();

        // Set an empty body so the node will be saved with an empty body array.
        $this->editNewsletterPage->go($nid);
        $this->editNewsletterPage->body->setBodyText('');
        $this->editNewsletterPage->contextualToolbar->buttonSave->click();
        $node = node_load($nid, null, true);

        try {
            paddle_mailchimp_generate_mail_html_for_node($node, false);
        } catch (\EntityMetadataWrapperException $e) {
            // If an exception has been thrown, it means that something went
            // wrong with the entity_metadata_wrapper().
            $this->fail('The entity_metadata_wrapper failed because there is no body filled out.');
        }
    }
}
