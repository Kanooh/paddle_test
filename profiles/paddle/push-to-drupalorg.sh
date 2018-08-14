#!/bin/bash

# Splits up the unified Bitbucket repository into the original projects and
# pushes the latest changes back to drupal.org.

# Checks if the last command succeeded. If the parameter 'warn' is not passed
# the script exits with an error. Otherwise it prints a warning and continues.
function checkresult {
  if [ $? -eq 0 ] ; then
    printf "\e[1;32m[OK]\e[0m\n"
    return 0
  else
    if [ "$1" = "warn" ] ; then
      printf "\e[1;33m[WARNING]\e[0m\n"
      return 1
    else
      printf "\e[1;31m[ERROR]\e[0m\n"
      exit 1
    fi
  fi
}

# Pushes the current branch back to the given repository.
# Usage: pushback remote url branch prefix
# - remote: The name of the remote repository.
# - url: The URL of the remote repository.
# - branch: The branch to push back to.
# - prefix: The path to the module, from the root of the unified repo.
function pushback {
  local REMOTE=$1
  local URL=$2
  local BRANCH=$3
  local PREFIX=$4

  # Check if the remote has already been created. If not, create it.
  printf "Checking if the remote exists.\t\t"
  git remote show "$REMOTE" &> /dev/null
  if ! checkresult warn ; then
    printf "Remote doesn't exist. Creating.\t\t"
    git remote add "$REMOTE" "$URL" ; checkresult
  fi

  # Fetch remote data.
  printf "Fetching remote repository.\t\t"
  git fetch -q "$REMOTE" ; checkresult

  # Perform the split.
  echo "Splitting the repo. This may take a while."
  git branch -D "$BRANCH" &> /dev/null
  git subtree split --prefix="${PREFIX}" -b "$BRANCH" ; checkresult

  # Check out the split off branch.
  printf "Switching to the split off branch.\t"
  git checkout -q "$BRANCH" ; checkresult

  # Merge the changes if the upstream dev branch exists.
  printf "Checking if upstream branch exists.\t"
  git ls-remote --exit-code "$REMOTE" "$BRANCH" &> /dev/null
  if checkresult warn ; then
    printf "Merging upstream branch.\t\t"
    git merge -q "${REMOTE}/$BRANCH" -s ours -m "Automatic merge with the unified repository." ; checkresult
  fi

  # Push back to drupal.org.
  printf "Pushing back to drupal.org.\t\t"
  RETRIES=0
  git push -q "$REMOTE" "$BRANCH"

  # If the load on git.drupal.org is too high it refuses push access. Wait a
  # minute and try again.
  while ! checkresult warn ; do
    # Give up after 3 retries.
    let "RETRIES++"
    if [ $RETRIES -gt 3 ] ; then
      echo "Could not push to drupal.org after 3 retries. Giving up."
      break
    fi
    printf "Push failed, sleeping for 60 seconds.\t"
    sleep 1m ; checkresult
    printf "Pushing back to drupal.org.\t\t"
    git push -q "$REMOTE" "$BRANCH"
  done

  # Switch back to the develop branch and clean up the split branch.
  printf "Cleaning up.\t\t\t\t"
  git checkout -q develop
  git branch -D "$BRANCH" &> /dev/null ; checkresult
}

# A list of configuration variables.
CONFIG_VARIABLES=( "SSH_USER" "UNIFIED_REPO_PATH" "UNIFIED_REPO_URI" )

# Load config file if it exists.
if [ -f push-to-drupalorg.conf ]; then
  . push-to-drupalorg.conf
fi

# Get user input for missing parameters.
# The SSH user for git.drupal.org.
while [ -z "$SSH_USER" ] ; do
  echo -e "\nEnter the SSH user name to use to connect to git.drupal.org. This user should"
  echo "have access to all repositories."
  read -p "SSH user name: " SSH_USER
done

# The path to the local copy of the unified repository.
while [ -z "$UNIFIED_REPO_PATH" ] ; do
  echo -e "\nEnter the full system path to the local copy of the unified repository. If the"
  echo "repository does not yet exist at this location it will be cloned automatically."
  read -p "Path to repository: " UNIFIED_REPO_PATH
done

# The URL of the unified repository.
while [ -z "$UNIFIED_REPO_URI" ] ; do
  echo -e "\nEnter the URI of the unified repository. This defaults to the one on Bitbucket."
  read -e -p "URI of the unified repo: " -i "https://bitbucket.org/kanooh/paddle-all.git" UNIFIED_REPO_URI
  printf "Checking if the URI is valid.\t"
  if ! git ls-remote $UNIFIED_REPO_URI &> /dev/null ; then
    printf "\e[1;31m[ERROR]\e[0m\n"
    UNIFIED_REPO_URI=
  else
    printf "\e[1;32m[OK]\e[0m\n"
  fi
done

# Save configuration.
if [ ! -f push-to-drupalorg.conf ]; then
  read -e -p "Do you want to save these settings (y/n)? " -i "y" SAVE_CONFIG
  if [ "$SAVE_CONFIG" == "y" ] ; then
    for CONFIG_VARIABLE in ${CONFIG_VARIABLES[@]} ; do
      echo "$CONFIG_VARIABLE=\"${!CONFIG_VARIABLE}\"" >> push-to-drupalorg.conf
    done
  fi
fi

# Clone unified repository if needed.
if [ ! -d "$UNIFIED_REPO_PATH" ] ; then
  printf "Downloading unified repo.\t\t"
  git clone -q "$UNIFIED_REPO_URI" "$UNIFIED_REPO_PATH" ; checkresult
fi

# Download latest changes.
cd "$UNIFIED_REPO_PATH"
printf "Checking out the develop branch.\t"
git checkout -q develop ; checkresult
printf "Fetching latest changes.\t\t"
git fetch -q ; checkresult
printf "Resetting HEAD.\t\t\t\t"
git reset --hard -q origin/develop ; checkresult

# Push back the changes to the Paddle distribution itself.
echo -e "\n---paddle---"
pushback paddle "${SSH_USER}@git.drupal.org:project/paddle.git" 7.x-1.x "profiles/paddle"

# Push back the Selenium tests.
echo -e "\n---paddle_selenium_tests---"
pushback paddle_selenium_tests "${SSH_USER}@git.drupal.org:project/paddle_selenium_tests" 7.x-1.x "selenium"

# Push back the Paddle Theme.
echo -e "\n---paddle_theme---"
pushback paddle_theme "${SSH_USER}@git.drupal.org:project/paddle_theme" 7.x-1.x "sites/all/themes/paddle_theme"

# Push back the Paddle Branded Theme. This theme contains assets (such as fonts
# and images) that are not GPL licensed and cannot be hosted on drupal.org.
# Ref. https://bitbucket.org/kanooh/paddle-branded-theme
echo -e "\n---paddle_branded_theme---"
pushback paddle_branded_theme "git@bitbucket.org:kanooh/paddle-branded-theme.git" 7.x-1.x "sites/all/themes/paddle_branded_theme"

# Push back the Paddle Admin Theme.
echo -e "\n---paddle_admin_theme---"
pushback paddle_admin_theme "${SSH_USER}@git.drupal.org:project/paddle_admin_theme" 7.x-1.x "sites/all/themes/paddle_admin_theme"

# Push back the Paddle Landing Page module to the sandbox. For the moment this
# module can keep playing in there until it is moved into the main distribution.
# Ref. https://one-agency.atlassian.net/browse/KANWEBS-1224
echo -e "\n---paddle_landing_page---"
pushback paddle_landing_page "${SSH_USER}@git.drupal.org:sandbox/Cyberwolf/1948826.git" 7.x-1.x "sites/all/modules/paddle/paddle_landing_page"

# Compile a list of modules to push back. We'll take all modules that start with
# 'paddle' in sites/all/modules/paddle.
printf "Compiling a list of modules.\t\t"
MODULES=`find sites/all/modules/paddle/* -maxdepth 1 -type d -name "paddle*" -printf "%f\n"`
checkresult

# Loop over the modules.
for MODULE in $MODULES ; do
  # Skip the Paddle VO Additional Themes module. When we try to split this a
  # huge repository containing thousands of unrelated commits is created. This
  # happens when a particular reverted merge commit is reverted a second time.
  # It might be due to a bug in git subtree.
  # Ref. https://one-agency.atlassian.net/browse/KANWEBS-2327
  if [ $MODULE == paddle_vo_additional_themes ] ; then continue ; fi

  echo -e "\n---$MODULE---"

  # Check if a release exists on drupal.org.
  printf "Checking release on drupal.org.\t\t"
  URL="https://www.drupal.org/project/$MODULE"
  curl -s --head "$URL" | head -n 1 | grep "HTTP/1.[01] [23].." > /dev/null
  if checkresult warn ; then
    REMOTE="$MODULE"
    URL="${SSH_USER}@git.drupal.org:project/${MODULE}.git"
    BRANCH=7.x-1.x
    PREFIX="sites/all/modules/paddle/${MODULE}"

    pushback "$REMOTE" "$URL" "$BRANCH" "$PREFIX"
  else
    echo -e "\e[1;33mNo release found for $MODULE. Skipping.\e[0m"
  fi
done
