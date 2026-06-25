#!/bin/bash
set -e

cd "$(dirname "$0")/.."

echo "Building index.html..."
php php/build.php index > index.html

echo "Building detail pages..."
php -r '
require "php/data.php";
foreach ($SOFTWARE as $sw) echo $sw["id"] . "\n";
' | while read -r slug; do
	echo "  p/$slug.html..."
	php php/build.php detail "$slug" > "p/$slug.html"
done

echo "Done."
