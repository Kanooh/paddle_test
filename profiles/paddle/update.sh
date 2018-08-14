#!/bin/bash

# Always execute this script after downloading a newer version of the Drupal
# Paddle distribution to make the new code have impact on your Paddle
# installation.
#
# The standard Drupal precautions apply. Such as taking a backup. Read more at
# Drupal its UPGRADE.txt file. This script does not create a backup for you.

# Enable maintenance mode.
drush variable-set maintenance_mode 1

# Update database.
drush updatedb --yes

# Clear all caches.
drush cache-clear all

# Revert all features.
drush features-revert-all --yes

# Import all Paddle specific translations.
drush paddle-import-translations paddle-translations/translations.csv

# Clear all caches.
drush cache-clear all

# Refresh the Paddle Store content.
drush search-api-disable paddle_apps
drush search-api-enable paddle_apps
drush search-api-index paddle_apps

# Disable maintenance mode.
drush variable-set maintenance_mode 0
