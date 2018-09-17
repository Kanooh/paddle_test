<?php
/**
 * @file
 * Contains Kanooh\Paddle\Pages\Admin\Archive\RestoreNodeModal.
 */

namespace Kanooh\Paddle\Pages\Admin\Archive;

use Kanooh\Paddle\Pages\Element\Modal\ConfirmModal;

/**
 * Class representing the restore node confirmation modal.
 *
 * @package Kanooh\Paddle\Pages\Admin\Archive
 */
class RestoreNodeModal extends ConfirmModal
{
    /**
     * The XPath selector that identifies the Archive button.
     */
    protected $submitButtonXPathSelector = '//input[@type="submit" and @value="Restore"]';
}
