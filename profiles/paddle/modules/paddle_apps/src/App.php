<?php
/**
 * @file
 * Definition of Drupal\paddle_apps\App.
 */

namespace Drupal\paddle_apps;

/**
 * Class representing an app.
 *
 * An app has a level of either 'free' or 'extra'. Subscriptions can maintain
 * different policies based on the level of an app.
 */
class App {

  /**
   * Marks an app as "free".
   */
  const LEVEL_FREE = 'free';

  /**
   * Marks an app as "extra", which is either "payable" or "custom".
   */
  const LEVEL_EXTRA = 'extra';

  /**
   * Marks an app as enabled.
   *
   * An app is enabled when its module is enabled.
   */
  const APP_ENABLED = 1;

  /**
   * Status for disabled apps.
   *
   * An app is disabled when its module is disabled but the module schema is
   * still in the database.
   */
  const APP_DISABLED = 0;

  /**
   * Status for incompatible apps.
   */
  const APP_INCOMPATIBLE = -1;

  /**
   * Status for installable apps.
   *
   * An app is installable when all the dependencies, if present, are met.
   */
  const APP_INSTALLABLE = 2;

  /**
   * @var array
   *   A Drupal module info array.
   */
  protected $appInfoArray;

  /**
   * @var integer
   *   A numeric, unique hash derived from the machine name.
   *
   *   Required for search_api which expects numeric IDs.
   */
  public $id;

  /**
   * @var string
   *   The machine name (module name) of the app.
   *
   *   Defined as public to allow the entity API to access it freely.
   */
  public $machineName;

  /**
   * @var string
   *   The human readable name of the app.
   *
   *   Defined as public to allow the entity API to access it freely.
   */
  public $name;

  /**
   * @var integer
   *   The module status: 0 for disabled, 1 for enabled.
   */
  public $status;

  /**
   * @var string
   *   The app's level.
   */
  public $level;

  /**
   * @var boolean
   *   Whether the app relies on a third-party service or not.
   */
  public $thirdPartyService;

  /**
   * @var string
   *   The description of the app.
   */
  public $description;

  /**
   * @var array
   *   Logo file array
   */
  public $logo;

  /**
   * @var string
   *   The name of the vendor.
   */
  public $vendor;

  /**
   * @var string
   *   The link to the vendor web page.
   */
  public $vendorLink;

  /**
   * @var string
   *   The detailed description of the app.
   */
  public $detailedDescription;

  /**
   * @var array
   *   Array of faqs related to the app.
   */
  public $faq = array();

  /**
   * @var array
   *   Array of screenshot images.
   */
  public $screenshots = array();

  /**
   * @var bool
   *   Whether the app is restorable or not.
   */
  public $restorable;

  /**
   * @var string
   *   Function name that returns the configuration form.
   */
  public $configureForm;

  /**
   * Constructs a new app.
   *
   * @param array $app_info_array
   *   A Drupal module info array.
   *
   * @throws InvalidAppLevelException
   *   if the app level specified in the module info array is not valid.
   */
  public function __construct($app_info_array = array()) {
    $this->appInfoArray = $app_info_array;

    // Determine the app's level.
    if (!isset($this->appInfoArray['paddle']['level'])) {
      // If no level was specified, default to free.
      $this->level = self::LEVEL_FREE;
    }
    else {
      // Check that the specified level is allowed.
      $allowed_levels = array(self::LEVEL_FREE, self::LEVEL_EXTRA);
      $level = $this->appInfoArray['paddle']['level'];
      if (!in_array($level, $allowed_levels)) {
        // Throw an exception of an invalid level is detected.
        $app_name = $this->appInfoArray['name'];
        throw new InvalidAppLevelException("The specified level ${level} of app ${app_name} is not valid.");
      }
      else {
        // Otherwise set the level as specified.
        $this->level = $level;
      }
    }

    $this->name = isset($app_info_array['name']) ? $app_info_array['name'] : '';
    $this->machineName = isset($app_info_array['machine_name']) ? $app_info_array['machine_name'] : '';
    $this->id = self::idFromMachineName($this->machineName);
    $this->status = isset($app_info_array['status']) ? $app_info_array['status'] : 0;
    $this->description = isset($app_info_array['description']) ? $app_info_array['description'] : '';
    $this->detailedDescription = isset($app_info_array['detailedDescription']) ? $app_info_array['detailedDescription'] : '';
    $this->thirdPartyService = (bool) (isset($app_info_array['paddle']['third_party_service']) ? $app_info_array['paddle']['third_party_service'] : FALSE);
    $this->vendor = isset($app_info_array['paddle']['vendor']) ? $app_info_array['paddle']['vendor'] : 'Kanooh';
    $this->vendorLink = isset($app_info_array['paddle']['vendor_link']) ? $app_info_array['paddle']['vendor_link'] : 'http://www.kanooh.be/';
    $this->logo = isset($app_info_array['logo']) ? $app_info_array['logo'] : array();
    $this->restorable = isset($app_info_array['restorable']) ? $app_info_array['restorable'] : FALSE;
    foreach (array('faq', 'screenshots') as $property) {
      if (!empty($app_info_array[$property]) && is_array($app_info_array[$property])) {
        $this->{$property} = $app_info_array[$property];
      }
    }
    $this->configureForm = isset($app_info_array['configure form']) ? $app_info_array['configure form'] : '';
  }

  /**
   * Generates a "unique" integer ID for an app based on its machine name.
   *
   * @param string $machine_name
   *   The app's machine name.
   *
   * @return int
   *   The app's ID.
   */
  public static function idFromMachineName($machine_name) {
    return (int) substr(hexdec(substr(sha1($machine_name), 0, 15)), 0, 9);
  }

  /**
   * Get the level of the app.
   *
   * @return string
   *   The level.
   */
  public function getLevel() {
    return $this->level;
  }
}
