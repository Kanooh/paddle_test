<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Comment\EditPage\EditPage
 */

namespace Kanooh\Paddle\Pages\Comment\EditPage;

use Kanooh\Paddle\Pages\Node\ViewPage\CommentForm;
use Kanooh\Paddle\Pages\PaddlePage;

/**
 * The edit page for the core comment entity.
 *
 * @property CommentForm $commentForm
 *   The comment form on the page.
 */
class EditPage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'comment/%/edit';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'commentForm':
                $xpath = '//form[contains(@class, "comment-form")]';
                $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));

                if ((bool) count($elements)) {
                    return new CommentForm($this->webdriver, $this->webdriver->byClassName('comment-form'));
                }

                return false;
        }
        return parent::__get($property);
    }
}
