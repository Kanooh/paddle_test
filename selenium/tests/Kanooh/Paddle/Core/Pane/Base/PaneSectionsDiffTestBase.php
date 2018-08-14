<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Pane\Base\PaneSectionsDiffTestBase.
 */

namespace Kanooh\Paddle\Core\Pane\Base;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\DiffPage\DiffPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\RevisionsPage\RevisionsPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\Pane\Pane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\SectionedPanelsContentType;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\ScaldService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Base class to test revision diff for pane sections.
 */
abstract class PaneSectionsDiffTestBase extends WebDriverTestCase
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
     * @var AssetCreationService
     */
    protected $assetCreationService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var DiffPage
     */
    protected $diffPage;

    /**
     * @var LayoutPage
     */
    protected $layoutPage;

    /**
     * @var RevisionsPage
     */
    protected $revisionsPage;

    /**
     * @var ScaldService
     */
    protected $scaldService;

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

        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->assetCreationService = new AssetCreationService($this);
        $this->diffPage = new DiffPage($this);
        $this->layoutPage = new LayoutPage($this);
        $this->revisionsPage = new RevisionsPage($this);
        $this->scaldService = new ScaldService($this);
        $this->userSessionService = new UserSessionService($this);
        $this->appService = new AppService($this, $this->userSessionService);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Go to the login page and log in as chief editor.
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * Creates an instance of the pane content type needed for the test.
     *
     * @return SectionedPanelsContentType
     *   A content type instance for the pane we need to test.
     */
    abstract protected function getPaneContentTypeInstance();

    /**
     * Callback to configure the pane content type.
     *
     * @param SectionedPanelsContentType $content_type
     *   The pane content type that has been added.
     */
    abstract protected function configurePaneContentType($content_type);

    /**
     * Run additional setup code needed for the test.
     *
     * Operation like atom creation or paddlet configuration goes here.
     * Everything that needs a page to be loaded basically, as that is not
     * possible anymore inside the pane configuration callback.
     */
    protected function additionalTestSetUp()
    {
        // By default, no additional setup is needed.
    }

    /**
     * Tests comparison of revisions for the pane sections.
     *
     * @group revisions
     */
    public function testPaneSectionsDiff()
    {
        // Run any additional setup needed before executing test code.
        $this->additionalTestSetUp();

        // Create a basic page to hold a pane.
        $nid = $this->contentCreationService->createBasicPage();

        // Add the pane to the page.
        $top_section_title = $this->alphanumericTestDataProvider->getValidValue();
        $bottom_section_title = $this->alphanumericTestDataProvider->getValidValue();
        $test_case = $this;
        $callable = new SerializableClosure(
            function ($content_type) use ($test_case, $top_section_title, $bottom_section_title) {
                $test_case->setSectionText($content_type, 'top', $top_section_title);
                $test_case->setSectionText($content_type, 'bottom', $bottom_section_title);

                // Run additional configurations to the pane.
                $test_case->configurePaneContentType($content_type);
            }
        );
        $pane = $this->addPane($nid, $callable);

        $this->administrativeNodeViewPage->revisionsLink->click();
        $this->revisionsPage->checkArrival();
        $this->revisionsPage->contextualToolbar->buttonCompare->click();
        $this->diffPage->checkArrival();
        // Verify that the section configuration info is reported in the diffs.
        $this->assertTrue($this->diffPage->checkExactTextAddedPresent('Top section text: ' . $top_section_title));
        $this->assertTrue($this->diffPage->checkExactTextAddedPresent('Top section url type: No link'));
        $this->assertTrue($this->diffPage->checkExactTextAddedPresent('Bottom section text: ' . $bottom_section_title));
        $this->assertTrue($this->diffPage->checkExactTextAddedPresent('Bottom section url type: No link'));

        // Change the top section title and link it to an internal node.
        $new_top_section_title = $this->alphanumericTestDataProvider->getValidValue();
        $new_bottom_section_title = $this->alphanumericTestDataProvider->getValidValue();
        $top_section_internal_title = $this->alphanumericTestDataProvider->getValidValue();
        $top_section_internal_nid = $this->contentCreationService->createBasicPage($top_section_internal_title);
        $bottom_section_internal_title = $this->alphanumericTestDataProvider->getValidValue();
        $bottom_section_internal_nid = $this->contentCreationService->createBasicPage($bottom_section_internal_title);
        $callable = new SerializableClosure(
            function ($content_type) use (
                $test_case,
                $new_top_section_title,
                $new_bottom_section_title,
                $top_section_internal_nid,
                $bottom_section_internal_nid
            ) {
                $test_case->setSectionText($content_type, 'top', $new_top_section_title);
                $test_case->setSectionInternalUrl($content_type, 'top', $top_section_internal_nid);
                $test_case->setSectionText($content_type, 'bottom', $new_bottom_section_title);
                $test_case->setSectionInternalUrl($content_type, 'bottom', $bottom_section_internal_nid);
            }
        );
        $pane = $this->editPane(
            $nid,
            $pane->getUuid(),
            $pane->getXPathSelectorByUuid(),
            $callable
        );

        $this->administrativeNodeViewPage->revisionsLink->click();
        $this->revisionsPage->checkArrival();
        $this->revisionsPage->contextualToolbar->buttonCompare->click();
        $this->diffPage->checkArrival();
        // Verify that the title change and the internal link are reported
        // in the diffs.
        $this->assertTrue($this->diffPage->checkExactTextDeletedPresent('Top section text: ' . $top_section_title));
        $this->assertTrue($this->diffPage->checkExactTextDeletedPresent('Top section url type: No link'));
        $this->assertTrue($this->diffPage->checkExactTextDeletedPresent('Bottom section text: ' . $bottom_section_title));
        $this->assertTrue($this->diffPage->checkExactTextDeletedPresent('Bottom section url type: No link'));
        $this->assertTrue($this->diffPage->checkExactTextAddedPresent('Top section text: ' . $new_top_section_title));
        $this->assertTrue($this->diffPage->checkExactTextAddedPresent('Top section url type: Internal'));
        $this->assertTrue($this->diffPage->checkExactTextAddedPresent("Top section url: $top_section_internal_title (node/$top_section_internal_nid)"));
        $this->assertTrue($this->diffPage->checkExactTextAddedPresent('Bottom section text: ' . $new_bottom_section_title));
        $this->assertTrue($this->diffPage->checkExactTextAddedPresent('Bottom section url type: Internal'));
        $this->assertTrue($this->diffPage->checkExactTextAddedPresent("Bottom section url: $bottom_section_internal_title (node/$bottom_section_internal_nid)"));

        // Add an image in the top section and link it to an external url.
        $image = $this->assetCreationService->createImage();
        $top_external_url = 'http://www.example.com';
        $bottom_external_url = 'http://www.kanooh.be';
        $callable = new SerializableClosure(
            function ($content_type) use ($test_case, $image, $top_external_url, $bottom_external_url) {
                $test_case->setTopSectionImage($content_type, $image['id']);
                $test_case->setSectionExternalUrl($content_type, 'top', $top_external_url);
                $test_case->setSectionExternalUrl($content_type, 'bottom', $bottom_external_url);
            }
        );
        $pane = $this->editPane(
            $nid,
            $pane->getUuid(),
            $pane->getXPathSelectorByUuid(),
            $callable
        );

        $this->administrativeNodeViewPage->revisionsLink->click();
        $this->revisionsPage->checkArrival();
        $this->revisionsPage->contextualToolbar->buttonCompare->click();
        $this->diffPage->checkArrival();
        // Verify that the image and the external url are reported in the diffs.
        $this->assertTrue($this->diffPage->checkExactTextDeletedPresent('Top section text: ' . $new_top_section_title));
        $this->assertTrue($this->diffPage->checkExactTextDeletedPresent('Top section url type: Internal'));
        $this->assertTrue($this->diffPage->checkExactTextDeletedPresent("Top section url: $top_section_internal_title (node/$top_section_internal_nid)"));
        $this->assertTrue($this->diffPage->checkExactTextDeletedPresent('Bottom section url type: Internal'));
        $this->assertTrue($this->diffPage->checkExactTextDeletedPresent("Bottom section url: $bottom_section_internal_title (node/$bottom_section_internal_nid)"));
        $this->assertTrue($this->diffPage->checkExactTextAddedPresent('Top section image: ' . $image['title']));
        $this->assertTrue($this->diffPage->checkExactTextAddedPresent('Top section url type: External'));
        $this->assertTrue($this->diffPage->checkExactTextAddedPresent("Top section url: $top_external_url"));
        $this->assertTrue($this->diffPage->checkExactTextAddedPresent('Bottom section url type: External'));
        $this->assertTrue($this->diffPage->checkExactTextAddedPresent("Bottom section url: $bottom_external_url"));

        // Change the image style and disable the link.
        $callable = new SerializableClosure(
            function ($content_type) use ($test_case) {
                $test_case->setTopSectionImageStyle($content_type, '16:9');
                $test_case->disableSectionLink($content_type, 'top');
                $test_case->disableSectionLink($content_type, 'bottom');
            }
        );
        $pane = $this->editPane(
            $nid,
            $pane->getUuid(),
            $pane->getXPathSelectorByUuid(),
            $callable
        );

        $this->administrativeNodeViewPage->revisionsLink->click();
        $this->revisionsPage->checkArrival();
        $this->revisionsPage->contextualToolbar->buttonCompare->click();
        $this->diffPage->checkArrival();
        // Verify that the image style and the link changes are reported in
        // the diffs.
        $this->assertTrue($this->diffPage->checkExactTextDeletedPresent('Top section url type: External'));
        $this->assertTrue($this->diffPage->checkExactTextDeletedPresent("Top section url: $top_external_url"));
        $this->assertTrue($this->diffPage->checkExactTextDeletedPresent('Bottom section url type: External'));
        $this->assertTrue($this->diffPage->checkExactTextDeletedPresent("Bottom section url: $bottom_external_url"));
        $this->assertTrue($this->diffPage->checkExactTextAddedPresent('Top section image style: 16:9'));
        $this->assertTrue($this->diffPage->checkExactTextAddedPresent('Top section url type: No link'));
        $this->assertTrue($this->diffPage->checkExactTextAddedPresent('Bottom section url type: No link'));

        // Disable the top section.
        $callable = new SerializableClosure(
            function ($content_type) use ($test_case) {
                $test_case->disablePaneSection($content_type, 'top');
                $test_case->disablePaneSection($content_type, 'bottom');
            }
        );
        $this->editPane(
            $nid,
            $pane->getUuid(),
            $pane->getXPathSelectorByUuid(),
            $callable
        );

        $this->administrativeNodeViewPage->revisionsLink->click();
        $this->revisionsPage->checkArrival();
        $this->revisionsPage->contextualToolbar->buttonCompare->click();
        $this->diffPage->checkArrival();
        // Verify that the section configuration is gone.
        $this->assertTrue($this->diffPage->checkExactTextDeletedPresent('Top section image: ' . $image['title']));
        $this->assertTrue($this->diffPage->checkExactTextDeletedPresent('Top section image style: 16:9'));
        $this->assertTrue($this->diffPage->checkExactTextDeletedPresent('Top section url type: No link'));
        $this->assertTrue($this->diffPage->checkExactTextDeletedPresent('Bottom section url type: No link'));
        $this->assertTrue($this->diffPage->checkExactTextDeletedPresent('Bottom section text: ' . $new_bottom_section_title));
    }

    /**
     * Add a pane to a node, runs the configuration of the pane and saves the page.
     *
     * @param int $nid
     *   The nid of the page where to add the pane.
     * @param callable $callback
     *   A callback to execute after opening the pane modal.
     * @param array $additional_params
     *   An array of additional parameters to pass to the callback.
     *
     * @return Pane
     *   The created pane.
     */
    protected function addPane($nid, $callback, $additional_params = array())
    {
        // Get a random region to add the pane.
        $this->layoutPage->go($nid);
        $region = $this->layoutPage->display->getRandomRegion();

        // Create an instance of the pane.
        $content_type = $this->getPaneContentTypeInstance();

        $test_case = $this;
        $callable = new SerializableClosure(
            function () use ($test_case, $content_type, $callback, $additional_params) {
                // Invoke the provided callback.
                array_unshift($additional_params, $content_type);
                call_user_func_array($callback, $additional_params);
            }
        );
        $pane = $region->addPane($content_type, $callable);

        // Save the layout page.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        return $pane;
    }

    /**
     * Edits a pane in a node.
     *
     * @param int $nid
     *   The nid of the page where to edit the pane.
     * @param string $pane_uuid
     *   The UUID of the pane.
     * @param string $xpath_selector
     *   The XPath of the pane.
     * @param callable $callback
     *   A callback to execute after opening the pane modal for editing.
     * @param array $additional_params
     *   An array of additional parameters to pass to the callback.
     *
     * @return Pane
     *   The updated pane instance.
     */
    protected function editPane($nid, $pane_uuid, $xpath_selector, $callback, $additional_params = array())
    {
        $this->layoutPage->go($nid);

        $pane = new Pane($this, $pane_uuid, $xpath_selector);
        $test_case = $this;
        $callable = new SerializableClosure(
            function () use ($pane, $test_case, $callback, $additional_params) {
                $pane->toolbar->buttonEdit->click();
                $pane->editPaneModal->waitUntilOpened();

                // Instantiate the content type.
                $content_type = $test_case->getPaneContentTypeInstance();
                // Call the provided callback to actually edit the pane.
                array_unshift($additional_params, $content_type);
                call_user_func_array($callback, $additional_params);

                // Close modal.
                $pane->editPaneModal->submit();
                $pane->editPaneModal->waitUntilClosed();
            }
        );
        $pane->executeAndWaitUntilReloaded($callable);

        // Get an updated pane.
        $pane = new Pane($this, $pane->getUuid(), $pane->getXPathSelector());

        // Save the layout page.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        return $pane;
    }

    /**
     * Callback to set a text title in a section of a pane.
     *
     * @param SectionedPanelsContentType $content_type
     *   The pane content type being edited.
     * @param string $section_name
     *   The name of the section where to set the title. Either 'top' or 'bottom'.
     * @param string $title
     *   The text to set as title.
     */
    protected function setSectionText($content_type, $section_name, $title)
    {
        $section = $content_type->{$section_name . 'Section'};

        // Ensure that the section is enabled.
        $section->enable->check();

        // For top section, always enable the "text" version.
        if ($section_name == 'top') {
            $section->contentTypeRadios->text->select();
        }

        // The node content pane doesn't show the text field for the bottom
        // section by default, using a "Read more" option. Since this function
        // should not change the link mode in general, do that only when the
        // text is not displayed.
        if (!$section->text->isDisplayed()) {
            $section->urlTypeRadios->noLink->select();
        }

        $section->text->fillOnceVisible($title);
    }

    /**
     * Callback to set the url type of a section to none.
     *
     * @param SectionedPanelsContentType $content_type
     *   The pane content type being edited.
     * @param string $section_name
     *   The name of the section where to set the title. Either 'top' or 'bottom'.
     */
    protected function disableSectionLink($content_type, $section_name)
    {
        $section = $content_type->{$section_name . 'Section'};
        $section->urlTypeRadios->noLink->select();
    }

    /**
     * Callback to set an internal link to a section in a pane.
     *
     * @param SectionedPanelsContentType $content_type
     *   The pane content type being edited.
     * @param string $section_name
     *   The name of the section where to set the title. Either 'top' or 'bottom'.
     * @param int $nid
     *   The node id to link to.
     */
    protected function setSectionInternalUrl($content_type, $section_name, $nid)
    {
        $section = $content_type->{$section_name . 'Section'};

        // Enable the internal url.
        $section->urlTypeRadios->internal->select();

        // Select the referenced node.
        $section->internalUrl->fill('node/' . $nid);
        // Pick the suggestion.
        $auto_complete = new AutoComplete($this);
        $auto_complete->waitUntilDisplayed();
        $auto_complete->pickSuggestionByPosition(0);
    }

    /**
     * Callback to set an external link to a section in a pane.
     *
     * @param SectionedPanelsContentType $content_type
     *   The pane content type being edited.
     * @param string $section_name
     *   The name of the section where to set the title. Either 'top' or 'bottom'.
     * @param string $url
     *   The external url to link to.
     */
    protected function setSectionExternalUrl($content_type, $section_name, $url)
    {
        $section = $content_type->{$section_name . 'Section'};

        // Enable the internal url.
        $section->urlTypeRadios->external->select();

        // Select the referenced node.
        $section->externalUrl->fill($url);
    }

    /**
     * Callback to set an image in the top section of a pane.
     *
     * @param SectionedPanelsContentType $content_type
     *   The pane content type being edited.
     * @param int $atom_id
     *   The id of the atom being set.
     */
    protected function setTopSectionImage($content_type, $atom_id)
    {
        $content_type->topSection->contentTypeRadios->image->select();
        $content_type->topSection->image->selectButton->click();
        $this->scaldService->insertAtom($atom_id);
    }

    /**
     * Callback to set an image style in the top section of a pane.
     *
     * @param SectionedPanelsContentType $content_type
     *   The pane content type being edited.
     * @param string $image_style
     *   The label of the style to select.
     */
    protected function setTopSectionImageStyle($content_type, $image_style)
    {
        $content_type->topSection->image->style->selectOptionByLabel($image_style);
    }

    /**
     * Callback to disable the bottom section completely.
     *
     * @param SectionedPanelsContentType $content_type
     *   The pane content type being edited.
     * @param string $section_name
     *   The name of the section to disable.
     */
    protected function disablePaneSection($content_type, $section_name)
    {
        $section = $content_type->{$section_name . 'Section'};
        $section->enable->uncheck();
    }
}
