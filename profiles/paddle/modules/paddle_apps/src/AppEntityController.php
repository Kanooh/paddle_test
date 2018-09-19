<?php
/**
 * @file
 * Contains the AppEntityController class.
 */

namespace Drupal\paddle_apps;

/**
 * AppEntityController knows where to get the App meta information.
 *
 * Extends EntityAPIControllerExportable instead of just extending
 * EntityAPIController to work around a bug causing subsequent
 * entity_load('paddle_app') calls to load no entities at all except the first
 * time.
 * @see https://www.drupal.org/node/1273756
 */
class AppEntityController extends \EntityAPIControllerExportable {

  /**
   * {@inheritdoc}
   */
  public function query($ids, $conditions, $revision_id = FALSE) {
    $entities = array();

    // Get App information from the source - .info files.
    $apps = $this->loadAppsInformation();

    foreach ($apps as $app_info) {
      $app = new App($app_info);
      $entities[$app->id] = $app;
    }

    return $entities;
  }

  /**
   * {@inheritdoc}
   */
  public function save($entity, \DatabaseTransaction $transaction = NULL) {
    // Our entity type doesn't have a base table where to write the entities
    // themselves. So we need to override the save() methods of
    // EntityAPIControllerExportable and EntityAPIController to get rid of
    // drupal_write_record calls.
    //
    // @see EntityAPIControllerExportable::save()
    // Preload $entity->original by name key if necessary.
    if (!empty($entity->{$this->nameKey}) && empty($entity->{$this->idKey}) && !isset($entity->original)) {
      $entity->original = entity_load_unchanged($this->entityType, $entity->{$this->nameKey});
    }
    // Update the status for entities getting overridden.
    if (entity_has_status($this->entityType, $entity, ENTITY_IN_CODE) && empty($entity->is_rebuild)) {
      $entity->{$this->statusKey} |= ENTITY_CUSTOM;
    }

    // @see EntityAPIController::save()
    try {
      if (!empty($entity->{$this->idKey}) && !isset($entity->original)) {
        // In order to properly work in case of name changes, load the original
        // entity using the id key if it is available.
        $entity->original = entity_load_unchanged($this->entityType, $entity->{$this->idKey});
      }
      $entity->is_new = !empty($entity->is_new) || empty($entity->{$this->idKey});
      $this->invoke('presave', $entity);

      if ($entity->is_new) {
        $this->invoke('insert', $entity);
      }
      else {
        $this->resetCache(array($entity->{$this->idKey}));
        $this->invoke('update', $entity);
      }

      // Ignore slave server temporarily.
      db_ignore_slave();
      unset($entity->is_new);
      unset($entity->is_new_revision);
      unset($entity->original);

      return TRUE;
    }
    catch (\Exception $e) {
      $transaction->rollback();
      watchdog_exception($this->entityType, $e);
      throw $e;
    }
  }

  /**
   * Load all the apps present in the system.
   *
   * Code taken from apps module, apps_server_local() function.
   *
   * @return array
   *   An array of apps metadata.
   */
  protected function loadAppsInformation() {
    $apps = array();

    $modules = system_rebuild_module_data();
    foreach ($modules as $module => $info) {
      // Process modules with the apps metadata array.
      if (isset($info->info['apps'])) {
        // Process basic information taken from the info files.
        $app = $this->processAppInfo($module, $info);

        // Process app dependencies.
        $app = $this->processDependencies($app);

        // Set the correct status.
        if ($app['incompatible']) {
          $app['status'] = App::APP_INCOMPATIBLE;
        }
        elseif (!$app['installed'] || !$app['dep_installed']) {
          $app['status'] = App::APP_INSTALLABLE;
        }
        else {
          $app['status'] = $app['enabled'] ? App::APP_ENABLED : App::APP_DISABLED;
        }

        // Add extra app info through hooks.
        $app = $this->processExtraInfo($app);

        // Process app images (logo and screenshots), if any.
        $app = $this->processAppImages($app);

        $apps[$module] = $app;
      }
    }

    return $apps;
  }

  /**
   * Process the information fetched from .info files for an app.
   *
   * Contains code from the apps module, functions apps_server_local()
   * and apps_manifest().
   *
   * @param string $module
   *   The module name of the app.
   * @param object $info_data
   *   The raw information retrieved from the .info file.
   *
   * @return array
   *   An array with the apps information.
   */
  protected function processAppInfo($module, $info_data) {
    $info = $info_data->info['apps'];
    $app_path = drupal_get_path('module', $module);

    // Set some defaults, if needed.
    $info['machine_name'] = $module;

    // Name and description are translatable.
    $info['name'] = i18n_string('paddle_apps:name:' . $info_data->name, $info_data->info['name'], array('update' => TRUE));
    $info['description'] = i18n_string('paddle_apps:description:' . $info_data->name, $info_data->info['description'], array('update' => TRUE));

    if (!isset($info['version'])) {
      $info['version'] = $info_data->info['version'];
    }
    if (isset($info['screenshots'])) {
      foreach ($info['screenshots'] as $id => $image) {
        $info['screenshots'][$id] = $app_path . '/' . $image;
      }
    }
    if (isset($info['logo'])) {
      $info['logo'] = $app_path . '/' . $info['logo'];
    }

    // Get all the modules data. This is statically cached so it doesn't
    // impact performances (too much, at least).
    $modules = system_rebuild_module_data();

    $current_app_module = isset($modules[$module]) ? $modules[$module] : FALSE;
    $info['enabled'] = $current_app_module && $current_app_module->status;
    $info['incompatible'] = FALSE;
    $info['installed'] = (bool) $current_app_module;
    $info['dep_installed'] = TRUE;
    $info['disabled'] = $current_app_module && empty($current_app_module->status) && $current_app_module->schema_version > SCHEMA_UNINSTALLED;
    $info['featured'] = isset($info['featured app']) && ($module == $info['featured app']);
    $info['dependencies'] = isset($info['dependencies']) ? $info['dependencies'] : array();
    $info['libraries'] = isset($info['libraries']) ? $info['libraries'] : array();

    return $info;
  }

  /**
   * Process dependencies for an app.
   *
   * Checks both modules and libraries to see if an app is installable.
   * Contains code from apps module, function apps_manifest().
   *
   * @param array $app_info
   *   The app info retrieved until now.
   *
   * @return array
   *   Updated app info.
   */
  protected function processDependencies($app_info) {
    foreach ($app_info['dependencies'] as $name_version => $downloadable) {
      // Parse dep versions.
      $version = drupal_parse_dependency($name_version);
      $name = $version['name'];

      // Check status of modules.
      $current = isset($modules[$name]) ? $modules[$name] : FALSE;
      $incompatible = $current ? (bool) drupal_check_incompatibility($version, $current->info['version']) : FALSE;
      $installed = (bool) $current;
      $enabled = $current && $current->status;
      $status = $incompatible ? App::APP_INCOMPATIBLE : (!$installed ? App::APP_INSTALLABLE : ($enabled ? App::APP_ENABLED : App::APP_DISABLED));

      if ($status == App::APP_INCOMPATIBLE) {
        // If any one module is incompatible then the app is incompatible.
        $app_info['incompatible'] = TRUE;
      }
      if ($status == App::APP_INSTALLABLE) {
        // If any one module is installable then we are not installed yet.
        $app_info['dep_installed'] = FALSE;
      }
      // Rebuild dep with new data.
      $info = array(
        'downloadable' => $downloadable,
        'version' => $version,
        'status' => $status,
        'incompatible' => $incompatible,
        'enabled' => $enabled,
        'installed' => $installed,
      );
      unset($app_info['dependencies'][$name_version]);
      $app_info['dependencies'][$version['name']] = $info;
    }

    if (isset($app_info['libraries'])) {
      $profile = variable_get('install_profile', 'standard');
      $profile_path = drupal_get_path('profile', $profile);
      foreach ($app_info['libraries'] as $name_version => $downloadable) {
        $info = array(
          'downloadable' => $downloadable,
          'version' => array('name' => $name_version),
          'status' => App::APP_INSTALLABLE,
          'incompatible' => 0,
          'enabled' => 0,
          'installed' => is_dir(DRUPAL_ROOT . "/sites/all/libraries/$name_version") || is_dir($profile_path . "/libraries/$name_version"),
        );
        $app_info['libraries'][$name_version] = $info;
      }
    }

    return $app_info;
  }

  /**
   * Retrieve extra app info provided by hooks.
   *
   * Contains code from apps module, function apps_add_app_info().
   *
   * @param array $app_info
   *   The current app info.
   *
   * @return array
   *   The updated app info.
   */
  protected function processExtraInfo($app_info) {
    $module = $app_info['machine_name'];

    $extra_info = ($i = module_invoke($module, 'apps_app_info')) ? $i : array();
    $app_info += $extra_info;
    $app_info += array(
      'configure form' => $module . "_apps_app_configure_form",
    );

    return $app_info;
  }

  /**
   * Process images for an app.
   *
   * Contains code from apps module, function
   * apps_request_manifest_image_process().
   *
   * @param array $app_info
   *   The current app info.
   *
   * @return array
   *   The updated app info.
   */
  protected function processAppImages($app_info) {
    $module = $app_info['machine_name'];

    if (isset($app_info['logo'])) {
      $logo = $this->retrieveImage($module, $app_info['logo'], t("@name Logo", array('@name' => $app_info['name'])));
      $app_info['logo'] = !empty($logo) ? $logo : FALSE;
    }

    if (isset($app_info['screenshots'])) {
      foreach ($app_info['screenshots'] as $index => $url) {
        $name = isset($app_info['name']) ? $app_info['name'] : 'paddle_apps';
        $screenshot = $this->retrieveImage($module, $url, t("@name Screenshot @index", array('@name' => $name, '@index' => $index)));

        if (!empty($screenshot)) {
          $app_info['screenshots'][$index] = $screenshot;
        }
        else {
          unset($app_info['screenshots'][$index]);
        }
      }
    }

    return $app_info;
  }

  /**
   * Check and retrieve app images into the file_managed table.
   *
   * Contains code from apps module, function apps_retrieve_app_image().
   *
   * @param string $module
   *   The name of the app module.
   * @param string $url
   *   The url of the image.
   * @param string $title
   *   The title attribute for the image.
   * @param string $alt
   *   The alt attribute for the image.
   *
   * @return array|bool
   *   FALSE if failed to retrieve image, a file_managed object as array
   *   otherwise.
   */
  protected function retrieveImage($module, $url, $title = '', $alt = '') {
    global $user;

    // Unlike the original apps_retrieve_app_image(), we only allow local files.
    $parsed = parse_url($url);

    // If the host value is present, it's a remote file.
    if (!empty($parsed['host'])) {
      return FALSE;
    }

    // Check if the file exists locally.
    if (!file_exists($url)) {
      return FALSE;
    }

    $file_name = basename($url);
    $dir = "apps/$module";
    $original_uri = file_build_uri("$dir/$file_name");
    $current = FALSE;

    $fids = db_select('file_managed', 'f')
      ->condition('uri', $original_uri)
      ->fields('f', array('fid'))
      ->execute()
      ->fetchCol();

    if (!empty($fids) && isset($fids[0]) && is_numeric($fids[0])) {
      $current = file_load($fids[0]);
    }

    // Check to see if the file has been updated.
    if ($current && file_exists($current->uri)) {
      $use_current = filemtime($url) <= $current->timestamp && $current->filesize == filesize($url);

      // If the file has not been updated, use the old file_managed entry.
      if ($use_current) {
        $current->title = !empty($title) ? $title : '';
        $current->alt = !empty($alt) ? $alt : $title;
        return (array) $current;
      }
    }

    // Don't use the file if it's not an image..
    if (module_exists('image') && !image_get_info($url)) {
      return FALSE;
    }

    $data = file_get_contents($url);
    if (empty($data)) {
      // Some issues retrieving file.
      return FALSE;
    }

    // Prepare the directory to be writable.
    $dir_uri = file_build_uri($dir);
    if (!file_prepare_directory($dir_uri, FILE_CREATE_DIRECTORY)) {
      return FALSE;
    }

    // Save the file, replacing any file with the same name.
    // We are not using file_save_data() as you cannot save title and alt
    // fields at once. Since file_save() calls a number of hooks on file save,
    // we want to avoid calling them twice in a matter of milliseconds.
    $uri = file_unmanaged_save_data($data, $original_uri, FILE_EXISTS_REPLACE);

    // Quit on saving errors.
    if (!$uri) {
      return FALSE;
    }

    // Create a file object.
    $file = new \stdClass();
    $file->fid = NULL;
    $file->uri = $uri;
    $file->filename = drupal_basename($uri);
    $file->filemime = file_get_mimetype($file->uri);
    $file->uid      = $user->uid;
    $file->status   = FILE_STATUS_PERMANENT;

    // If we have an existing file re-use its database record.
    if ($current) {
      $file->fid = $current->fid;
    }

    // Add alt and title to the image.
    $file->title = !empty($title) ? $title : '';
    $file->alt = !empty($alt) ? $alt : $file->title;

    // Finally save the file.
    file_save($file);

    return (array) $file;
  }
}
