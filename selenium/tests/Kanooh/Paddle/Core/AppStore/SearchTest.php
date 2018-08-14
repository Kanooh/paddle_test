<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\AppStore\SearchTest.
 */

namespace Kanooh\Paddle\Core\AppStore;

use Kanooh\Paddle\Apps\Carousel;
use Kanooh\Paddle\Apps\Embed;
use Kanooh\Paddle\Apps\GoogleCustomSearch;
use Kanooh\Paddle\Utilities\MultilingualService;
use Kanooh\Paddle\Apps\Multilingual;
use Kanooh\Paddle\Pages\Admin\Apps\AppsOverviewPage\AppsOverviewPage;
use Kanooh\Paddle\Pages\Admin\Apps\InfoPage\InfoPage;
use Kanooh\Paddle\Pages\Admin\User\UserProfileEditPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests the search functionality in the Paddle Store.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class SearchTest extends WebDriverTestCase
{
    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var AppsOverviewPage
     */
    protected $appsOverviewPage;

    /**
     * @var UserProfileEditPage
     */
    protected $userProfileEditPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        $this->appsOverviewPage = new AppsOverviewPage($this);
        $this->userProfileEditPage = new UserProfileEditPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->appService = new AppService($this, $this->userSessionService);

        $this->userSessionService->login('SiteManager');
    }

    /**
     * Tests the faceted filters.
     *
     * We primarily test that the facet count next to the filters matches the
     * results when filtering. We tend to not test which apps are visible for
     * specific filters, as that would be testing the app metadata and search
     * api queries, and not the facets. The only assumptions that we make is
     * that paddle_carousel, paddle_embed, and paddle_google_custom_search
     * exist, and that paddle_google_custom_search is the only one of those
     * that requires a third party subscription. This reduces the chance that
     * the test will break if an app's metadata is changed.
     *
     * @group store
     */
    public function testFacetedFilters()
    {
        // Go to the app store overview page so we can get a list of all
        // apps to disable.
        $this->appsOverviewPage->go();

        // Disable all apps first so we have more control over results of the
        // status filter.
        $this->appService->disableAppsByMachineNames(array_keys($this->appsOverviewPage->apps));

        // Enable some specific apps to test the status filter later.
        $enabled_apps = array(
            'paddle_carousel' => new Carousel(),
            'paddle_embed' => new Embed(),
            'paddle_google_custom_search' => new GoogleCustomSearch(),
        );
        foreach ($enabled_apps as $app) {
            $this->appService->enableApp($app);
        }

        // Go to the apps overview page.
        $this->appsOverviewPage->go();
        $form = $this->appsOverviewPage->filterForm;

        // The "all" value should be set by default in the status filter.
        $this->assertTrue($form->statusAll->isSelected());

        // Make sure that the facet count of the "all" value matches the amount
        // of visible apps.
        $all_apps_count = count($this->appsOverviewPage->apps);
        $this->assertEquals($form->facetCount($form->statusAll), $all_apps_count);

        // Store the amount of third-party apps & apps develop by Kanooh for
        // later use.
        $third_party_facet_count = $form->facetCount($form->thirdParty);
        $vendor_kanooh_facet_count = $form->facetCount($form->vendorKanooh);

        // Select the "enabled" value and wait for the form & results to reload.
        $form->statusEnabled->select();
        $this->appsOverviewPage->waitUntilUpdated();

        // Make sure the enabled apps are visible, and that the facet count
        // matches the amount of apps that we enabled.
        $this->assertEquals(count($enabled_apps), $form->facetCount($form->statusEnabled));
        $this->assertEquals(count($enabled_apps), count($this->appsOverviewPage->apps));
        $this->assertEquals($form->facetCount($form->statusAll), $all_apps_count);
        foreach ($enabled_apps as $machine_name => $app) {
            $this->assertContains($machine_name, array_keys($this->appsOverviewPage->apps));
        }

        // Only one of the apps that we enabled requires a third-party
        // subscription, so the current third-party facet count should equal 1.
        $this->assertEquals(1, $form->facetCount($form->thirdParty));

        // Select the third-party filter while the status filter is set to
        // "enabled" and make sure only the paddle_google_custom_search app is
        // visible and that the facet count is correct.
        $form->thirdParty->check();
        $this->appsOverviewPage->waitUntilUpdated();
        $this->assertEquals(1, count($this->appsOverviewPage->apps));
        $machine_names = array_keys($this->appsOverviewPage->apps);
        $this->assertEquals('paddle_google_custom_search', $machine_names[0]);
        $form->thirdParty->uncheck();
        $this->appsOverviewPage->waitUntilUpdated();

        // All three enabled apps are developed by Kanooh, which means the
        // facet count for the "developed by Kanooh" filter should equal 3.
        $this->assertEquals(3, $form->facetCount($form->vendorKanooh));

        // Select the "disabled" value and wait for the form & results to
        // reload.
        $form->statusDisabled->select();
        $this->appsOverviewPage->waitUntilUpdated();

        // Make sure the enabled apps are not visible, and that the facet count
        // matches the amount of apps that are visible.
        $this->assertCount($form->facetCount($form->statusDisabled), $this->appsOverviewPage->apps);
        foreach ($enabled_apps as $machine_name => $app) {
            $this->assertNotContains($machine_name, array_keys($this->appsOverviewPage->apps));
        }

        // The facet count of the third party filter should be equal to the
        // total amount (stored previously) minus the one that is enabled.
        $this->assertEquals($third_party_facet_count - 1, $form->facetCount($form->thirdParty));

        // Select the third-party filter while the status filter is set to
        // "disabled" and make sure the paddle_google_custom_search app is not
        // visible and that the facet count is correct.
        $form->thirdParty->check();
        $this->appsOverviewPage->waitUntilUpdated();
        $this->assertEquals($third_party_facet_count - 1, count($this->appsOverviewPage->apps));
        $machine_names = array_keys($this->appsOverviewPage->apps);
        $this->assertNotContains('paddle_google_custom_search', $machine_names);
        $form->thirdParty->uncheck();
        $this->appsOverviewPage->waitUntilUpdated();

        // The facet count of the "developed by Kanooh" filter should be equal
        // to the count stored when the status filter was set to "all", minus
        // the three that we enabled.
        $this->assertEquals($vendor_kanooh_facet_count - 3, $form->facetCount($form->vendorKanooh));

        // Set the status filter back to "all" before testing the other filters.
        $form->statusAll->select();
        $this->appsOverviewPage->waitUntilUpdated();

        // Test the level and third party filters. Also combine the level
        // filters with the third party filter to test combinations.
        $type_filters = array(
            'levelFree',
            'levelExtra',
            'thirdParty',
        );
        foreach ($type_filters as $type_filter) {
            // Check the filter.
            $form->{$type_filter}->check();
            $this->appsOverviewPage->waitUntilUpdated();

            // Verify that the filter's facet count matches the number of
            // visible apps.
            $facet_count = $form->facetCount($form->{$type_filter});
            $this->assertCount($facet_count, $this->appsOverviewPage->apps);

            // Combine with the third party filter, if we're not testing the
            // third party filter itself.
            if ($type_filter != 'thirdParty') {
                // Calculate the expected number of apps based on the facet
                // count of the current filter and the third party filter.
                // Lowest facet count is the number of apps that we should get
                // as results.
                $third_party_facet_count = $form->facetCount($form->thirdParty);
                if ($facet_count < $third_party_facet_count) {
                    $expected_facet_count = $facet_count;
                } else {
                    $expected_facet_count = $third_party_facet_count;
                }

                // Check the third party filter, and make sure the number of
                // visible apps matches the amount that we were expecting based
                // on the facets.
                $form->thirdParty->check();
                $this->appsOverviewPage->waitUntilUpdated();
                $this->assertCount($expected_facet_count, $this->appsOverviewPage->apps);

                // Both the third party filter, and the filter that we're
                // testing should have the same facet count now.
                $this->assertEquals($expected_facet_count, $form->facetCount($form->{$type_filter}));
                $this->assertEquals($expected_facet_count, $form->facetCount($form->thirdParty));

                // Un-check the third party filter so we can test the next
                // filter.
                $form->thirdParty->uncheck();
                $this->appsOverviewPage->waitUntilUpdated();
            }

            // Un-check the filter so we can test the next one.
            $form->{$type_filter}->uncheck();
            $this->appsOverviewPage->waitUntilUpdated();
        }

        // Loop over each vendor and verify the facet count. This list will
        // grow in the future so don't rely on the vendor properties on the
        // filter form class, instead use the getVendorValues() and
        // vendorCheckbox() methods.
        foreach ($form->getVendorValues() as $vendor) {
            $form->vendorCheckbox($vendor)->check();
            $this->appsOverviewPage->waitUntilUpdated();

            $this->assertCount($form->facetCount($form->vendorCheckbox($vendor)), $this->appsOverviewPage->apps);

            $form->vendorCheckbox($vendor)->uncheck();
            $this->appsOverviewPage->waitUntilUpdated();
        }
    }

    /**
     * Tests the translation of a paddlet in the paddle store.
     *
     * @group store
     */
    public function testPaddletTranslation()
    {
        $this->appService->enableApp(new Multilingual);
        $info_page = new InfoPage($this, new Multilingual);

        // Make sure multilingual setting are set to defaults.
        MultilingualService::setPaddleTestDefaults($this);

        // Test that the nl data attribute is set.
        $this->appsOverviewPage->go();
        $this->byCssSelector('[data-lang="nl"]');
        $info_page->go();
        $this->byCssSelector('[data-lang="nl"]');

        // Change the user his preferred language to English.
        $this->userProfileEditPage->go($this->userSessionService->getUserId('SiteManager'));
        $this->userProfileEditPage->form->completeUserProfile();
        $this->userProfileEditPage->form->language->english->select();
        $this->userProfileEditPage->contextualToolbar->buttonSave->click();
        $this->userProfileEditPage->checkArrival();

        // Test that the en data attribute is set.
        $this->appsOverviewPage->go();
        $this->byCssSelector('[data-lang="en"]');
        $info_page->go();
        $this->byCssSelector('[data-lang="en"]');

        // Reset defaults.
        $this->userProfileEditPage->go($this->userSessionService->getUserId('SiteManager'));
        $this->userProfileEditPage->form->language->dutch->select();
        $this->userProfileEditPage->contextualToolbar->buttonSave->click();
        $this->userProfileEditPage->checkArrival();
    }
}
