<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Multilingual\ContentManagerNodeTranslationsTest
 */

namespace Kanooh\Paddle\App\Multilingual;

use Kanooh\Paddle\Apps\Multilingual;
use Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage\SearchPage as ContentManagerPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\MultilingualService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Checks the presence of the links to the translation node page.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ContentManagerNodeTranslationsTest extends WebDriverTestCase
{
    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var ContentManagerPage
     */
    protected $contentManagerPage;

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

        // Instantiate some classes to use in the test.
        $this->contentManagerPage = new ContentManagerPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not enabled yet.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new Multilingual);

        // Make sure the tests have the expected multilingual configuration.
        MultilingualService::setPaddleTestDefaults($this);
    }

    /**
     * Tests if the translations are shown correctly in the content manager.
     */
    public function testCorrectTranslationsShown()
    {
        // Create a basic page.
        $node_1 = $this->contentCreationService->createBasicPage();

        // Translate the nodes in French and English.
        $node_1_fr = $this->contentCreationService->translateNode($node_1, 'fr');
        $node_1_en = $this->contentCreationService->translateNode($node_1, 'en');

        // Head to the content manager page.
        $this->contentManagerPage->go();
        $row = $this->contentManagerPage->contentTable->getNodeRowByNid($node_1);

        // Check if the node is shown as translated in French and English but not German.
        $this->assertTrue($row->isTranslatedInLanguage('fr'));
        $this->assertTrue($row->isTranslatedInLanguage('en'));
        $this->assertFalse($row->isTranslatedInLanguage('de'));

        // Assert that the links direct to the node.
        $fr_link = $row->getTranslationLink('fr');
        $this->assertNotFalse(strpos($fr_link, 'node/' . $node_1_fr));
        $en_link = $row->getTranslationLink('en');
        $this->assertNotFalse(strpos($en_link, 'node/' . $node_1_en));
    }

    /**
     * Tests if the missing translations are shown correctly.
     */
    public function testMissingTranslationFilter()
    {
        // Create a basic page.
        $node_1 = $this->contentCreationService->createBasicPage();

        // Head to the content manager page.
        $this->contentManagerPage->go();

        // Filter on FR/EN/DE and assert that the node is shown each time.
        $this->changeNoTranslationFilter('fr');
        $this->assertNotFalse($this->contentManagerPage->contentTable->getNodeRowByNid($node_1));
        $this->changeNoTranslationFilter('en');
        $this->assertNotFalse($this->contentManagerPage->contentTable->getNodeRowByNid($node_1));
        $this->changeNoTranslationFilter('de');
        $this->assertNotFalse($this->contentManagerPage->contentTable->getNodeRowByNid($node_1));

        // Assert that there is no value 'nl' in the select.
        $options = $this->contentManagerPage->hasNoTranslation->getOptions();
        $this->assertArrayNotHasKey('nl', $options);

        // Translate the node in French.
        $node_1_fr = $this->contentCreationService->translateNode($node_1, 'fr');

        // Assert that the node is not shown anymore when French is selected.
        $this->contentManagerPage->go();
        $this->contentManagerPage->advancedOptions->click();
        $this->contentManagerPage->hasNoTranslation->selectOptionByValue('fr');
        $this->contentManagerPage->applyButton->click();
        $this->assertFalse($this->contentManagerPage->contentTable->getNodeRowByNid($node_1));

        // Change the language picker to FR.
        $this->contentManagerPage->languageSwitcher->switchLanguage('fr');

        // Assert that for EN/DE the node is shown as untranslated but for NL it
        // should not be shown.
        $this->changeNoTranslationFilter('nl');
        $this->assertFalse($this->contentManagerPage->contentTable->getNodeRowByNid($node_1_fr));
        $this->changeNoTranslationFilter('en');
        $this->assertNotFalse($this->contentManagerPage->contentTable->getNodeRowByNid($node_1_fr));
        $this->changeNoTranslationFilter('de');
        $this->assertNotFalse($this->contentManagerPage->contentTable->getNodeRowByNid($node_1_fr));

        // Assert that there is no 'fr' value in the select.
        $options = $this->contentManagerPage->hasNoTranslation->getOptions();
        $this->assertArrayNotHasKey('fr', $options);
    }

    /**
     * Filters on the language from which we want to see the untranslated nodes in.
     *
     * @param $langcode
     *   The language which we want to filter on.
     */
    protected function changeNoTranslationFilter($langcode)
    {
        $this->contentManagerPage->advancedOptions->click();
        $this->contentManagerPage->hasNoTranslation->selectOptionByValue($langcode);
        $this->contentManagerPage->applyButton->click();
    }
}
