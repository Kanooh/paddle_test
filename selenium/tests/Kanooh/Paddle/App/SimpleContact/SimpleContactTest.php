<?php

/**
 * Contains \Kanooh\Paddle\App\SimpleContact\SimpleContactTest.
 */

namespace Kanooh\Paddle\App\SimpleContact;

use Drupal\Component\Utility\Random;
use Kanooh\Paddle\Apps\SimpleContact;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditSimpleContactPagePage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as FrontEndView;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests the Simple Contact Form paddlet.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class SimpleContactTest extends WebDriverTestCase
{
    /**
     * The admin node view.
     *
     * @var ViewPage
     */
    protected $adminNodeViewPage;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * The service to create content of several types.
     *
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * The "edit simple contact page" page.
     *
     * @var EditSimpleContactPagePage
     */
    protected $editSimpleContactPage;

    /**
     * The front-end view of a node.
     *
     * @var FrontEndView
     */
    protected $frontendNodeViewPage;

    /**
     * The random data generation class.
     *
     * @var Random $random
     */
    protected $random;

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
        $this->adminNodeViewPage = new ViewPage($this);
        $this->editSimpleContactPage = new EditSimpleContactPagePage($this);
        $this->frontendNodeViewPage = new FrontEndView($this);
        $this->random = new Random();
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Enable the app we are testing.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new SimpleContact);

        // Go to the login page and log in as chief editor.
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        // Log out.
        $this->userSessionService = new UserSessionService($this);
        $this->userSessionService->logout();

        parent::tearDown();
    }

    /**
     * Tests the creation of a simple contact page.
     */
    public function testCreate()
    {
        $nid = $this->contentCreationService->createSimpleContact();
        $this->adminNodeViewPage->go($nid);
        $form = $this->byClassName('field-name-field-paddle-contact-form')->size();
        $this->assertTrue($form > 0);

        $this->frontendNodeViewPage->go($nid);
        $form = $this->byClassName('field-name-field-paddle-contact-form')->size();
        $this->assertTrue($form > 0);
        $this->assertTextPresent('(required)');
    }

    /**
     * Tests the order of the rendered fields on the admin node view and
     * front-end view.
     *
     * @see https://one-agency.atlassian.net/browse/KANWEBS-2752
     *
     * @group regression
     * @group editing
     */
    public function testFieldsOrder()
    {
        // Create a Simple contact page and edit it to add body.
        $nid = $this->contentCreationService->createSimpleContact();
        $this->editSimpleContactPage->go($nid);
        $this->editSimpleContactPage->body->setBodyText($this->random->name(200));
        $this->editSimpleContactPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        $expected_fields = array('body', 'field-paddle-contact-form');

        // Check that the fields are in the correct order on the admin node view.
        $this->checkFieldOrder($expected_fields);

        // Check that the fields are in the correct order on the front-end view.
        $this->adminNodeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->frontendNodeViewPage->checkArrival();
        $this->checkFieldOrder($expected_fields);
    }

    /**
     * Checks that the expected fields match the found including the order.
     *
     * @param  array $expected_fields
     *   Array containing the machine names of the expected field in the
     *   expected order.
     */
    public function checkFieldOrder($expected_fields)
    {
        $fields_xpath = '//div[contains(@class, "region-content")]//div[contains(@class, "node-simple-contact-page")]/div[contains(@class, "content")]/div[contains(@class, "field")]';
        $found_fields = $this->elements($this->using('xpath')->value($fields_xpath));
        $this->assertTrue(count($found_fields) > 0);

        // Extract the field names of the found fields.
        $field_names = array();
        foreach ($found_fields as $field) {
            $classes = explode(' ', $field->attribute('class'));
            foreach ($classes as $class) {
                if (strpos($class, 'field-name-') === 0) {
                    $field_names[] = str_replace('field-name-', '', $class);
                }
            }
        }

        // Check that the found fields are found and in the correct order.
        foreach ($expected_fields as $index => $field) {
            $this->assertEquals($field, $field_names[$index]);
        }
    }
}
