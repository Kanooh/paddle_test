<?php

/**
 * @file
 * Contains Kanooh\Paddle\Core\Wysiwyg\CKEditorConsistencyTest.
 */

namespace Kanooh\Paddle\Core\Wysiwyg;

use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests that text saved with CKEditor is consistent.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class CKEditorConsistencyTest extends WebDriverTestCase
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
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->editPage = new EditPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * Tests that text saved with CKEditor is not changed badly by HTMLPurifier.
     *
     * @group wysiwyg
     */
    public function testCKEditorHTMLPurifier()
    {
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $nid = $this->contentCreationService->createBasicPage($title);

        $this->administrativeNodeView->go($nid);
        $this->administrativeNodeView->contextualToolbar->buttonPageProperties->click();
        $this->editPage->checkArrival();

        // Set the HTML in the CKEditor instance.
        $this->editPage->body->waitUntilReady();
        $this->editPage->body->buttonSource->click();
        $html = $this->fullOptionsText();
        $this->editPage->body->setBodyText($html);

        // Verify that the correct buttons are shown.
        $this->assertTrue($this->editPage->body->buttonJustifyleft->displayed());
        $this->assertTrue($this->editPage->body->buttonJustifyright->displayed());
        $this->assertTrue($this->editPage->body->buttonJustifycenter->displayed());

        // Save the page.
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeView->checkArrival();

        // Edit again to check that the HTML is the same.
        $this->administrativeNodeView->contextualToolbar->buttonPageProperties->click();
        $this->editPage->checkArrival();
        $this->editPage->body->waitUntilReady();
        $this->editPage->body->buttonSource->click();
        $body_text = $this->editPage->body->getBodyText();

        // Check that HTMLPurifier didn't strip something.
        $this->assertEquals(preg_replace('/\s+/', '', $html), preg_replace('/\s+/', '', $body_text));

        // Now add some data attributes and verify that they are saved.
        $html = '<div data-doelgroep="doelgroep"><span data-metdetail="metdetail">text</span></div>';
        $this->editPage->body->setBodyText($html);

        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeView->checkArrival();

        $this->administrativeNodeView->contextualToolbar->buttonPageProperties->click();
        $this->editPage->checkArrival();
        $this->editPage->body->waitUntilReady();
        $body_text = $this->editPage->body->getBodyText();
        // Check that HTMLPurifier didn't strip something.
        $this->assertEquals(preg_replace('/\s+/', '', $html), preg_replace('/\s+/', '', $body_text));
    }

    /**
     * Provides html with all the options our CKEditor profile supports.
     *
     * @return string
     *   The HTML text.
     */
    public function fullOptionsText()
    {
        return '<p>normal</p><h2>heading 2</h2><h3>heading 3</h3><h4>heading 4</h4><h5>heading 5</h5><blockquote>quote</blockquote><p><strong>BOLD</strong></p><p><em>ITALIC</em></p><p><u>UNDER</u></p><p><s>STRIKE</s></p><p><sub>SUBSCRIPT</sub></p><p><sup>SUPERSCRIPT</sup></p><p>BULLET LIST</p><ul><li>one</li><li>two</li><li>three</li></ul><p>ORDERED LIST</p><ol><li>one</li><li>two</li><li>three</li></ol><p class="rteindent1">INDENTED</p><p class="rtecenter">ALIGN CENTER</p><p class="rteright">ALIGN RIGHT</p><p class="rtejustify">ALIGN JUSTIFY</p><p class="rtejustify"><span class="atom-file-container atom-file file-application-pdf" contenteditable="false"><a href="http://localhost/testing/sites/default/files/atoms/files/pdf-sample.pdf">pdf-sample.pdf</a> (7.76 KB)</span></p><p class="rtejustify"><img alt="MEEEE" src="http://localhost/testing/sites/default/files/thumbnails/image/me.jpg" title="" /></p><div class="playable-video"><video controls="controls" height="480" poster="http://localhost/testing/sites/default/files/thumbnails/video/youtube-CTmxDQ4y66k.jpg" preload="none" width="640"><source src="https://www.youtube.com/watch?v=CTmxDQ4y66k" type="video/youtube" /><track kind="subtitles" src="http://localhost/testing/sites/default/files/subtitles/sample_subtitles.srt" srclang="nl" /></video></div><hr /><table align="center" border="1" cellpadding="1" cellspacing="1" style="width: 100%;" summary="Summary"><caption>Caption</caption><thead style="background-color: #AABBCC;"><tr><th>Kjij</th><th>Mfik</th></tr><tr><th colspan="2">Span th</th></tr></thead><tbody style="background-color: #FFABFF;"><tr style="background-color: #0000FF;"><td style="border-color: rgb(255, 0, 0); background-color: #FF0000;">1</td><td>4</td></tr><tr><td>2</td><td>5</td></tr><tr><td>3</td><td>6</td></tr><tr><td colspan="2">Span td</td></tr></tbody></table><p><a id="anchor" name="anchor"></a></p><p><a href="http://google.com" id="some-id" target="_blank">LINK</a></p>';
    }
}
