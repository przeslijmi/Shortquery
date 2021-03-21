<?php declare(strict_types=1);

// PHP Composer autoload.
require 'vendor/autoload.php';

// Configs.
require '.config.php';

// Check if this is local (test, dev) usage or real server usage.
if (isset($_SERVER['PATH_INFO']) === true) {
    define('CALL_TYPE', 'client');
} else {
    define('CALL_TYPE', 'cmd');
}

// Define ROOT_PATH.
define('ROOT_PATH', dirname(dirname(__FILE__)) );
