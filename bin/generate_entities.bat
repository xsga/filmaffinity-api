@echo off

cls

echo.
echo ** xsga-PHP-API Doctrine-ORM generate entities v1.0.0
echo.

rem Config variables.
set DOCTRINE=../vendor/doctrine/orm/bin/doctrine
set TMP_FOLDER=../tmp/entity
set NAMESPACE=batch\entity\
set TMP_ENTITY_FOLDER=..\tmp\entity\batch\entity\
set ENTITY_FOLDER=..\src\entity

rem Executes orm:convert:mapping.
php "%DOCTRINE%" orm:convert:mapping --force --from-database --namespace=%NAMESPACE% annotation "%TMP_FOLDER%"

rem Copy generated files from temporal folder to final folder.
copy "%TMP_ENTITY_FOLDER%*.*" "%ENTITY_FOLDER%"

rem Deletes generated files from temporal folder.
del /Q "%TMP_ENTITY_FOLDER%*.*"

rem Executes orm:generate:entities.
php "%DOCTRINE%" orm:generate:entities "%TMP_FOLDER%" --generate-annotations=true

rem Copy generated files from temporal folder to final folder.
copy "%TMP_ENTITY_FOLDER%*.*" "%ENTITY_FOLDER%"

rem Deletes temporal folder.
rd /S /Q "%TMP_FOLDER%"

echo.
