<?php

use Lib\Container;

Container::getInstance()->set('redis', function () {
    $redis = new Redis();
    $redis->connect($_ENV['redis_host'], $_ENV['redis_port']);
    return $redis;
});
