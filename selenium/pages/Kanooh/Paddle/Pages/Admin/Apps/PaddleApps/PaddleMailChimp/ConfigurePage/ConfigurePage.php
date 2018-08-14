<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleMailChimp\ConfigurePage\ConfigurePage.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleMailChimp\ConfigurePage;

use Kanooh\Paddle\Pages\Element\Links\ContentAdminMenuLinks;
use Kanooh\Paddle\Pages\Element\MailChimp\SignupFormsTable;
use Kanooh\Paddle\Pages\PaddlePage;

/**
 * The configuration page for the MailChimp paddlet.
 *
 * @property ConfigurePageContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 * @property ContentAdminMenuLinks $adminMenuLinks
 *   The admin menu links.
 * @property SignupFormsTable $signupFormsTable
 *   The table containing the Signup forms.
 */
class ConfigurePage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/paddlet_store/app/paddle_mailchimp/configure';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'contextualToolbar':
                return new ConfigurePageContextualToolbar($this->webdriver);
            case 'adminMenuLinks':
                return new ContentAdminMenuLinks($this->webdriver);
                break;
            case 'signupFormsTable':
                return new SignupFormsTable($this->webdriver, '//div[contains(@class, "view-signup-forms")]//table[contains(@class, "views-table")]/tbody');
        }
        return parent::__get($property);
    }
}
