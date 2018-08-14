<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Links\AdminMenuLinks.
 */

namespace Kanooh\Paddle\Pages\Element\Links;

/**
 * Base class for a collection of admin menu links on a page.
 */
abstract class AdminMenuLinks extends Links
{

    /**
     * The XPath selector that identifies the admin menu.
     *
     * @var string
     */
    protected $adminMenuXPathSelector = '//div[@id = "block-paddle-menu-display-management-level-2"]';

    /**
     * The XPath selector that identifies the first level of the admin menu.
     *
     * Technically this is the second level, since there is a level above that
     * contains the "Admin" link, but let's keep it simple shall we?
     *
     * @var string
     */
    protected $topLevelXPathSelector = '//ul[contains(@class, "level-2")]';

    /**
     * The XPath selector that identifies the second level of the admin menu.
     *
     * Technically this is the third level.
     *
     * @var string
     */
    protected $secondLevelXPathSelector = '//ul[contains(@class, "level-3")]';

    /**
     * Returns the default links from the first level.
     *
     * @return array
     *   An array of links.
     */
    public function getDefaultTopLevelLinks()
    {
        // Unfortunately browsers do not support XPath 2 yet, otherwise
        // we could have used the ends-with() function.
        return array(
            'Dashboard' => array(
                'xpath' => $this->adminMenuXPathSelector . $this->topLevelXPathSelector .
                    '//a[substring(@href, string-length(@href) - 15) = "/admin/dashboard"]',
                'title' => 'Dashboard',
            ),
            'Structure' => array(
                'xpath' => $this->adminMenuXPathSelector . $this->topLevelXPathSelector .
                    '//a[substring(@href, string-length(@href) - 15) = "/admin/structure"]',
                'title' => 'Structure',
            ),
            'Content' => array(
                'xpath' => $this->adminMenuXPathSelector . $this->topLevelXPathSelector .
                    '//a[substring(@href, string-length(@href) - 21) = "/admin/content_manager"]',
                'title' => 'Content',
            ),
            'PaddleStore' => array(
                'xpath' => $this->adminMenuXPathSelector . $this->topLevelXPathSelector .
                    '//a[substring(@href, string-length(@href) - 19) = "/admin/paddlet_store"]',
                'title' => 'Paddle Store',
            ),
            'Themes' => array(
                'xpath' => $this->adminMenuXPathSelector . $this->topLevelXPathSelector .
                    '//a[substring(@href, string-length(@href) - 12) = "/admin/themes"]',
                'title' => 'Themes',
            ),
            'Users' => array(
                'xpath' => $this->adminMenuXPathSelector . $this->topLevelXPathSelector .
                    '//a[substring(@href, string-length(@href) - 12) = "/admin/users"]',
                'title' => 'Users',
            ),
        );
    }

    /**
     * Returns the second level links.
     *
     * @todo Fill in all of the available second level links when the need
     *   arises.
     *
     * @return array
     *   An array, keyed by top level menu link machine name, containing arrays
     *   of second level links.
     */
    public function getSecondLevelLinks()
    {
        return array(
            'Structure' => array(
                'Menus' => array(
                    'xpath' => $this->adminMenuXPathSelector . $this->secondLevelXPathSelector .
                        '//a[substring(@href, string-length(@href) - 28) = "/admin/structure/menu_manager"]',
                    'title' => 'Menus',
                ),
                // "PaddleSocialIdentities" added to access that it no longer is accessible.
                'PaddleSocialIdentities' => array(
                    'xpath' => $this->adminMenuXPathSelector . $this->secondLevelXPathSelector .
                        '//a[substring(@href, string-length(@href) - 40) = "/admin/structure/paddle-social-identity"]',
                    'title' => 'Paddle Social Identities',
                ),
                'Regions' => array(
                    'xpath' => $this->adminMenuXPathSelector . $this->secondLevelXPathSelector .
                        '//a[substring(@href, string-length(@href) - 30) = "/admin/structure/content_region"]',
                    'title' => 'Regions',
                ),
                'Taxonomy' => array(
                    'xpath' => $this->adminMenuXPathSelector . $this->secondLevelXPathSelector .
                        '//a[substring(@href, string-length(@href) - 32) = "/admin/structure/taxonomy_manager"]',
                    'title' => 'Taxonomy',
                ),
            ),
            'Content' => array(
                'ManageContent' => array(
                    'xpath' => $this->adminMenuXPathSelector . $this->secondLevelXPathSelector .
                        '//a[substring(@href, string-length(@href) - 26) = "/admin/content_manager/list"]',
                    'title' => 'Manage content',
                ),
                'AddContent' => array(
                    'xpath' => $this->adminMenuXPathSelector . $this->secondLevelXPathSelector .
                        '//a[substring(@href, string-length(@href) - 25) = "/admin/content_manager/add"]',
                    'title' => 'Add content',
                ),
            ),
            'PaddleStore' => array(
                'ActivePaddlets' => array(
                    'xpath' => $this->adminMenuXPathSelector . $this->secondLevelXPathSelector .
                        '//a[substring(@href, string-length(@href) - 25) = "/admin/content_manager/add"]',
                    'title' => 'Active Paddlets',
                ),
                'AvailablePaddlets' => array(
                      'xpath' => $this->adminMenuXPathSelector . $this->secondLevelXPathSelector .
                        '//a[substring(@href, string-length(@href) - 29) = "/admin/paddlet_store/available"]',
                      'title' => 'Available Paddlets',
                ),
            ),
        );
    }
}
