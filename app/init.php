<?php
// Load config
require_once 'config/config.php';

// Load helpers
require_once 'helper/url_helper.php';
require_once 'helper/session_helper.php';

// Autoload libs
spl_autoload_register(function ($class) {
   require_once 'libraries/' . $class . '.php';
});
