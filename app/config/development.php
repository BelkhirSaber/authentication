<?php 
return [
  'app' => [
    'url' => 'http://localhost:8000',
    'hash' => [
      'algo' => PASSWORD_BCRYPT,
      'cost' => 10
    ]
  ],

  'db' => [
    'driver' => 'mysql',
    'host' => '127.0.0.1',
    'name' => 'authentication',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_general_ci',
    'prefix' => '',
  ],

  'auth' => [
    'session' => 'user_id',
    'remember' => 'user_r'
  ],

  'mail' => [
    'gmail' => [
      'smtp_auth' => true,
      'smtp_secure' => 'TLS',
      'host' => 'smtp.gmail.com',
      'username' => 'linux.sabertesteur@gmail.com',
      'password' => 'djpasvtvumyyznex',
      'port' => 587,
      'html' => true,
    ],
    'mailtrap' =>[
      'smtp_auth' => true,
      'host' => 'sandbox.smtp.mailtrap.io',
      'port' => 2525,
      'username' => '00e682bfed28e4',
      'password' => 'deda2901e9ee94',
      'html' => true,
    ]
  ],

  'twig' => [
    'debug' => true,
  ],

  'csrf' =>[
    'key' => 'csrf_token'
  ]

];