#!/bin/bash

# This script gets executed before starting Selenium tests. It does things the
# tests rely on and things that are helpful for debugging purposes.

# Error reporting level: display errors and warnings.
drush vset error_level 1

# Allow unlimited free paddlets.
drush vset paddle_store_subscription_type standaard

# Deprecated but still needed as long as there's code using this. Allows
# communication between Selenium test and a Paddle website through a web
# service.
drush en -y paddle_webdriver

# Disable Paddle maintenance mode.
drush vset paddle_maintenance_mode 0
