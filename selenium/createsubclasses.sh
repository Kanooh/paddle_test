#!/bin/bash -e

# Get the script folder.
ROOT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# Get some input from the user.
until [[ "$NAMESPACE" =~ ^[a-zA-Z\\]+$ ]] ; do
  read -rp "Namespace of the base test (e.g. Kanooh\\Paddle\\Core\\ContentType\\Base): " NAMESPACE
done

until [[ "$BASECLASS" =~ ^[a-zA-Z]+$ ]] ; do
  read -p "Class name of the base test (e.g. PageInformationTestBase): " BASECLASS
done

until [[ "$CLASS" =~ ^[a-zA-Z]+$ ]] ; do
  read -p "Class name of the test (e.g. PageInformationTest): " CLASS
done

# Loop over the existing folders that contain test subclasses.
echo "Generating files..."
for DIR in `find $ROOT_DIR/tests/Kanooh/Paddle/ -name Common -type d` ; do
  # Copy one of the existing tests.
  FILENAME=$DIR/${CLASS}.php
  cp "$DIR/EditorialNotesTest.php" "$FILENAME"

  # Edit the file.
  ESCAPED=$(echo $NAMESPACE | sed -e 's/\\/\\\\/g')
  perl -pi -e "s/^( \* Contains [a-zA-Z0-9\\\]*)EditorialNotesTest.$/\${1}${CLASS}\./" "$FILENAME"
  perl -pi -e "s/^use Kanooh\\\Paddle\\\Core\\\ContentType\\\Base\\\EditorialNotesTestBase;/use ${ESCAPED}\\\\${BASECLASS};/" "$FILENAME"
  perl -pi -e "s/^ \* EditorialNotesTest class/ \* $CLASS class/" "$FILENAME"
  perl -pi -e "s/^class EditorialNotesTest.*/class $CLASS extends $BASECLASS/" "$FILENAME"

  # Inform about the files that were created.
  echo "$FILENAME"
done
