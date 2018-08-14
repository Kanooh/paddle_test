<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Quiz\QuizTableRow.
 */

namespace Kanooh\Paddle\Pages\Element\Quiz;

use Kanooh\Paddle\Pages\Element\Form\Checkbox;
use Kanooh\Paddle\Pages\Element\Table\Row;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class QuizTableRow
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $deleteLink
 *   The delete link of the quiz.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $editLink
 *   The edit link of the quiz.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $exportLink
 *   The export link of the quiz results.
 * @property Checkbox $status
 *   Status (published / unpublished) checkbox.
 * @property string $title
 *   Title of the quiz.
 * @property int $qid
 *   The Quiz ID.
 */
class QuizTableRow extends Row
{
    /**
     * The webdriver element of the quiz table row.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Construct a new QuizTableRow.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium web driver test case.
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The webdriver element of the quiz table row.
     */
    public function __construct(WebDriverTestCase $webdriver, $element)
    {
        parent::__construct($webdriver);
        $this->element = $element;
    }

    /**
     * Magic getter for the quiz row's properties.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'deleteLink':
                return $this->element->byXPath('.//a[contains(@class, "ui-icon-delete")]');
                break;
            case 'editLink':
                return $this->element->byXPath('.//a[contains(@class, "ui-icon-edit")]');
                break;
            case 'exportLink':
                return $this->element->byXPath('.//a[contains(@class, "paddle-quiz-export-link")]');
                break;
            case 'status':
                $element = $this->element->byXPath('.//td[contains(@class, "status")]//input[@type="checkbox"]');
                return new Checkbox($this->webdriver, $element);
                break;
            case 'title':
                $cell = $this->element->byXPath('.//td[contains(@class, "quiz-title")]');
                return $cell->text();
                break;
            case 'qid':
                return $this->element->attribute('data-quiz-id');
                break;
        }
    }

    /**
     * Checks whether or not the export link is present.
     *
     * @return bool
     *   True if the export link is present, false otherwise.
     */
    public function isExportLinkPresent()
    {
        try {
            $this->exportLink;
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
