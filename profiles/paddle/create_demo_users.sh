#!/bin/bash

# This script will create some demo users to test with:
# - demo: Chief Editor, Site manager
# - demo_editor: Editor
# - demo_chief_editor: Chief Editor
# - demo_read_only: Read Only
#
# Use the password "demo" for each of these users.
#
# Disclaimer: this is only intended to be used in controlled environments for
# demonstration purposes. Do not use this on websites that are publicly
# accessible.

# Execute the drush commands from within the site.
ROOT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd "${ROOT_DIR}"

# Create the users.
drush user-create demo --password="demo" --mail="demo@example.com"
drush user-create demo_editor --password="demo" --mail="demo_editor@example.com"
drush user-create demo_chief_editor --password="demo" --mail="demo_chief_editor@example.com"
drush user-create demo_read_only --password="demo" --mail="demo_read_only@example.com"

# Add the roles to the users.
drush user-add-role "Chief Editor" demo,demo_chief_editor
drush user-add-role "Editor" demo_editor
drush user-add-role "Site Manager" demo
drush user-add-role "Read Only" demo_read_only

exit 0
