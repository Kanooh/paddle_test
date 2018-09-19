<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Formbuilder\FormSubmissionsPage.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Formbuilder;

use Kanooh\Paddle\Pages\PaddlePage;

/**
 * The 'Submissions' page of the Paddle Formbuilder module.
 *
 * @property FormSubmissionsContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 */
class FormSubmissionsPage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/content_manager/node/%/submissions';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'contextualToolbar':
                return new FormSubmissionsContextualToolbar($this->webdriver);
        }
        return parent::__get($property);
    }

    /**
     * Check if the submission is present.
     *
     * @param int $submission_no
     *   The number of the submission.
     *
     * @return bool
     *   True if the submission is present, false otherwise.
     */
    public function submissionFound($submission_no)
    {
        $xpath = $this->getSubmissionOperationsCellXPath($submission_no);
        $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));
        return (bool) count($elements);
    }

    /**
     * Check if an operation link is present.
     *
     * If the link is present, user_access() has run so he can do that action.
     *
     * @param int $submission_no
     *   The number of the submission.
     * @param string $operation
     *   The operation to find, like view, edit, delete, resend.
     *
     * @return bool
     *   True if the link is present, false otherwise.
     */
    public function isSubmissionOperationPresent($submission_no, $operation)
    {
        $xpath = $this->getSubmissionOperationsCellXPath($submission_no);
        $xpath .= '//a/span[contains(text(), "' . $operation . '")]';
        $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));

        return (bool) count($elements);
    }

    /**
     * Fetch the operation table cell for a specific submission.
     *
     * @param int $submission_no
     *   The number of the submission.
     *
     * @return array
     *   The elements found.
     */
    protected function getSubmissionOperationsCellXPath($submission_no)
    {
        $xpath = '//div[contains(@class, "view-webform-submissions")]'
          . '//td[contains(@class, "views-field-view-submission-' . $submission_no . '")]';
        return $xpath;
    }
}
