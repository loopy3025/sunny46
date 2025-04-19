<?php

/**
 * @file
 * Local development override configuration feature.
 */

$db_name = 'default';

/**
 * Database configuration.
 */
$databases['default']['default'] = [
  'database' => $db_name,
  'username' => 'user',
  'password' => 'user',
  'host' => 'db',
  'port' => '3306',
  'driver' => 'mysql',
  'prefix' => '',
];


