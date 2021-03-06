<?php

use Lib\RegexRouter;
use Lib\Request;
use Lib\Response;
use Lib\Container;

define("ROOT", dirname(__DIR__));

include ROOT.'/define.php';
include ROOT.'/lib/lib.php';
include ROOT.'/vendor/autoload.php';
include ROOT.'/logic/logic.php';

autoload_dir("Lib", ROOT.'/lib');
env_load(ROOT);

include ROOT.'/container.php';

RegexRouter::add('#^/$#', function ($params) {
    include ROOT.'/view/index.php';
}, 'GET');
RegexRouter::add('#^/$#', function ($params) {
    $url = Request::POST('url');
    $redis = Container::getInstance()->get('redis');
    $uid = uniqid();
    $redis->lpush(TASK_LIST_NAME, json_encode(['type'=>'movie', 'url' => $url, 'uid' => $uid]));
    echo $uid;
}, 'POST');
RegexRouter::add('#^/task-result/(.+)$#', function ($params) {
    $task_id = $params[1];
    $redis = Container::getInstance()->get('redis');
    $json_raw = $redis->get(TASK_RESULT_PREFIX.":$task_id");
    if (!$json_raw) $r = [];
    else $r = json_decode($json_raw, true);
    Response::echoJson($r);
}, 'GET');
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
RegexRouter::add('#^/status$#', function ($params) {
    $redis = Container::getInstance()->get('redis');
    echo $redis->ping();
}, 'GET');
RegexRouter::run();
