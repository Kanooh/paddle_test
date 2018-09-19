<?php
/**
 * @file
 * Contains Kanooh\Paddle\Pages\Admin\Archive\ArchiveNodeModal.
 */

namespace Kanooh\Paddle\Pages\Admin\Archive;

use Kanooh\Paddle\Pages\Element\Modal\ConfirmModal;

/**
 * Class representing the archive node confirmation modal.
 *
 * @package Kanooh\Paddle\Pages\Admin\Archive
 */
class ArchiveNodeModal extends ConfirmModal
{
    /**
     * The XPath selector that identifies the Archive button.
     */
    protected $submitButtonXPathSelector = '//input[@type="submit" and @value="Archive"]';
}
