<?php

$libPath = realpath(__DIR__ . '/../lib');

include $libPath . '/Spector/Common/ClassLoader.php';

$loader = new \Spector\Common\ClassLoader('Spector', $libPath);
$loader->register();