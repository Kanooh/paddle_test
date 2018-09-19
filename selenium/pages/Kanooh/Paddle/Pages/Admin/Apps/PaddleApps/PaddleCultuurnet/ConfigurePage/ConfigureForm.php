<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleCultuurnet\ConfigurePage\ConfigureForm.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleCultuurnet\ConfigurePage;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\FileField;
use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * Class representing the Paddle Cultuurnet configuration form.
 *
 * @property Text $applicationKey
 * @property Text $sharedSecret
 * @property Text $topPaneTitle
 * @property Text $topPaneTag
 * @property Text $bottomPaneTitle
 * @property Text $bottomPaneTag
 * @property FileField $pageLogo
 */
class ConfigureForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'applicationKey':
                return new Text($this->webdriver, $this->webdriver->byName('culturefeed_search_api_application_key'));
                break;
            case 'sharedSecret':
                return new Text($this->webdriver, $this->webdriver->byName('culturefeed_search_api_shared_secret'));
                break;
            case 'topPaneTitle':
                return new Text($this->webdriver, $this->webdriver->byName('top_pane_title'));
                break;
            case 'topPaneTag':
                return new Text($this->webdriver, $this->webdriver->byName('top_pane_tag'));
                break;
            case 'bottomPaneTitle':
                return new Text($this->webdriver, $this->webdriver->byName('bottom_pane_title'));
                break;
            case 'bottomPaneTag':
                return new Text($this->webdriver, $this->webdriver->byName('bottom_pane_tag'));
                break;
            case 'pageLogo':
                return new FileField(
                    $this->webdriver,
                    '//input[@name="culturefeed_search_page_logo[fid]',
                    '//input[@name="culturefeed_search_page_logo_upload_button"]',
                    '//input[@name="culturefeed_search_page_logo_remove_button"]'
                );
        }
        throw new FormFieldNotDefinedException($name);
    }
}
