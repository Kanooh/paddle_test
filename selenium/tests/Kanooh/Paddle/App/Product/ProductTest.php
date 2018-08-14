<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Product\ProductTest.
 */

namespace Kanooh\Paddle\App\Product;

use Kanooh\Paddle\Apps\OrganizationalUnit;
use Kanooh\Paddle\Apps\Product;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Admin\Archive\ArchiveNodeModal;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as FrontEndView;
use Kanooh\Paddle\Pages\Node\EditPage\EditOrganizationalUnitPage;
use Kanooh\Paddle\Pages\Node\EditPage\Product\ProductEditPage;
use Kanooh\Paddle\Pages\Node\ViewPage\Product\ProductViewPage;
use Kanooh\Paddle\Pages\Node\DeletePage\DeletePage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class NodeTest
 * @package Kanooh\Paddle\App\Product
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ProductTest extends WebDriverTestCase
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
     * @var AssetCreationService
     */
    protected $assetCreationService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var EditOrganizationalUnitPage
     */
    protected $editOuPage;

    /**
     * @var ProductEditPage
     */
    protected $editPage;

    /**
     * @var DeletePage
     */
    protected $deletePage;

    /**
     * @var ProductViewPage
     */
    protected $frontendPage;

    /**
     * @var FrontEndView
     */
    protected $frontendNodeViewPage;

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
        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->assetCreationService = new AssetCreationService($this);
        $this->editOuPage = new EditOrganizationalUnitPage($this);
        $this->editPage = new ProductEditPage($this);
        $this->deletePage = new DeletePage($this);
        $this->frontendPage = new ProductViewPage($this);
        $this->frontendNodeViewPage = new FrontEndView($this);

        // Go to the login page and log in as Chief Editor.
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new Product);
        $this->appService->enableApp(new OrganizationalUnit);
    }

    /**
     * Tests the node edit of the product page.
     *
     * @group Product
     */
    public function testNodeEdit()
    {
        // Create an OU for later reference.
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $ou_nid = $this->contentCreationService->createOrganizationalUnit($title);
        $this->administrativeNodeViewPage->go($ou_nid);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Create a product for testing.
        $product_nid = $this->contentCreationService->createProductPage();

        // Go to the edit page and fill out all custom fields.
        $this->editPage->go($product_nid);

        $form_url = 'https://twitter.com/';
        $form_title = $this->alphanumericTestDataProvider->getValidValue();
        $this->editPage->productEditForm->form->url->fill($form_url);
        $this->editPage->productEditForm->form->title->fill($form_title);

        $this->editPage->productEditForm->organizationalUnit->fill($title);
        $this->editPage->productEditForm->organizationalUnit->waitForAutoCompleteResults();
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilSuggestionCountEquals(1);
        $autocomplete->pickSuggestionByPosition(0);

        $introduction = $this->alphanumericTestDataProvider->getValidValue();
        $this->editPage->productEditForm->introduction->setBodyText($introduction);

        $conditions = $this->alphanumericTestDataProvider->getValidValue();
        $this->editPage->productEditForm->conditions->setBodyText($conditions);

        $procedure = $this->alphanumericTestDataProvider->getValidValue();
        $this->editPage->productEditForm->procedure->setBodyText($procedure);

        $amount = $this->alphanumericTestDataProvider->getValidValue();
        $this->editPage->productEditForm->amount->setBodyText($amount);

        $required = $this->alphanumericTestDataProvider->getValidValue();
        $this->editPage->productEditForm->required->setBodyText($required);

        $target_group = $this->alphanumericTestDataProvider->getValidValue();
        $this->editPage->productEditForm->targetGroup->setBodyText($target_group);

        $exceptions = $this->alphanumericTestDataProvider->getValidValue();
        $this->editPage->productEditForm->exceptions->setBodyText($exceptions);

        $legislation_url = 'https://facebook.com/';
        $legislation_title = $this->alphanumericTestDataProvider->getValidValue();
        $rows = $this->editPage->productEditForm->legislationTable->rows;
        $rows[0]->url->fill($legislation_url);
        $rows[0]->title->fill($legislation_title);

        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Go back to the edit page and verify that everything has been kept.
        $this->editPage->go($product_nid);
        $this->assertEquals($form_url, $this->editPage->productEditForm->form->url->getContent());
        $this->assertEquals($form_title, $this->editPage->productEditForm->form->title->getContent());
        $this->assertContains($title, $this->editPage->productEditForm->organizationalUnit->getContent());
        $this->assertContains($introduction, $this->editPage->productEditForm->introduction->getBodyText());
        $this->assertContains($conditions, $this->editPage->productEditForm->conditions->getBodyText());
        $this->assertContains($procedure, $this->editPage->productEditForm->procedure->getBodyText());
        $this->assertContains($amount, $this->editPage->productEditForm->amount->getBodyText());
        $this->assertContains($target_group, $this->editPage->productEditForm->targetGroup->getBodyText());
        $this->assertContains($exceptions, $this->editPage->productEditForm->exceptions->getBodyText());

        $rows = $this->editPage->productEditForm->legislationTable->rows;
        $this->assertEquals($legislation_url, $rows[0]->url->getContent());
        $this->assertEquals($legislation_title, $rows[0]->title->getContent());
    }

    /**
     * Tests the fields order on product view page.
     *
     * @group Product
     */
    public function testFieldsOrder()
    {
        // Create a product for testing.
        $product_nid = $this->contentCreationService->createProductPage();

        // Go to the edit page and fill out all custom fields.
        $this->editPage->go($product_nid);

        $form_url = 'https://twitter.com/';
        $form_title = $this->alphanumericTestDataProvider->getValidValue();
        $this->editPage->productEditForm->form->url->fill($form_url);
        $this->editPage->productEditForm->form->title->fill($form_title);

        $body = $this->alphanumericTestDataProvider->getValidValue();
        $this->editPage->productEditForm->body->setBodyText($body);

        $introduction = $this->alphanumericTestDataProvider->getValidValue();
        $this->editPage->productEditForm->introduction->setBodyText($introduction);

        $conditions = $this->alphanumericTestDataProvider->getValidValue();
        $this->editPage->productEditForm->conditions->setBodyText($conditions);

        $procedure = $this->alphanumericTestDataProvider->getValidValue();
        $this->editPage->productEditForm->procedure->setBodyText($procedure);

        $amount = $this->alphanumericTestDataProvider->getValidValue();
        $this->editPage->productEditForm->amount->setBodyText($amount);

        $required = $this->alphanumericTestDataProvider->getValidValue();
        $this->editPage->productEditForm->required->setBodyText($required);

        $target_group = $this->alphanumericTestDataProvider->getValidValue();
        $this->editPage->productEditForm->targetGroup->setBodyText($target_group);

        $exceptions = $this->alphanumericTestDataProvider->getValidValue();
        $this->editPage->productEditForm->exceptions->setBodyText($exceptions);

        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        $expected_fields = array('field-paddle-prod-form', 'field-paddle-introduction','body');

        // Check that the fields are in the correct order on the front-end view.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->frontendNodeViewPage->checkArrival();
        $this->checkFieldOrder($expected_fields);
    }

    /**
     * Tests the lead image pane to be shown.
     *
     * @group Product
     */
    public function testNodeViewLeadImagePane()
    {
        // Create a product and fill out the featured image field.
        $atom_1 = $this->assetCreationService->createImage();
        $atom_2 = $this->assetCreationService->createImage();
        $nid = $this->contentCreationService->createProductPageViaUI();
        $this->editPage->go($nid);
        $this->editPage->featuredImage->selectAtom($atom_1['id']);
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Verify that the lead image pane is shown in the front end.
        $this->frontendPage->go($nid);
        $this->assertEquals('atom-id-' . $atom_1['id'], $this->frontendPage->leadImagePane->image->attribute('class'));

        // Edit the page and replace the featured image.
        $this->editPage->go($nid);
        $this->editPage->featuredImage->clear();
        $this->editPage->featuredImage->selectAtom($atom_2['id']);
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Verify that the updated lead image pane is shown in the front end.
        $this->frontendPage->go($nid);
        $this->assertEquals('atom-id-' . $atom_2['id'], $this->frontendPage->leadImagePane->image->attribute('class'));

        // Remove the featured image and verify that the pane has been removed.
        $this->editPage->go($nid);
        $this->editPage->featuredImage->clear();
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        $this->frontendPage->go($nid);
        try {
            $this->frontendPage->leadImagePane;
            $this->fail('there should be no image pane shown.');
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            // Everything is fine.
        }
    }

    /**
     * Tests the contact pane to be shown.
     *
     * @group Product
     */
    public function testNodeViewContactPane()
    {
        // Create an organizational unit and fill out the wanted fields. Publish
        // the page.
        $ou_title = $this->alphanumericTestDataProvider->getValidValue();
        $ou_nid = $this->contentCreationService->createOrganizationalUnit($ou_title);
        $this->editOuPage->go($ou_nid);

        $street = $this->alphanumericTestDataProvider->getValidValue() . ' ' . $this->alphanumericTestDataProvider->generateRandomInteger(1, 10);
        $postal = $this->alphanumericTestDataProvider->generateRandomInteger(1, 10);
        $city = $this->alphanumericTestDataProvider->getValidValue();
        $email = 'dontmovetogent@please.com';
        $website = 'http://www.old-guys-smell.com';
        $phone = $this->alphanumericTestDataProvider->generateRandomInteger(1000000000, 9999999999);

        $this->editOuPage->locationStreet->fill($street);
        $this->editOuPage->locationPostalCode->fill($postal);
        $this->editOuPage->locationCity->fill($city);
        $this->editOuPage->email->fill($email);
        $this->editOuPage->website->fill($website);
        $this->editOuPage->phone->fill($phone);

        $this->editOuPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Create a product and fill the responsible organization field.
        $nid = $this->contentCreationService->createProductPageViaUI();
        $this->editPage->go($nid);
        $this->editPage->productEditForm->organizationalUnit->fill($ou_title);
        $this->editPage->productEditForm->organizationalUnit->waitForAutoCompleteResults();
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilSuggestionCountEquals(1);
        $autocomplete->pickSuggestionByPosition(0);

        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Verify that the contact pane is shown in the front end.
        $this->frontendPage->go($nid);
        $this->assertNotEmpty($this->frontendPage->contactPane->topSectionText);
        $this->assertEquals($ou_title, $this->frontendPage->contactPane->title->text());
        $this->assertEquals($street, $this->frontendPage->contactPane->street->text());
        $this->assertEquals($postal, $this->frontendPage->contactPane->postalCode->text());
        $this->assertEquals($city, $this->frontendPage->contactPane->locality->text());
        $this->assertEquals($email, $this->frontendPage->contactPane->email->text());
        $this->assertContains($email, $this->frontendPage->contactPane->email->attribute('href'));
        $this->assertEquals($website, $this->frontendPage->contactPane->website->text());
        $this->assertContains($website, $this->frontendPage->contactPane->website->attribute('href'));
        $this->assertEquals(t('Tel.') . ' ' . $phone, $this->frontendPage->contactPane->phone->text());

        // Remove the featured image and verify that the pane has been removed.
        $this->editPage->go($nid);
        $this->editPage->productEditForm->organizationalUnit->clear();
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        $this->frontendPage->go($nid);
        try {
            $this->frontendPage->contactPane;
            $this->fail('there should be no contact pane shown.');
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            // Everything is fine.
        }

        // Add the organizational unit reference back to the product page.
        $this->editPage->go($nid);
        $this->editPage->productEditForm->organizationalUnit->fill($ou_title);
        $this->editPage->productEditForm->organizationalUnit->waitForAutoCompleteResults();
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilSuggestionCountEquals(1);
        $autocomplete->pickSuggestionByPosition(0);

        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Then archive and delete the organizational unit node.
        $this->administrativeNodeViewPage->go($ou_nid);
        $this->administrativeNodeViewPage->contextualToolbar->buttonArchive->click();
        $modal = new ArchiveNodeModal($this);
        $modal->waitUntilOpened();
        $modal->confirm();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonDelete->click();
        $this->deletePage->checkArrival();
        $this->deletePage->buttonConfirm->click();

        // Verify that the contact pane is not there anymore.
        $this->frontendPage->go($nid);
        try {
            $this->frontendPage->contactPane;
            $this->fail('there should be no contact pane shown.');
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
          // Everything is fine.
        }
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
        $fields_xpath = '//div[contains(@class, "node")]/div[@class="content"]//div';
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
