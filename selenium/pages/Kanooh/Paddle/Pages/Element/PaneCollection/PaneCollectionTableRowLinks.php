<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PaneCollection\PaneCollectionTableRowLinks.
 */

namespace Kanooh\Paddle\Pages\Element\PaneCollection;

use Kanooh\Paddle\Pages\Element\Links\Links;

/**
 * The action links in a pane collection overview table row.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkEdit
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkDelete
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkLayout
 */
class PaneCollectionTableRowLinks extends Links
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
            'Layout' => array(
                'xpath' => './/td/a[contains(@class, "ui-icon-edit-page-layout")]',
            ),
        );
    }
}
