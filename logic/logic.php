<?php

function fetch_movie($url, $echo_func) {

    if (!preg_match('/\d+/', $url, $m)) {
        return "url not good";
    }

    $html = file_get_contents($url);
    $file = ROOT.'/_movie/'.$m[0];
    echo_func("$file");
    file_put_contents($file, $html);
    if (!preg_match_all('/id="toggle-(\d+)-copy" class="unfold"/', $html, $m)) {
        return "no comments";
    }
    foreach ($m[1] as $id) {
        $url = "https://movie.douban.com/j/review/$id/full";
        echo_func("$url");
        $j = file_get_contents($url);
        file_put_contents(ROOT.'/_j_review/'.$id, $j);
    }
    return true;
}
