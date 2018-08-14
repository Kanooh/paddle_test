#!/bin/bash

# This script will create users that will be used by support:
# - kanooh_administrator: Site manager, Chief Editor
# - kanooh_editor: Editor
# - kanooh_chief_editor: Chief Editor
#
# These users have no password due to security reasons
# Support will have to use drush to create one time login link
# or receive the one time login by password reminder mail

# Execute the drush commands from within the site.
ROOT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd "${ROOT_DIR}"

# Create the users.
drush user-create kanooh_administrator --mail="kanooh_administrator@kanooh.be"
drush user-create kanooh_editor --mail="kanooh_editor@kanooh.be"
drush user-create kanooh_chief_editor --mail="kanooh_chief_editor@kanooh.be"

# Add the roles to the users.
drush user-add-role "Chief Editor" kanooh_chief_editor,kanooh_administrator
drush user-add-role "Editor" kanooh_editor,kanooh_chief_editor
drush user-add-role "Site Manager" kanooh_administrator

exit 0
