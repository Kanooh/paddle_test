<?php

/**
 * @file
 * Contains Kanooh\Paddle\Core\Wysiwyg\TablePropertiesTest.
 */

namespace Kanooh\Paddle\Core\Wysiwyg;

use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Element\Wysiwyg\Table\TablePropertiesModalInfoForm;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as NodeViewPage;
use Kanooh\Paddle\Pages\Element\Wysiwyg\Table\TablePropertiesModalAdvancedForm;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests the image properties dialog.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class TablePropertiesTest extends WebDriverTestCase
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
     * Data provider for the table effects.
     */
    public function tableEffectsDataProvider()
    {
        return array(
            array('zebraStriping', 'checkbox', 'zebra-striping'),
            array('default-style', 'select', 'default-style'),
            array('no-border', 'select', 'no-border'),
            array('horizontal-border', 'select', 'horizontal-border'),
            array('vertical-border', 'select', 'vertical-border'),
            array('full-border', 'select', 'full-border'),
        );
    }

    /**
     * Tests the table effects in the dialog.
     *
     * @param string $table_effect
     *   The machine name of the table effect which will be used to find the
     *   form elements.
     * @param string $type
     *   The form type of the element which controls the table property we are
     *   testing.
     * @param string $class
     *   The class that the table property will add to the table.
     *
     * @dataProvider tableEffectsDataProvider
     *
     * @group wysiwyg
     */
    public function testTablePropertiesDialog($table_effect, $type, $class)
    {
        $nid = $this->contentCreationService->createBasicPage();
        $this->editPage->go($nid);

        $this->editPage->body->waitUntilReady();
        $this->editPage->body->buttonTable->click();

        // Check that by default the Stylesheet Classes field is empty.
        $table_modal = $this->editPage->body->modalTableProperties;
        $table_modal->waitUntilOpened();
        $table_modal->tabs->linkAdvanced->click();
        $table_modal->waitUntilTabDisplayed(TablePropertiesModalAdvancedForm::TABNAME);
        $this->assertEquals('', $table_modal->advancedForm->stylesheetClasses->getContent());

        // Add an extra class.
        $extra_class = $this->alphanumericTestDataProvider->getValidValue();
        $table_modal->advancedForm->stylesheetClasses->fill($extra_class);

        // Go back to the properties tab and enable the table effect and make
        // sure that the extra class is still there.
        $table_modal->tabs->linkTableProperties->click();
        $table_modal->waitUntilTabDisplayed(TablePropertiesModalInfoForm::TABNAME);
        if ($type == 'checkbox') {
            $table_modal->tablePropertiesForm->$table_effect->check();
        } else {
            $table_modal->tablePropertiesForm->tableBordersStyle->selectOptionByValue($class);
        }
        $table_modal->tabs->linkAdvanced->click();
        $table_modal->waitUntilTabDisplayed(TablePropertiesModalAdvancedForm::TABNAME);

        $this->assertEquals($extra_class, $table_modal->advancedForm->stylesheetClasses->getContent());

        // Now save and verify that the table has the correct classes in the
        // front-end.
        $table_modal->submit();
        $table_modal->waitUntilClosed();

        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeView->checkArrival();

        $this->nodeViewPage->go($nid);
        $table = $this->byCssSelector("table.$class.$extra_class");
        $this->assertNotEmpty($table);
    }

    /**
     * Tests the hover effect functionality.
     */
    public function testHoverEffect()
    {
        $nid = $this->contentCreationService->createBasicPage();
        $this->editPage->go($nid);

        $this->editPage->body->waitUntilReady();
        $this->editPage->body->buttonTable->click();

        // Check that by default the Stylesheet Classes field is empty.
        $table_modal = $this->editPage->body->modalTableProperties;
        $table_modal->waitUntilOpened();
        $table_modal->tabs->linkAdvanced->click();
        $table_modal->waitUntilTabDisplayed(TablePropertiesModalAdvancedForm::TABNAME);
        $this->assertEquals('', $table_modal->advancedForm->stylesheetClasses->getContent());

        // Add an extra class.
        $extra_class = $this->alphanumericTestDataProvider->getValidValue();
        $table_modal->advancedForm->stylesheetClasses->fill($extra_class);

        // Go back to the properties tab and disable the hover style and make
        // sure that the extra class is still there.
        $table_modal->tabs->linkTableProperties->click();
        $table_modal->waitUntilTabDisplayed(TablePropertiesModalInfoForm::TABNAME);
        $table_modal->tablePropertiesForm->hoverEffect->uncheck();
        $table_modal->tabs->linkAdvanced->click();
        $table_modal->waitUntilTabDisplayed(TablePropertiesModalAdvancedForm::TABNAME);

        $this->assertEquals($extra_class, $table_modal->advancedForm->stylesheetClasses->getContent());

        // Now save and verify that the table has the correct classes in the
        // front-end.
        $table_modal->submit();
        $table_modal->waitUntilClosed();

        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeView->checkArrival();

        $this->nodeViewPage->go($nid);
        $table = $this->byCssSelector("table.no-table-hover.$extra_class");
        $this->assertNotEmpty($table);
    }

    /**
     * Tests multiple table effect classes together on a table.
     *
     * @group wysiwyg
     */
    public function testMultipleTableEffects()
    {
        $nid = $this->contentCreationService->createBasicPage();

        $this->editPage->go($nid);
        $this->editPage->body->waitUntilReady();
        $this->editPage->body->buttonTable->click();
        $table_modal = $this->editPage->body->modalTableProperties;
        $table_modal->waitUntilOpened();

        // Add an extra class.
        $extra_class = $this->alphanumericTestDataProvider->getValidValue();
        $table_modal->tabs->linkAdvanced->click();
        $table_modal->waitUntilTabDisplayed(TablePropertiesModalAdvancedForm::TABNAME);
        $table_modal->advancedForm->stylesheetClasses->fill($extra_class);

        // Enable the fields again and save to check on the front-end.
        $table_modal->tabs->linkTableProperties->click();
        $table_modal->waitUntilTabDisplayed(TablePropertiesModalInfoForm::TABNAME);
        $table_modal->tablePropertiesForm->zebraStriping->check();
        $table_modal->tablePropertiesForm->hoverEffect->uncheck();
        $table_modal->submit();
        $table_modal->waitUntilClosed();

        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeView->checkArrival();

        $this->nodeViewPage->go($nid);
        $table = $this->byCssSelector("table.zebra-striping.no-table-hover.$extra_class");
        $this->assertNotEmpty($table);
    }

    /**
     * Tests the default table.
     *
     * @group wysiwyg
     */
    public function testTableNoCustomProperties()
    {
        $nid = $this->contentCreationService->createBasicPage();
        $this->editPage->go($nid);

        $this->editPage->body->waitUntilReady();
        $this->editPage->body->buttonTable->click();

        // Just submit the modal without setting extra stuff.
        $table_modal = $this->editPage->body->modalTableProperties;
        $table_modal->waitUntilOpened();

        // Check the default values.
        $this->assertFalse($table_modal->tablePropertiesForm->zebraStriping->isChecked());
        $this->assertTrue($table_modal->tablePropertiesForm->hoverEffect->isChecked());
        $actual_value = $table_modal->tablePropertiesForm->tableBordersStyle->getSelectedValue();
        $this->assertEquals('horizontal-border', $actual_value);

        $table_modal->submit();
        $table_modal->waitUntilClosed();

        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeView->checkArrival();

        $this->nodeViewPage->go($nid);

        // We should not find a table with these classes set on it.
        $unwanted_classes = array(
            'zebra-striping',
            'no-table-hover',
            'default-style',
            'no-border',
            'vertical-border',
            'full-border',
        );
        foreach ($unwanted_classes as $unwanted_class) {
            try {
                $this->byCssSelector($unwanted_class);
                $this->fail("There should be no table with $unwanted_class class applied.");
            } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
                // We got here so no table was found.
                continue;
            }
        }
    }

    /**
     * Test that the default values of the table dialog are what we expect.
     */
    public function testTableDialogDefaultValues()
    {
        $nid = $this->contentCreationService->createBasicPage();
        $this->editPage->go($nid);

        $this->editPage->body->waitUntilReady();
        $this->editPage->body->buttonTable->click();

        // Just submit the modal without setting extra stuff.
        $table_modal = $this->editPage->body->modalTableProperties;
        $table_modal->waitUntilOpened();

        // Check the default values.
        $this->assertEquals('0', $table_modal->tablePropertiesForm->tableBordersSize->getContent());
        $this->assertEquals('9', $table_modal->tablePropertiesForm->cellPadding->getContent());
        try {
            $table_modal->tablePropertiesForm->cellSpacing;
            $this->fail();
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            // All is fine, the cell padding textbox should not be found.
        }

        $table_modal->submit();
        $table_modal->waitUntilClosed();

        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeView->checkArrival();
    }
}
