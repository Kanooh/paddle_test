# Install an app

## Through the web interface

### Prerequisites

- Required modules have to be downloaded already
- Cron must be active 

### Steps

1. Log in as a user with enough [permissions](permissions) (Chief Editor, 
Site Manager, ...)
2. Go to the 'Paddle store'
3. Click the 'Install' button of the app you want to install.
4. Wait until cron has run, as explained by the confirmation dialog message:

> Installing *app name* takes a few minutes to complete. During the 
> installation, the site will be in maintenance mode. You can check the status 
> via Paddle store.

## With Drush
To enable an app with Drush, just type

    drush pm-enable app_module_name
