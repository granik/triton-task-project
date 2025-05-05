<?php

$db_config = [
  'host' => getenv('DB_HOST'),
  'port' => getenv('DB_PORT', '3306'),
  'user' => getenv('DB_USER'),
  'password' => getenv('DB_PASSW'),
  'database' => getenv('DB_NAME'),
];

return [
    'class' => 'yii\db\Connection',
    'dsn' => sprintf('mysql:host=%s;port=%s;dbname=%s', $db_config['host'], $db_config['port'], $db_config['database']),
    'username' => $db_config['user'],
    'password' => $db_config['password'],
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
