<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleComment\CommentManagerPage\CommentManagerExposedForm.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleComment\CommentManagerPage;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\Select;

/**
 * Class representing the Paddle Comment exposed filter's form.
 *
 * @property Select $published
 *   The Published/Unpublished exposed form filter.
 * @property Select $nodeContentType
 *   The node content type exposed form filter.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonApply
 *   The submit button of the exposed form.
 */
class CommentManagerExposedForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'published':
                return new Select($this->webdriver, $this->webdriver->byId('edit-status'));
                break;
            case 'nodeContentType':
                return new Select($this->webdriver, $this->webdriver->byId('edit-type'));
                break;
            case 'buttonApply':
                return $this->webdriver->byId('edit-submit-comment-manager');
                break;
        }
        throw new FormFieldNotDefinedException($name);
    }
}
