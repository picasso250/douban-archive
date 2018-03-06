<?php

use Lib\Container;

define("ROOT", dirname(__DIR__));

include ROOT.'/define.php';
include ROOT.'/lib/lib.php';
include ROOT.'/vendor/autoload.php';
include ROOT.'/logic/logic.php';

autoload_dir("Lib", ROOT.'/lib');
env_load(ROOT);

include ROOT.'/container.php';

$redis = Container::getInstance()->get('redis');
while (1) {
    $entry_raw = $redis->brpop(TASK_LIST_NAME, 1);
    if (!$entry_raw) {
        echo "after 1 second\n";
        continue;
    }
    $entry = json_decode($entry_raw[1]);
    $func = 'do_'.$entry->type;
    if (!function_exists($func)) continue;
    $func($entry);
}

function do_movie($task) {
    $j = [];
    $key = TASK_RESULT_PREFIX.":$task->uid";
    $redis = Container::getInstance()->get('redis');
    $r = fetch_movie($task->url, function($msg) use($task, &$j, $key, $redis) {
        $old = $redis->get($key);
        if (!$old) $j = ['msg' => ''];
        else $j = json_decode($old, true);
        $j['msg'] .= "$msg\n";
        $redis->setex($key, 30*24*3600, json_encode($j));
        echo "$msg\n";
    });
    $j['result'] = $r;
    var_dump($r);
    $redis->setex($key, 30*24*3600, json_encode($j));
}
