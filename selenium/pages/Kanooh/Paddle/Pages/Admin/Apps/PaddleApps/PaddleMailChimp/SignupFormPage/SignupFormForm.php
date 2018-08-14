<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleMailChimp\SignupFormPage\SignupFormForm.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleMailChimp\SignupFormPage;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Element\Form\AjaxCheckbox;
use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * Class representing the main form on the Signup form entity add/edit page.
 *
 * @property Text $title
 *   The title of the entity.
 */
class SignupFormForm extends Form
{
    public function __get($name)
    {
        switch ($name) {
            case 'title':
                return new Text($this->webdriver, $this->webdriver->byName('title'));
                break;
        }
    }

    /**
     * Checks the checkbox of the appropriate MailChimp list and waits until its
     * merge field are loaded.
     *
     * @param  string $name
     *   The human-readable name of list.
     */
    public function selectListByName($name)
    {
        $xpath = '//div[@id="edit-mc-lists"]//label[normalize-space(text()) = "' . $name . '"]/../input';
        $checkbox = new AjaxCheckbox($this->webdriver, $this->webdriver->byXPath($xpath));
        $checkbox->check();

        $callable = new SerializableClosure(
            function () use ($checkbox) {
                return $checkbox->isChecked();
            }
        );
        $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());
    }
}
