@echo off

cls

echo.
echo ** xsga-PHP-API Doctrine-ORM generate proxies v1.0.0
echo.

rem Config variables.
set DOCTRINE=../vendor/doctrine/orm/bin/doctrine

rem Executes orm:generate-proxies.
php "$DOCTRINE%" orm:generate-proxies

echo.
