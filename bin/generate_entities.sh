#!/bin/bash

clear

echo
echo "** xsga-PHP-API Doctrine-ORM generate entities v1.0.0"
echo

# Config variables.
DOCTRINE=../vendor/bin/doctrine
TMP_FOLDER=../tmp/entity
NAMESPACE=batch\\entity\\
TMP_ENTITY_FOLDER=../tmp/entity/batch/entity/
ENTITY_FOLDER=../src/entity

# Executes orm:convert:mapping.
php "$DOCTRINE" orm:convert:mapping --force --from-database --namespace="$NAMESPACE" annotation "$TMP_FOLDER"

# Copy generated files from temporal folder to final folder.
cp "$TMP_ENTITY_FOLDER"* "$ENTITY_FOLDER"

# Deletes generated files from temporal folder.
rm "$TMP_ENTITY_FOLDER"*

# Executes orm:generate:entities.
php "$DOCTRINE" orm:generate:entities "$TMP_FOLDER" --generate-annotations=true

# Copy generated files from temporal folder to final folder.
cp "$TMP_ENTITY_FOLDER"* "$ENTITY_FOLDER"

# Deletes temporal folder.
rm -r "$TMP_FOLDER"

echo
