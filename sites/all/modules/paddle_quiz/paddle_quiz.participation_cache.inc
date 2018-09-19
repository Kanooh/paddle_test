<?php

/**
 * @file
 * Contains \QuizParticipationCache.
 */

/**
 * Class QuizParticipationCache.
 *
 * Objects from this class are stored using the ctools object cache to keep
 * track of the progress of a user in a quiz.
 */
class QuizParticipationCache {
  /**
   * The quiz the user is participating in.
   *
   * This is also stored in the cache to prevent errors when a quiz is updated
   * while a visitor is participating in it. The visitor will always get the
   * same quiz entity from the moment he starts the quiz until he finishes it.
   *
   * @var QuizEntity
   */
  public $quiz;

  /**
   * Timestamp of the time the user started the quiz.
   *
   * @var int
   */
  public $start = 0;

  /**
   * Timestamp of the time the user finished the quiz.
   *
   * @var int
   */
  public $end = 0;

  /**
   * The current step the user is on.
   *
   * @var string
   */
  public $step = 'start';

  /**
   * The current question the user is on (if the step is 'question').
   *
   * @var int
   */
  public $question = 0;

  /**
   * List of answers (indices) provided by the user.
   *
   * @var int[]
   */
  public $answers = array();

  /**
   * Name of the user.
   *
   * @var string
   */
  public $name = '';

  /**
   * E-mail address of the user.
   *
   * @var string
   */
  public $email = '';

  /**
   * Answer provided for the tiebreaker (if there is one).
   *
   * @var string
   */
  public $tiebreaker = '';

  /**
   * Total score of the user.
   *
   * This is a percentage of amount of correct answers over total amount of
   * questions.
   *
   * @var int
   */
  public $score = 0;

  /**
   * Creates a new QuizParticipationCache object.
   *
   * @param QuizEntity $quiz
   *   The quiz the user is participating in.
   */
  public function __construct(QuizEntity $quiz) {
    $this->quiz = $quiz;
  }
}
