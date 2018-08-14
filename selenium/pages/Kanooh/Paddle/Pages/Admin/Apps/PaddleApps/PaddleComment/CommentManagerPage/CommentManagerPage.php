<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleComment\CommentManagerPage.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleComment\CommentManagerPage;

use Kanooh\Paddle\Pages\Element\BulkActions\BulkActions;
use Kanooh\Paddle\Pages\AdminPage;

/**
 * Class representing the Content Manager page.
 *
 * @property CommentManagerExposedForm $exposedForm
 *   The exposed form for the view on the page.
 * @property CommentManagerPageTable $commentTable
 *   The table containing the nodes.
 * @property BulkActions $bulkActions
 *   The Bulk Actions Form.
 */
class CommentManagerPage extends AdminPage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/content_manager/comments';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'exposedForm':
                $element = $this->webdriver->byId('views-exposed-form-comment-manager-comment-overview-page');
                return new CommentManagerExposedForm($this->webdriver, $element);
            case 'commentTable':
                return new CommentManagerPageTable($this->webdriver);
            case 'bulkActions':
                $xpath = 'views-form-comment-manager-comment-overview-page';
                return new BulkActions($this->webdriver, $this->webdriver->byId($xpath));
        }
        return parent::__get($property);
    }

    /**
     * {@inheritdoc}
     */
    public function waitUntilPageIsLoaded()
    {
        $this->webdriver->waitUntilElementIsDisplayed(
            '//body[contains(@class, "page-admin-content-manager-comments")]'
        );
    }
}
