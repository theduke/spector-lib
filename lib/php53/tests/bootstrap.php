<?php

if (!defined('SPECTOR_DATA_PATH')) define('SPECTOR_DATA_PATH', __DIR__ . '/data');

$libPath = realpath(__DIR__ . '/..');

require_once $libPath . '/Spector/Common/ClassLoader.php';

$loader = new \Spector\Common\ClassLoader('Spector', $libPath);
$loader->register();

$c = '\Spector\Import\Fetcher\Drupal';

spl_autoload_call('Spector\Import\Fetcher\Drupal');

new $c();

