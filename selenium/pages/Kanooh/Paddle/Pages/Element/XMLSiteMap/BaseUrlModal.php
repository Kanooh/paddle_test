<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\XMLSiteMap\BaseUrlModal.
 */

namespace Kanooh\Paddle\Pages\Element\XMLSiteMap;

use Kanooh\Paddle\Pages\Element\Modal\Modal;

/**
 * Class BaseUrlModal
 *
 * @property BaseUrlForm $form
 */
class BaseUrlModal extends Modal
{
    public function __get($name)
    {
        switch ($name) {
            case 'form':
                return new BaseUrlForm($this->webdriver, $this->webdriver->byXPath('//form[contains(@id, "paddle-xml-sitemap-edit-base-url-form")]'));
        }
    }
}
