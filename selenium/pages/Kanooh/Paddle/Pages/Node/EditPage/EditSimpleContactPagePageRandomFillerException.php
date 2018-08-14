<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\EditSimpleContactPagePageRandomFillerException.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage;

use Kanooh\Paddle\Pages\PaddlePageException;

/**
 * Class EditSimpleContactPagePageRandomFillerException
 */
class EditSimpleContactPagePageRandomFillerException extends PaddlePageException
{
    /**
     * Constructor.
     *
     * @param object $page
     *   Object that's not a valid edit page for Simple Contact Pages.
     */
    public function __construct($page)
    {
        parent::__construct($page->getClass() . ' is not a valid instance of a Simple Contact Page add or edit page.');
    }
}
