<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\AssetsPage\AssetsPageContextualToolbar.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\AssetsPage;

use Kanooh\Paddle\Pages\Element\Toolbar\ContextualToolbar;

/**
 * Class AssetsPageContextualToolbar
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonAddNewAsset
 *   The "Add new asset" button.
 */
class AssetsPageContextualToolbar extends ContextualToolbar
{
    /**
     * {@inheritdoc}
     */
    public function buttonInfo()
    {
        return array(
            'AddNewAsset' => array(
                'title' => 'Add new asset',
            ),
        );
    }
}
