#!/bin/bash

clear

echo
echo "** xsga-PHP-API Doctrine-ORM generate proxies v1.0.0"
echo

# Config variables.
DOCTRINE=../vendor/doctrine/orm/bin/doctrine

# Executes orm:generate-proxies.
php "$DOCTRINE" orm:generate-proxies

echo
