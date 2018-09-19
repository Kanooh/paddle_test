<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Users\Roles\RoleManagementTableRowLinks.
 */

namespace Kanooh\Paddle\Pages\Admin\Users\Roles;

use Kanooh\Paddle\Pages\Element\Links\Links;

/**
 * The action links in a role overview table row.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkEdit
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkDelete
 */
class RoleManagementTableRowLinks extends Links
{
    /**
    * {@inheritdoc}
    */
    public function linkInfo()
    {
        return array(
            'Edit' => array(
                'xpath' => './/td/a[contains(@class, "ui-icon-edit")]',
            ),
            'Delete' => array(
                'xpath' => './/td/a[contains(@class, "ui-icon-delete")]',
            ),
        );
    }
}
