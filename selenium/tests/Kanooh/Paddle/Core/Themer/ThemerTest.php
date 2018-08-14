<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Core\Themer\ThemerTest.
 */

namespace Kanooh\Paddle\Core\Themer;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminViewPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerAddPage\ThemerAddPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\ColorPaletteColorBoxes;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\ThemerEditPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\KanoohThemeV2\ThemerEditPage as KanoohThemeV2EditPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerOverviewPage\ThemerOverviewPage;
use Kanooh\Paddle\Pages\Element\ColorPicker\ColorPicker;
use Kanooh\Paddle\Pages\Element\Modal\EditPaddleStyleModal;
use Kanooh\Paddle\Pages\Element\Modal\StylePaneModal;
use Kanooh\Paddle\Pages\Element\Pane\CustomContentPane;
use Kanooh\Paddle\Pages\Element\Pane\Pane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CustomContentPanelsContentType;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CustomContentPanelsContentTypeForm;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\DrupalService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;
use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ThemerTest extends WebDriverTestCase
{
    /**
     * @var AdminViewPage
     */
    protected $adminViewPage;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var ViewPage
     */
    protected $frontendViewPage;

    /**
     * @var KanoohThemeV2EditPage
     */
    protected $kanoohThemeV2EditPage;

    /**
     * @var LayoutPage
     */
    protected $layoutPage;

    /**
     * @var ThemerAddPage
     */
    protected $themerAddPage;

    /**
     * @var ThemerEditPage
     */
    protected $themerEditPage;

    /**
     * @var ThemerOverviewPage
     */
    protected $themerOverviewPage;

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

        $this->adminViewPage = new AdminViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->frontendViewPage = new ViewPage($this);
        $this->layoutPage = new LayoutPage($this);
        $this->kanoohThemeV2EditPage = new KanoohThemeV2EditPage($this);
        $this->themerAddPage = new ThemerAddPage($this);
        $this->themerEditPage = new ThemerEditPage($this);
        $this->themerOverviewPage = new ThemerOverviewPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Log in as site manager.
        $this->userSessionService->login('SiteManager');
    }

    /**
     * Tests the themer actions.
     *
     * @group themer
     */
    public function testThemerActions()
    {
        // Create a new theme.
        $this->themerOverviewPage->go();
        $this->themerOverviewPage->contextualToolbar->buttonCreateTheme->click();
        $this->themerAddPage->checkArrival();
        $this->themerAddPage->baseTheme->selectOptionByValue('vo_strict');
        $this->assertEquals('VO Theme', $this->themerAddPage->baseTheme->selectedLabel());
        $this->themerAddPage->baseTheme->selectOptionByValue('vo_standard');
        $this->themerAddPage->buttonSubmit->click();

        // Click the cancel button in the contextual toolbar and verify you end
        // up on the overview page.
        $this->themerEditPage->checkArrival();
        $this->themerEditPage->contextualToolbar->buttonBack->click();
        $this->acceptAlert();
        $this->themerOverviewPage->checkArrival();
    }

    /**
     * Tests the renaming of the 'VO Standard theme' to 'Kanooh theme'.
     *
     * @group themer
     */
    public function testVoStandardThemeRenaming()
    {
        $this->themerOverviewPage->go();
        $this->assertTextPresent('kañooh Theme');
        $this->themerOverviewPage->contextualToolbar->buttonCreateTheme->click();
        $this->themerAddPage->checkArrival();
        $this->themerAddPage->baseTheme->selectOptionByLabel('kañooh Theme');
        $this->assertEquals('vo_standard', $this->themerAddPage->baseTheme->value());
    }

    /**
     * Tests switching between default paddle themes.
     *
     * @group themer
     */
    public function testDefaultThemeSwitch()
    {
        // Enable the kañooh theme.
        $this->themerOverviewPage->go();

        if ($this->themerOverviewPage->getActiveTheme()->title->text() != 'kañooh Theme') {
            $this->themerOverviewPage->theme('vo_standard')->enable->click();
        }

        // Create a basic page.
        $nid = $this->contentCreationService->createBasicPage();

        // Go to the layout page.
        $this->layoutPage->go($nid);
          // Select a region to put a pane on.
        $region = $this->layoutPage->display->getRandomRegion();

        // Add a custom content pane.
        $custom_content_pane = new CustomContentPanelsContentType($this);
        $custom_content_pane->body = $this->alphanumericTestDataProvider->getValidValue();
        /** @var Pane $pane */
        $pane = $region->addPane($custom_content_pane);
        $pane_uuid = $pane->getUuid();

        // Open the pane style modal.
        $pane->toolbar->buttonPaddleStyle->click();
        $modal = new StylePaneModal($this);
        $modal->waitUntilOpened();

        // Set the subpalette to the fourth one. Beings 0-indexed, the
        // palette number will be 3.
        $modal->subPaletteRadios->subPalette3->select();
        $modal->submit();
        $modal->waitUntilClosed();

        // Save the node and go to the frond end.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();
        $this->frontendViewPage->go($nid);

        // Verify that the correct palette is shown in the front end for the
        // pane.
        $frontend_pane = new CustomContentPane($this, $pane_uuid);
        $this->assertEquals(3, $frontend_pane->getSubPaletteNumber());

        // Change the theme to the VO Theme and verify the main palette is shown
        // for the pane.
        $this->themerOverviewPage->go();
        $this->themerOverviewPage->theme('vo_strict')->enable->click();
        $this->frontendViewPage->go($nid);
        $frontend_pane = new CustomContentPane($this, $pane_uuid);
        $this->assertEquals(0, $frontend_pane->getSubPaletteNumber());

        // Check in the backend that the default selection is set to the main
        // palette.
        $this->layoutPage->go($nid);
        $pane = new Pane($this, $pane_uuid);
        $pane->toolbar->buttonPaddleStyle->click();
        $modal = new StylePaneModal($this);
        $modal->waitUntilOpened();
        $this->assertTrue($modal->subPaletteRadios->subPalette0->isSelected());
        $modal->submit();
        $modal->waitUntilClosed();

        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();
    }

    /**
     * Test setting of the color of a color palette with color pickers.
     *
     * @dataProvider themeNameDataProvider
     *
     * @param string $theme_name
     *   The machine name of the theme to test on.
     *
     * @group themer
     */
    public function testColorPickerPalettes($theme_name)
    {
        $drupal = new DrupalService();
        $drupal->bootstrap($this);

        // Enable the go_theme.
        module_enable(array('paddle_go_themes'));
        drupal_flush_all_caches();

        $colors = array(
          'main' => array(array(255, 0, 0), array(0, 0, 255), array(255, 255, 255)),
          'sub_palette1' => array(array(200, 240, 0), array(200, 0, 255), array(100, 50, 200)),
        );

        // Create a basic page and add 2 panes to it - one for each palette.
        $nid = $this->contentCreationService->createBasicPage();
        $palette_pane_uuids = $this->addCustomContentPanesToPage($nid, array(0, 1));

        // Create new theme based on the go_theme.
        $this->themerOverviewPage->go();
        $this->themerOverviewPage->contextualToolbar->buttonCreateTheme->click();
        $this->themerAddPage->checkArrival();
        $this->themerAddPage->baseTheme->selectOptionByValue($theme_name);
        $this->themerAddPage->buttonSubmit->click();
        $this->themerEditPage->checkArrival();
        $theme = $this->themerEditPage->getThemeName();

        // Select the custom palette and change the colors for it.
        if ($theme_name == 'kanooh_theme_v2') {
            $this->kanoohThemeV2EditPage->basicStyling->header->click();
            $this->kanoohThemeV2EditPage->basicStyling->colorPaletteRadios->select('custom_palette');
        } else {
            $this->themerEditPage->branding->colorPaletteRadios->select('custom_palette');
        }
        $this->changeCurrentPaletteColors($colors, $theme_name);

        // Save and enable the theme.
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();
        $this->themerOverviewPage->theme($theme)->enable->click();
        $this->themerOverviewPage->checkArrival();

        // Go to the front page and make sure the panes are styled with the
        // custom colors picked with the color picker.
        $this->frontendViewPage->go($nid);
        $this->assertColorPalettesApplied($palette_pane_uuids, array_values($colors), $theme_name);

        // Now edit the theme and change the colors.
        $colors = array(
          'main' => array(array(200, 240, 0), array(178, 43, 240), array(255, 255, 255)),
          'sub_palette1' => array(array(255, 0, 0), array(17, 0, 255), array(213, 20, 10)),
        );

        $this->themerOverviewPage->go();
        $this->themerOverviewPage->getActiveTheme()->edit->click();
        $this->themerEditPage->checkArrival();

        if ($theme_name == 'kanooh_theme_v2') {
            $this->kanoohThemeV2EditPage->basicStyling->header->click();
        }
        $this->changeCurrentPaletteColors($colors, $theme_name);

        // Save the theme.
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();

        $this->layoutPage->go($nid);
        $uuid = $palette_pane_uuids[0];
        $pane = new Pane($this, $uuid, '//div[@data-pane-uuid = "' . $uuid . '"]');
        $pane->toolbar->buttonPaddleStyle->click();

        $modal = new StylePaneModal($this);
        $modal->waitUntilOpened();

        $options = $modal->options;
        try {
            $options->byXPath('//div[@style="background-color: rgb(255, 0, 0);"]');
            $options->byXPath('//div[@style="background-color: rgb(17, 0, 255);"]');
            $options->byXPath('//div[@style="background-color: rgb(213, 20, 10);"]');
        } catch (\Exception $e) {
            $this->fail('No correct colors for subpalette found');
        }

        $modal->submit();
        $modal->waitUntilClosed();

        // Go to the front page and make sure the panes are styled with the
        // custom color picked with the color picker.
        $this->frontendViewPage->go($nid);
        $this->assertColorPalettesApplied($palette_pane_uuids, array_values($colors), $theme_name);
    }

    /**
     * Data provider for the type ahead test.
     */
    public function themeNameDataProvider()
    {
        return array(
            array('go_theme'),
            array('kanooh_theme_v2'),
        );
    }

    /**
     * Adds Custom Content panes to a node and changes the Paddle Style setting
     * for them.
     *
     * @param string $nid
     *   The node id of the node to which to add the pane.
     * @param array $sub_palette_indexes
     *   The indexes of the palettes to set to the pane styling to. The number
     *   of elements in the array will determine the number of pane to be created.
     *
     * @return array
     *   The pane uuid of all the panes created.
     */
    public function addCustomContentPanesToPage($nid, $sub_palette_indexes)
    {
        $pane_uuids = array();
        $this->layoutPage->go($nid);
        $region = $this->layoutPage->display->getRandomRegion();

        foreach ($sub_palette_indexes as $index) {
            $content_type = new CustomContentPanelsContentType($this);
            $callable = new SerializableClosure(
                function () use ($content_type) {
                    /* @var CustomContentPanelsContentTypeForm $form */
                    $form = $content_type->getForm();
                    $form->body->waitUntilReady();
                    $form->body->setBodyText('BODY');
                    $content_type->topSection->enable->check();
                    $content_type->topSection->contentTypeRadios->text->select();
                    $content_type->topSection->text->fill('TOP');
                    $content_type->bottomSection->enable->check();
                    $content_type->bottomSection->urlTypeRadios->noLink->select();
                    $content_type->bottomSection->text->fill('BOTTOM');
                }
            );
            $pane = $region->addPane($content_type, $callable);

            $pane_uuid = $pane->getUuid();

            // Edit the Paddle style (the sub-palette). No need to change it if
            // the index is 0 as this is the default value.
            if ($index > 0) {
                $pane->toolbar->buttonPaddleStyle->click();
                $modal = new EditPaddleStyleModal($this);
                $modal->waitUntilOpened();
                $modal->form->subPaletteRadios[$index]->select();
                $modal->submit();
                $modal->waitUntilClosed();
            }
            $pane_uuids[] = $pane_uuid;
        }

        // Save the page.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();

        return $pane_uuids;
    }

    /**
     * Sets new values to the first and second colors of the main and all 3
     * sub-palettes using the color picker.
     *
     * @param $colors
     *   The colors to set to the palette.
     * @param string $theme_name
     *   The machine name of the theme.
     */
    protected function changeCurrentPaletteColors($colors, $theme_name)
    {
        /* @var ColorPaletteColorBoxes $palette_boxes */
        if ($theme_name == 'kanooh_theme_v2') {
            $palette_boxes = $this->kanoohThemeV2EditPage->basicStyling->getColorBoxesForPalette('custom_palette');
        } else {
            $palette_boxes = $this->themerEditPage->branding->getColorBoxesForPalette('custom_palette');
        }

        // Change the main palette colors 1 & 2 & 3. This will affect the pane
        // with default styling.
        $palette_boxes->mainPaletteBoxes[0]->click();
        $this->setColorToColorPicker($colors['main'][0], $theme_name);

        $palette_boxes->mainPaletteBoxes[1]->click();
        $this->setColorToColorPicker($colors['main'][1], $theme_name);

        $palette_boxes->mainPaletteBoxes[2]->click();
        $this->setColorToColorPicker($colors['main'][2], $theme_name);

        // Now modify the colors for one of the sub-palettes in the same way.
        $palette_boxes->subPaletteBoxes[0][0]->click();
        $this->setColorToColorPicker($colors["sub_palette1"][0], $theme_name);

        $palette_boxes->subPaletteBoxes[0][1]->click();
        $this->setColorToColorPicker($colors["sub_palette1"][1], $theme_name);

        $palette_boxes->subPaletteBoxes[0][2]->click();
        $this->setColorToColorPicker($colors["sub_palette1"][2], $theme_name);
    }

    /**
     * Asserts that the color palettes picked with color picker are correctly
     * applied by checking the background-color property of the pane sections on
     * the front-end.
     *
     * @param $palette_pane_uuids
     *   Array of uuids of the panes for which to check.
     * @param $colors
     *   The colors sets for each pane. The number of elements should be the
     *   same as the number of panes. Each color set should be of 2 colors. Each
     *   color is array, each value from 0 to 255 for each component of RGB.
     */
    protected function assertColorPalettesApplied($palette_pane_uuids, $colors, $theme_name)
    {
        foreach ($palette_pane_uuids as $index => $uuid) {
            $pane = new CustomContentPane($this, $uuid, '//div[@data-pane-uuid="' . $uuid . '"]');
            $color = $colors[$index];
            $color1 = 'rgba(' . $color[0][0] . ', ' . $color[0][1] . ', ' . $color[0][2] . ', 1)';
            $color2 = 'rgba(' . $color[1][0] . ', ' . $color[1][1] . ', ' . $color[1][2] . ', 1)';
            $color3 = 'rgba(' . $color[2][0] . ', ' . $color[2][1] . ', ' . $color[2][2] . ', 1)';
            $this->assertEquals($color1, $pane->top->css('background-color'));

            if ($theme_name == 'kanooh_theme_v2') {
                $this->assertEquals($color3, $pane->body->css('background-color'));
                $this->assertEquals($color3, $pane->bottom->css('background-color'));
            } else {
                $this->assertEquals($color2, $pane->body->css('background-color'));
                $this->assertEquals($color1, $pane->bottom->css('background-color'));
            }
        }
    }

    /**
     * Sets the color of a single color box(position) using the color picker.
     *
     * @param $color
     *   The color to set to the color picker. This should be an array with 3
     *   values - one for each component of RGB.
     * @param string $theme_name
     *   The machine name of the theme.
     */
    protected function setColorToColorPicker($color, $theme_name)
    {
        if ($theme_name == 'kanooh_theme_v2') {
            $color_picker = $this->kanoohThemeV2EditPage->basicStyling->getActiveColorPicker();
        } else {
            $color_picker = $this->themerEditPage->branding->getActiveColorPicker();
        }

        /* @var ColorPicker $color_picker */
        $this->assertNotNull($color_picker);
        $color_picker->waitUntilOpened();

        $color_picker->rgbRColor->click();
        // We have to use the keys because the Color Picker JS interferes with
        // setting the value.
        $keys = Keys::BACKSPACE . Keys::BACKSPACE . Keys::BACKSPACE . $color[0];
        $this->keys($keys);

        $color_picker->rgbGColor->click();
        $keys = Keys::BACKSPACE . Keys::BACKSPACE . Keys::BACKSPACE . $color[1];
        $this->keys($keys);

        $color_picker->rgbBColor->click();
        $keys = Keys::BACKSPACE . Keys::BACKSPACE . Keys::BACKSPACE . $color[2];
        $this->keys($keys);

        $color_picker->waitUntilClosed();
    }
}
