@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../kelvinmo/simplejwt/bin/jwkstool
php "%BIN_TARGET%" %*
