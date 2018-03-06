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
    $entry_raw = $redis->brpop(TASK_LIST_NAME, 10);
    if (!$entry_raw) continue;
    $entry = json_decode($entry_raw);
    $func = 'do_'.$entry->type;
    if (!func_exists($func)) continue;
    $func($entry);
}

function do_movie($task) {
    fetch_movie($task->url, function($msg) use($task) {
        $redis = Container::getInstance()->get('redis');
        $old_msg = $redis->get(TASK_RESULT_PREFIX.":$task->uid");
        $new_msg = $old_msg."$msg\n";
        $old_msg = $redis->setex(TASK_RESULT_PREFIX.":$task->uid", 30*24*3600, $new_msg);
    });
}
