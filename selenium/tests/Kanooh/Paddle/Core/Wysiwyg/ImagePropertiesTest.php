<?php

/**
 * @file
 * Contains Kanooh\Paddle\Core\Wysiwyg\ImagePropertiesTest.
 */

namespace Kanooh\Paddle\Core\Wysiwyg;

use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as NodeViewPage;
use Kanooh\Paddle\Pages\Element\Wysiwyg\ImagePropertiesModalAdvancedForm;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\TestDataProvider\UrlTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests the image properties dialog.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ImagePropertiesTest extends WebDriverTestCase
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
     * The frontend node view page.
     *
     * @var NodeViewPage
     */
    protected $nodeViewPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * Provides test data to generate urls.
     *
     * @var UrlTestDataProvider
     */
    protected $urlProvider;

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
        $this->nodeViewPage = new NodeViewPage($this);
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
     * Tests the image properties in the dialog.
     *
     * @dataProvider mockImageProvider
     *
     * @group editing
     * @group regression
     * @group KANWEBS-2274
     */
    public function testImagePropertiesDialog($img_url, $alt, $class, $link)
    {
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $nid = $this->contentCreationService->createBasicPage($title);

        $this->editPage->go($nid);

        // Construct the HTML to insert into the CKEditor. Make sure the class
        // attribute is not set if we have no class name, as this was the cause
        // of the regression. (Images with no class attribute would show no
        // properties data in the dialog.)
        $html = '<img src="' . $img_url . '" alt="' . $alt . '" ' .
            (!empty($class) ? 'class="' . $class . '"' : '') . ' />';

        // We need to make sure that the image dialog also works when inside an
        // <a> tag, as this is the first element passed to the dialog if there
        // is an <a> tag around the image. This element shouldn't have a class
        // attribute either.
        if (!empty($link)) {
            $html = '<a href="' . $link . '">' . $html . '</a>';
        }

        // Set the HTML in the CKEditor instance.
        $this->editPage->body->waitUntilReady();
        $this->editPage->body->setBodyText($html);

        // Double-click the image in the CKEditor.
        $this->editPage->body->inIframe(
            function () {
                $xpath = '//img';
                $this->waitUntilElementIsPresent($xpath);
                $img = $this->byXPath($xpath);
                $this->moveto($img);
                $this->doubleclick();
            }
        );

        // Wait until the image properties modal is open.
        $image_modal = $this->editPage->body->modalImageProperties;
        $image_modal->waitUntilOpened();

        // Make sure the URL and Alternative text fields are set correctly.
        $this->assertEquals($img_url, $image_modal->imageInfoForm->url->getContent());
        $this->assertEquals($alt, $image_modal->imageInfoForm->alternativeText->getContent());

        // Switch to the advanced tab.
        $image_modal->tabs->linkAdvanced->click();
        $image_modal->waitUntilTabDisplayed(ImagePropertiesModalAdvancedForm::TABNAME);

        // Make sure the class field is set correctly.
        $this->assertEquals((string) $class, $image_modal->advancedForm->stylesheetClasses->getContent());

        // Close modal and save page, to prevent alert boxes from popping up
        // after this.
        $image_modal->close();
        $image_modal->waitUntilClosed();
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeView->checkArrival();
    }

    /**
     * Provides various images to test with.
     *
     * @return array
     *   List of images to test on.
     */
    public function mockImageProvider()
    {
        $img_url = $this->base_url . "/misc/druplicon.png";
        $url_provider = new UrlTestDataProvider();

        $images = array();

        // Image url, with alt, no class, no link.
        $images[] = array($img_url, "ALT TEXT", false, false);

        // Image url, with alt, with a class, no link.
        $images[] = array($img_url, "ALT TEXT", "CLASSNAME", false);

        // Image url, with alt, no class, linked.
        $link = $url_provider->getValidValue();
        $images[] = array($img_url, "ALT TEXT", false, $link);

        // Image url, with alt, with class, linked.
        $link = $url_provider->getValidValue();
        $images[] = array($img_url, "ALT TEXT", "CLASSNAME", $link);


        return $images;
    }
}
