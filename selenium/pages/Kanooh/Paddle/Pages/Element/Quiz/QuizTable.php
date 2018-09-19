<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Quiz\QuizTable.
 */

namespace Kanooh\Paddle\Pages\Element\Quiz;

use Kanooh\Paddle\Pages\Element\Table\Table;
use Kanooh\WebDriver\WebdriverTestCase;

/**
 * Table list of quizzes.
 *
 * @property QuizTableRow[] $rows
 *   All of the items inside the table.
 */
class QuizTable extends Table
{
    /**
     * The webdriver element of the quiz table.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Construct a new QuizTable.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium web driver test case.
     * @param string $xpath
     *   The xpath selector of the quiz table instance.
     */
    public function __construct(WebDriverTestCase $webdriver, $xpath)
    {
        parent::__construct($webdriver);
        $this->xpathSelector = $xpath;
        $this->element = $this->webdriver->byXPath($xpath);
    }

    /**
     * Magic getter for children elements.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'rows':
                $criteria = $this->element->using('xpath')->value('.//tbody//td[not(contains(@class, "empty"))]/..');
                $rows = $this->element->elements($criteria);
                $items = array();
                foreach ($rows as $row) {
                    $items[] = new QuizTableRow($this->webdriver, $row);
                }
                return $items;
                break;
        }
    }

    /**
     * Checks if the table is empty or not.
     *
     * @return boolean
     *   TRUE if the table is empty (shows an empty message), FALSE if not.
     */
    public function isEmpty()
    {
        // The table is empty if it has no rows, or the first row has no quiz
        // id (in which case it's actually a placeholder text).
        $rows = $this->rows;
        if (count($rows) == 0 || (count($rows) == 1 && empty($rows[0]->qid))) {
            return true;
        }
        return false;
    }

    /**
     * Returns a row based on the qid given.
     *
     * @param string $qid
     *   Quiz ID of the row to return.
     *
     * @return QuizTableRow
     *   The row for the given wid, or false if not found.
     */
    public function getRowByQid($qid)
    {
        $criteria = $this->element->using('xpath')->value('.//tbody//tr[@data-quiz-id="' . $qid . '"]');
        $rows = $this->element->elements($criteria);
        if (empty($rows)) {
            return false;
        }
        return new QuizTableRow($this->webdriver, $rows[0]);
    }

    /**
     * Returns all quiz ids for the quizzes in the table.
     *
     * @return int[]
     *   All quiz ids sorted by their order in the table.
     */
    public function getQids()
    {
        $qids = array();
        foreach ($this->rows as $row) {
            $qids[] = $row->qid;
        }
        return $qids;
    }
}
