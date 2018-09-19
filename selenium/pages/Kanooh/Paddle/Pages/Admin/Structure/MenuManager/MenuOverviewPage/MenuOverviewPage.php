<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Structure\MenuManager\MenuOverviewPage\MenuOverviewPage.
 */

namespace Kanooh\Paddle\Pages\Admin\Structure\MenuManager\MenuOverviewPage;

use Kanooh\Paddle\Pages\Admin\Structure\MenuManager\CreateMenuItemModal;
use Kanooh\Paddle\Pages\Admin\Structure\MenuManager\CreateMenuModal;
use Kanooh\Paddle\Pages\Admin\Structure\MenuManager\DeleteMenuItemModal;
use Kanooh\Paddle\Pages\Admin\Structure\MenuManager\DeleteMenuModal;
use Kanooh\Paddle\Pages\AdminPage;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\Links\StructureAdminMenuLinks;
use Kanooh\Paddle\Pages\Element\MenuManager\OverviewForm\OverviewForm;
use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;

/**
 * The Menu Manager overview page class.
 *
 * @property StructureAdminMenuLinks $adminMenuLinks
 *   The admin menu links.
 * @property MenuOverviewPageContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 * @property OverviewForm $overviewForm
 *   The main form on the page used to edit the menu items.
 * @property LeftMenuDisplay $leftMenuDisplay
 *   The menu display shown on the left side of the page holding list of the menus.
 */
class MenuOverviewPage extends AdminPage
{
    /**
     * Id of the main menu.
     *
     * @var int
     */
    const MAIN_MENU_ID = 1;

    /**
     * Name of the main menu.
     *
     * @var string
     */
    const MAIN_MENU_NAME = 'main_menu_nl';

    /**
     * The language of the main menu.
     *
     * @var string
     */
    const MAIN_MENU_LANGUAGE = 'nl';

    /**
     * Id of the footer menu.
     *
     * @var int
     */
    const FOOTER_MENU_ID = 2;

    /**
     * Name of the footer menu.
     *
     * @var string
     */
    const FOOTER_MENU_NAME = 'footer_menu_nl';

    /**
     * Id of the top menu.
     *
     * @var int
     */
    const TOP_MENU_ID = 3;

    /**
     * Name of the footer menu.
     *
     * @var string
     */
    const TOP_MENU_NAME = 'top_menu_nl';

    /**
     * Id of the disclaimer menu.
     *
     * @var int
     */
    const DISCLAIMER_MENU_ID = 4;

    /**
     * Name of the disclaimer menu.
     *
     * @var string
     */
    const DISCLAIMER_MENU_NAME = 'disclaimer_menu_nl';

    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/structure/menu_manager/%';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'adminMenuLinks':
                return new StructureAdminMenuLinks($this->webdriver);
            case 'contextualToolbar':
                return new MenuOverviewPageContextualToolbar($this->webdriver);
            case 'overviewForm':
                return new OverviewForm($this->webdriver, $this->webdriver->byId('paddle-menu-manager-menu-overview-form'));
            case 'leftMenuDisplay':
                return new LeftMenuDisplay($this->webdriver);
        }

        return parent::__get($property);
    }

    /**
     * Creates a custom menu and returns the new tsid.
     * @param  array $values
     *   The values we need to create a custom menu - 'title'(mandatory) and
     *   'description'.
     * @return int | null
     *   The translation set id of the new menu, null if it cannot be found.
     */
    public function createCustomMenu($values)
    {
        // Create the menu itself.
        $this->contextualToolbar->buttonCreateMenu->click();
        $modal = new CreateMenuModal($this->webdriver);
        $modal->waitUntilOpened();
        $modal->title->value($values['title']);
        $modal->submit();
        $modal->waitUntilClosed();

        // Try to get the tsid.
        $this->checkArrival();

        return $this->getTsidByMenuName($values['title']);
    }

    /**
     * Deletes a custom menu.
     */
    public function deleteCustomMenu()
    {
        $this->contextualToolbar->buttonDeleteMenu->click();
        $modal = new DeleteMenuModal($this->webdriver);
        $modal->waitUntilOpened();
        $modal->submit();
        $modal->waitUntilClosed();
    }

    /**
     * Deletes all menu items from the current menu.
     */
    public function emptyMenu()
    {
        $mlids = $this->overviewForm->overviewFormTable->getAllMenuItemMlids();
        foreach ($mlids as $mlid) {
            $this->deleteMenuItem($mlid);
        }
    }

    /**
     * Edit a custom menu.
     * @param  array $values
     *   The values we need to edit a custom menu - 'title'(mandatory) and
     *   'description'.
     */
    public function editCustomMenu($values)
    {
        // Create the menu itself.
        $this->contextualToolbar->buttonEditMenu->click();
        $modal = new CreateMenuModal($this->webdriver);
        $modal->waitUntilOpened();
        if (!empty($values['title'])) {
            $modal->title->clear();
            $modal->title->value($values['title']);
        }
        $modal->submit();
        $modal->waitUntilClosed();
    }

    /**
     * Creates new menu item.
     *
     * @param array $values
     *   The values we need to create the menu item - 'title'(mandatory),
     *   'description', 'parent'.
     * @param array $parents
     *   Array of mlids which are parents of the new menu item. Used to retrieve
     *   the mlid.
     *
     * @return int
     *   The mlid of the newly created menu item.
     */
    public function createMenuItem($values, array $parents = array())
    {
        if (!$values['title']) {
            // The "title" field value is set - the menu item cannot be saved.
            return 0;
        }

        $title = $values['title'];

        // Open the modal by clicking on "Create menu item" button.
        $this->contextualToolbar->buttonCreateMenuItem->click();
        $modal = new CreateMenuItemModal($this->webdriver);
        $modal->waitUntilOpened();
        $modal->createMenuItemForm->title->fill($title);
        if (!empty($values['description'])) {
            $modal->createMenuItemForm->description->value($values['description']);
        }
        if (!empty($values['internal_link'])) {
            $modal->createMenuItemForm->internalLinkRadioButton->select();
            $modal->createMenuItemForm->internalLinkPath->fill($values['internal_link']);

            if ($values['internal_link'] != '<front>') {
                $autoComplete = new AutoComplete($this->webdriver);
                $autoComplete->waitUntilSuggestionCountEquals(1);

                // Use the arrow keys to select the result, and press enter to confirm.
                $this->webdriver->keys(Keys::DOWN . Keys::ENTER);
                $modal->createMenuItemForm->internalLinkRadioButton->select();
                // @TODO: replace this sleep with a working AJAX callback.
                sleep(1);
            }
        }
        if (!empty($values['external_link'])) {
            $modal->createMenuItemForm->externalLinkRadioButton->select();
            $modal->createMenuItemForm->externalLinkPath->fill($values['external_link']);
        }
        if (!empty($values['parent'])) {
            $modal->createMenuItemForm->navigation->selectOptionByValue($values['parent']);
        }
        $modal->submit();
        $modal->waitUntilClosed();

        $this->waitUntilPageIsLoaded();
        $this->overviewForm->openTreeToMenuItem($parents, $title);

        $item_row = $this->overviewForm->overviewFormTable->getMenuItemRowByTitle($title);

        return $item_row->getMlid();
    }

    /**
     * Edit existing menu item.
     *
     * @param int $mlid
     *   The mlid of the menu item we are editing.
     * @param array $values
     *   The edit values we want to set - 'title'(mandatory),
     *   'description', 'parent'.
     */
    public function editMenuItem($mlid, $values)
    {
        // Open the modal by clicking on edit menu item link.
        $item_row = $this->overviewForm->overviewFormTable->getMenuItemRowByMlid($mlid);
        $this->webdriver->clickOnceElementIsVisible($item_row->linkEditMenuItem);

        $modal = new CreateMenuItemModal($this->webdriver);
        $modal->waitUntilOpened();
        if (!empty($values['title'])) {
            $modal->createMenuItemForm->title->fill($values['title']);
        }
        if (!empty($values['parent'])) {
            $modal->createMenuItemForm->navigation->selectOptionByValue($values['parent']);
        }
        if (!empty($values['backgroundImage'])) {
            $modal->createMenuItemForm->backgroundImage->selectAtom($values['backgroundImage']);
        }
        if (!empty($values['description'])) {
            $modal->createMenuItemForm->description->value($values['description']);
        }
        if (!empty($values['internal_link'])) {
            $modal->createMenuItemForm->internalLinkRadioButton->select();
            $modal->createMenuItemForm->internalLinkPath->fill($values['internal_link']);

            if ($values['internal_link'] != '<front>') {
                $autoComplete = new AutoComplete($this->webdriver);
                $autoComplete->waitUntilSuggestionCountEquals(1);

                // Use the arrow keys to select the result, and press enter to confirm.
                $this->webdriver->keys(Keys::DOWN . Keys::ENTER);
                $modal->createMenuItemForm->internalLinkRadioButton->select();
                // @TODO: replace this sleep with a working AJAX callback.
                sleep(1);
            }
        }
        if (!empty($values['external_link'])) {
            $modal->createMenuItemForm->externalLinkRadioButton->select();
            $modal->createMenuItemForm->externalLinkPath->fill($values['external_link']);
        }
        $modal->submit();
        $modal->waitUntilClosed();
    }

    /**
     * Deletes a menu item.
     * @param  int $mlid
     *   The mlid of the menu item.
     */
    public function deleteMenuItem($mlid)
    {
        $row = $this->overviewForm->overviewFormTable->getMenuItemRowByMlid($mlid);
        $row->linkDeleteMenuItem->click();
        $modal = new DeleteMenuItemModal($this->webdriver);
        $modal->waitUntilOpened();
        $modal->submit();
        $modal->waitUntilClosed();
    }

    /**
     * Finds the tsid of a menu by its title.
     * @param  string $menu_title
     *   The menu title of the menu.
     *
     * @return int | null
     *   The tsid of the menu, null if not found.
     */
    public function getTsidByMenuName($menu_title)
    {
        $link = $this->leftMenuDisplay->getMenuItemLinkByTitle($menu_title);
        if ($link) {
            $parts = explode('/', $link->attribute('href'));

            return end($parts);
        }

        return null;
    }

    /**
     * Finds and returns the machine name of a custom menu.
     *
     * @return string
     *   The machine name.
     */
    public function getMenuName()
    {
        $this->contextualToolbar->buttonCreateMenuItem->click();
        $modal = new CreateMenuItemModal($this->webdriver);
        $modal->waitUntilOpened();
        // Use the fact that the current menu is always selected in the <select>.
        $parts = explode(':', $modal->createMenuItemForm->navigation->selectedValue());
        $menu_name = reset($parts);
        $modal->close();
        $modal->waitUntilClosed();

        return $menu_name;
    }
}
