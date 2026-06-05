<?php

/**
 * cPanel deployment entry point.
 *
 * Place this file as  public_html/index.php
 * The Laravel project root must be at  ~/templecrs/
 *
 * Directory layout expected:
 *   /home/<user>/templecrs/   ← full Laravel project (git clone)
 *   /home/<user>/public_html/ ← document root for the domain
 *     index.php               ← this file
 *     .htaccess               ← copied from templecrs/public/.htaccess
 *     build/                  ← copied from templecrs/public/build/
 *     storage -> symlink      ← ln -sfn ~/templecrs/storage/app/public ~/public_html/storage
 */

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Maintenance mode
if (file_exists($maintenance = __DIR__.'/../templecrs/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Autoloader
require __DIR__.'/../templecrs/vendor/autoload.php';

// Bootstrap & handle
/** @var Application $app */
$app = require_once __DIR__.'/../templecrs/bootstrap/app.php';

$app->handleRequest(Request::capture());
