<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\LandingPage\Common\SocialMediaTest.
 */

namespace Kanooh\Paddle\Core\ContentType\LandingPage\Common;

use Kanooh\Paddle\App\SocialMedia\ContentType\Base\SocialMediaTestBase;
use Kanooh\Paddle\Pages\Node\EditPage\EditLandingPage;

/**
 * SocialMediaTest class.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class SocialMediaTest extends SocialMediaTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createLandingPage(null, $title);
    }

    /**
     * Tests that when "Show title" is disabled the button is not shown.
     *
     * @see https://one-agency.atlassian.net/browse/KANWEBS-2504
     *
     * @group socialMedia
     * @group regression
     */
    public function testShowTitleConflict()
    {
        // Set the share button to show on landing pages and with one network.
        $this->configurationPage->go();
        $checkbox = $this->configurationPage->configureForm->getContentTypeCheckboxByName('landing_page');
        if (!$checkbox->isChecked()) {
            $checkbox->check();
        }
        $checkbox = $this->configurationPage->configureForm->getSocialCheckboxByName('facebook');
        if (!$checkbox->isChecked()) {
            $checkbox->check();
        }
        $this->configurationPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The configuration options have been saved.');

        // Create a landing page.
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $nid = $this->setupNode($title);

        // Check that by default both the title and share button are there.
        $this->frontEndViewPage->go($nid);
        $title_path = "//h1[contains(@id, 'page-title')]";
        $this->waitUntilElementIsPresent($title_path);
        try {
            $this->frontEndViewPage->shareWidget;
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            $this->fail("The share widget is not shown for landing page.");
        }

        // Uncheck the "Show title" checkbox.
        $edit_page = new EditLandingPage($this);
        $edit_page->go($nid);
        if ($edit_page->showTitleCheckbox->selected()) {
            $edit_page->showTitleCheckbox->click();
        }
        $edit_page->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();

        // Go to the front-end page of the node.
        $this->frontEndViewPage->go($nid);

        // Check that the title is not displayed.
        $this->waitUntilElementIsNoLongerPresent($title_path);
        // Check the button is still here.
        try {
            $this->frontEndViewPage->shareWidget;
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            $this->fail("The share widget is not shown for landing page.");
        }
    }
}
