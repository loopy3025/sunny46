<?php

// phpcs:ignoreFile

/**
 * @file
 * Sunny46 custom settings file.
 */


// Define the directory for configuration sync.
$settings['config_sync_directory'] = $app_root . '/../config/sync';


# A2 database connection
$databases['default']['default'] = array (
  'database' => 'sunnyco2_drupal',
  'username' => 'sunnyco2_application',
  'password' => 'djqknqkj223722jfn2kf!!sdvswjvnkwefiwe323CWE',
  'prefix' => '',
  'host' => 'localhost',
  'port' => '',
  'isolation_level' => 'READ COMMITTED',
  'driver' => 'mysql',
  'namespace' => 'Drupal\\mysql\\Driver\\Database\\mysql',
  'autoload' => 'core/modules/mysql/src/Driver/Database/mysql/',
);
$settings['hash_salt'] = '1ovTQGcxYY-bh9sSDVGQRSIuEGqUZSsdx9bbfg0ir_hsRCedIsnRTErG93_INFRFxOPvc7WTUw';


/**
 * If there is a local settings file, then include it
 */
$local_settings = __DIR__ . "/settings.local.php";
if (file_exists($local_settings)) {
  include $local_settings;
}
