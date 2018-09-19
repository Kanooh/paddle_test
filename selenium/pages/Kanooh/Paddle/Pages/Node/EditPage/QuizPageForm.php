<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\QuizPageForm.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Quiz\QuizReferenceField;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;

/**
 * Class representing the quiz page edit form.
 *
 * @property QuizReferenceField $quizReference
 *   Field to select a quiz to show on the page.
 */
class QuizPageForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'quizReference':
                // Check that the id attribute contains the id we're looking for
                // as an exact match may not be possible if the form has been
                // rebuild and the field ids are appended with numbers.
                $xpath = './/div[contains(@id, "edit-field-paddle-quiz-reference-und")]';
                $container = $this->element->byXPath($xpath);
                return new QuizReferenceField($this->webdriver, $container);
        }
        throw new FormFieldNotDefinedException($name);
    }
}
