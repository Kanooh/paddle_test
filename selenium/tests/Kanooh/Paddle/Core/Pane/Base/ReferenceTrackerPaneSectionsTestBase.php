<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Pane\Base\ReferenceTrackerPaneSectionsTestBase.
 */

namespace Kanooh\Paddle\Core\Pane\Base;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
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
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
abstract class ReferenceTrackerPaneSectionsTestBase extends WebDriverTestCase
{

    /**
     * Holds additional references created during tests.
     *
     * @var array
     */
    protected $additionalReferences = array();

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
     * @var LayoutPage
     */
    protected $layoutPage;

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
        $this->layoutPage = new LayoutPage($this);
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
     * @param array $referenced_ids
     *   Array of ids of the entities that needs to be referenced.
     */
    abstract protected function configurePaneContentType($content_type, $referenced_ids);

    /**
     * Tests tracking of internal links and atoms in the pane sections.
     *
     * @group linkChecker
     */
    public function testPaneSectionsReferences()
    {
        // Create two nodes to be referenced.
        $referenced_ids = array(
            'topSection' => $this->contentCreationService->createBasicPage(),
            'bottomSection' => $this->contentCreationService->createBasicPage(),
        );

        // And an image atom.
        $atom = $this->assetCreationService->createImage();

        // Create a basic page to hold a pane.
        $referencing_nid = $this->contentCreationService->createBasicPage();

        // Run any additional setup needed before adding the pane.
        $this->additionalTestSetUp();

        // Add a pane to the page.
        $pane = $this->addPane($referencing_nid, $referenced_ids);

        // Verify that the node is referencing the expected nodes.
        $expected = array(
            'node' => array_values($referenced_ids),
        );

        // Some panes might add additional references when adding the pane.
        $expected = array_merge_recursive($expected, $this->additionalReferences);

        $this->assertEquals(
            $expected,
            reference_tracker_get_outbound_references('node', $referencing_nid)
        );

        // Edit the pane and set an image in the top section.
        $pane = $this->editPane(
            $referencing_nid,
            $pane->getUuid(),
            $pane->getXPathSelectorByUuid(),
            array($this, 'setTopSectionImage'),
            array($atom['id'])
        );

        // Now also the atom is being referenced.
        $expected = array(
            'node' => array_values($referenced_ids),
            'scald_atom' => array($atom['id']),
        );
        $expected = array_merge_recursive($expected, $this->additionalReferences);

        $this->assertEquals(
            $expected,
            reference_tracker_get_outbound_references('node', $referencing_nid)
        );

        // Now disable the top section link.
        $pane = $this->editPane(
            $referencing_nid,
            $pane->getUuid(),
            $pane->getXPathSelectorByUuid(),
            array($this, 'disableTopSectionLink')
        );

        // The top node link reference should be gone now.
        $expected = array(
            'node' => array($referenced_ids['bottomSection']),
            'scald_atom' => array($atom['id']),
        );
        $expected = array_merge_recursive($expected, $this->additionalReferences);
        $this->assertEquals(
            $expected,
            reference_tracker_get_outbound_references('node', $referencing_nid)
        );

        // Disable the top section.
        $this->editPane(
            $referencing_nid,
            $pane->getUuid(),
            $pane->getXPathSelectorByUuid(),
            array($this, 'disablePaneSection'),
            array('topSection')
        );

        // Also the atom reference should be gone now.
        $expected = array(
            'node' => array($referenced_ids['bottomSection']),
        );
        $expected = array_merge_recursive($expected, $this->additionalReferences);
        $this->assertEquals(
            $expected,
            reference_tracker_get_outbound_references('node', $referencing_nid)
        );

        // Remove the bottom reference by disabling the section completely.
        $this->editPane(
            $referencing_nid,
            $pane->getUuid(),
            $pane->getXPathSelectorByUuid(),
            array($this, 'disablePaneSection'),
            array('bottomSection')
        );

        // No references should be left now, except the additional ones.
        $this->assertEquals(
            $this->additionalReferences,
            reference_tracker_get_outbound_references('node', $referencing_nid)
        );
    }

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
     * Add a pane to a node, runs the configuration of the pane and saves the page.
     *
     * @param int $referencing_nid
     *   The nid of the page where to add the pane.
     * @param array $referenced_ids
     *   Array of ids of the entities that needs to be referenced.
     * @return Pane
     *   The created pane.
     */
    protected function addPane($referencing_nid, $referenced_ids)
    {
        // Get a random region to insert the pane in.
        $this->layoutPage->go($referencing_nid);
        $region = $this->layoutPage->display->getRandomRegion();

        // Create an instance of the pane.
        $content_type = $this->getPaneContentTypeInstance();

        $test_case = $this;
        $callable = new SerializableClosure(
            function () use ($test_case, $content_type, $referenced_ids) {
                // Enable the links in the sections.
                foreach (array('topSection', 'bottomSection') as $section_name) {
                    // Get the section.
                    $section = $content_type->{$section_name};

                    // Enable the section.
                    $section->enable->check();

                    // For top section, always enable the "text" version.
                    if ($section_name == 'topSection') {
                        $section->contentTypeRadios->text->select();
                    }

                    // Select the internal url type.
                    // This has to be done before filling the link text because
                    // in the bottom pane the text field is shown only after
                    // setting this value.
                    $callable = new SerializableClosure(
                        function () use ($section) {
                          try {
                            $section->urlTypeRadios->internal->select();
                            return $section->urlTypeRadios->internal->isSelected() ? true : null;

                          } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {

                            // If we run into any error means it was clicked or no longer able to be clicked.
                            return true;
                          }
                        }
                    );
                    $test_case->waitUntil($callable, $test_case->getTimeout());

                    // Fill the link text.
                    $section->text->fillOnceVisible($test_case->alphanumericTestDataProvider->getValidValue());

                    // Select the referenced node.
                    $section->internalUrl->fill('node/' . $referenced_ids[$section_name]);

                    // Pick the suggestion.
                    $autocomplete = new AutoComplete($test_case);
                    $autocomplete->waitUntilDisplayed();
                    $autocomplete->pickSuggestionByPosition();

                    // Scroll back to top of modal pane.
                    $test_case->execute(
                        array(
                            'script' => "scrollBy(0,5000);",
                            'args' => array(),
                        )
                    );
                }

                // Run the pane configuration method.
                $test_case->configurePaneContentType($content_type, $referenced_ids);
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
     * Callback to disable the link in the top section of a pane.
     *
     * @param SectionedPanelsContentType $content_type
     *   The pane content type being edited.
     */
    protected function disableTopSectionLink($content_type)
    {
        $content_type->topSection->urlTypeRadios->noLink->select();
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
        $content_type->{$section_name}->enable->uncheck();
    }
}
