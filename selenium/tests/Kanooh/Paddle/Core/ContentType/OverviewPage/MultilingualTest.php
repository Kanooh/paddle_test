<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\OverviewPage\MultilingualTest
 */

namespace Kanooh\Paddle\Core\ContentType\OverviewPage;

use Kanooh\Paddle\Apps\Multilingual;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleMultilingual\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs tests on the automatic creation of overview pages.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class MultilingualTest extends WebDriverTestCase
{
    /**
     * App service.
     *
     * @var AppService
     */
    protected $appService;

    /**
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * User session service.
     *
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Instantiate some classes to use in the test.
        $this->configurePage = new ConfigurePage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Log in as site manager.
        $this->userSessionService->login('ChiefEditor');
        $this->appService = new AppService($this, $this->userSessionService);

        // Enable the multilingual paddlet.
        $this->appService->enableApp(new Multilingual);
    }

    /**
     * Tests the automatic creation of overview pages.
     */
    public function testAutomaticCreationOverviewPages()
    {
        $nid = $this->contentCreationService->createOverviewPage();

        // Get all the translations for the source node.
        $translated_nodes = db_select('node', 'n')
          ->fields('n')
          ->condition('n.type', 'paddle_overview_page', '=')
          ->condition('tnid', $nid, '=')
          ->execute()
          ->fetchAll();

        $enabled_languages = language_list('enabled');
        $this->assertEquals(count($enabled_languages[1]), count($translated_nodes));

        $default_languages = array_keys($enabled_languages[1]);
        $found_languages = array();
        foreach ($translated_nodes as $node) {
            $this->assertContains($node->language, $default_languages);
            $found_languages[] = $node->language;
        }

        $intersection = array_intersect($default_languages, $found_languages);
        $this->assertEquals(count($enabled_languages[1]), count($intersection));

        // Tests the automatic creation of an overview page when a new language is
        // being enabled.
        // Go to the configuration page and enable an extra language.
        $this->configurePage->go();
        $this->configurePage->form->enableBulgarian->check();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved.');
        $this->configurePage->checkArrival();

        // Verify an extra node has been created for the newly created language.
        $translated_node = db_select('node', 'n')
          ->fields('n')
          ->condition('n.type', 'paddle_overview_page', '=')
          ->condition('tnid', $nid, '=')
          ->condition('language', 'bg', '=')
          ->execute()
          ->fetchAll();

        $this->assertNotEmpty($translated_node);

        // Go to the configuration page and restore defaults.
        $this->configurePage->go();
        $this->configurePage->form->enableBulgarian->uncheck();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved.');
        $this->configurePage->checkArrival();
    }
}
