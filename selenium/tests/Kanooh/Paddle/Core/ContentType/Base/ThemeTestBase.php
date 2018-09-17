<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\Base\ThemeTestBase.
 */

namespace Kanooh\Paddle\Core\ContentType\Base;

use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminNodeViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\DrupalService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests the themes.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
abstract class ThemeTestBase extends WebDriverTestCase
{

    /**
     * @var AddPage
     */
    protected $addContentPage;

    /**
     * @var AdminNodeViewPage
     */
    protected $adminNodeViewPage;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

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
        $this->addContentPage = new AddPage($this);
        $this->adminNodeViewPage = new AdminNodeViewPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->userSessionService->login('ChiefEditor');
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->appService = new AppService($this, $this->userSessionService);

        $drupal = new DrupalService();
        $drupal->bootstrap($this);
    }

    /**
     * Set up a node.
     */
    abstract protected function setupNode();

    /**
     * Data provider for themes.
     *
     * @return array
     *   An array containing arrays of themes and the modules we need to enable
     *   to get these themes.
     */
    public function themeProvider()
    {
        return array(
          array('paddle_go_themes', 'go_theme'),
          array('paddle_vo_themes', 'vo_strict'),
          array('paddle_vo_themes', 'paddle_theme_branded'),
        );
    }

    /**
     * Tests if a node can be created trough the interface.
     *
     * @param string $module
     *  The name of the module we need to enable in order to get the theme.
     * @param string $theme
     *  The machine name of the theme for which to do the test.
     *
     * @dataProvider themeProvider
     *
     * @group themer
     * @group themeTestBase
     */
    public function testCreateNode($module, $theme)
    {
        // Enable the theme.
        module_enable(array($module));
        drupal_flush_all_caches();
        paddle_themer_enable_theme($theme);

        // Create a node.
        try {
            $this->setupNode();
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            $this->fail('Could not create node.');
            return;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        $this->contentCreationService->cleanUp($this);
        parent::tearDown();
    }
}
