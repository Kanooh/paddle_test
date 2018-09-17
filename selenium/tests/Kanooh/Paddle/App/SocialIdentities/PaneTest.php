<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\SocialIdentities\PaneTest.
 */

namespace Kanooh\Paddle\App\SocialIdentities;

use Kanooh\Paddle\Apps\SocialIdentities;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleSocialIdentities\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPage;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\Pane\Pane;
use Kanooh\Paddle\Pages\Element\Pane\SocialIdentityPane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\SocialMediaIdentityPanelsContentType;
use Kanooh\Paddle\Pages\Element\SocialIdentities\SocialIdentityModal;
use Kanooh\Paddle\Pages\Element\SocialIdentities\SocialIdentitiesTableRow;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests the social media identity pane.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PaneTest extends WebDriverTestCase
{
    /**
     * Admin node view page.
     *
     * @var AdminViewPage
     */
    protected $adminViewPage;

    /**
     * Test data provider for alphanumeric data.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * The paddlet configuration page.
     *
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * Landing page layout page.
     *
     * @var PanelsContentPage
     */
    protected $layoutPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * The front end node view page.
     *
     * @var ViewPage
     */
    protected $viewPage;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Prepare some variables for later use.
        $this->adminViewPage = new AdminViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->configurePage = new ConfigurePage($this);
        $this->layoutPage = new PanelsContentPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->viewPage = new ViewPage($this);

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new SocialIdentities);

        // Log in as a site manager.
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * Tests the basic configuration and functionality of the pane.
     *
     * @group panes
     * @group socialIdentities
     */
    public function testPane()
    {
        $test_data = array();
        // Go to the configuration page and create 3 social identities.
        $this->configurePage->go();
        for ($i = 0; $i < 3; $i++) {
            $name = $this->alphanumericTestDataProvider->getValidValue();
            $this->configurePage->contextualToolbar->buttonCreateIdentity->click();
            $modal = new SocialIdentityModal($this);
            $modal->waitUntilOpened();

            // Fill in the required fields and submit.
            $modal->form->name->fill($name);

            // Add some actual identities for the first 2 entities but not for
            // the third.
            $test_identities = array();
            if ($i < 3) {
                // Put the iconless identities - "vimeo", "wordpress" and
                // "blogger" first for special treatment.
                $identities = array(
                    'link',
                    'vimeo',
                    'wordpress',
                    'blogger',
                    'facebook',
                    'twitter',
                    'linkedin',
                    'google-plus',
                    'pinterest',
                    'flickr',
                    'youtube',
                    'tumblr',
                    'foursquare',
                    'instagram',
                );

                for ($x = 0; $x < count($identities); $x++) {
                    $modal->form->addNewUrlField();
                    $row = $modal->form->table->getRowByPosition($x);
                    $identity = $identities[$x];
                    $url = 'http://' . $identity . '.com/' . $this->alphanumericTestDataProvider->getValidValue();

                    if ($identity == 'google-plus') {
                        $url = 'http://plus.google.com/' . $this->alphanumericTestDataProvider->getValidValue();
                    }

                    $row->url->fill($url);
                    // Use an actual title for the first 7 identities and no
                    // title for the following 7. This is to avoid too much
                    // randomness.
                    $title = $x <= 7 ? $this->alphanumericTestDataProvider->getValidValue() : '';
                    $row->title->fill($title);

                    // Make sure the if the title is not filled in the URL will be displayed.
                    $rendered_title = $title ?: $url;
                    $icon = ($x > 3) ? $identity : 'link';
                    $test_identities[] = array('title' => $rendered_title, 'icon' => $icon, 'url' => $url);
                }
            }
            $modal->form->saveButton->click();
            $modal->waitUntilClosed();
            $this->configurePage->checkArrival();

            // Get the created identity from the table.
            $social_identities = $this->configurePage->socialIdentitiesTable->rows;
            /** @var SocialIdentitiesTableRow $social_identity */
            $social_identity = end($social_identities);

            $test_data[] = array(
                'name' => $name,
                'psiid' => $social_identity->psiid,
                'identities' => $test_identities,
            );
        }

        // Create a landing page.
        $nid = $this->contentCreationService->createLandingPage();
        $this->layoutPage->go($nid);
        $region = $this->layoutPage->display->getRandomRegion();

        $panes_before = $region->getPanes();
        // Create a social media identity pane and select the first identity.
        $content_type = new SocialMediaIdentityPanelsContentType($this);

        $region->buttonAddPane->click();
        $this->waitUntilTextIsPresent('Add new pane');

        $modal = new AddPaneModal($this);
        $modal->selectContentType($content_type);

        $content_type->identity = $test_data[0]['psiid'];
        $content_type->fillInConfigurationForm();

        $modal->submit();
        $modal->waitUntilClosed();

        $region->refreshPaneList();
        $panes_after = $region->getPanes();
        /** @var Pane $pane */
        $pane = current(array_diff_key($panes_after, $panes_before));
        $pane_uuid = $pane->getUuid();

        $this->checkIdentityPresentInPane($test_data[0], $pane_uuid);

        // Edit the pane and select the other two identities we created. Verify
        // they are shown correctly on all pages.
        for ($i = 1; $i < 3; $i++) {
            $this->layoutPage->go($nid);
            $content_type = new SocialMediaIdentityPanelsContentType($this);
            $content_type->identity = $test_data[$i]['psiid'];
            $pane->edit($content_type);
            $this->layoutPage->contextualToolbar->buttonSave->click();
            $this->adminViewPage->checkArrival();
            $this->adminViewPage->contextualToolbar->buttonPageLayout->click();

            $this->checkIdentityPresentInPane($test_data[$i], $pane_uuid);
        }
    }

    /**
     * Check that the social media identity is shown on all pages where needed.
     *
     * @param  array $test_data
     *   The data used to create the identity.
     * @param string $pane_uuid
     *   The uuid of the pane in which we are checking.
     */
    public function checkIdentityPresentInPane($test_data, $pane_uuid)
    {
        $pages_to_check = array(
          'layout' => 'Save',
          'adminView' => 'PreviewRevision',
          'view' => '',
        );

        foreach ($pages_to_check as $page => $button_to_click) {
            $this->{$page . 'Page'}->checkArrival();

            // Get the identities from the pane.
            $identity_pane = new SocialIdentityPane($this, $pane_uuid, '//div[@data-pane-uuid="' . $pane_uuid . '"]');
            $identities_found = $identity_pane->identities;

            if (count($test_data['identities'])) {
                // Check that for each identity we found a link with the correct
                // URL and title and that its icon was found.
                foreach ($test_data['identities'] as $data) {
                    $this->assertArrayHasKey($data['url'], $identities_found);
                    $this->assertContains($data['title'], $identities_found[$data['url']]['title']);
                    $this->assertEquals($data['icon'], $identities_found[$data['url']]['icon']);
                }
            } else {
                $this->assertTextPresent($test_data['name']);
            }
            if ($button_to_click) {
                $this->{$page . 'Page'}->contextualToolbar->{'button' . $button_to_click}->click();
            }
        }
    }
}
