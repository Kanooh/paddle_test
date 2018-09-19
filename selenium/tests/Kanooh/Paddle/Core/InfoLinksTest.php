<?php

/**
 * @file
 * Contains \Kanooh\Paddle\InfoLinksTest.
 */

namespace Kanooh\Paddle\Core;

use Kanooh\Paddle\Pages\User\LoginPage\LoginPage;
use Kanooh\Paddle\Pages\User\PasswordPage\PasswordPage;
use Kanooh\Paddle\Utilities\DrupalService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests the info links on the user login and forgot password pages.
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class InfoLinksTest extends WebDriverTestCase
{

    /**
     * The login page.
     *
     * @var LoginPage
     */
    protected $loginPage;

    /**
     * The password page.
     *
     * @var PasswordPage
     */
    protected $passwordPage;

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
        $this->loginPage = new LoginPage($this);
        $this->passwordPage = new PasswordPage($this);
        $this->userSessionService = new UserSessionService($this);

        $drupal = new DrupalService();
        $drupal->bootstrap($this);
    }

    /**
     * Tests the info links on the user login and forgot password pages.
     * @todo Test the More info links.
     *
     * @group admin
     */
    public function testInfoLinks()
    {
        // We need to be on a(ny) web page before we can perform HTTP requests.
        $this->loginPage->go();

        $this->assertHeaderTopIsNotPresent();

        $original_title = variable_get('site_name');

        $new_title = 'Paddle @&">/\\<\'';
        variable_set('site_name', $new_title);

        // Refresh the page for the changes to take effect.
        $this->loginPage->go();

        // Make sure the subject of the contact link starts with the new site
        // title.
        $message = "Contact link's subject on login page contains the site name.";
        $this->assertLinkSubjectStartsWith($this->loginPage->linkContact, $new_title, $message);

        // Set the title back to the original title.
        variable_set('site_name', $original_title);

        $this->passwordPage->go();
        $this->assertHeaderTopIsNotPresent();

        // Now test the password page for a logged in user.
        $this->userSessionService->login('SiteManager');
        $this->passwordPage->go();
        $this->assertHeaderTopIsNotPresent();
    }

    /**
     * Verify there is no header top with search bar etc.
     */
    public function assertHeaderTopIsNotPresent()
    {
        $this->assertFalse($this->isElementByPropertyPresent('class', '.header-top'));
    }

    /**
     * Asserts that a link's subject parameters start with a specific string.
     *
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $link
     *   Contact link that should contain the site name in its subject parameter.
     * @param string $needle
     *   Needle that should be present in the href attribute.
     * @param string $message
     *   Message to show.
     */
    public function assertLinkSubjectStartsWith($link, $needle, $message = '')
    {
        // We need to decode the href, because the supplied string could contain
        // invalid characters for urls. Urlencoding the string doesn't always
        // work, eg. spaces would be encoded to + instead of %20.
        $href = urldecode($link->attribute('href'));

        // Get the subject from the href string.
        $query = parse_url($href, PHP_URL_QUERY);
        $query_array = array();
        parse_str($query, $query_array);
        $subject = isset($query_array['subject']) ? $query_array['subject'] : '';

        $this->assertEquals(strpos($subject, $needle), 0, $message);
    }
}
