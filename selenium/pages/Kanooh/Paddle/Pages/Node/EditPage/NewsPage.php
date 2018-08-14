<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\NewsPage.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage;

use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Pages\Element\Scald\ImageAtomField;

/**
 * Page to edit a news item.
 *
 * @property Text $creationDate
 *   Textfield for the creation date.
 * @property Text $creationTime
 *   Textfield for the creation time.
 * @property NewsForm $newsForm
 *   The edit news item form.
 */
class NewsPage extends EditPage
{
    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'creationDate':
                return new Text($this->webdriver, $this->webdriver->byName('creation_date[date]'));
            case 'creationTime':
                return new Text($this->webdriver, $this->webdriver->byName('creation_date[time]'));
            case 'newsForm':
                return new NewsForm($this->webdriver, $this->webdriver->byId('news-item-node-form'));
        }
        return parent::__get($property);
    }
}
