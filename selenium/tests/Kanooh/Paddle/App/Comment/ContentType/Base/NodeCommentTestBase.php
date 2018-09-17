<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Comment\ContentType\Base\NodeCommentTestBase.
 */

namespace Kanooh\Paddle\App\Comment\ContentType\Base;

use Drupal\Driver\Exception\Exception;
use Kanooh\Paddle\Apps\Comment;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleComment\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as FrontEndNodeViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs tests for comments on content types.
 *
 * @package Kanooh\Paddle\App\Comment\ContentType\Base
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
abstract class NodeCommentTestBase extends WebDriverTestCase
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
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * @var EditPage
     */
    protected $editPage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var FrontEndNodeViewPage
     */
    protected $frontEndNodeViewPage;

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

        // Prepare some variables for later use.
        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->configurePage = new ConfigurePage($this);
        $this->editPage = new EditPage($this);
        $this->frontEndNodeViewPage = new FrontEndNodeViewPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);

        // Log in as a site manager.
        $this->userSessionService->login('ChiefEditor');
        $this->appService->enableApp(new Comment);
    }

    /**
     * Create a node for later use.
     *
     * @return string
     *   The node id of the created node.
     */
    abstract protected function setupNode();

    /**
     * Get the machine name of the content type.
     *
     * @return string
     *   The machine name of the content type.
     */
    abstract protected function getContentTypeName();

    /**
     * Tests the saving of the paddlet's default settings and the configuration.
     *
     * @group NodeCommentTestBase
     */
    public function testDefaultSettingsAndConfiguration()
    {
        $type_name = $this->getContentTypeName();
        // Check some default settings now the paddlet is installed.
        $this->assertDefaultContentTypeSettings($type_name);

        // Now check the configuration page.
        $this->configurePage->go();
        $content_type = 'type' . str_replace(' ', '', ucwords(str_replace('_', ' ', $type_name)));
        $this->assertFalse($this->configurePage->form->$content_type->isChecked());

        // Now check the content type settings.
        $this->configurePage->form->$content_type->check();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved.');

        // Use the global $conf as variable_get() is not immediately updated.
        $conf = variable_initialize();
        $this->assertEquals(COMMENT_NODE_CLOSED, $conf['comment_' . $type_name]);

        // Restore the default value.
        $this->configurePage->form->$content_type->uncheck();
        $this->configurePage->contextualToolbar->buttonSave->click();
    }

    /**
     * Tests if the comment settings form is correctly displayed on the node edit form.
     *
     * @group NodeCommentTestBase
     */
    public function testCommentEnablingPerContentType()
    {
        $type_name = $this->getContentTypeName();
        $this->configurePage->go();
        $content_type = 'type' . str_replace(' ', '', ucwords(str_replace('_', ' ', $type_name)));

        // Now check the content type settings.
        $this->configurePage->form->$content_type->check();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved.');

        // Create a page.
        $nid = $this->setupNode();
        $this->editPage->go($nid);

        // Verify the default settings and verify no comment form is shown in the front end.
        $this->assertTrue($this->editPage->commentRadioButtons->isDisplayed());
        $this->assertTrue($this->editPage->commentRadioButtons->closed->isEnabled());
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->frontEndNodeViewPage->go($nid);
        $this->assertFalse($this->frontEndNodeViewPage->commentForm);

        // Some content types have something set in the content already, ie:
        // poll has the vote button set. Also, when paddle_social_media is
        // enabled, the div will be shown because the share buttons will be
        // present.
        // @TODO: After KANWEBS-5312 has been fixed, the check should happen on
        // the Rate Module again.
        $node = node_load($nid);
        if (!in_array($node->type, array('poll', 'calendar_item', 'simple_contact_page')) &&
            !module_exists('paddle_social_media') &&
            !isset($node->field_paddle_enable_rating)
        ) {
            // Verify that no empty box is shown.
            try {
                $this->byCssSelector('.pane-content .node');
                $this->fail('There should be no content shown.');
            } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
                // Do nothing.
            }
        }

        // set the comments to open and verify they are shown in the front end.
        $this->editPage->go($nid);
        $this->editPage->commentRadioButtons->open->select();
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->frontEndNodeViewPage->go($nid);
        $this->assertFalse($this->frontEndNodeViewPage->addCommentLink);
        $comment_text = $this->alphanumericTestDataProvider->getValidValue();
        $this->frontEndNodeViewPage->commentForm->comment->fill($comment_text);
        $this->frontEndNodeViewPage->commentForm->save->click();
        $this->waitUntilTextIsPresent('Your comment has been posted.');

        // Set the comments to hidden and verify they are no longer shown.
        $this->editPage->go($nid);
        $this->editPage->commentRadioButtons->hidden->select();
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->frontEndNodeViewPage->go($nid);
        $this->assertFalse($this->frontEndNodeViewPage->commentForm);

        // Restore the default value.
        $this->configurePage->go();
        $this->configurePage->form->$content_type->uncheck();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved.');
    }

    /**
     * Checks that a given content type has the default comment settings.
     *
     * @param $type_machine_name
     *   The machine name of the content type to check.
     */
    protected function assertDefaultContentTypeSettings($type_machine_name)
    {
        $this->assertEquals(COMMENT_NODE_HIDDEN, variable_get("comment_$type_machine_name", 0));
        $this->assertEquals(COMMENT_MODE_THREADED, variable_get("comment_default_mode_$type_machine_name", 1));
        $this->assertEquals(PADDLE_COMMENTS_PER_PAGE, variable_get("comment_default_per_page_$type_machine_name", 30));
        $this->assertEquals(COMMENT_ANONYMOUS_MAYNOT_CONTACT, variable_get("comment_anonymous_$type_machine_name", 0));
        $this->assertEquals(PADDLE_COMMENTS_TITLE_ALLOWED, variable_get("comment_subject_field_$type_machine_name", 0));
        $this->assertEquals(COMMENT_FORM_BELOW, variable_get("comment_form_location_$type_machine_name", 1));
        $this->assertEquals(DRUPAL_DISABLED, variable_get("comment_preview_$type_machine_name", 0));
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
