<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Embed\ConfigurationTest.
 */

namespace Kanooh\Paddle\App\Embed;

use Drupal\Component\Utility\Random;
use Kanooh\Paddle\Apps\Embed;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleEmbed\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Element\Embed\WidgetDeleteModal;
use Kanooh\Paddle\Pages\Element\Embed\WidgetSettingsModal;
use Kanooh\Paddle\Pages\Element\Embed\WidgetPreviewModal;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs configuration tests on the Embed paddlet.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ConfigurationTest extends WebDriverTestCase
{
    /**
     * @var AppService
     */
    protected $appService;

    /**
     * The paddlet configuration page.
     *
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * Random data generator.
     *
     * @var Random
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

        // Prepare some variables for later use.
        $this->configurePage = new ConfigurePage($this);
        $this->random = new Random();
        $this->userSessionService = new UserSessionService($this);

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new Embed);
    }

    /**
     * Tests the permission to manage widgets.
     */
    public function testManageWidgetsAccess()
    {
        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor');

        // Go to the configuration page and make sure the user can't manage the
        // widgets.
        $this->configurePage->go();
        $this->assertTextPresent('You have insufficient access to manage widgets.');
        $this->assertFalse($this->configurePage->widgetTablePresent());

        // Switch to site manager.
        $this->userSessionService->switchUser('SiteManager');

        // Go to the configuration page and make sure the user is able to
        // manage the widgets.
        $this->configurePage->go();
        $this->assertTextNotPresent('You have insufficient access to manage widgets.');
        $this->assertTrue($this->configurePage->widgetTablePresent() || $this->textPresent('No widgets have been created yet.'));
    }

    /**
     * Checks if a given string is present.
     *
     * @param string $text
     *   The needle to look for.
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   Optional, the element in which to look for the text. Defaults to body.
     *
     * @return boolean
     *   TRUE if the text is present, FALSE if not.
     */
    protected function textPresent($text, \PHPUnit_Extensions_Selenium2TestCase_Element $element = null)
    {
        $element = $element ?: $this->byCssSelector('body');
        return strpos($element->text(), $text) !== false;
    }

    /**
     * Tests the operations possible on widgets.
     */
    public function testWidgetOperations()
    {
        // Log in as a site manager.
        $this->userSessionService->login('SiteManager');

        // Go to the configuration page and get a list of existing widgets (if
        // any).
        $this->configurePage->go();
        if ($this->configurePage->widgetTablePresent()) {
            $widgets = $this->configurePage->widgetTable->rows;
        } else {
            $widgets = array();
        }

        // Click the button to create a new widget.
        $this->configurePage->contextualToolbar->buttonCreate->click();
        $modal = new WidgetSettingsModal($this);
        $modal->waitUntilOpened();

        // Click the save button. The form should show some validation errors.
        $modal->form->saveButton->click();
        $this->waitUntilTextIsPresent('Widget title field is required.');
        $this->waitUntilTextIsPresent('Paste code field is required.');

        // Fill in the required fields and submit again.
        $title = $this->random->name(12);
        $code = '<script type="text/javascript"> jQuery("#widget-preview").html("<div id=\"embed-1\">Generated by embed code 1.</div>"); </script>';
        $modal->form->title->fill($title);
        $modal->form->code->fill($code);
        $modal->form->saveButton->click();

        // Check the preview.
        $modal = new WidgetPreviewModal($this);
        $modal->waitUntilOpened();
        $this->waitUntilElementIsPresent('//div[@id="embed-1"]');
        $this->waitUntilTextIsPresent('Generated by embed code 1.');
        $modal->close();
        $modal->waitUntilClosed();

        // Get a new list of widgets. Compare the count of the two and get the
        // one we just created.
        $updated_widgets = $this->configurePage->widgetTable->rows;
        $this->assertCount(count($widgets)+1, $updated_widgets);
        $widget = end($updated_widgets);

        // Make sure the title in the list is the same as the one entered.
        $this->assertEquals($title, $widget->title);

        // Click the preview link, and verify that it works the same as the
        // preview after creating a widget.
        $widget->linkPreview->click();
        $modal = new WidgetPreviewModal($this);
        $modal->waitUntilOpened();
        $this->waitUntilElementIsPresent('//div[@id="embed-1"]');
        $this->waitUntilTextIsPresent('Generated by embed code 1.');
        $modal->close();
        $modal->waitUntilClosed();

        // Click the edit link and enter new values.
        $widget->linkEdit->click();
        $modal = new WidgetSettingsModal($this);
        $modal->waitUntilOpened();

        $this->assertEquals($title, $modal->form->title->getContent());
        $this->assertEquals($code, $modal->form->code->getContent());

        $new_title = $this->random->name(12);
        $new_code = '<script type="text/javascript"> jQuery("#widget-preview").html("<div id=\"embed-2\">Generated by embed code 2.</div>"); </script>';

        $modal->form->title->fill($new_title);
        $modal->form->code->fill($new_code);
        $modal->form->saveButton->click();

        // Make sure the preview is visible and updated correctly.
        $modal = new WidgetPreviewModal($this);
        $modal->waitUntilOpened();
        $this->waitUntilElementIsPresent('//div[@id="embed-2"]');
        $this->waitUntilTextIsPresent('Generated by embed code 2.');
        $modal->close();
        $modal->waitUntilClosed();

        // Get the refreshed list of widgets. Make sure the count is the same
        // as before (after we created a new one).
        $updated_widgets = $this->configurePage->widgetTable->rows;
        $this->assertCount(count($widgets)+1, $updated_widgets);
        $widget = end($updated_widgets);

        // Make sure the title was updated in the list.
        $this->assertEquals($new_title, $widget->title);

        // Click the delete button, but cancel the confirmation.
        $widget->linkDelete->click();
        $modal = new WidgetDeleteModal($this);
        $modal->waitUntilOpened();
        $modal->buttonCancel->click();
        $modal->waitUntilClosed();

        // Make sure the widget count is the same.
        $previous_count = count($updated_widgets);
        $updated_widgets = $this->configurePage->widgetTable->rows;
        $widget = end($updated_widgets);
        $this->assertCount($previous_count, $updated_widgets);

        // Click the delete button again, but actually delete the widget.
        $this->deleteWidget($widget);

        // Make sure the widget count is reduced by one.
        $updated_widgets = ($this->configurePage->widgetTablePresent()) ? $this->configurePage->widgetTable->rows : array();
        $this->assertCount($previous_count-1, $updated_widgets);

        // Delete all other widgets (if any) and make sure the placeholder text
        // appears. Don't store the rows in a variable, as they will be stale
        // once a widget has been deleted and the rows have been refreshed.
        while ($this->configurePage->widgetTablePresent()) {
            $widget = $this->configurePage->widgetTable->rows[0];
            $this->deleteWidget($widget);
        }
        $this->assertTextPresent('No widgets have been created yet.');
    }

    /**
     * Deletes a given widget.
     *
     * @param \Kanooh\Paddle\Pages\Element\Embed\WidgetTableRow $widget
     *   The widget (row) to delete.
     */
    protected function deleteWidget($widget)
    {
        $widget->linkDelete->click();
        $modal = new WidgetDeleteModal($this);
        $modal->waitUntilOpened();
        $modal->buttonConfirm->click();
        $modal->waitUntilClosed();
    }
}
