<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\NewsForm.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\Select;
use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Pages\Element\Scald\ImageAtomField;
use Kanooh\Paddle\Pages\Element\Wysiwyg\Wysiwyg;

/**
 * Class representing the news item edit form.
 *
 * @property Text $title
 *   The title textfield.
 * @property ImageAtomField $leadImage
 *   The primary image of the news item.
 * @property Select $imageStyle
 *   The dropdown to choose the image style from.
 * @property NewsLeadImagePositionRadioButtons $leadImagePosition
 *   The display position of the lead image.
 * @property Wysiwyg $body
 *   The body of the node.
 */
class NewsForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'title':
                return new Text($this->webdriver, $this->webdriver->byName('title'));
            case 'leadImage':
                return new ImageAtomField(
                    $this->webdriver,
                    $this->element->byXPath('.//div/input[@name="field_paddle_featured_image[und][0][sid]"]/..')
                );
            case 'leadImagePosition':
                return new NewsLeadImagePositionRadioButtons(
                    $this->webdriver,
                    $this->element->byCssSelector('div#edit-field-paddle-news-image-position')
                );
            case 'body':
                return new Wysiwyg($this->webdriver, 'edit-body-und-0-value');
        }
        throw new FormFieldNotDefinedException($name);
    }
}
