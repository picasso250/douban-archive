<?php

define("ROOT", dirname(__DIR__));

require ROOT.'/logic/logic.php';

if (!isset($argv[1])) {
    echo "Usage: $argv[0] movie_url\n";
    exit;
}

$url = $argv[1];
$r = fetch_movie($url);
if ($r!==true) {
    echo "$r\n";
    exit(1);
}
echo "OK\n";
