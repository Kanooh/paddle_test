#!/bin/bash

# This will build the Paddle distribution along with the assets from the Kanooh
# project from the Flemish government. These assets are copyrighted and may not
# be distributed. Some of these assets (such as fonts, stock images and grapic
# elements) are also subject to licensing fees. Please refer to a Kanooh
# representative for more information.
#
# Please use the ./build.sh script to build a GPL licensed, free and open source
# version of the Paddle distribution, without any copyrighted media.

ROOT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
BUILD_DIR="${ROOT_DIR}/build"

# Build the distribution.
/bin/bash build.sh --branded "$@" || exit $?;

# Download custom translations, which can be imported with the drush 'pet'
# command.
cd "${BUILD_DIR}"
git clone git@bitbucket.org:kanooh/paddle-translations.git || exit $?;

# Remove the .git directories if they are not needed.
for param in "$@"
do
  if [[ $param == "--git" || $param == "-g" ]] ; then
    GIT=true;
  fi
done
if [ -z $GIT ] ;  then
  cd "${BUILD_DIR}"
  echo "Clearing .git directories"
  find . -type d -name ".git" -print0 | xargs -0 rm -rf
fi
