<?php

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../app/AppKernel.php";

use Symfony\Component\HttpFoundation\Request;

$request = Request::createFromGlobals();
$kernel = new AppKernel('prod', true);
$kernel->handle($request)->send();