<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\Cirro\CirroEditPage.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage\Cirro;

use Kanooh\Paddle\Pages\Node\EditPage\EditPage;

/**
 * Page to edit a CIRRO page.
 *
 * @property CirroEditForm $form
 */
class CirroEditPage extends EditPage
{
    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'form':
                return new CirroEditForm($this->webdriver, $this->webdriver->byId('paddle-cirro-page-node-form'));
        }
        return parent::__get($property);
    }
}
