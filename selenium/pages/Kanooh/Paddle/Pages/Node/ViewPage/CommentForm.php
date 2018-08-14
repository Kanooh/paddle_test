<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\ViewPage\CommentForm.
 */

namespace Kanooh\Paddle\Pages\Node\ViewPage;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * Class representing the comment form in the front end.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $administrationLink
 *   The link to open the Administration fieldset.
 * @property Text $name
 *   The name of the person posting the comment.
 * @property Text $comment
 *   The body of the comment form.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $save
 *   The submit button of the comment form.
 */
class CommentForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'administrationLink':
                return $this->webdriver->byXPath('//fieldset[@id = "edit-author"]/legend//a');
            case 'name':
                return new Text($this->webdriver, $this->element->byName('name'));
            case 'comment':
                return new Text($this->webdriver, $this->element->byName('comment_body[und][0][value]'));
            case 'save':
                return $this->element->byXPath('.//input[contains(@class, "form-submit")]');
        }
        throw new FormFieldNotDefinedException($name);
    }

    /**
     * Opens the Administration fieldset if closed, does nothing otherwise.
     */
    public function openAdministrationFieldset()
    {
        $element = $this->webdriver->byXPath('//fieldset[@id = "edit-author"]');
        $classes = explode(' ', $element->attribute('class'));
        if (in_array('collapsed', $classes)) {
            $this->administrationLink->click();
        }
    }
}
