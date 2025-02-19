<?php

error_reporting(E_ALL);
ini_set("display_errors","1");
ini_set("display_startup_errors","1");


require_once '../App.php';

/** @var array $config */
$config = require_once "../config.php";

$application = new App($config);
$application->run();
