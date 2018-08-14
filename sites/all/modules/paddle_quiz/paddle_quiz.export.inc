<?php

/**
 * @file
 * Contains all functions related to the exporting of quiz results.
 */

/**
 * Verifies that the PHPExcel library is available.
 *
 * @return bool
 *   True if the PHPExcel library is available, false otherwise.
 */
function paddle_quiz_export_library_available() {
  $phpexcel = libraries_load('phpexcel');
  return ($phpexcel['installed'] == TRUE && $phpexcel['loaded'] == TRUE);
}

/**
 * Requests a new export for the results of a specific quiz.
 *
 * @param QuizEntity $quiz
 *   The quiz of which the results should be exported.
 */
function paddle_quiz_export_request(\QuizEntity $quiz) {
  global $user;

  db_insert('paddle_quiz_export')
    ->fields(array(
      'qid' => $quiz->qid,
      'uid' => $user->uid,
      'requested' => REQUEST_TIME,
    ))
    ->execute();
}

/**
 * Processes pending quiz result exports, up to a specific limit.
 *
 * @param int $limit
 *   Max number of quiz exports to result.
 */
function paddle_quiz_export_process_pending($limit = 2) {
  // Get all pending exports (those who don't have an export file yet), sorted
  // by requested time so the oldest get processed first.
  $results = db_select('paddle_quiz_export', 'e')
    ->fields('e')
    ->condition('fid', NULL)
    ->orderBy('requested', 'ASC')
    ->range(0, $limit)
    ->execute();

  foreach ($results as $result) {
    if (!paddle_quiz_export_library_available()) {
      watchdog(
        'paddle_quiz_export',
        'Export for quiz with id @id was not processed because the PHPExcel library is not available.',
        array(
          '@id' => $result->qid,
        ),
        WATCHDOG_ERROR
      );
      return;
    }

    $fid = paddle_quiz_export_generate_file($result);

    db_update('paddle_quiz_export')
      ->fields(array(
        'fid' => $fid,
        'created' => time(),
      ))
      ->condition('eid', $result->eid)
      ->execute();

    paddle_quiz_export_notify($result->eid);
  }
}

/**
 * Generates the export file for a quiz's results.
 *
 * @param object $export
 *   Export record from table paddle_quiz_export as an object.
 *
 * @return int
 *   Fid of the newly created managed file.
 */
function paddle_quiz_export_generate_file($export) {
  // Create a new Excel file object.
  libraries_load('phpexcel');
  $excel = new PHPExcel();
  $excel->setActiveSheetIndex(0);

  // Determine the column names.
  $t_options = array('context' => 'paddle_quiz:export');
  $columns = array(
    'pid' => t('Participation ID', array(), $t_options),
    'email' => t('E-mail address', array(), $t_options),
    'name' => t('Name', array(), $t_options),
    'score' => t('Score', array(), $t_options),
    'score_max' => t('Max score', array(), $t_options),
    'score_percentage' => t('Score percentage', array(), $t_options),
    'tiebreaker' => t('Tiebreaker', array(), $t_options),
    'start' => t('Start time', array(), $t_options),
    'end' => t('End time', array(), $t_options),
  );

  $questions = paddle_quiz_export_distinct_questions($export);
  $question_columns = array();
  foreach ($questions as $question) {
    $question_columns[$question] = $question;
    $question_columns[$question . '_correct'] = t('Correct?');
  }

  $columns = array_merge($columns, $question_columns);

  // Write the column names to the first row of the Excel sheet.
  $row_count = 1;
  $excel->getActiveSheet()->fromArray(array_values($columns), '', 'A' . $row_count);

  // Create an empty "default" array for each new row to write.
  $empty_row = array_fill_keys(array_keys($columns), '');

  $quiz = paddle_quiz_load($export->qid);
  $pid = 0;
  $limit = 10;
  $max_end_time = $export->requested;
  do {
    // Get 10 participations for the quiz.
    $participations = $quiz->getParticipationsRange($pid, $limit, $max_end_time);

    // Loop over each of those participations and write a row for each one.
    foreach ($participations as $pid => $participation) {
      // Copy the participation fields to a new export row.
      unset($participation['qid']);
      $export_row = array_merge($empty_row, $participation);

      // Format the timestamps.
      $export_row['start'] = format_date($export_row['start'], 'short');
      $export_row['end'] = format_date($export_row['end'], 'short');

      // Get the questions & answers for the participation.
      $answers = db_select('paddle_quiz_answer', 'a')
        ->fields('a')
        ->condition('pid', $pid)
        ->execute();

      // Set each answer in the new export row.
      foreach ($answers as $answer_record) {
        $question = $answer_record->question;
        $export_row[$question] = $answer_record->answer;
        $export_row[$question . '_correct'] = $answer_record->correct;
      }

      // Write the new export row to the Excel sheet.
      $row_count++;
      $excel->getActiveSheet()->fromArray(array_values($export_row), '', 'A' . $row_count);
    }

    // Increase the pid before getting a new range of participations, otherwise
    // the last participation would be included in the new range.
    $pid++;
  } while (count($participations) == $limit);

  // Determine the destination path for the new file.
  $destination_directory = 'public://paddle_quiz_exports/';
  $destination = $destination_directory . uuid_generate() . '.xlsx';
  $destination = file_destination($destination, FILE_EXISTS_RENAME);

  // Make sure the directory exists and is writeable.
  file_prepare_directory($destination_directory, FILE_CREATE_DIRECTORY | FILE_MODIFY_PERMISSIONS);

  // Write the Excel file object to disk.
  $writer = new \PHPExcel_Writer_Excel2007($excel);
  $writer->save(drupal_realpath($destination));

  // Store the new file as a managed file.
  $file = new stdClass();
  $file->uid = $export->uid;
  $file->filename = basename($destination);
  $file->uri = $destination;
  $file->status = 1;
  $file->filemime = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
  file_save($file);

  // Mark the file as "in use" by our module.
  file_usage_add($file, 'paddle_quiz', 'paddle_quiz_export', $export->eid);

  // Return the managed file's id.
  return $file->fid;
}

/**
 * Get all distinct questions for an export.
 *
 * @param object $export
 *   Export record from table paddle_quiz_export as an object.
 *
 * @return string[]
 *   Array of distinct question strings.
 */
function paddle_quiz_export_distinct_questions($export) {
  $questions_select = db_select('paddle_quiz_answer', 'a');
  $questions_select->join('paddle_quiz_participation', 'p', 'p.pid = a.pid');
  $questions_select->fields('a', array('question'))
    ->condition('p.qid', $export->qid)
    ->condition('p.end', $export->requested, '<=')
    ->distinct();
  return $questions_select->execute()->fetchCol();
}

/**
 * Notifies the user that requested an export that it is ready to download.
 *
 * @param int $eid
 *   Export id.
 */
function paddle_quiz_export_notify($eid) {
  $export = db_select('paddle_quiz_export', 'e')
    ->fields('e')
    ->condition('e.eid', $eid)
    ->execute()
    ->fetchAssoc();

  $user = user_load($export['uid']);
  $languages = language_list('language');
  $language = $languages[$user->language];

  drupal_mail('paddle_quiz', 'export', $user->mail, $language, array(
    'export' => $export,
  ));
}

/**
 * Removes all expired quiz result exports.
 */
function paddle_quiz_export_remove_expired() {
  $expire_time = strtotime('-5 days');

  // Get all exports that are expired.
  $results = db_select('paddle_quiz_export', 'e')
    ->fields('e', array('eid', 'fid'))
    ->condition('created', $expire_time, '<=')
    ->execute();

  foreach ($results as $export) {
    paddle_quiz_export_remove_export($export);
  }
}

/**
 * Removes a specific quiz results export.
 *
 * @param object $export
 *   The quiz export as an object.
 */
function paddle_quiz_export_remove_export($export) {
  // Start a database transaction.
  $transaction = db_transaction();

  try {
    // Delete the export request.
    db_delete('paddle_quiz_export')
      ->condition('eid', $export->eid)
      ->execute();

    // Mark the export file as unused.
    $file = file_load($export->fid);
    if ($file) {
      file_usage_delete(
        $file,
        'paddle_quiz',
        'paddle_quiz_export',
        $export->eid
      );

      // Delete the export file if no other modules are using it either.
      file_delete($file);
    }

    // Commit the transaction by removing the variable from scope.
    unset($transaction);
  }
  catch (\Exception $e) {
    // If an error occurred when deleting the export, roll back any changes.
    $transaction->rollback();
  }
}
