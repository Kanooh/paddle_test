<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleSocialIdentities\ConfigurePage\ConfigurePage.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleSocialIdentities\ConfigurePage;

use Kanooh\Paddle\Pages\Element\SocialIdentities\SocialIdentitiesTable;
use Kanooh\Paddle\Pages\PaddlePage;

/**
 * The configuration page for the Social Identities paddlet.
 *
 * @property ConfigurePageContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 * @property SocialIdentitiesTable $socialIdentitiesTable
 *   The table of social identities.
 */
class ConfigurePage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/paddlet_store/app/paddle_social_identities/configure';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'contextualToolbar':
                return new ConfigurePageContextualToolbar($this->webdriver);
            case 'socialIdentitiesTable':
                return new SocialIdentitiesTable($this->webdriver, '//table[@id="identity-list"]');
                break;
        }
        return parent::__get($property);
    }

    /**
     * Checks if the social identities table is present on the page.
     *
     * @return boolean
     *   TRUE if present, FALSE if not.
     */
    public function socialIdentitiesTablePresent()
    {
        $criteria = $this->webdriver->using('xpath')->value('//table[@id="identity-list"]');
        $elements = $this->webdriver->elements($criteria);
        if (count($elements) > 0) {
            return true;
        }
        return false;
    }
}
