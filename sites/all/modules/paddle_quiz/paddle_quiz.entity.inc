<?php

/**
 * @file
 * Contains \QuizEntity.
 */

/**
 * Class QuizEntity.
 */
class QuizEntity extends Entity {
  /**
   * Info screen location "start" value.
   */
  const INFO_START = 'start';

  /**
   * Info screen location "end" value.
   */
  const INFO_END = 'end';

  /**
   * Quiz ID.
   *
   * @var int
   */
  public $qid = 0;

  /**
   * Title property.
   *
   * @var string
   */
  public $title = '';

  /**
   * Status property.
   *
   * Either 1 for published, or 0 for unpublished.
   *
   * @var int
   */
  public $status = 0;

  /**
   * Creates a new quiz entity.
   *
   * @param array $values
   *   Values of the entity's properties.
   */
  public function __construct(array $values = array()) {
    parent::__construct($values, 'paddle_quiz');
  }

  /**
   * Returns the label of the entity.
   *
   * @return string
   *   Label of the quiz entity.
   */
  protected function defaultLabel() {
    return $this->title;
  }

  /**
   * Loads the field collection items for the questions.
   */
  public function loadQuestions() {
    if (!empty($this->field_paddle_quiz_questions[LANGUAGE_NONE])) {
      foreach ($this->field_paddle_quiz_questions[LANGUAGE_NONE] as &$question) {
        if (is_array($question)) {
          $id = $question['value'];
          $field_collection_entity = field_collection_item_load($id);
          $question = $field_collection_entity;
        }
      }
    }
  }

  /**
   * Returns the correct answers for all the questions in the quiz.
   *
   * This only works if loadQuestions() has been called before.
   *
   * @return int[]
   *   Array of answer indexes, keyed by the index of their question.
   */
  public function getCorrectAnswers() {
    $answers = array();
    if (isset($this->field_paddle_quiz_questions)) {
      foreach ($this->field_paddle_quiz_questions[LANGUAGE_NONE] as &$question) {
        if (!is_array($question)) {
          $answers[] = $question->field_paddle_quiz_correct_answer[LANGUAGE_NONE][0]['value'];
        }
      }
    }
    return $answers;
  }

  /**
   * Calculates the amount of correct answers provided.
   *
   * @param int[] $answers
   *   Answers (indexes) provided by the user.
   *
   * @return int
   *   Exact amount of correct answers provided.
   */
  public function getScore($answers) {
    $correct_answers = $this->getCorrectAnswers();
    $total_correct = 0;
    foreach ($correct_answers as $index => $correct_answer) {
      if ($answers[$index] === $correct_answer) {
        $total_correct++;
      }
    }
    return $total_correct;
  }

  /**
   * Calculates the score as a percentage based on provided answers.
   *
   * @param int[] $answers
   *   Answers (indexes) provided by the user.
   *
   * @return int
   *   Total score, which is a percentage of the correct answers based on the
   *   total amount of questions.
   */
  public function getScorePercentage($answers) {
    $correct_answers = $this->getCorrectAnswers();
    $score = $this->getScore($answers);
    return floor(($score / count($correct_answers)) * 100);
  }

  /**
   * Checks whether the quiz has a disclaimer or not.
   *
   * @return bool
   *   TRUE if the quiz has a disclaimer, FALSE otherwise.
   */
  public function hasDisclaimer() {
    $disclaimer_value = '';

    if (!empty($this->field_paddle_quiz_disclaimer[LANGUAGE_NONE][0]['safe_value'])) {
      $disclaimer_value = $this->field_paddle_quiz_disclaimer[LANGUAGE_NONE][0]['safe_value'];

      // Strip any HTML tags, as we want to see whether the disclaimer contains
      // any actual text.
      $disclaimer_value = strip_tags($disclaimer_value);

      // Decode all HTML entities like &nbsp; so we can trim them at the start
      // and end of the string.
      $disclaimer_value = html_entity_decode($disclaimer_value);

      // Trim all whitespace and newlines from the start and end of the string.
      // Note that non-breaking whitespaces are converted to 0xA0 instead of
      // 0x20 for the regular whitespaces, so we need to pass 0xA0 as well to
      // the character mask. See the "notes" section (above "see also") on
      // http://php.net/html_entity_decode and the comment with an explanation
      // of the solution on http://php.net/manual/en/function.trim.php#98812
      $default_char_mask = " \t\n\r\0\x0B";
      $nbsp_whitespace = chr(0xC2) . chr(0xA0);
      $disclaimer_value = trim($disclaimer_value, $default_char_mask . $nbsp_whitespace);
    }

    return !empty($disclaimer_value);
  }

  /**
   * Checks whether the quiz has a tiebreaker question or not.
   *
   * @return bool
   *   TRUE if the quiz has a tiebreaker question, FALSE otherwise.
   */
  public function hasTiebreakerQuestion() {
    return !empty($this->field_paddle_quiz_tiebreaker[LANGUAGE_NONE][0]['value']);
  }

  /**
   * Returns the location of the info screen in the quiz.
   *
   * @return string
   *   QuizEntity::INFO_START or QuizEntity::INFO_END
   */
  public function getInfoScreenLocation() {
    $quiz_wrapper = entity_metadata_wrapper('paddle_quiz', $this);
    $quiz_info_location = $quiz_wrapper->field_paddle_quiz_info_location->value();

    if (isset($quiz_info_location)) {
      if ($quiz_info_location == 'end') {
        return self::INFO_END;
      }
      elseif ($quiz_info_location == 'start') {
        return self::INFO_START;
      }
    }
    return FALSE;
  }

  /**
   * Stores a quiz participation cache object permanently.
   *
   * Also deletes any previous participations by users with the same e-mail
   * address.
   *
   * @param \QuizParticipationCache $cache
   *   Quiz participation object.
   */
  public function storeParticipation(\QuizParticipationCache $cache) {
    // Start a new transaction during which we will delete the previous
    // participations (if any) and save the new participation.
    $transaction = db_transaction();
    try {
      // Delete any previous participations.
      if (!empty($cache->email)) {
        $this->deleteParticipations($cache->email);
      }

      // Store (new) results in the database.
      $correct_answers = $this->getCorrectAnswers();
      $pid = db_insert('paddle_quiz_participation')
        ->fields(array(
          'qid' => $this->qid,
          'email' => $cache->email,
          'name' => $cache->name,
          'score' => $this->getScore($cache->answers),
          'score_max' => count($correct_answers),
          'score_percentage' => $this->getScorePercentage($cache->answers),
          'start' => $cache->start,
          'end' => $cache->end,
          'tiebreaker' => $cache->tiebreaker,
        ))
        ->execute();

      foreach ($this->field_paddle_quiz_questions[LANGUAGE_NONE] as $index => &$question) {
        $answer = $cache->answers[$index];
        $correct_answer = $correct_answers[$index];
        db_insert('paddle_quiz_answer')
          ->fields(array(
            'pid' => $pid,
            'question' => $question->field_paddle_quiz_question[LANGUAGE_NONE][0]['value'],
            'answer' => $question->field_paddle_quiz_answers[LANGUAGE_NONE][$answer]['value'],
            'correct' => (int) ($answer == $correct_answer),
          ))
          ->execute();
      }
    }
    catch (\Exception $e) {
      $transaction->rollback();
    }
  }

  /**
   * Gets participations of this quiz, created within a given timeframe.
   *
   * @param int $pid
   *   Participation id to start the range from (inclusive).
   * @param int $limit
   *   Max number of participations to return in between the time range.
   * @param int $latest_time
   *   Maximum end time of the participations (inclusive), or NULL to omit.
   *
   * @return object[]
   *   Array of participation records as associative arrays, keyed by their pid.
   */
  public function getParticipationsRange($pid = 0, $limit = 10, $latest_time = NULL) {
    $query = db_select('paddle_quiz_participation', 'p')
      ->condition('p.qid', $this->qid)
      ->condition('p.pid', $pid, '>=');

    if (!is_null($latest_time)) {
      $query->condition('p.end', $latest_time, '<=');
    }

    return $query->fields('p')
      ->orderBy('p.end')
      ->range(0, $limit)
      ->execute()
      ->fetchAllAssoc('pid', PDO::FETCH_ASSOC);
  }

  /**
   * Deletes participations of this quiz.
   *
   * @param string $email
   *   E-mail address of the participant. Optional. If not provided, all
   *   participations will be deleted.
   */
  protected function deleteParticipations($email = '') {
    // Get all participation ids of the quiz. We need the pids to deletes
    // related answers, so we need to do a db_select() before we can do
    // db_delete().
    $pid_select = db_select('paddle_quiz_participation', 'p')
      ->fields('p', array('pid'))
      ->condition('qid', $this->qid);

    if (!empty($email)) {
      $pid_select->condition('email', $email);
    }

    // Get the pids.
    $pids = $pid_select->execute()
      ->fetchCol();

    // Delete any participations and related answers that were found.
    if (!empty($pids)) {
      db_delete('paddle_quiz_answer')
        ->condition('pid', $pids, 'IN')
        ->execute();
      db_delete('paddle_quiz_participation')
        ->condition('pid', $pids, 'IN')
        ->execute();
    }
  }

  /**
   * Deletes exports of this quiz.
   */
  protected function deleteExports() {
    $exports = db_select('paddle_quiz_export', 'e')
      ->fields('e', array('eid', 'fid'))
      ->condition('qid', $this->qid)
      ->execute();

    module_load_include('inc', 'paddle_quiz', 'paddle_quiz.export');
    foreach ($exports as $export) {
      paddle_quiz_export_remove_export($export);
    }
  }

  /**
   * Returns the total amount of participations for the quiz.
   *
   * @return int
   *   Total amount of participations.
   */
  public function countParticipations() {
    /* @var \SelectQuery $query */
    $query = db_select('paddle_quiz_participation', 'p')
      ->fields('p', array('pid'))
      ->condition('qid', $this->qid)
      ->countQuery();

    return $query->execute()
      ->fetchField();
  }

  /**
   * Deletes the quiz entity and all of its participation records.
   */
  public function delete() {
    $transaction = db_transaction();
    try {
      $this->deleteParticipations();
      $this->deleteExports();
      parent::delete();
    }
    catch (\Exception $e) {
      $transaction->rollback();
    }
  }
}
