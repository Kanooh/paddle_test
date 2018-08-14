<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Utilities\MenuCreationService.
 */

namespace Kanooh\Paddle\Utilities;

use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Utility class to help creating content.
 */
class MenuCreationService
{
    /**
     * The Selenium webdriver.
     *
     * @var WebDriverTestCase
     */
    protected $webdriver;

    /**
     * Test data provider for alphanumeric data.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * Constructs a ContentCreationService object.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     */
    public function __construct(WebDriverTestCase $webdriver)
    {
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->webdriver = $webdriver;

        $drupal = new DrupalService();
        $drupal->bootstrap($webdriver);
    }
    
    /**
     * Creates a random menu item in the main menu.
     */
    public function createRandomMenuItem()
    {
        $menu_item = array(
          'link_title' => $this->alphanumericTestDataProvider->getValidValue(),
          'link_path' => '<front>',
          'menu_name' => 'main_menu_nl',
          'weight' => 0,
          'expanded' => 0,
        );
        menu_link_save($menu_item);
    }
        
    /**
     * Creates a menu item for a node.
     *
     * @param int $nid
     *   The node id to set the internal link.
     * @param string $menu_machine_name
     *   The machine name of the menu where you want to create the menu item.
     *   Defaults to "main_menu_nl".
     * @param string $title
     *   The title of menu item.
     * @param string $parent_mlid
     *   The mlid of the parent menu item.
     *
     * @return int|bool
     *   The mlid of the saved menu link, or FALSE if the menu link could not be
     *   saved.
     */
    public function createNodeMenuItem($nid = null, $menu_machine_name = 'main_menu_nl', $title = '', $parent_mlid = null)
    {
        $menu_item = array(
          'link_title' => !empty($title) ? $title : $this->alphanumericTestDataProvider->getValidValue(),
          'link_path' => !empty($nid) ? 'node/' . $nid : '<front>',
          'menu_name' => $menu_machine_name,
          'weight' => 0,
          'expanded' => 0,
        );

        if (!empty($parent_mlid)) {
            $menu_item['plid'] = $parent_mlid;
        }

        return menu_link_save($menu_item);
    }
}
