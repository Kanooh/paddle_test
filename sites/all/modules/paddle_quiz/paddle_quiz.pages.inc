<?php

/**
 * @file
 * Contains callback functions for menu pages.
 */

/**
 * Quiz add page.
 *
 * @return string
 *   HTML for the quiz add page.
 */
function paddle_quiz_add_page() {
  // Get the add form.
  $form = entity_ui_get_form('paddle_quiz', NULL, 'add');

  // Overwrite the page title automatically set by entity_ui_get_form().
  drupal_set_title(t('Multiple choice quiz'));

  // Return the form for the page to render.
  return $form;
}

/**
 * Quiz edit page.
 *
 * @param \QuizEntity $quiz
 *   The quiz to edit.
 * @param string $step
 *   Step to start the edit form on.
 *
 * @return string
 *   HTML for the quiz edit page.
 */
function paddle_quiz_edit_page(\QuizEntity $quiz, $step = 'qa') {
  // Get the edit form.
  $form_state = form_state_defaults();
  $form_state['step'] = $step;
  $form = entity_ui_get_form('paddle_quiz', $quiz, 'edit', $form_state);

  // Overwrite the page title automatically set by entity_ui_get_form().
  drupal_set_title(t('Multiple choice quiz'));

  // Return the form for the page to render.
  return $form;
}

/**
 * Implements ENTITY_TYPE_form().
 *
 * Form definition for adding/editing a quiz.
 */
function paddle_quiz_form($form, &$form_state, $quiz = NULL, $op = 'add') {
  $quiz = is_null($quiz) ? new QuizEntity() : $quiz;
  $form_state['op'] = $op;

  $form['title'] = array(
    '#title' => t('Title'),
    '#type' => 'textfield',
    '#default_value' => $quiz->title,
    '#required' => TRUE,
    '#weight' => -50,
  );

  // Attach entity fields.
  field_attach_form('paddle_quiz', $quiz, $form, $form_state);

  if (!empty($form_state['step']) && $form_state['step'] == 'customize') {
    $form['field_paddle_quiz_info_location']['#states']['visible'] = array(':input[name="field_paddle_quiz_info_required[und]"]' => array('checked' => TRUE));
    $form['field_paddle_quiz_info_title']['#states']['visible'] = array(':input[name="field_paddle_quiz_info_required[und]"]' => array('checked' => TRUE));
    $form['field_paddle_quiz_info_message']['#states']['visible'] = array(':input[name="field_paddle_quiz_info_required[und]"]' => array('checked' => TRUE));
    $form['field_paddle_quiz_info_user']['#states']['visible'] = array(':input[name="field_paddle_quiz_info_required[und]"]' => array('checked' => TRUE));
    $form['field_paddle_quiz_info_button_l']['#states']['visible'] = array(':input[name="field_paddle_quiz_info_required[und]"]' => array('checked' => TRUE));
  }

  // Loop over each question and the button to add more questions.
  $questions = &$form['field_paddle_quiz_questions'][LANGUAGE_NONE];
  $questions['#theme'] = 'paddle_quiz_field_multiple_value_form';
  $questions['#fieldset'] = TRUE;

  foreach (element_children($questions) as $question_key) {
    $question = &$questions[$question_key];

    // Add more button.
    if ($question_key === 'add_more') {
      // Change the label of the button to add more questions.
      $question['#value'] = t('Add question');
    }
    // Question.
    else {
      // Re-order the fields inside the question so the remove button appears
      // somewhere at the top.
      $question['field_paddle_quiz_question']['#weight'] = 0;
      $question['remove_button']['#weight'] = 25;
      $question['field_paddle_quiz_question_image']['#weight'] = 50;
      $question['field_paddle_quiz_answers']['#weight'] = 75;

      // Hide the field that stores the index of the correct answer.
      $question['field_paddle_quiz_correct_answer']['#access'] = FALSE;

      // Add a common class to all remove buttons for the questions, and make
      // the label a bit more clear.
      $question['remove_button']['#attributes']['class'][] = 'remove-question';
      $question['remove_button']['#value'] = t('Remove question');

      // Add the question number to the title of the field.
      $question_no = $question_key + 1;
      $question_title = &$question['field_paddle_quiz_question'][LANGUAGE_NONE][0]['value'];
      $question_title['#title'] .= ' ' . $question_no;

      // Loop over all answers, and the button to add more answers.
      $answers = &$question['field_paddle_quiz_answers'][LANGUAGE_NONE];
      foreach (element_children($answers) as $answer_key) {
        $answer = &$answers[$answer_key];
        $answers['#theme'] = 'paddle_quiz_field_multiple_value_form';
        $answers['#fieldset'] = FALSE;

        // Change the label of the button to add more answers.
        if ($answer_key === 'add_more') {
          $answer['#value'] = t('Add answer');
        }
        // Answer.
        else {
          // Add the answer number to the title of the field.
          $answer_no = $answer_key + 1;
          $answer_title = t('Answer @no', array('@no' => $answer_no));
          $answer['value']['#title'] = $answer_title;

          // Add a radio button to indicate the correct answer. Note that we
          // need to give the radio button a name based on the field collection
          // uuid, because even when using #tree radio buttons can have
          // conflicts on the front-end if they have the same name. And if we
          // use the question index key, we get incorrect values when deleting
          // a question.
          $field_collection_item = &$form_state['field']['field_paddle_quiz_questions'][LANGUAGE_NONE]['entity'][$question_key];
          if (!isset($field_collection_item->uuid)) {
            // If we're dealing with a new field collection item, generate a
            // uuid beforehand. Normally field_collection does this on save but
            // we need one now.
            $field_collection_item->uuid = uuid_generate();
          }
          $uuid = $field_collection_item->uuid;
          $radio_name = 'correct_qa_' . $uuid;

          // If the form is being rebuild (for example when a new question is
          // added), make sure to select the radio button based on the user's
          // input.
          $old_correct_answer = $question['field_paddle_quiz_correct_answer'][LANGUAGE_NONE][0]['value']['#default_value'];
          if (isset($form_state['input'][$radio_name])) {
            $radio_default = $form_state['input'][$radio_name];
          }
          // If the old correct answer is not an empty string use that one
          // as default if there was no new input.
          elseif (!empty($old_correct_answer)) {
            $radio_default = $old_correct_answer;
          }
          // Otherwise the first answer should be selected by default.
          else {
            $radio_default = 0;
          }

          // Radio button.
          $answer[$radio_name] = array(
            '#type' => 'radio',
            '#weight' => -5,
            '#return_value' => $answer_key,
            '#default_value' => $radio_default,
            '#name' => $radio_name,
          );

          // Make sure the radio button is disabled when its corresponding
          // answer field is empty.
          $answer_selector = ':input[name="field_paddle_quiz_questions[und][' . $question_key . '][field_paddle_quiz_answers][und][' . $answer_key . '][value]"]';
          $answer[$radio_name]['#states'] = array(
            'disabled' => array(
              $answer_selector => array('value' => ''),
            ),
          );
        }
      }
    }
  }

  // Translatable defaults for the required text fields.
  $defaults = array(
    'field_paddle_quiz_start_button' => t('Start the quiz!'),
    'field_paddle_quiz_info_title' => t('We need some info.'),
    'field_paddle_quiz_info_message' => t('We need your info to send you a email with the results of the quiz. We will not spam you and use your email only for this purpose.'),
    'field_paddle_quiz_result_title' => t('Your score.'),
    'field_paddle_quiz_btn_next_l' => t('Next'),
    'field_paddle_quiz_btn_previous_l' => t('Previous'),
    'field_paddle_quiz_info_button_l' => t('Start now'),
  );
  foreach ($defaults as $field => $default) {
    $element = &$form[$field][LANGUAGE_NONE][0]['value'];
    $default = empty($element['#default_value']) ? $default : $element['#default_value'];
    $element['#default_value'] = $default;
  }

  // Hide the tiebreaker fields if there's no tiebreaker.
  $tiebreaker_selector = ':input[name="field_paddle_quiz_tiebreaker[und]"]';
  $states = array(
    'visible' => array(
      $tiebreaker_selector => array('checked' => TRUE),
    ),
    'required' => array(
      $tiebreaker_selector => array('checked' => TRUE),
    ),
  );
  $form['field_paddle_quiz_tiebreaker_q'][LANGUAGE_NONE][0]['#states'] = $states;
  $form['field_paddle_quiz_tiebreaker_l'][LANGUAGE_NONE][0]['value']['#states'] = $states;

  // Steps info.
  $current_step = paddle_quiz_form_current_step($form_state);
  $steps = array(
    'qa' => t('Questions'),
    'customize' => t('Customize'),
    'design' => t('Design'),
    'preview' => t('Preview'),
  );
  if (!in_array($current_step, array_keys($steps))) {
    $current_step = 'qa';
  }

  // Hide fields and/or field groups based on what step we're on.
  $step_elements = array(
    'qa' => array(
      'fields' => array(
        'title',
        'field_paddle_quiz_questions',
      ),
    ),
    'customize' => array(
      'groups' => array(
        'group_paddle_quiz_start',
        'group_paddle_quiz_prize',
        'group_paddle_quiz_info',
        'group_paddle_quiz_result',
        'group_paddle_quiz_buttons',
      ),
    ),
    'design' => array(
      'groups' => array(
        'group_paddle_quiz_start_design',
        'group_paddle_quiz_global_design',
      ),
    ),
    'preview' => array(),
  );

  // Exclude the fields or groups that belong to the current step from being
  // hidden.
  unset($step_elements[$current_step]);

  // Hide all other fields or groups.
  foreach ($step_elements as $step => $elements) {
    $fields = !empty($elements['fields']) ? $elements['fields'] : array();
    foreach ($fields as $field) {
      $form[$field]['#access'] = FALSE;
    }
    if (!empty($elements['groups'])) {
      field_group_hide_field_groups($form, $elements['groups']);
    }
  }

  // The preview step has no fields or field groups, but is a form by itself.
  if ($current_step == 'preview') {
    // Get the quiz participation form.
    module_load_include('inc', 'paddle_quiz', 'paddle_quiz.participation');

    // The quiz in the form state is most likely not 100% up-to-date, especially
    // when adding a new quiz. So reload the quiz from the database, and make
    // sure the question entities are also loaded before passing it on to the
    // participation form.
    $quiz = paddle_quiz_load($quiz->qid, TRUE);
    $quiz->loadQuestions();

    $preview_form_state = array(
      'paddle_quiz' => $quiz,
      'preview' => TRUE,
    );
    $preview_form = drupal_build_form('paddle_quiz_participation_form', $preview_form_state);

    // Add some divs so it appears as if the form is in a pane. This is just
    // for styling purposes.
    $preview_form['#prefix'] = '<div class="pane-paddle-quiz pane-paddle-quiz-preview"><div class="pane-content">' . $preview_form['#prefix'];
    $preview_form['#suffix'] .= '</div></div>';

    // As we can't put a form inside another form, we add the preview form as a
    // suffix to the current form so it appears underneath it.
    $form['#suffix'] = drupal_render($preview_form);
  }

  // Add a row of buttons to the top of the form, with all steps in them.
  $form['steps'] = array(
    '#type' => 'container',
    '#tree' => TRUE,
    '#weight' => -100,
  );
  $count = 0;
  foreach ($steps as $step => $label) {
    $count++;

    $step_classes = array();
    if ($step == $current_step) {
      $step_classes[] = 'active';
    }
    $step_classes[] = ($count % 2 == 0) ? 'even' : 'odd';
    $step_button_classes = $step_classes;
    $step_button_classes[] = 'step-button';

    $form['steps'][$step] = array(
      '#type' => 'submit',
      '#value' => t('Step @no: @label', array(
        '@no' => $count,
        '@label' => $label,
      )),
      '#next_step' => $step,
      '#attributes' => array('class' => $step_button_classes),
    );

    // We need to add an extra element for the triangle, as we can't use pseudo
    // elements on input tags.
    $step_arrow_classes = $step_classes;
    $step_arrow_classes[] = 'step-arrow';
    $step_arrow_attributes = drupal_attributes(array(
      'class' => implode(" ", $step_arrow_classes),
    ));
    $form['steps'][$step]['#suffix'] = '<span' . $step_arrow_attributes . '></span>';
  }

  // Determine the next step.
  $step_keys = array_keys($steps);
  $step_index = array_search($current_step, $step_keys);
  $next_index = $step_index + 1;
  $next_step = !empty($step_keys[$next_index]) ? $step_keys[$next_index] : FALSE;

  // Container for all submit buttons.
  $form['actions'] = array(
    '#type' => 'container',
  );
  // Determine the weight of the actions container based on the weights of the
  // field groups.
  $actions_weight = 0;
  foreach ($form['#groups'] as $group) {
    if ($group->weight >= $actions_weight) {
      $actions_weight = $group->weight + 2;
    }
  }
  $form['actions']['#weight'] = $actions_weight;

  // Add a button to the next step, if there is one.
  if (!empty($next_step)) {
    $form['actions'][$next_step] = array(
      '#type' => 'submit',
      '#value' => $steps[$next_step] . ' ►',
      '#attributes' => array('class' => array('next-step')),
      '#next_step' => $next_step,
    );
  }

  // Hidden save button.
  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save'),
    '#next_step' => FALSE,
    '#redirect' => TRUE,
    '#attributes' => array(
      'id' => 'quiz-save',
      'class' => array('hidden'),
    ),
  );

  // Attach extra CSS to the form.
  $form['#attached']['css'][] = drupal_get_path('module', 'paddle_quiz') . '/css/paddle_quiz.form.css';

  // Add a class to the form element so we don't need to rely on the form id
  // for styling (as it may change after adding a new field collection item).
  $form['#attributes']['class'][] = 'paddle-quiz-form';

  // Remove some unnecessary elements after build.
  $form['#after_build'][] = 'paddle_quiz_cleanup_quiz_form';

  // Move buttons to contextual toolbar if available.
  if (module_exists('paddle_contextual_toolbar')) {
    $form['#after_build'][] = 'paddle_quiz_contextual_actions_quiz_form';
  }

  return $form;
}

/**
 * Implements ENTITY_TYPE_form_validate().
 */
function paddle_quiz_form_validate($form, &$form_state) {
  field_attach_form_validate('paddle_quiz', $form_state['paddle_quiz'], $form, $form_state);

  // Check that a correct answer is indicated for each question.
  if (paddle_quiz_form_current_step($form_state) == 'qa') {
    $questions = &$form_state['values']['field_paddle_quiz_questions'][LANGUAGE_NONE];
    foreach ($questions as $key => $question) {
      if ($key !== 'add_more') {
        $correct_answer = paddle_quiz_form_determine_new_correct_answer($key, $form_state);
        if ($correct_answer < 0) {
          $error = t('Please indicate a correct answer for question @no.', array(
              '@no' => $key + 1,
          ));
          form_set_error('field_paddle_quiz_questions][' . LANGUAGE_NONE . '][' . $key, $error);
        }
      }
    }
  }
}

/**
 * Implements hook_field_collection_is_empty_alter().
 */
function paddle_quiz_field_collection_is_empty_alter(&$is_empty, $item) {
  // Always indicate field_paddle_quiz_questions field collections as not empty,
  // otherwise they won't be validated and "empty" questions will be saved as
  // well.
  if ($item->field_name === 'field_paddle_quiz_questions') {
    $is_empty = FALSE;
  }
}

/**
 * Implements ENTITY_TYPE_form_submit().
 *
 * Submit handler for the quiz add/edit form.
 */
function paddle_quiz_form_submit($form, &$form_state) {
  $trigger = $form_state['triggering_element'];

  // Get the quiz entity.
  /* @var QuizEntity $quiz */
  $quiz = $form_state['paddle_quiz'];

  if (paddle_quiz_form_current_step($form_state) == 'qa') {
    // Set the title property.
    $quiz->title = $form_state['values']['title'];

    // Store the indexes of the correct answers in the correct field.
    $questions = &$form_state['values']['field_paddle_quiz_questions'][LANGUAGE_NONE];
    foreach ($questions as $key => &$question) {
      // Skip add more button in the list of questions.
      if ($key === 'add_more') {
        continue;
      }

      $correct_answer = paddle_quiz_form_determine_new_correct_answer($key, $form_state);
      if ($correct_answer !== FALSE) {
        $question['field_paddle_quiz_correct_answer'][LANGUAGE_NONE][0]['value'] = $correct_answer;
        $question['entity']->field_paddle_quiz_correct_answer[LANGUAGE_NONE][0]['value'] = $correct_answer;
      }
    }
  }

  // Attach the new field values to the entity object.
  field_attach_submit('paddle_quiz', $quiz, $form, $form_state);

  // Save the quiz.
  $quiz->save();

  if (!empty($trigger['#redirect'])) {
    // Redirect and set a message to notify the user that the changes have been
    // saved.
    drupal_set_message(t('The quiz "@title" has been saved.', array(
        '@title' => $quiz->title,
    )));
    $form_state['redirect'] = PADDLE_QUIZ_OVERVIEW_URL;
  }
  elseif (!empty($trigger['#next_step'])) {
    $next_step = $trigger['#next_step'];
    $form_state['step'] = $next_step;

    // If we're editing, do a redirect to the complete url for the specific
    // next step.
    if ($form_state['op'] == 'edit') {
      $path = 'admin/content/paddle_quiz/' . $quiz->qid . '/edit/' . $next_step;
      $form_state['redirect'] = $path;
    }
    // When adding a new quiz simply rebuild the form on the same page.
    else {
      $form_state['rebuild'] = TRUE;
    }
  }
}

/**
 * Determines the current step of the form.
 *
 * @param array $form_state
 *   Form state array.
 *
 * @return string
 *   Step key.
 */
function paddle_quiz_form_current_step($form_state) {
  return !empty($form_state['step']) ? $form_state['step'] : 'qa';
}

/**
 * Determines the index of the correct answer for a specific question.
 *
 * @param int $question_key
 *   Question key (index).
 * @param array $form_state
 *   Form state array.
 *
 * @return int
 *   Index of the correct answer for the question. Returns -1 if no correct
 *   answer was indicated.
 */
function paddle_quiz_form_determine_new_correct_answer($question_key, $form_state) {
  $question = $form_state['values']['field_paddle_quiz_questions'][LANGUAGE_NONE][$question_key];

  if (!isset($question['entity'])) {
    return -1;
  }

  $uuid = $question['entity']->uuid;

  // Determine the radio button name for the question.
  $radio_button = 'correct_qa_' . $uuid;
  if (isset($form_state['input'][$radio_button])) {
    // Get the radio button's value.
    $correct_answer = $form_state['input'][$radio_button];

    // Check all answers before the correct answer. If any are empty we need
    // to lower the index for the correct answer as those (empty) answers will
    // be removed and the correct answer will move up.
    $question = $form_state['values']['field_paddle_quiz_questions'][LANGUAGE_NONE][$question_key];
    $answers = $question['field_paddle_quiz_answers'][LANGUAGE_NONE];
    for ($i = 0; $i < $correct_answer; $i++) {
      $answer = $answers[$i];
      if (empty($answer['value'])) {
        $correct_answer--;
      }
    }

    if ($correct_answer < 0) {
      $correct_answer = 0;
    }
    return $correct_answer;
  }
  else {
    return -1;
  }
}

/**
 * After-build function of the quiz add/edit form.
 *
 * @param array $form
 *   Form render array.
 * @param array $form_state
 *   Form state array.
 *
 * @return array
 *   Updated form render array.
 */
function paddle_quiz_contextual_actions_quiz_form($form, &$form_state) {
  // Hide the save button.
  $form['submit']['#attributes']['class'][] = 'hidden';

  // Reset the contextual toolbar actions for the page.
  $actions = array();

  // Add a cancel button.
  $actions[] = array(
    'action' => l(t('Cancel'), PADDLE_QUIZ_OVERVIEW_URL, array(
      'attributes' => array(
        'class' => array('ui-icon', 'fa-arrow-left'),
      ),
    )),
    'class' => array('cancel'),
    'weight' => 0,
  );

  // Add a save button.
  $actions[] = array(
    'action' => l(t('Save'), '', array(
      'attributes' => array(
        'data-paddle-contextual-toolbar-click' => 'quiz-save',
        'class' => array('ui-icon', 'fa-check'),
      ),
    )),
    'class' => array('save'),
    'weight' => 5,
  );

  // Set the new contextual toolbar actions.
  paddle_contextual_toolbar_actions($actions);

  return $form;
}

/**
 * Removes unnecessary elements from the quiz form after building.
 *
 * @param array $form
 *   Form render array.
 * @param array $form_state
 *   Form state array.
 *
 * @return array
 *   Updated form render array.
 */
function paddle_quiz_cleanup_quiz_form($form, &$form_state) {
  // Remove formatting info underneath CKEditor fields. Only Full HTML is
  // enabled on these anyway, so there's no need to confuse the user with
  // guidelines about the different formats.
  $ckeditor_fields = array(
    'field_paddle_quiz_disclaimer',
    'field_paddle_quiz_start_message',
    'field_paddle_quiz_info_message',
    'field_paddle_quiz_result_message',
    'field_paddle_quiz_tiebreaker_q',
  );
  foreach ($ckeditor_fields as $field) {
    // Don't unset() as it breaks the CKEditor for some reason.
    $form[$field][LANGUAGE_NONE][0]['format']['#access'] = FALSE;
  }

  // Return the form array.
  return $form;
}

/**
 * Quiz entity settings page.
 *
 * Only shows a message for now, but we need it to make the links to manage the
 * fields and displays visible.
 *
 * @return string
 *   HTML for the quiz entity settings page.
 */
function paddle_quiz_entity_settings_page() {
  $build['description'] = array(
    '#type' => 'item',
    '#markup' => t('Manage paddle_quiz entity fields and displays using the links above.'),
  );
  $build['manage'] = array(
    '#type' => 'item',
    '#markup' => l(t('Manage the actual quizzes here.'), PADDLE_QUIZ_OVERVIEW_URL),
  );
  return drupal_render($build);
}

/**
 * Quiz delete page.
 *
 * @param \QuizEntity $quiz
 *   The quiz to delete.
 * @param bool $modal
 *   Whether the page is loaded in a modal or not.
 *
 * @return string
 *   HTML for the confirmation form.
 */
function paddle_quiz_delete_page(\QuizEntity $quiz, $modal) {
  $title = t('Delete quiz %title', array('%title' => $quiz->title));

  if ($modal) {
    // Include the necessary ctools files.
    ctools_include('ajax');
    ctools_include('modal');

    // Indicate that we're using ajax in our form.
    $form_state = array(
      'ajax' => TRUE,
      'title' => $title,
      'build_info' => array(
        'args' => array($quiz),
      ),
    );

    // Load the ajax commands to display the form.
    $commands = ctools_modal_form_wrapper('paddle_quiz_delete_form', $form_state);

    // If the form is completed return some commands to refresh the page.
    if (!empty($form_state['completed'])) {
      // Dismiss the modal.
      $commands[] = ctools_modal_command_dismiss();

      // Remove any old messages and display new ones.
      $commands[] = ajax_command_remove('div.messages');
      $commands[] = ajax_command_after('#main-content', theme('status_messages'));

      // Refresh the configuration form with the overview table.
      $configuration_form = drupal_get_form('paddle_quiz_configuration_form');

      // Set the action to the correct path, as the current path is
      // "admin/content/paddle_quiz/%paddle_quiz/delete/%ctools_js".
      $configuration_form['#action'] = base_path() . PADDLE_QUIZ_OVERVIEW_URL;

      // Replace the old configuration form with the new one.
      $configuration_form = drupal_render($configuration_form);
      $commands[] = ajax_command_replace('#paddle-quiz-configuration-form-wrapper', $configuration_form);
    }

    // Return the ajax commands.
    print ajax_render($commands);
    ajax_footer();
    drupal_exit();
  }
  else {
    return drupal_get_form('paddle_quiz_delete_form', $quiz);
  }
}

/**
 * Quiz delete form.
 *
 * @param array $form
 *   Form array.
 * @param array $form_state
 *   Form state array.
 * @param \QuizEntity $quiz
 *   Quiz entity to delete.
 *
 * @return array
 *   Form array.
 */
function paddle_quiz_delete_form($form, &$form_state, \QuizEntity $quiz) {
  $form_state['paddle_quiz'] = $quiz;

  $form['body'] = array(
    '#type' => 'container',
    '#attributes' => array(
      'class' => array('form-body'),
    ),
  );
  $form['body']['confirmation'] = array(
    '#type' => 'item',
    '#markup' => t('Are you sure you want to delete the quiz %title? This will also delete all quiz results, and this action cannot be undone!', array(
      '%title' => $quiz->title,
    )),
  );

  $form['buttons'] = array(
    '#type' => 'container',
    '#attributes' => array('class' => array('form-buttons')),
  );
  $form['buttons']['confirm'] = array(
    '#type' => 'submit',
    '#value' => t('Delete'),
    '#delete' => TRUE,
  );
  // Can't use "cancel" as a key here, as that would cause the button to be
  // hidden by CSS.
  $form['buttons']['cancelling'] = array(
    '#type' => 'submit',
    '#value' => t('Cancel'),
  );

  return $form;
}

/**
 * Submit function for the quiz delete form.
 *
 * @param array $form
 *   Form array.
 * @param array $form_state
 *   Form state array.
 */
function paddle_quiz_delete_form_submit($form, &$form_state) {
  if (!empty($form_state['clicked_button']['#delete'])) {
    /* @var \QuizEntity $quiz */
    $quiz = $form_state['paddle_quiz'];
    $quiz->delete();
    drupal_set_message(t('The quiz %quiz has been deleted.', array(
      '%quiz' => $quiz->title,
    )));
  }
  if (empty($form_state['ajax'])) {
    $form_state['redirect'] = PADDLE_QUIZ_OVERVIEW_URL;
  }
  else {
    $form_state['completed'] = TRUE;
  }
}

/**
 * Quiz export page.
 *
 * Queues a quiz to be exported on cron.
 *
 * @param \QuizEntity $quiz
 *   Entity object of the quiz of which to export the results.
 * @param bool $modal
 *   Whether or not the page was loaded in a modal.
 *
 * @return mixed
 *   Ajax commands or HTML for an actual page.
 */
function paddle_quiz_export_page(\QuizEntity $quiz, $modal) {
  $title = t('Export quiz results of %title', array('%title' => $quiz->title));

  if ($modal) {
    // Include the necessary ctools files.
    ctools_include('ajax');
    ctools_include('modal');

    // Indicate that we're using ajax in our form.
    $form_state = array(
      'ajax' => TRUE,
      'title' => $title,
      'build_info' => array(
        'args' => array($quiz),
      ),
    );

    // Load the ajax commands to display the form.
    $commands = ctools_modal_form_wrapper('paddle_quiz_export_form', $form_state);

    // If the form is completed return some commands to refresh the page.
    if (!empty($form_state['completed'])) {
      // Dismiss the modal.
      $commands[] = ctools_modal_command_dismiss();

      // Remove any old messages and display new ones.
      $commands[] = ajax_command_remove('div.messages');
      $commands[] = ajax_command_after('#main-content', theme('status_messages'));
    }

    // Return the ajax commands.
    print ajax_render($commands);
    ajax_footer();
    drupal_exit();
  }
  else {
    return drupal_get_form('paddle_quiz_export_form', $quiz);
  }
}

/**
 * Quiz export form.
 *
 * @param array $form
 *   Form array.
 * @param array $form_state
 *   Form state array.
 * @param \QuizEntity $quiz
 *   Quiz entity to export the results of.
 *
 * @return array
 *   Form array.
 */
function paddle_quiz_export_form($form, &$form_state, \QuizEntity $quiz) {
  global $user;

  $form_state['paddle_quiz'] = $quiz;

  $form['body'] = array(
    '#type' => 'container',
    '#attributes' => array(
      'class' => array('form-body'),
    ),
  );
  $form['buttons'] = array(
    '#type' => 'container',
    '#attributes' => array('class' => array('form-buttons')),
  );

  module_load_include('inc', 'paddle_quiz', 'paddle_quiz.export');
  if (paddle_quiz_export_library_available()) {
    $form['body']['confirmation'] = array(
      '#type' => 'item',
      '#markup' => t('Your export will be prepared in the background and you will be notified by e-mail (%email) when it is ready to download.', array(
        '%email' => $user->mail,
      )),
    );

    $form['buttons']['confirm'] = array(
      '#type' => 'submit',
      '#value' => t('Export'),
      '#export' => TRUE,
    );

    // Can't use "cancel" as a key here, as that would cause the button to be
    // hidden by CSS.
    $form['buttons']['cancelling'] = array(
      '#type' => 'submit',
      '#value' => t('Cancel'),
    );
  }
  else {
    $form['body']['error'] = array(
      '#type' => 'item',
      '#markup' => t('The results for this quiz can not be exported because the required PHPExcel library is missing on this system. Please contact your site administrator to install this library first.'),
    );

    $form['buttons']['confirm'] = array(
      '#type' => 'submit',
      '#value' => t('OK'),
    );
  }

  return $form;
}

/**
 * Submit callback for the quiz export form.
 *
 * @param array $form
 *   Form array.
 * @param array $form_state
 *   Form state array.
 */
function paddle_quiz_export_form_submit($form, &$form_state) {
  if (!empty($form_state['clicked_button']['#export'])) {
    /* @var \QuizEntity $quiz */
    $quiz = $form_state['paddle_quiz'];

    module_load_include('inc', 'paddle_quiz', 'paddle_quiz.export');
    paddle_quiz_export_request($quiz);

    drupal_set_message(t('The export for quiz %quiz is being prepared and you will be notified soon.', array(
      '%quiz' => $quiz->title,
    )));
  }
  if (empty($form_state['ajax'])) {
    $form_state['redirect'] = PADDLE_QUIZ_OVERVIEW_URL;
  }
  else {
    $form_state['completed'] = TRUE;
  }
}
