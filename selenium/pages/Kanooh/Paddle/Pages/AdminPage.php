<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\AdminPage.
 */

namespace Kanooh\Paddle\Pages;

use Kanooh\Paddle\Pages\Element\Admin\UserManagementBlock;
use Kanooh\Paddle\Pages\Element\LanguageSwitcher\LanguageSwitcher;
use Kanooh\Paddle\Pages\Element\Links\TopLevelAdminMenuLinks;
use Kanooh\Paddle\Pages\Element\Search\HeaderTopSearchBox;
use Kanooh\Paddle\Pages\Element\SiteSettingsMenuBlock\SiteSettingsMenuBlock;

/**
 * A base class for a backend page.
 *
 * @property TopLevelAdminMenuLinks $adminMenuLinks
 *   The admin menu links.
 * @property HeaderTopSearchBox $searchBox
 *   The search box in the header_top region.
 * @property SiteSettingsMenuBlock $siteSettingsMenuBlock
 *   The Site Settings Menu at the top of the page.
 * @property UserManagementBlock $userManagementBlock
 *   The user management block.
 * @property LanguageSwitcher|null $languageSwitcher
 *   The language switcher block on the page. Null is absent.
 */
class AdminPage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/%';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'adminMenuLinks':
                return new TopLevelAdminMenuLinks($this->webdriver);
            case 'searchBox':
                return new HeaderTopSearchBox($this->webdriver);
            case 'siteSettingsMenuBlock':
                return new SiteSettingsMenuBlock($this->webdriver);
            case 'userManagementBlock':
                return new UserManagementBlock($this->webdriver);
            case 'languageSwitcher':
                try {
                    // Pass the container as it is uncertain if the language
                    // switcher is an <ul> or <select>.
                    $container = $this->webdriver->byId('block-locale-language-content');
                    return new LanguageSwitcher($this->webdriver, $container);
                } catch (\Exception $e) {
                    return null;
                }
        }
        return parent::__get($property);
    }
}
