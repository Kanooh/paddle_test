<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\EditSimpleContactPagePageRandomFiller.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage;

use Kanooh\Paddle\Pages\PaddlePage;

/**
 * Page to edit a simple contact page.
 */
class EditSimpleContactPagePageRandomFiller extends EditPageRandomFiller
{
    /**
     * Constructs an EditsimpleContactPagePageRandomFiller object.
     *
     * This can be used for node edit pages.
     *
     * @param mixed $page
     *   The page for which to form the fields.
     *
     * @throws EditSimpleContactPagePageRandomFillerException
     *   When $page is not an instance of a valid Simple Contact Page edit page.
     */
    public function __construct(PaddlePage $page)
    {
        if (!$page instanceof EditSimpleContactPagePage && !$page instanceof EditPage) {
            throw new EditSimpleContactPagePageRandomFillerException($page);
        }

        parent::__construct($page);
    }
}
