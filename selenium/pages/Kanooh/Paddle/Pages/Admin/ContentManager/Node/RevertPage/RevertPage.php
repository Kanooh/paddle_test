<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Node\RevertPage\RevertPage.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Node\RevertPage;

use Kanooh\Paddle\Pages\Element\Links\ContentAdminMenuLinks;
use Kanooh\Paddle\Pages\PaddlePage;

/**
 * The confirmation page for the reverting to a revision.
 *
 * @property ContentAdminMenuLinks $adminMenuLinks
 *   The admin menu links.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonRevert
 *   The 'Revert' button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonCancel
 *   The 'Cancel' button.
 */
class RevertPage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'node/%/revisions/%/revert';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'adminMenuLinks':
                return new ContentAdminMenuLinks($this->webdriver);
            case 'buttonRevert':
                return $this->webdriver->byId('edit-submit');
            case 'buttonCancel':
                return $this->webdriver->byId('edit-cancel');
        }
        return parent::__get($property);
    }
}
