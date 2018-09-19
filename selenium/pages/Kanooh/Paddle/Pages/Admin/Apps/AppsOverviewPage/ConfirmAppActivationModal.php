<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\AppsOverviewPage\ConfirmAppActivationModal.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\AppsOverviewPage;

use Kanooh\Paddle\Pages\Element\Modal\Modal;

/**
 * Class representing the "Paddlet activation" modal dialog.
 */
class ConfirmAppActivationModal extends Modal
{
    /**
     * {@inheritdoc}
     */
    protected $submitButtonXPathSelector = '//form[@id="paddle-apps-confirm-activation"]//input[@class="form-submit"]';
}
