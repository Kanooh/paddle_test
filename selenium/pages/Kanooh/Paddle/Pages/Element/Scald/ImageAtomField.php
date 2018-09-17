<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Scald\ImageAtomField.
 */

namespace Kanooh\Paddle\Pages\Element\Scald;

use Kanooh\Paddle\Pages\Element\Form\AjaxSelect;

/**
 * A form field representing an image only atom field.
 *
 * @property AjaxSelect $style
 *   The select to choose the image style for the atom.
 */
class ImageAtomField extends AtomField
{
    /**
     * An XPath selector representing the style select for image only atom fields.
     *
     * @var string
     */
    protected $styleFieldXPathSelector = './..//select[substring(@name, string-length(@name) - string-length("[style]") + 1) = "[style]"]';

    /**
     * @inheritDoc
     */
    public function __get($name)
    {
        switch ($name) {
            case 'style':
                $element = $this->element->byXPath($this->styleFieldXPathSelector);

                return new AjaxSelect($this->webdriver, $element);
        }

        return parent::__get($name);
    }
}
