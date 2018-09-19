<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\Publication\PublicationEditPage.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage\Publication;

use Kanooh\Paddle\Pages\Node\EditPage\EditPage;

/**
 * Page to edit a publication.
 *
 * @property PublicationEditForm $publicationEditForm
 */
class PublicationEditPage extends EditPage
{
    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'publicationEditForm':
                return new PublicationEditForm($this->webdriver, $this->webdriver->byId('paddle-publication-node-form'));
        }
        return parent::__get($property);
    }
}
