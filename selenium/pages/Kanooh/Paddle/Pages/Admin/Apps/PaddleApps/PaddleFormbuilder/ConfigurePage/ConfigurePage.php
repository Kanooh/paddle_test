<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleFormbuilder\ConfigurePage\ConfigurePage.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleFormbuilder\ConfigurePage;

use Kanooh\Paddle\Apps\Formbuilder;
use Kanooh\Paddle\Pages\Admin\Apps\ConfigurePage\ConfigurePageBase;
use Kanooh\Paddle\Pages\Element\Formbuilder\PermissionsTable;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * The configuration page for the Formbuilder paddlet.
 *
 * @property PermissionsTable $permissionsTable
 */
class ConfigurePage extends ConfigurePageBase
{

    /**
     * {@inheritdoc}
     */
    public function __construct(WebDriverTestCase $webdriver)
    {
        parent::__construct($webdriver, new Formbuilder);
    }

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'permissionsTable':
                return new PermissionsTable($this->webdriver, '//table[@id="permissions"]');
        }
        return parent::__get($property);
    }
}
