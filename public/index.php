<?php

use Lib\RegexRouter;
use Lib\Request;

define("ROOT", dirname(__DIR__));

include ROOT.'/lib/lib.php';
include ROOT.'/logic/logic.php';

autoload_dir("Lib", ROOT.'/lib');
env_load(ROOT);

RegexRouter::add('#^/$#', function ($params) {
    include ROOT.'/view/index.php';
}, 'GET');
RegexRouter::add('#^/$#', function ($params) {
    $url = Request::POST('url');
    echo "<pre>";
    $r = fetch_movie($url);
    echo ($r!==true)? "Good":"Error";
}, 'POST');
RegexRouter::add('#^/subject/(\d+)/$#', function ($params) {
    $id = $params[1];
    $file = ROOT.'/_movie/'.$id;
    readfile($file);
}, 'GET');
RegexRouter::add('#^/j/review/(\d+)/full$#', function ($params) {
    $id = $params[1];
    $file = ROOT.'/_j_review/'.$id;
    readfile($file);
}, 'GET');
RegexRouter::run();
