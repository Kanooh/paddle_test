<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Publication\PublicationTest.
 */

namespace Kanooh\Paddle\App\Publication;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Apps\Publication;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Element\Publication\AuthorsTableRow;
use Kanooh\Paddle\Pages\Element\Publication\RelatedDocumentsTableRow;
use Kanooh\Paddle\Pages\Element\Publication\RelatedLinksTableRow;
use Kanooh\Paddle\Utilities\HttpRequest\HttpRequest;
use Kanooh\Paddle\Pages\Node\ViewPage\Publication\PublicationViewPage;
use Kanooh\Paddle\Pages\Node\EditPage\Publication\PublicationEditPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\ScaldService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class PublicationTest
 * @package Kanooh\Paddle\App\Publication
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PublicationTest extends WebDriverTestCase
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
     * @var PublicationEditPage
     */
    protected $editPage;

    /**
     * @var PublicationViewPage
     */
    protected $frontendPage;

    /**
     * @var LayoutPage
     */
    protected $layoutPage;

    /**
     * @var ScaldService
     */
    protected $scaldService;

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
        $this->editPage = new PublicationEditPage($this);
        $this->layoutPage = new LayoutPage($this);
        $this->scaldService = new ScaldService($this);
        $this->frontendPage = new PublicationViewPage($this);


        // Go to the login page and log in as Chief Editor.
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new Publication);
    }

    /**
     * Tests the node edit of the publication page.
     */
    public function testNodeEdit()
    {
        // Create a publication.
        $nid = $this->contentCreationService->createPublicationPage();

        // Create some files for testing.
        $file_1 = $this->assetCreationService->createFile();
        $file_2 = $this->assetCreationService->createFile();

        // Go to the edit page and fill out all custom fields.
        $this->editPage->go($nid);

        $authors_1 = $this->alphanumericTestDataProvider->getValidValue();
        $authors_2 = $this->alphanumericTestDataProvider->getValidValue();
        $keyword = $this->alphanumericTestDataProvider->getValidValue();
        $collections = $this->alphanumericTestDataProvider->getValidValue();
        $mesh_term = $this->alphanumericTestDataProvider->getValidValue();
        $year = $this->alphanumericTestDataProvider->generateRandomInteger(1000, 9999);
        $number = $this->alphanumericTestDataProvider->generateRandomInteger(100, 999);
        $type = $this->alphanumericTestDataProvider->getValidValue();
        $publisher = $this->alphanumericTestDataProvider->getValidValue();
        $place_published = $this->alphanumericTestDataProvider->getValidValue();
        $publication_language = $this->alphanumericTestDataProvider->getValidValue();
        $legal_depot_number = $this->alphanumericTestDataProvider->generateRandomInteger(1000, 9999);
        $study = $this->alphanumericTestDataProvider->getValidValue();
        $documents_language = $this->alphanumericTestDataProvider->getValidValue();
        $url = 'http://' . $this->alphanumericTestDataProvider->getValidValue() . 'com';
        $related_url_1 = 'http://' . $this->alphanumericTestDataProvider->getValidValue() . 'com';
        $related_url_title_1 = $this->alphanumericTestDataProvider->getValidValue();
        $related_url_2 = 'http://' . $this->alphanumericTestDataProvider->getValidValue() . 'com';
        $related_url_title_2 = $this->alphanumericTestDataProvider->getValidValue();
        $date_published = $this->alphanumericTestDataProvider->getValidValue();

        // Set the url fields.
        $this->editPage->publicationEditForm->url->fill($url);

        $rows = $this->editPage->publicationEditForm->relatedLinksTable->rows;
        /** @var RelatedLinksTableRow $row */
        $row = reset($rows);
        $row->title->fill($related_url_title_1);
        $row->url->fill($related_url_1);
        $row->newWindow->check();

        $count_rows = $this->editPage->publicationEditForm->relatedLinksTable->getNumberOfRows();
        $this->editPage->publicationEditForm->relatedLinksTable->addAnotherItem->click();

        // We need to wait until the new row with the fields appears.
        $table = $this->editPage->publicationEditForm->relatedLinksTable;
        $callable = new SerializableClosure(
            function () use ($count_rows, $table) {
                if ($table->getNumberOfRows() == ($count_rows + 1)) {
                    return true;
                }
            }
        );
        $this->waitUntil($callable, $this->getTimeout());

        $rows = $this->editPage->publicationEditForm->relatedLinksTable->rows;
        /** @var relatedLinksTableRow $row */
        $row = end($rows);
        $row->title->fill($related_url_title_2);
        $row->url->fill($related_url_2);

        // Set authors.
        $this->addTwoAuthors($authors_1, $authors_2);

        $this->editPage->publicationEditForm->keywords->fill($keyword);
        $this->editPage->publicationEditForm->collections->fill($collections);
        $this->editPage->publicationEditForm->meshTerms->fill($mesh_term);
        $this->editPage->publicationEditForm->publicationYear->fill($year);
        $this->editPage->publicationEditForm->publicationType->selectOptionByValue('book');
        $this->editPage->publicationEditForm->number->fill($number);
        $this->editPage->publicationEditForm->type->fill($type);
        $this->editPage->publicationEditForm->publisher->fill($publisher);
        $this->editPage->publicationEditForm->placePublished->fill($place_published);
        $this->editPage->publicationEditForm->publicationLanguage->fill($publication_language);
        $this->editPage->publicationEditForm->legalDepotNumber->fill($legal_depot_number);
        $this->editPage->publicationEditForm->study->fill($study);
        $this->editPage->publicationEditForm->documentsLanguage->fill($documents_language);
        $this->editPage->publicationEditForm->datePublished->fill($date_published);

        // Set some related documents.
        $rows = $this->editPage->publicationEditForm->relatedDocumentsTable->rows;
        /** @var RelatedDocumentsTableRow $row */
        $row = reset($rows);
        // Move to the element above the related documents table.
        // This is to make sure the contextual toolbar won't be in the way.
        $this->moveto($this->editPage->publicationEditForm->datePublished->getWebdriverElement());
        $row->atom->selectButton->click();
        $this->scaldService->insertAtom($file_1['id']);

        $count_rows = $this->editPage->publicationEditForm->relatedDocumentsTable->getNumberOfRows();
        $this->editPage->publicationEditForm->relatedDocumentsTable->addAnotherItem->click();

        // We need to wait until the new row with the fields appears.
        $table = $this->editPage->publicationEditForm->relatedDocumentsTable;
        $callable = new SerializableClosure(
            function () use ($count_rows, $table) {
                if ($table->getNumberOfRows() == ($count_rows + 1)) {
                    return true;
                }
            }
        );
        $this->waitUntil($callable, $this->getTimeout());

        $rows = $this->editPage->publicationEditForm->relatedDocumentsTable->rows;
        /** @var RelatedDocumentsTableRow $row */
        $row = end($rows);
        $this->moveto($row->atom->selectButton);
        $row->atom->selectButton->click();
        $this->scaldService->insertAtom($file_2['id']);
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Go back to the edit page and verify that everything has been kept.
        $this->editPage->go($nid);

        $rows = $this->editPage->publicationEditForm->authorsTable->rows;
        $this->assertEquals($authors_1, $rows[0]->name->getContent());
        $this->assertEquals($authors_2, $rows[1]->name->getContent());

        $rows = $this->editPage->publicationEditForm->relatedDocumentsTable->rows;
        $this->assertEquals($file_1['id'], $rows[0]->atom->valueField->value());
        $this->assertEquals($file_2['id'], $rows[1]->atom->valueField->value());

        $this->assertTextPresent($keyword);
        $this->assertTextPresent($mesh_term);
        $this->assertEquals($collections, $this->editPage->publicationEditForm->collections->getContent());
        $this->assertEquals($year, $this->editPage->publicationEditForm->publicationYear->getContent());
        $this->assertEquals($number, $this->editPage->publicationEditForm->number->getContent());
        $this->assertEquals($type, $this->editPage->publicationEditForm->type->getContent());
        $this->assertEquals($place_published, $this->editPage->publicationEditForm->placePublished->getContent());
        $this->assertEquals($publisher, $this->editPage->publicationEditForm->publisher->getContent());
        $this->assertEquals($publication_language, $this->editPage->publicationEditForm->publicationLanguage->getContent());
        $this->assertEquals($legal_depot_number, $this->editPage->publicationEditForm->legalDepotNumber->getContent());
        $this->assertEquals($documents_language, $this->editPage->publicationEditForm->documentsLanguage->getContent());
        $this->assertEquals('book', $this->editPage->publicationEditForm->publicationType->getSelectedValue());
        $this->assertEquals($url, $this->editPage->publicationEditForm->url->getContent());
        $this->assertEquals($date_published, $this->editPage->publicationEditForm->datePublished->getContent());

        $rows = $this->editPage->publicationEditForm->relatedLinksTable->rows;
        $this->assertEquals($related_url_title_1, $rows[0]->title->getContent());
        $this->assertEquals($related_url_1, $rows[0]->url->getContent());
        $this->assertTrue($rows[0]->newWindow->isChecked());
        $this->assertEquals($related_url_title_2, $rows[1]->title->getContent());
        $this->assertEquals($related_url_2, $rows[1]->url->getContent());
        $this->assertFalse($rows[1]->newWindow->isChecked());

        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Verify that the tags have been created.
        $query = new \EntityFieldQuery();
        $query->entityCondition('entity_type', 'taxonomy_term')
            ->entityCondition('bundle', 'paddle_keywords')
            ->propertyCondition('name', $keyword)
            ->range(0, 1);
        $result = $query->execute();
        $this->assertNotEmpty($result);

        $query = new \EntityFieldQuery();
        $query->entityCondition('entity_type', 'taxonomy_term')
            ->entityCondition('bundle', 'paddle_mesh_terms')
            ->propertyCondition('name', $mesh_term)
            ->range(0, 1);
        $result = $query->execute();
        $this->assertNotEmpty($result);

        $query = new \EntityFieldQuery();
        $query->entityCondition('entity_type', 'taxonomy_term')
            ->entityCondition('bundle', 'paddle_publication_year')
            ->propertyCondition('name', $year)
            ->range(0, 1);
        $result = $query->execute();
        $this->assertNotEmpty($result);

        $query = new \EntityFieldQuery();
        $query->entityCondition('entity_type', 'taxonomy_term')
            ->entityCondition('bundle', 'paddle_authors')
            ->propertyCondition('name', array($authors_1, $authors_2), 'IN');
        $result = $query->execute();
        $this->assertNotEmpty($result);
        $this->assertCount(2, $result['taxonomy_term']);
    }

    /**
     * Tests the node page layout of the publication page.
     */
    public function testPageLayout()
    {
        // Create a publication.
        $nid = $this->contentCreationService->createPublicationPage();

        // Create some files for testing.
        $file_1_title = $this->alphanumericTestDataProvider->getValidValue();
        $file_2_title = $this->alphanumericTestDataProvider->getValidValue();

        $file_1 = $this->assetCreationService->createFile(array('title' => $file_1_title));
        $file_2 = $this->assetCreationService->createFile(array('title' => $file_2_title));

        // Set some values for related links.
        $related_url_1 = 'http://' . $this->alphanumericTestDataProvider->getValidValue() . 'com';
        $related_url_title_1 = $this->alphanumericTestDataProvider->getValidValue();
        $related_url_2 = 'http://' . $this->alphanumericTestDataProvider->getValidValue() . 'com';
        $related_url_title_2 = $this->alphanumericTestDataProvider->getValidValue();

        // Go to the edit page and fill out all custom fields.
        $this->editPage->go($nid);

        // Set some related documents.
        $rows = $this->editPage->publicationEditForm->relatedDocumentsTable->rows;
        /** @var RelatedDocumentsTableRow $row */
        $row = reset($rows);
        // Move to the element above to prevent Selenium from trying to click on the contextual menu.
        $this->moveto($this->editPage->publicationEditForm->publicationYear->getWebdriverElement());
        $row->atom->selectButton->click();
        $this->scaldService->insertAtom($file_1['id']);

        $count_rows = $this->editPage->publicationEditForm->relatedDocumentsTable->getNumberOfRows();
        $this->editPage->publicationEditForm->relatedDocumentsTable->addAnotherItem->click();

        // We need to wait until the new row with the fields appears.
        $table = $this->editPage->publicationEditForm->relatedDocumentsTable;
        $callable = new SerializableClosure(
            function () use ($count_rows, $table) {
                if ($table->getNumberOfRows() == ($count_rows + 1)) {
                    return true;
                }
            }
        );
        $this->waitUntil($callable, $this->getTimeout());

        $rows = $this->editPage->publicationEditForm->relatedDocumentsTable->rows;
        /** @var RelatedDocumentsTableRow $row */
        $row = end($rows);
        $this->moveto($row->atom->selectButton);
        $row->atom->selectButton->click();
        $this->scaldService->insertAtom($file_2['id']);

        $rows = $this->editPage->publicationEditForm->relatedLinksTable->rows;
        /** @var RelatedLinksTableRow $row */
        $row = reset($rows);
        $row->title->fill($related_url_title_1);
        $row->url->fill($related_url_1);
        $row->newWindow->check();

        $count_rows = $this->editPage->publicationEditForm->relatedLinksTable->getNumberOfRows();
        $this->editPage->publicationEditForm->relatedLinksTable->addAnotherItem->click();

        // We need to wait until the new row with the fields appears.
        $table = $this->editPage->publicationEditForm->relatedLinksTable;
        $callable = new SerializableClosure(
            function () use ($count_rows, $table) {
                if ($table->getNumberOfRows() == ($count_rows + 1)) {
                    return true;
                }
            }
        );
        $this->waitUntil($callable, $this->getTimeout());

        $rows = $this->editPage->publicationEditForm->relatedLinksTable->rows;
        /** @var relatedLinksTableRow $row */
        $row = end($rows);
        $row->title->fill($related_url_title_2);
        $row->url->fill($related_url_2);

        // Fill in the values which will appear in the related info pane.
        $collection = $this->alphanumericTestDataProvider->getValidValue();
        $this->editPage->publicationEditForm->collections->fill($collection);
        $number = $this->alphanumericTestDataProvider->generateRandomInteger(100, 999);
        $this->editPage->publicationEditForm->number->fill($number);
        $language = $this->alphanumericTestDataProvider->getValidValue();
        $this->editPage->publicationEditForm->documentsLanguage->fill($language);
        $type = $this->alphanumericTestDataProvider->getValidValue();
        $this->editPage->publicationEditForm->type->fill($type);
        $year = $this->alphanumericTestDataProvider->getValidValue();
        $this->editPage->publicationEditForm->publicationYear->fill($year);
        $kce_number = $this->alphanumericTestDataProvider->getValidValue();
        $this->editPage->publicationEditForm->kceNumber->fill($kce_number);
        $authors_1 = $this->alphanumericTestDataProvider->getValidValue();
        $authors_2 = $this->alphanumericTestDataProvider->getValidValue();
        $this->addTwoAuthors($authors_1, $authors_2);
        $keyword_1 = $this->alphanumericTestDataProvider->getValidValue();
        $keyword_2 = $this->alphanumericTestDataProvider->getValidValue();
        $this->editPage->publicationEditForm->keywords->fill($keyword_1);
        $this->editPage->publicationEditForm->keywordsAddButton->click();
        $this->editPage->publicationEditForm->keywords->fill($keyword_2);

        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Go to the layout page and verify that the related documents pane is
        // first in the right region.
        $this->layoutPage->go($nid);
        $region = $this->layoutPage->display->region('right');
        $panes = $region->getPanes();

        $i = 1;
        foreach ($panes as $pane) {
            $element = $pane->getWebdriverElement();
            // Start from position 2 since the first one is the empty placeholder.
            if ($i == 2) {
                $this->assertContains('Related documents', $element->text());
                $this->assertContains($file_1_title, $element->text());
                $this->assertContains($file_2_title, $element->text());
            }

            if ($i == 3) {
                $this->assertContains('Related link', $element->text());
                $this->assertContains($related_url_title_1, $element->text());
                $this->assertContains($related_url_title_2, $element->text());
            }

            $i++;
        }
        // Get the  left pane. We use getLockedRegion, since it is our only locked region.
        $region = $this->layoutPage->display->getLockedRegion();
        $panes = $region->getPanes();
        $i = 1;
        foreach ($panes as $pane) {
            $element = $pane->getWebdriverElement();
            if ($i == 3) {
                // The related info pane.
                if (strpos($element->text(), 'Type:') === false ||
                    strpos($element->text(), 'Publication year:') === false ||
                    strpos($element->text(), 'Report number:') === false ||
                    strpos($element->text(), 'Authors:') === false ||
                    strpos($element->text(), 'Language:') === false ||
                    strpos($element->text(), 'Keywords:') === false
                ) {
                    $this->fail('All labels should be correctly shown in the pane');
                }

                $this->assertContains(ucfirst($keyword_1) . ', ' . $keyword_2, $element->text());
                $this->assertContains($authors_1 . ', ' . $authors_2, $element->text());
                $this->assertContains($type, $element->text());
                $this->assertContains($year, $element->text());
                $this->assertContains($kce_number, $element->text());
                $this->assertContains($language, $element->text());
            }
            if ($i == 4) {
                // The reference number pane.
                if (strpos($element->text(), 'Export the bibliographical reference (.RIS)') === false) {
                    $this->fail('Reference label should be correctly shown in the pane');
                }

                // Make sure the risexport link has the right link.
                $xpath = '//div[contains(@class, "pane-reference-number")]/div/div[contains(@class, "pane-section-body")]/p/a';
                $this->assertContains('risexport.txt/' . $nid, $this->byXPath($xpath)->attribute('href'));
            }
            $i++;
        }
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
        $nid = $this->contentCreationService->createPublicationPageViaUI();
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
     * Adds 2 authors to the edit page.
     *
     * @param string $author_1
     *   The name of the first author.
     * @param $author_2
     *   The name of the second author.
     */
    protected function addTwoAuthors($author_1, $author_2)
    {
        $rows = $this->editPage->publicationEditForm->authorsTable->rows;
        /** @var AuthorsTableRow $row */
        $row = reset($rows);
        $row->name->fill($author_1);

        $count_rows = $this->editPage->publicationEditForm->authorsTable->getNumberOfRows();
        // Move to the element above to prevent Selenium from trying to click on the contextual menu.
        $this->moveto($this->editPage->creationDate);
        $this->editPage->publicationEditForm->authorsTable->addAnotherItem->click();

        // We need to wait until the new row with the fields appears.
        $table = $this->editPage->publicationEditForm->authorsTable;
        $callable = new SerializableClosure(
            function () use ($count_rows, $table) {
                if ($table->getNumberOfRows() == ($count_rows + 1)) {
                    return true;
                }
            }
        );
        $this->waitUntil($callable, $this->getTimeout());

        $rows = $this->editPage->publicationEditForm->authorsTable->rows;
        /** @var AuthorsTableRow $row */
        $row = end($rows);
        $row->name->fill($author_2);
    }


    /**
     * Tests the publications file export functionality.
     */
    public function testPublicationsFileExport()
    {
        // Create a publication and publish it.
        $nid = $this->contentCreationService->createPublicationPage();
        $this->editPage->go($nid);
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();

        // Logged in users should not see the view page.
        $this->url("risexport/$nid");
        $this->assertTextPresent('Access Denied');

        // Send a request to export the TXT and verify the response is correct.
        $request = new HttpRequest($this);
        $request->setMethod(HttpRequest::POST);
        $request->setUrl($this->base_url . '/risexport.txt/' . $nid);
        $request->setData(array('op' => 'Export txt'));
        $response = $request->send();
        $this->assertEquals(200, $response->status);

        // Make sure anonymous user cant see the view export page itself.
        $this->userSessionService->logout();

        // go() method wont work here since it will wait until the class is on the page, so we use URL.
        $this->url("risexport/$nid");
        $this->assertTextPresent('Access denied. You may need to login below or register to access this page.');
    }
}
