@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../vendor/fiunchinho/phpunit-randomizer/bin/phpunit-randomizer
php "%BIN_TARGET%" %*
