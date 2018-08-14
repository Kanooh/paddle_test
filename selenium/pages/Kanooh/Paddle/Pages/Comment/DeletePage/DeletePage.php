<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Comment\DeletePage\DeletePage
 */

namespace Kanooh\Paddle\Pages\Comment\DeletePage;

use Kanooh\Paddle\Pages\PaddlePage;

/**
 * The delete page for the core comment entity.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $delete
 *   The button to confirm the comment deletion.
 */
class DeletePage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'comment/%/delete';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'delete':
                return $this->webdriver->byCssSelector('#comment-confirm-delete .form-submit');
        }
        return parent::__get($property);
    }
}
