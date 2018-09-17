<?php

/**
 * @file
 * Defines PaddleMenuDisplay.
 */

/**
 * Class PaddleMenuDisplay.
 *
 * @todo Convert all properties to protected scope, provide getters and
 * setters, provide documentation and enforce variable types where possible.
 */
class PaddleMenuDisplay {

  /**
   * The menu display id.
   *
   * @var int
   */
  public $mdid;

  /**
   * The title of the menu display.
   *
   * @var string
   */
  public $title;

  /**
   * The machine name of the menu display.
   *
   * @var string
   */
  public $name;

  /**
   * The description of the menu display.
   *
   * @var string
   */
  public $description;

  /**
   * The machine name of the custom menu that is used for the menu display.
   *
   * @var string
   */
  public $menu_name;

  /**
   * The root path that is used for rendering the menu display.
   *
   * If this is an empty string the current active trail will be used.
   *
   * @var string
   */
  public $root_path;

  /**
   * The first level that should be displayed.
   *
   * This can either be:
   * - a numeric value: This will be used as is.
   * - 'curr_lvl': The first level will match the level of the currently active
   *   menu item, according to the active trail.
   * - 'curr_lvl_plus_1': The first level will be one level below the level of
   *   the currently active menu item, according to the active trail.
   *
   * @var string
   */
  public $from_level;

  /**
   * The last level that should be displayed.
   *
   * This can either be:
   * - a numeric value: This will be used as is.
   * - 'curr_lvl': The last level will match  the level of the currently active
   *   menu item, according to the active trail.
   * - 'curr_lvl_plus_1': The last level will be one level below the level of
   *   the currently active menu item, according to the active trail.
   *
   * @var string
   */
  public $to_level;

  /**
   * Whether to add class "first" and "last" to the <li> in the menu display.
   *
   * @var bool
   */
  public $add_active_class;
  public $add_first_last;
  /**
   * Whether to add class "odd" and "even" to the <li> in the menu display.
   *
   * @var bool
   */
  public $add_odd_even;
  /**
   * Whether to add class "active_trail" to the <li> in the menu items in the
   * active trail.
   *
   * @var bool
   */
  // @todo Document these properties.
  public $add_active_trail;
  public $tag_classes;
  public $cont_types_roots;
  public $no_links;
  public $path_roots;
  public $div_class;
  public $ul_class;
  public $li_class;
  public $a_class;

  /**
   * The current active trail.
   *
   * @var array
   */
  public $active_trail = array();

  /**
   * The root path to use on 404 pages.
   *
   * This maps to the '404_root_path' configuration property.
   *
   * @var string
   */
  public $page_not_found_root_path;

  /**
   * The root path to use on 403 (access denied) pages.
   *
   * @var string
   */
  public $access_denied_root_path;

  /**
   * The langcode of the language of the menu display.
   *
   * @var string
   */
  public $language;

  /**
   * Whether or not the menu display follows the active trail.
   *
   * @var bool
   */
  public $follow_active_trail = FALSE;

  /**
   * Constructs a new PaddleMenuDisplay object.
   *
   * @todo Include the logic in paddle_menu_display_setup_current_display().
   *
   * @param object $record
   *   A database record containing the menu display configuration.
   */
  public function __construct($record) {
    global $language;

    // Unserialize fields.
    foreach (array('cont_types_roots', 'no_links', 'path_roots') as $field) {
      $record->$field = unserialize($record->$field) ?: array();
    }

    // Unserialize tag classes.
    if ($tag_classes = unserialize($record->tag_classes) ?: array()) {
      foreach (array('div_class', 'ul_class', 'li_class', 'a_class') as $class) {
        $record->$class = isset($tag_classes[$class]) ? $tag_classes[$class] : '';
      }
    }

    // Populate properties with the settings from the database record.
    foreach (get_object_vars($record) as $property => $value) {
      $this->$property = $value;
    }

    // Set the language to the currently active language.
    $this->language = $language->language;
  }

  /**
   * Magic getter. Allows to retrieve the '404_root_path' property.
   *
   * @todo: This provides backwards compatibility. This can be removed when all
   *   instances of '404_root_path' are replaced with 'page_not_found_root_path'
   *   and an update hook is provided.
   *
   * @param string $name
   *   The name of the property to get. Only '404_root_path' is supported.
   *
   * @return string
   *   The requested root path.
   *
   * @throws Exception
   *   Thrown when any property other than '404_root_path' is requested.
   */
  public function __get($name) {
    if ($name == '404_root_path') {
      return $this->page_not_found_root_path;
    }
    throw new Exception(format_string('Requested unknown property @name.', array('@name' => $name)));
  }

  /**
   * Magic setter. Allows to set the '404_root_path' property.
   *
   * @todo: This provides backwards compatibility. This can be removed when all
   *   instances of '404_root_path' are replaced with 'page_not_found_root_path'
   *   and an update hook is provided.
   *
   * @param string $name
   *   The name of the property to set. Only '404_root_path' is supported.
   * @param string $value
   *   The value for the '404_root_path' property.
   *
   * @throws Exception
   *   Thrown when any property other than '404_root_path' is requested.
   */
  public function __set($name, $value) {
    if ($name == '404_root_path') {
      $this->page_not_found_root_path = $value;
    }
    else {
      throw new Exception(format_string('Tried to set unknown property @name.', array('@name' => $name)));
    }
  }

  /**
   * Returns the most shallow level that should be rendered.
   *
   * @return int
   *   The requested level.
   */
  public function fromLevel() {
    // If the from level is a fixed value, return it as is.
    if (intval($this->from_level)) {
      return $this->from_level;
    }

    // If the from level is dynamic, return it according to the active trail.
    return $this->getActiveTrailDepth($this->from_level);
  }

  /**
   * Returns the deepest level that should be rendered.
   *
   * @return int
   *   The requested level.
   */
  public function toLevel() {
    // If the to level is a fixed value, return it as is.
    if (intval($this->to_level)) {
      return $this->to_level;
    }

    // If the to level is dynamic, return it according to the active trail.
    return $this->getActiveTrailDepth($this->to_level);
  }

  /**
   * Returns the depth of the current active trail.
   *
   * @param string $dynamic_level
   *   (Optional) The dynamic level type that is used. Can be 'curr_lvl' or
   *   'curr_lvl_plus_1'.
   *
   * @return int
   *   The depth of the current active trail, or FALSE if there is no active
   *   trail.
   */
  public function getActiveTrailDepth($dynamic_level = NULL) {
    $depth = count($this->active_trail);

    if (!$depth) {
      return FALSE;
    }

    switch ($dynamic_level) {
      case 'curr_lvl_plus_1':
        return $depth + 1;

      default:
        return $depth;
    }
  }

  /**
   * Returns the type of dynamic 'from level' that is used.
   *
   * @return string
   *   The current dynamic 'from level' type ('curr_lvl' or 'curr_lvl_plus_1'),
   *   or FALSE if the 'from level' is fixed.
   */
  public function getDynamicFromLevel() {
    if (!intval($this->from_level)) {
      return $this->from_level;
    }
    return FALSE;
  }

  /**
   * Returns the type of dynamic 'to level' that is used.
   *
   * @return string
   *   The current dynamic 'to level' type ('curr_lvl' or 'curr_lvl_plus_1'),
   *   or FALSE if the 'to level' is fixed.
   */
  public function getDynamicToLevel() {
    if (!intval($this->to_level)) {
      return $this->to_level;
    }
    return FALSE;
  }

  /**
   * Retrieves the deepest visible mlid according to the active trail.
   *
   * This is intended to use as an identifier for caching the menu.
   *
   * If the item is on the deepest displayed level (the 'to level') then the
   * mlid of the parent item is returned rather than of the menu item itself. At
   * the deepest level the menu structure is identical for all siblings and it
   * would be wasteful to generate multiple identical caches.
   *
   * @return int
   *   The requested mlid, or 0 for the root level.
   */
  public function getCacheMlid() {
    // Use either the 'to level' depth or the number of items in the active
    // trail, whichever is lower. We subtract one from the 'to level' to avoid
    // needlessly caching the deepest level. Menu levels start counting at one,
    // and array keys at zero, so we have to subtract another one.
    $depth = min(count($this->active_trail), $this->toLevel() - 1) - 1;

    // Return zero if we have reached the root level.
    return $depth >= 0 ? $this->active_trail[$depth] : 0;
  }

  /**
   * Returns a SHA1 hash of the current menu display configuration.
   *
   * Since the menu displays can be dynamically altered and depend on a number
   * of request specific parameters (eg. the active language) this can be used
   * to uniquely identify a particular configuration for caching.
   *
   * @return string
   *   The SHA1 hash of the current menu display configuration.
   */
  public function getCacheHash() {
    // Create a hash based on only the properties that influence the menu tree
    // to be cached.
    $parameters = array(
      'cont_types_roots',
      'follow_active_trail',
      'language',
      'mdid',
      'menu_name',
      'name',
      'page_not_found_root_path',
      'path_roots',
    );
    $configuration = array_intersect_key((array) $this, array_flip($parameters));

    // Add our 'dynamic' properties.
    $configuration['from_level'] = $this->getDynamicFromLevel() ?: (int) $this->from_level;
    $configuration['to_level'] = $this->getDynamicToLevel() ?: (int) $this->to_level;

    // Eliminate different hashes due to type casting.
    $configuration['follow_active_trail'] = (int) $configuration['follow_active_trail'];

    return sha1(json_encode($configuration));
  }

  /**
   * Returns whether the menu display is following the active trail.
   *
   * @return bool
   *   TRUE if the active trail is being followed.
   */
  public function isFollowingActiveTrail() {
    // If the from and to levels are both set to 1 the active trail will not
    // have an effect on the rendering of the menu.
    if ($this->fromLevel() == 1 && $this->toLevel() == 1) {
      return FALSE;
    }

    // If not, check the dedicated variable.
    return (bool) $this->follow_active_trail;
  }

  /**
   * Returns whether the current menu item is in the active trail.
   *
   * @param int $mlid
   *   The mlid of the menu item we are checking for.
   *
   * @return bool
   *   TRUE if the current menu item is in the active trail.
   */
  public function isInActiveTrail($mlid = NULL) {
    if (!empty($mlid)) {
      return $this->isFollowingActiveTrail() && in_array($this->active_trail, array($mlid));
    }
    else {
      return $this->isFollowingActiveTrail() && $this->active_trail;
    }
  }

  /**
   * Returns the mlid of the currently active menu item.
   *
   * @return int
   *   The mlid of the currently active menu item, or FALSE if the active trail
   *   is not being followed.
   */
  public function getActiveMlid() {
    if (count($this->active_trail)) {
      return end($this->active_trail);
    }

    return FALSE;
  }

}
