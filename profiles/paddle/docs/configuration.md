# Paddle app configuration

## Implement hook_apps_app_info()
Apps get a 'Configure' button once they declare the function name that holds 
the configuration form. 

    /**
     * Implements hook_apps_app_info().
     */
    function module_name_apps_app_info() {
      return array(
        'configure form' => 'module_name_configuration_form',
      );
    }

## Define the configuration form
Define the form using Drupal Form API, as with any other module. Place it in a 
function named after the 'configure form' that is declared in 
`module_name_apps_app_info()`.

    /**
     * App configuration form callback.
     *
     * @return array
     *   Form render array.
     */
    function module_name_configuration_form() {
      $form = array();

      $form['some_field'] = array(
        '#type' => 'textfield',
        '#title' => t('Title'),
      );

      // Set the buttons in the contextual toolbar if available.
      if (module_exists('paddle_contextual_toolbar')) {
        $form['#after_build'][] = 'module_name_contextual_actions_configuration_form';
      }

      return $form;
    }

## Process form submission
If your form function is named `module_name_configuration_form()` you can 
create `module_name_configuration_form_submit()` to process form submission.

    /**
     * Submit function for the app configuration form.
     *
     * @param array $form
     *   Form array.
     * @param array $form_state
     *   Form state array.
     */
    function module_name_configuration_form_submit($form, &$form_state) {
    
    }

## Provide a toolbar button
In Paddle (administration interface), buttons are placed under the horizontal 
navigation menu. This is done with a hook implementation that:

- hides the actual form submit button at the bottom
- creates a link to it in the toolbar

See how to add this after-build function above in 
`module_name_configuration_form()`.

    /**
     * After-build function of app configuration form.
     *
     * @param array $form
     *   Form render array.
     * @param array $form_state
     *   Form state array.
     *
     * @return array
     *   Updated form render array.
     */
    function module_name_contextual_actions_configuration_form($form, &$form_state) {
      // Hide the save button.
      $form['save']['#attributes']['class'][] = 'hidden';
    
      $actions = paddle_contextual_toolbar_actions();
    
      // Add a save button to the contextual toolbar.
      $actions[] = array(
        'action' => l(t('Save'), '', array(
          'attributes' => array(
            'data-paddle-contextual-toolbar-click' => $form['actions']['submit']['#id'],
          ),
        )),
        'class' => array('save'),
      );
    
      paddle_contextual_toolbar_actions($actions);
    
      return $form;
    }
