<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\CreateQuizPageModal.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\AddPage;

use Kanooh\Paddle\Pages\Element\Quiz\QuizReferenceField;

/**
 * Class representing the modal dialog for creating new quiz pages.
 *
 * @package Kanooh\Paddle\Pages\Admin\ContentManager\AddPage
 *
 * @property QuizReferenceField $quizReference
 *   Field to select a quiz to show on the page.
 */
class CreateQuizPageModal extends CreateNodeModal
{
    /**
     * Magic getter.
     */
    public function __get($property)
    {
        switch ($property) {
            case 'quizReference':
                // Check that the id attribute contains the id we're looking for
                // as an exact match may not be possible if the form has been
                // rebuild and the field ids are appended with numbers.
                $xpath = $this->xpathSelector . '//div[contains(@id, "edit-field-paddle-quiz-reference-und")]';
                $container = $this->webdriver->byXPath($xpath);
                return new QuizReferenceField($this->webdriver, $container);
        }

        return parent::__get($property);
    }
}
