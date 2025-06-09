#!/bin/bash
FILE="vendor/mikey179/vfsstream/src/main/php/org/bovigo/vfs/vfsStream.php"

if [[ "$OSTYPE" == "darwin"* ]]; then
  sed -i '' 's/name{0}/name[0]/g' "$FILE"
else
  sed -i 's/name{0}/name[0]/g' "$FILE"
fi
