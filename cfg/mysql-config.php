<?php
/**
 * ps: not referenced in Version1
 * MySQL settings 
 *    In a production system, you'd never want to check credentials into source code.
 *    You'd want to provide auth credentials via one of:
 *         1) AWS Roles/IAM credentials
 *         2) Setting environment variables / config at machine provisioning lifecycle
 *         3) Deploying configuration files that are parse/replaced with values from a secured keystore
 *         4) Reading values at runtime from a known configuration storeDB
 */
define( 'DB_NAME',     'testdb' );
define( 'DB_USER',     'user' );
define( 'DB_PASSWORD', 'passwd' );
define( 'DB_HOST',     'localhost' );
?>