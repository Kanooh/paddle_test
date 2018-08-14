#!/bin/bash

# Builds the Paddle distribution in a subfolder of the current directory.
#
# Options:
# --git: Retain the git repositories of all downloaded projects.
# --branded: Use the makefile including the branded themes.

ROOT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
BUILD_DIR="${ROOT_DIR}/build"
DRUSH_MAKE_OPTIONS=""


# Print out documentation.
function show_help {
  echo -e "Usage: ./build.sh [OPTIONS]"
  echo -e "Builds the Paddle distribution in the 'build' subfolder. Options:"
  echo -e "-h\t--help\tShow this help text."
  echo -e "-g\t--git\tRetain the git repositories of all downloaded projects."
}

# Check command line arguments.
while [[ "$1" == -* ]]; do
  case "$1" in
    -h|--help|-\?) show_help; exit 0;;
    -g|--git) GIT=true; shift;;
    -b|--branded) BRANDED=true; shift;;
    --) shift; break;;
    -*) echo "invalid option: $1" 1>&2; show_help; exit 1;;
  esac
done

# Do not continue if the distribution was already built.
if [ -d "${BUILD_DIR}" ] ; then
  echo "Error: Target directory already exists: ${BUILD_DIR}"
  echo "Please rename or remove this directory before continuing."
  exit 1
fi

# Set up Drush make options
if [ $GIT ] ; then
  DRUSH_MAKE_OPTIONS="$DRUSH_MAKE_OPTIONS --working-copy"
fi

if [ $BRANDED ]; then
  MAKE_FILE=drupal-org-branded.make
  NO_CORE_DRUSH_MAKE_OPTIONS="$DRUSH_MAKE_OPTIONS --no-core"
else
  MAKE_FILE=drupal-org.make
  NO_CORE_DRUSH_MAKE_OPTIONS="$DRUSH_MAKE_OPTIONS --drupal-org"
fi

# Move the distribution files into a profile.
mkdir -p "${BUILD_DIR}/profiles/paddle"
find "${ROOT_DIR}" -mindepth 1 -maxdepth 1 ! -iname "build" -exec cp -r {} "${BUILD_DIR}/profiles/paddle/" \;

cd "${BUILD_DIR}"

# Install Drupal core.
drush make --prepare-install --drupal-org=core "${ROOT_DIR}/drupal-org-core.make" -y $DRUSH_MAKE_OPTIONS
if [ $? -ne 0 ] ; then { echo "error: Drupal core build failed" ; exit 1 ; } fi

# Install contrib.
drush make --contrib-destination=sites/all "${ROOT_DIR}/${MAKE_FILE}" -y $NO_CORE_DRUSH_MAKE_OPTIONS
if [ $? -ne 0 ] ; then { echo "error: contributed modules could not be built" ; exit 1 ; } fi

# Install Selenium tests.
git clone --branch 7.x-1.x http://git.drupal.org/project/paddle_selenium_tests.git selenium
composer install --working-dir=./selenium

# Remove all .git directories if they are not needed.
if [ -z $GIT ] ;  then
  find . -type d -name ".git" -print0 | xargs -0 rm -rf
fi

cd "${ROOT_DIR}"

echo -e "\nBuild is available in ${BUILD_DIR}."

exit 0
