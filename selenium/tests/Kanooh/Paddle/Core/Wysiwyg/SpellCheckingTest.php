<?php

/**
 * @file
 * Contains Kanooh\Paddle\Core\Wysiwyg\SpellCheckingTest.
 */

namespace Kanooh\Paddle\Core\Wysiwyg;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests the spell-checking functionality in the WYSIWYG.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class SpellCheckingTest extends WebDriverTestCase
{
    /**
     * The administrative node view.
     *
     * @var ViewPage
     */
    protected $administrativeNodeView;

    /**
     * Data provider for alphanumeric data.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * The node edit page.
     *
     * @var EditPage
     */
    protected $editPage;

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
        $this->administrativeNodeView = new ViewPage($this);
        $this->editPage = new EditPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Log in as chief editor.
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
     * Tests that the Scayt spell-checking is disabled by default but is working
     * as a button.
     *
     * @group wysiwyg
     */
    public function testCKEditorSpellChecking()
    {
        $nid = $this->contentCreationService->createBasicPage();

        $this->editPage->go($nid);

        // Set the HTML in the CKEditor instance.
        $this->editPage->body->waitUntilReady();
        $wrong_word = 'hjklopk';
        $marked_word_xpath = '//span[@class = "scayt-misspell-word"][@data-scayt-word = "' . $wrong_word . '"]';
        $this->editPage->body->setBodyText($wrong_word);

        $webdriver = $this;
        $callable = new SerializableClosure(
            function () use ($webdriver, $wrong_word, $marked_word_xpath) {
                // Find the word.
                $xpath = '//p[text() = "' . $wrong_word . '"]';
                $elements = $webdriver->elements($webdriver->using('xpath')->value($xpath));
                $webdriver->assertEquals(count($elements), 1);

                // Make sure the word we entered is not marked as misspelled.
                $elements = $webdriver->elements($webdriver->using('xpath')->value($marked_word_xpath));
                $webdriver->assertEquals(count($elements), 0);
            }
        );

        $this->editPage->body->inIframe($callable);

        // Enable the spell-checking.
        $this->editPage->body->buttonScayt->click();
        $this->editPage->body->toggleSpellChecking();

        // Make sure the word we entered is now marked as misspelled.
        $callable = new SerializableClosure(
            function () use ($webdriver, $wrong_word, $marked_word_xpath) {
                $elements = $webdriver->elements($webdriver->using('xpath')->value($marked_word_xpath));
                $webdriver->assertEquals(count($elements), 1);
            }
        );
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeView->checkArrival();
    }
}
