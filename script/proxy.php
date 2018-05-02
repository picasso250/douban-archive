<?php

define("ROOT", dirname(__DIR__));

require ROOT.'/logic/logic.php';

$html = http_GET('http://www.xicidaili.com/');
// echo "$html\n";

$list = [];
preg_replace_callback("#<tr.+?</tr>#s", function ($m)use(&$list) {
    // echo "$m[0]\n";
    $elem = [];
    preg_replace_callback('#<td>(.+?)</td>#s', function($m)use(&$elem) {
        // echo "$m[0]\n";
        $elem[] = $m[1];
    }, $m[0]);
    if ($elem) $list[] = $elem;
}, $html);

$f = fopen("proxy.json", "w");
$avail = [];
foreach ($list as $elem) {
    $ip = $elem[0];
    $port = $elem[1];
    print_r($elem);
    echo "test $ip:$port\n";
    $ch = curl_init("https://www.douban.com/");
    curl_setopt($ch, CURLOPT_PROXY, "$ip:$port");
    curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_exec($ch);
    $info = curl_getinfo($ch);
    if (curl_errno($ch)) {
        echo "$ip:$port fail ".curl_error($ch)."\n";
        curl_close($ch);
        continue;
    }
    if ($info['http_code']!=200) {
        curl_close($ch);
        echo "$ip:$port fail http_code".$info['http_code']."\n";
        continue;
    }
    curl_close($ch);
    $avail[] = $elem;
    fwrite($f, json_encode($elem));
}
fclose($f);

print_r($avail);
