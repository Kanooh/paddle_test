#!/bin/bash

# This script gets executed before starting Simpletest tests. It does things the
# tests rely on and things that are helpful for debugging purposes.

# Enable the Drupal Simpletest modules.
drush --yes en simpletest

# Disable Paddle maintenance mode.
drush vset paddle_maintenance_mode 0
# This is duplicated in PaddleWebTestCase->setup() to affect the test websites
# that are built by Simpletest Paddle web test cases as well.
