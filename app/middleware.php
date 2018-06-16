<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

$app->add(new \Slim\Middleware\Session([
  'name' => 'admin_session',
  'autorefresh' => true,
  'lifetime' => '1 hour'
]));

$container['session'] = function ($c) {
  return new \SlimSession\Helper;
};
