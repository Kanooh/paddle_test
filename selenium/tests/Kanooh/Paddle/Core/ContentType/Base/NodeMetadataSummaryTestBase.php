<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\Base\NodeMetadataSummaryTestBase.
 */

namespace Kanooh\Paddle\Core\ContentType\Base;

use Drupal\Component\Utility\Random;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPage as LandingPagePanelsContentPage;
use Kanooh\Paddle\Pages\Admin\Structure\MenuManager\MenuOverviewPage\MenuOverviewPage;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\DrupalApi\DrupalNodeApi;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests the node meta data summary.
 */
abstract class NodeMetadataSummaryTestBase extends WebDriverTestCase
{
    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * Node edit page.
     *
     * @var EditPage
     */
    protected $editPage;

    /**
     * Node view page.
     *
     * @var ViewPage
     */
    protected $viewPage;

    /**
     * Node API.
     *
     * @var DrupalNodeApi
     */
    protected $nodeApi;

    /**
     * The panels display of a landing page.
     *
     * @var LandingPagePanelsContentPage
     */
    protected $landingPageLayoutPage;

    /**
     * The layout page.
     *
     * @var LayoutPage
     */
    protected $layoutPage;

    /**
     * The 'Add' page of the Paddle Content Manager module.
     *
     * @var AddPage
     */
    protected $addContentPage;

    /**
     * Autocomplete suggestions.
     *
     * @var AutoComplete
     */
    protected $autoComplete;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * The random data generation class.
     *
     * @var Random $random
     */
    protected $random;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Create some instances to use later on.
        $this->addContentPage = new AddPage($this);
        $this->editPage = new EditPage($this);
        $this->viewPage = new ViewPage($this);
        $this->nodeApi = new DrupalNodeApi($this, $this->base_url);
        $this->landingPageLayoutPage = new LandingPagePanelsContentPage($this);
        $this->layoutPage = new LayoutPage($this);
        $this->autoComplete = new AutoComplete($this);
        $this->random = new Random();

        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
    }

    /**
     * Creates a node of the content type that is being tested.
     *
     * @param string $title
     *   Optional title for the node. If omitted a random title will be used.
     *
     * @return int
     *   The node ID of the node that was created.
     */
    abstract public function setupNode($title = null);

    /**
     * Tests the node summary.
     *
     * We want to test this for all existing content types, because it has to
     * work exactly the same on each one.
     *
     * @group contentType
     * @group nodeMetadataSummary
     * @group nodeMetadataSummaryTestBase
     * @group system
     */
    public function testNodeSummary()
    {
        // Log in as an actual editor in the browser.
        $this->userSessionService->login('Editor', true);

        // Set up the node.
        $nid = $this->setupNode();
        $node = node_load($nid);

        // Set up the general terms.
        $general_vocabulary = taxonomy_vocabulary_machine_name_load('paddle_general');
        $parent_tid = 0;
        for ($i = 0; $i < 2; $i++) {
            $term = new \stdClass;
            $term->vid = $general_vocabulary->vid;
            $term->name = $this->random->name(8);
            if (!empty($parent_tid)) {
                $term->parent = $parent_tid;
            }
            taxonomy_term_save($term);
            $node->field_paddle_general_tags[LANGUAGE_NONE][] = array(
                'tid' => $term->tid,
            );
            $parent_tid = $term->tid;
        }

        // Set up the tags.
        $tags_vocabulary = taxonomy_vocabulary_machine_name_load('paddle_tags');
        for ($i = 0; $i < 2; $i++) {
            $tag = new \stdClass;
            $tag->vid = $tags_vocabulary->vid;
            $tag->name = $this->random->name(8);
            taxonomy_term_save($tag);
            $node->field_paddle_tags[LANGUAGE_NONE][] = array(
                'tid' => $tag->tid,
            );
        }

        // Set up the menu links.
        $menu_links = array();
        $menu_names = array(
            MenuOverviewPage::MAIN_MENU_NAME,
            MenuOverviewPage::FOOTER_MENU_NAME,
        );
        foreach ($menu_names as $menu_name) {
            $parent_link = array(
                'link_path' => '<front>',
                'link_title' => $this->random->name(8),
                'menu_name' => $menu_name,
            );
            $parent_mlid = menu_link_save($parent_link);
            $child_link = array(
                'link_path' => 'node/' . $node->nid,
                'link_title' => $this->random->name(8),
                'menu_name' => $menu_name,
                'plid' => $parent_mlid,
            );
            $child_mlid = menu_link_save($child_link);
            $menu_links[$menu_name] = array(
                $parent_mlid => $parent_link['link_title'],
                $child_mlid => $child_link['link_title'],
            );
        }

        // Manually rebuild the menu caches after adding the new menu links.
        menu_rebuild();

        // Add a SEO title and description.
        $seo_title = $this->random->name(12);
        $seo_description = $this->random->name(26);
        $node->field_paddle_seo_title[LANGUAGE_NONE][0]['value'] = $seo_title;
        $node->field_paddle_seo_description[LANGUAGE_NONE][0]['value'] = $seo_description;

        // Save the changes made to the node before checking the node summary.
        node_save($node);

        // Reload the node so that the save values for seo title and description
        // are added to the fields.
        $node = node_load($node->nid, null, true);

        // Add the menu links to the node object so we can easily pass them
        // to the assert method.
        $node->menu_links = $menu_links;

        // Test the node summary on the edit page just after the node has
        // been created.
        $this->editPage->go($nid);
        $this->assertNodeSummary($this->editPage, $node);

        // Test the node summary on the admin view.
        $this->editPage->contextualToolbar->buttonBack->click();
        $this->viewPage->checkArrival();
        $this->viewPage->nodeSummary->showAllMetadata();
        $this->assertNodeSummary($this->viewPage, $node, true);

        // Test the node summary on the layout page and go back to the admin
        // view.
        $this->viewPage->contextualToolbar->buttonPageLayout->click();
        $this->checkSummaryOnLayoutPage($node);

        $this->viewPage->checkArrival();

        // Assign the node to a chief editor.
        $this->viewPage->contextualToolbar->dropdownButtonToChiefEditor->getButton()->click();
        $this->viewPage->contextualToolbar->dropdownButtonToChiefEditor->getButtonInDropdown('demo_chief_editor')->click();

        // Log out, and log in as chief editor.
        $this->userSessionService->logout();
        $this->userSessionService->login('ChiefEditor');

        // Go to the node edit page to test the node summary after assigning it
        // to the chief editor. Also refresh the node object to get the latest
        // changes.
        $this->editPage->go($nid);
        $node = node_load($nid);

        // Test the last modified date. This should have changed when we saved
        // the node to leave the edit page as a regular editor.
        $changed_metadata = $this->editPage->nodeSummary->getMetadata('workflow', 'changed');
        $formatted = $this->formatTimestamp($node->changed);
        $this->assertEquals($formatted, $changed_metadata['value']);

        // Test the assigned author.
        $assignee_metadata = $this->editPage->nodeSummary->getMetadata('workflow', 'assigned');
        $this->assertEquals('demo_chief_editor', $assignee_metadata['value']);

        // Now publish the node so we can test the publication date and
        // validation author.
        $this->editPage->contextualToolbar->buttonBack->click();
        $this->viewPage->checkArrival();
        $this->viewPage->contextualToolbar->buttonPublish->click();
        $this->viewPage->checkArrival();
        $this->viewPage->contextualToolbar->buttonPageProperties->click();
        $this->editPage->checkArrival();

        // Test the publication date. We don't have any good/quick/easy way to
        // get this timestamp from Selenium, except by using the "raw" value
        // in the summary field. So best we can test is that the date is not
        // empty, and that it's formatted correctly.
        $publish_metadata = $this->editPage->nodeSummary->getMetadata('publication', 'publish');
        $this->assertNotEmpty($publish_metadata['value_raw']);
        $formatted = $this->formatTimestamp($publish_metadata['value_raw']);
        $this->assertEquals($formatted, $publish_metadata['value']);

        // Test the validation author.
        $validation_metadata = $this->editPage->nodeSummary->getMetadata('publication', 'validation');
        $this->assertEquals('demo_chief_editor', $validation_metadata['value']);

        // Test the status.
        $status_metadata = $this->editPage->nodeSummary->getMetadata('general', 'status');
        $this->assertEquals('Online', $status_metadata['value']);

        // Unpublish the node.
        $this->editPage->contextualToolbar->buttonBack->click();
        $this->acceptAlert();
        $this->viewPage->checkArrival();
        $this->viewPage->contextualToolbar->buttonOffline->click();
        $this->viewPage->checkArrival();

        // Test the depublication date.
        $this->viewPage->contextualToolbar->buttonPageProperties->click();
        $this->editPage->checkArrival();
        $unpublish_metadata = $this->editPage->nodeSummary->getMetadata('publication', 'unpublish');
        $this->assertNotEmpty($unpublish_metadata['value_raw']);
        $formatted = $this->formatTimestamp($unpublish_metadata['value_raw']);
        $this->assertEquals($formatted, $unpublish_metadata['value']);

        // Schedule the node to be published on another date.
        $supports_scheduling = $this->editPage->supportsSchedulerOptions();
        if ($supports_scheduling) {
            $publish_on_ts = strtotime('+1 day');
            $unpublish_on_ts = strtotime('+2 days');

            // We need to open the scheduler options first.
            $this->editPage->toggleSchedulerOptions();

            // The populateFields() is deprecated but we don't have any alternative
            // at the moment.
            $this->editPage->populateFields(
                array(
                    'publish_on[date]' => date('d/m/Y', $publish_on_ts),
                    'publish_on[time]' => date('H:i:s', $publish_on_ts),
                    'unpublish_on[date]' => date('d/m/Y', $unpublish_on_ts),
                    'unpublish_on[time]' => date('H:i:s', $unpublish_on_ts),
                )
            );
        }

        // Save the changes and go back to the admin node view.
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->viewPage->checkArrival();
        $this->viewPage->nodeSummary->showAllMetadata();

        // Test the status. It should be concept because although it's
        // scheduled for publication, it's no longer really published and we
        // edited it from scratch.
        $status_metadata = $this->viewPage->nodeSummary->getMetadata('general', 'status');
        $this->assertEquals('Concept', $status_metadata['value']);

        // Test the publish & un-publish date.
        if ($supports_scheduling) {
            $publish_metadata = $this->viewPage->nodeSummary->getMetadata('publication', 'publish');
            $formatted = $this->formatTimestamp($publish_on_ts);
            $this->assertEquals($formatted, $publish_metadata['value']);

            $unpublish_metadata = $this->viewPage->nodeSummary->getMetadata('publication', 'unpublish');
            $formatted = $this->formatTimestamp($unpublish_on_ts);
            $this->assertEquals($formatted, $unpublish_metadata['value']);
        }

        // "Publish" (validate) the node, and check that the status now changes
        // from Offline to Scheduled.
        $this->viewPage->contextualToolbar->buttonSchedule->click();
        $this->viewPage->checkArrival();
        $this->viewPage->nodeSummary->showAllMetadata();

        $status_metadata = $this->viewPage->nodeSummary->getMetadata('general', 'status');
        $this->assertEquals('Scheduled', $status_metadata['value']);
    }

    /**
     * Tests that the node metadata summary shows the current revision.
     *
     * Regression test for KANWEBS-1863. The node metadata summary on the
     * administrative node view was showing the published revision rather than
     * the current revision.
     *
     * @see https://one-agency.atlassian.net/browse/KANWEBS-1863
     *
     * @group contentType
     * @group nodeMetadataSummary
     * @group nodeMetadataSummaryTestBase
     * @group system
     * @group regression
     * @group workflow
     */
    public function testNodeSummaryCurrentRevision()
    {
        $this->userSessionService->login('ChiefEditor');

        // Create the node.
        $nid = $this->setupNode();
        $this->viewPage->go($nid);

        // Publish the node.
        $this->viewPage->contextualToolbar->buttonPublish->click();
        $this->viewPage->checkArrival();

        // Change the responsible author.
        $this->viewPage->contextualToolbar->buttonPageProperties->click();
        $this->editPage->checkArrival();
        $this->editPage->responsibleAuthor->fill('dem');
        $this->autoComplete->waitUntilDisplayed();
        $suggestions = $this->autoComplete->getSuggestions();
        $this->autoComplete->pickSuggestionByValue($suggestions[0]);
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->viewPage->checkArrival();
        $this->viewPage->nodeSummary->showAllMetadata();

        // Check that the responsible author is shown in the metadata summary.
        $metadata = $this->viewPage->nodeSummary->getMetadata('created', 'page-responsible-author');
        $this->assertEquals(trim($suggestions[0]), $metadata['value']);
    }

    /**
     * Test the metadata summary of a certain node on a certain page.
     *
     * @param $page
     * @param $node
     * @param boolean $extended
     *   Whether or not the extended node summary is shown.
     */
    public function assertNodeSummary($page, $node, $extended = false)
    {
        // Test the nid.
        $nid_metadata = $page->nodeSummary->getMetadata('general', 'nid');
        $this->assertEquals($node->nid, $nid_metadata['value']);

        // Test the content type.
        $type_metadata = $page->nodeSummary->getMetadata('general', 'type');
        $this->assertEquals($node->type, $type_metadata['value_raw']);

        // Test the status.
        $status_metadata = $page->nodeSummary->getMetadata('general', 'status');
        $this->assertEquals('Concept', $status_metadata['value']);

        // Test the creation date.
        $created_metadata = $page->nodeSummary->getMetadata('created', 'created');
        $formatted = $this->formatTimestamp($node->created);
        $this->assertEquals($formatted, $created_metadata['value']);

        // Test the creation author.
        $author_metadata = $page->nodeSummary->getMetadata('created', 'created-author');
        $this->assertEquals('demo_editor', $author_metadata['value']);

        // Test the assigned author. (Should be empty in this stage.)
        // We test against the raw value , because the theme might use
        // something else as the default '-' for empty values.
        $assignee_metadata = $page->nodeSummary->getMetadata('workflow', 'assigned');
        $this->assertEquals('', $assignee_metadata['value_raw']);

        // Test the last modified date.
        $changed_metadata = $page->nodeSummary->getMetadata('workflow', 'changed');
        $formatted = $this->formatTimestamp($node->changed);
        $this->assertEquals($formatted, $changed_metadata['value']);

        // Test the last modification author. At this point it should be the
        // same as the creation author, so we check against that username.
        $changed_uid_metadata = $page->nodeSummary->getMetadata('workflow', 'changed-author');
        $this->assertEquals('demo_editor', $changed_uid_metadata['value']);

        // Test the publish date. This should be empty at this point. We
        // test against the raw value for the same reason as stated above.
        $publish_metadata = $page->nodeSummary->getMetadata('publication', 'publish');
        $this->assertEquals('', $publish_metadata['value_raw']);

        // Test the unpublish date. This should be empty at this point. We
        // test against the raw value for the reason stated above.
        $unpublish_metadata = $page->nodeSummary->getMetadata('publication', 'unpublish');
        $this->assertEquals('', $unpublish_metadata['value_raw']);

        // Test the validation author. This should be empty at this point.
        // We test against the raw value for the reason stated above.
        $validation_metadata = $page->nodeSummary->getMetadata('publication', 'validation');
        $this->assertEquals('', $validation_metadata['value_raw']);

        if ($extended == true) {
            // Test the SEO title.
            $seo_title = $node->field_paddle_seo_title[LANGUAGE_NONE][0]['safe_value'];
            $seo_title_metadata = $this->viewPage->nodeSummary->getMetadata('seo', 'seo-title');
            $this->assertEquals($seo_title, $seo_title_metadata['value']);

            // Test the SEO description.
            $seo_description = $node->field_paddle_seo_description[LANGUAGE_NONE][0]['safe_value'];
            $seo_description_metadata = $this->viewPage->nodeSummary->getMetadata('seo', 'seo-description');
            $this->assertEquals($seo_description, $seo_description_metadata['value']);

            // Test the url alias.
            $alias = url($node->path['alias'], array(
                'alias' => true,
            ));
            $alias_metadata = $this->viewPage->nodeSummary->getMetadata('structure', 'alias');
            // Check that the metadata alias contains the alias from the test,
            // and not that it matches exactly. The actual alias may have been
            // prefixed with the subdirectory of the Drupal installation, but
            // the bootstrapped url() used above doesn't know whether the Drupal
            // installation is in a subdirectory or not.
            $this->assertContains($alias, $alias_metadata['value']);

            // Test the tags.
            $tags = array();
            foreach ($node->field_paddle_tags[LANGUAGE_NONE] as $tag_field) {
                $tid = $tag_field['tid'];
                $term = taxonomy_term_load($tid);
                $tags[$tid] = $term->name;
            }
            $tag_metadata = $this->viewPage->nodeSummary->getMetadata('structure', 'tags');
            $tags_string = implode($tags, ', ');
            $tags_tids = implode(array_keys($tags), ',');
            $this->assertEquals($tags_string, $tag_metadata['value']);
            $this->assertEquals($tags_tids, $tag_metadata['value_raw']);

            // Test the general terms.
            $general_terms = array();
            foreach ($node->field_paddle_general_tags[LANGUAGE_NONE] as $term_field) {
                $tid = $term_field['tid'];
                $term = taxonomy_term_load($tid);
                $general_terms[$tid] = $term->name;
            }
            $term_metadata = $this->viewPage->nodeSummary->getMetadata('structure', 'general-tags');
            $term_string = implode($general_terms, ', ');
            $term_tids = implode(array_keys($general_terms), ',');
            $this->assertEquals($term_string, $term_metadata['value']);
            $this->assertEquals($term_tids, $term_metadata['value_raw']);

            // Test the menu links.
            foreach ($node->menu_links as $menu_name => $menu_links) {
                $menu_link_mlid = end(array_keys($menu_links));
                $menu_link_metadata = $this->viewPage->nodeSummary->getMetadata('structure', 'menu-link-' . $menu_link_mlid);
                $this->assertEquals(implode($menu_links, ' > '), $menu_link_metadata['value']);
            }
        }
    }

    /**
     * Formats a timestamp to the format used in the node metadata summary.
     *
     * @param int $timestamp
     *   Unix timestamp.
     *
     * @return string
     *   Formatted date and time.
     */
    protected function formatTimestamp($timestamp)
    {
        return format_date($timestamp, 'short');
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        $this->contentCreationService->cleanUp($this);
        parent::tearDown();
    }

    /**
     * @param $node
     */
    protected function checkSummaryOnLayoutPage($node)
    {
        $this->layoutPage->checkArrival();
        $this->layoutPage->nodeSummary->showAllMetadata();
        $this->assertNodeSummary($this->layoutPage, $node);
        $this->layoutPage->contextualToolbar->buttonBack->click();
        $this->acceptAlert();
    }
}
