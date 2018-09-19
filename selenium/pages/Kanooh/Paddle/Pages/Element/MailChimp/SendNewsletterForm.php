<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\MailChimp\SendNewsletterForm.
 */

namespace Kanooh\Paddle\Pages\Element\MailChimp;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Pages\Element\Form\RadioButton;

/**
 * Represents the main form on the modal used to send newsletters as a test
 * e-mail or as a campaign.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $sendButton
 *   The form's send button.
 * @property Text $emails
 *   The form's e-mails text field.
 * @property RadioButton $sendModeNow
 *   The radio button indicating to send the campaign once.
 * @property RadioButton $sendModeLater
 *   The radio button indicating to send the campaign later.
 * @property Text $sendTimeDate
 *   The text field used to enter the date on which to send a campaign.
 * @property Text $sendTimeTime
 *   The text field used to enter the time on which to send a campaign.
 */
class SendNewsletterForm extends Form
{
    public function __get($name)
    {
        switch ($name) {
            case 'sendButton':
                return $this->element->byXPath('.//input[contains(@id, "edit-send") and @type = "submit"]');
                break;
            case 'emails':
                $element = $this->element->byXPath('.//input[@name="test_emails"]');
                return new Text($this->webdriver, $element);
                break;
            case 'sendModeNow':
                return new RadioButton($this->webdriver, $this->element->byXPath('.//input[@value="send_now"]'));
                break;
            case 'sendModeLater':
                return new RadioButton($this->webdriver, $this->element->byXPath('.//input[@value="send_later"]'));
                break;
            case 'sendTimeDate':
                $element = $this->element->byXPath('.//input[@name="send_time[date]"]');
                return new Text($this->webdriver, $element);
                break;
            case 'sendTimeTime':
                $element = $this->element->byXPath('.//input[@name="send_time[time]"]');
                return new Text($this->webdriver, $element);
                break;
        }
    }
}
