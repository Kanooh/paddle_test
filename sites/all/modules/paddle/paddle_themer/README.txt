Paddle Themer
=============

This module allows non-technical site administrators to change the look and feel
of the website. The configuration is split up in a number of "Style sets", for
example "Branding" and "Footer". Each of these sets have forms which allow the
administrator to change the fonts, colors etc.

The administrator can save these customized themes separately, clone them, and
preview different customized themes in the front end, to easily compare the
changes that have been made.


Requirements
------------
This module depends on the following contributed modules:
- Chaos Tool Suite (CTools) [1]
- Paddle Style [2]
- UUID [3]


Target audience
---------------
This module is mainly intended to be used in a "Site building platform" for
non-technical end users (not unlike "Drupal Gardens" [4] :). This module differs
from similar modules such as Sweaver [5] and Skinr [6] in that it puts all
power into the hands of the themer who is developing base themes for the
platform. The themer can define which page elements can be overridden by the end
user and decide in detail which options can be applied to each element.


Usage
-----
The easiest way to use this module is by using it with one of the predefined
base themes of the Paddle distribution (for example "Paddle Classic Theme" [7]).

Any themer worth its salt will want to set this up for their own base theme.
This is not for the faint of heart as a significant amount of work is involved,
but in return you can define constraints for your theme and protect it from
damage caused by overly enthousiastic clients (in theory at least).

The first thing to do is to create a "theme settings" file. This file should be
placed in the root folder of the theme, and be named 'theme-settings.php'. The
file will contain a single hook implementation (hook_paddle_themer_style_set()).
This hook can also be implemented in a custom module if desired, for example to
hook Paddle Themer into a core theme or a third party theme.

In the file, put the following code, replacing MYTHEME with the machine name of
your theme:

<?php

/**
 * @file
 * Hook implementations for My Theme.
 */

/**
 * Implements hook_paddle_themer_style_set().
 */
function MYTHEME_paddle_themer_style_set() {
  $style_sets = array();

  return $style_sets;
}

The "Style sets" that are defined here are shown in the user interface as a set
of vertical tabs, each containing settings for different parts of the theme,
such as 'Global styling', 'Header', 'Footer', 'Forms', 'Branding' etc.
Each style set should contain a machine name, a human readable name and a number
of "selectors", which are actual CSS selectors to which the styling will be
applied.

  // Example: a style set containing "Global" styling.
  $style_sets = array(
    // Define the 'global' style set.
    'global' => array(
      // The human readable name.
      'title' => t('Global styles'),
      // A set of page elements that can be styled, keyed by CSS selector.
      'selectors' => array(
        'h1' => array(
          // Each selector has a title explaining its use to the end user.
          'title' => t('H1 heading'),
          // A set of available styling options can be added. See next example.
          'styles' => array(),
        ),
        'h2' => array(
          'title' => t('H2 heading'),
          'styles' => array(),
        ),
        'a:hover' => array(
          'title' => t('Link: mouse over effect'),
          'styles' => array(),
        ),
      ),
    ),

    // Define the 'contact_form' style set.
    'contact_form' => array(
      'title' => t('Contact form'),
      'selectors' => array(
        '#contact-form .required' => array(
          'title' => t('Required field'),
          'styles' => array(),
        ),
        '#contact-form input.form-submit' => array(
          'title' => t('Submit button'),
          'styles' => array(),
        ),
      ),
    ),
  );

The above example will create two vertical tabs in the interface: "Global
styles" and "Contact form", each containing a number of elements that can be
changed by the user. However, it is necessary to define HOW each element should
be styled. It would not be desirable to make all styling options (fonts, colors,
backgrounds, borders, ...) available for all the elements on the page. Each
element has different requirements. For example it would be nice to be able to
change the font family, font size and color for the headings, but form submit
buttons should not have their font changed - instead it should only be possible
to change its background color and border style.

This is done by defining the "styles". Styles are provided by plugins that are
responsible for theming a specific aspect of the page. There are some generic
plugins available in the Paddle Style module (eg. 'font', 'color', ...), and
it is possible to create your own custom plugins to add custom functionality.
A plugin will theme one or more properties, for example the 'font' plugin allows
to theme the 'font-family', 'font-size', etc. You can decide which properties
are available to the end user by listing them in an 'allowed_values' array. If
you want to allow all properties a plugin supports, you can pass an empty array.

  // Example: a style set containing "Global" styling.
  $style_sets = array(
    'global' => array(
      'title' => t('Global styles'),
      'selectors' => array(
        'h1' => array(
          'title' => t('H1 heading'),
          'styles' => array(
            // Enable the 'font' plugin to allow the end user to tweak the fonts
            // for H1 headings.
            array(
              'plugin' => 'font',
              // Limit the font settings that are presented to the end user to
              // a subset of the settings that the font plugin supports. We only
              // allow to change font families and font sizes. The emphasis
              // settings (bold, italic, ...) will not be shown in the UI.
              // The font families are further restricted to a limited set of
              // sans serif fonts that work well in headings.
              // For the font size we specify an empty array, this does not
              // impose any restrictions, and allows the user to chose all font
              // sizes.
              'allowed_values' => array(
                'font_family' => array('times new roman', 'times', 'serif'),
                'font_size' => array(),
              ),
            ),
            // Enable the 'color' plugin. In this case we do not specify any
            // 'allowed_values', which means that all options are allowed.
            array(
              'plugin' => 'color',
            ),
          ),
        ),
        'h2' => array(
          ...
        ),
      ),
    ),

For a complete example of a style set definition, see paddle_themer.api.php.

The next step is to create one or more presets. These are variations on the base
theme which can serve as a starting point. Examples: 'Red', 'Corporate',
'Glossy', ... You can export these presets using Features.

Go to Structure > Paddle Themer themes > Add (admin/structure/paddle_themer/add)
and complete the multi-step form to create a preset:

 1. Enter the title of your preset.
 2. Select your base theme from the drop down list.
 3. Customize the preset using the Paddle Themer interface. This will present
    all the styling options that were defined in the style sets above.
 4. Upload an screenshot of the theme. This will be used in the theme overview.

Now go to Themes (admin/themes) to see your presets in the theme overview. You
can enable different presets, as well as customize them. When a preset is
customized it is automatically cloned and saved as a new one. This will be handy
in case an end user ends up with a less aesthetically pleasing result. It will
always be possible to switch back to a safe, good looking preset.


Credits
-------
Paddle Themer and Paddle Style are developed as part of the 'Paddle'
distribution, an initiative of the Flemish Government.


References
----------
[1] https://drupal.org/project/ctools
[2] https://drupal.org/project/paddle_style
[3] https://drupal.org/project/uuid
[4] http://www.drupalgardens.com
[5] https://drupal.org/project/sweaver
[6] https://drupal.org/project/skinr
