<?php

/**
 * @return true|string 成功或错误信息
 */
function fetch_movie($url, $echo_func) {

    if (!preg_match('/\d+/', $url, $m)) {
        return "url not good";
    }

    $url_info = parse_url($url);

    $html = http_GET($url);
    $file = ROOT.'/_movie/'.$m[0];
    if (file_exists($file)) {
        echo "$file already\n";
        return;
    }
    $echo_func("$file");
    // 静态资源
    $html = preg_replace_callback('#(?:https?:)?//[^"\']+\.doubanio\.com([^"\'?)(]+)#', function ($m)use($echo_func) {
        // echo "$m[0]\n";
        dl_res($m[0], $echo_func);
        return $m[1];
    }, $html);
    // url()
    $html = preg_replace_callback('/url\(([^(),{}]+)\)/', function ($m)use($echo_func, $url_info) {
        // echo "$m[0]\n";
        dl_res("http://$url_info[host]$m[1]", $echo_func);
        return $m[0];
    }, $html);
    file_put_contents($file, $html);
    // 电影链接 喜欢这部电影的人也喜欢 · · · · · ·
    $html = preg_replace_callback('#https://movie.douban.com/subject/\d+/#', function ($m)use($echo_func, $url_info) {
        echo "$m[0]\n";
        $a = parse_url($m[0]);
        fetch_movie($m[0], $echo_func);
        return $a['path'];
    }, $html);
    file_put_contents($file, $html);
    if (!preg_match_all('/id="toggle-(\d+)-copy" class="unfold"/', $html, $m)) {
        return "no comments";
    }
    foreach ($m[1] as $id) {
        $url = "https://movie.douban.com/j/review/$id/full";
        $echo_func("$url");
        $j = http_GET($url);
        file_put_contents(ROOT.'/_j_review/'.$id, $j);
    }
    return true;
}

function dl_res($url, $echo_func)
{
    // echo "dl $url\n";
    $a = parse_url($url);
    $file = ROOT."/public".$a['path'];
    // echo "to $file\n";
    if (file_exists($file)) {
        // do nothing
    } else {
        $dir = dirname($file);
        if (!is_dir($dir)) {
            $echo_func("mkdir $dir");
            mkdir($dir, 0777, true);
        }
        if (false == ($url = full_url($url))) {
            return $m[0];
        }
        $echo_func("GET $url => $file");
        $j = http_GET($url);
        file_put_contents($file, $j);
    }
}

function http_GET($url)
{
    $ch = curl_init($url);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $ret = curl_exec($ch);
    $info = curl_getinfo($ch);
    if (curl_errno($ch)) {
        echo "curl error ",curl_error($ch),"\n";
    }
    curl_close($ch);
    return $ret;
}

/**
 * @return false|string 补全后的url，错误时返回false
 */
function full_url($url)
{
    $a = parse_url ($url);
    if (!isset($a['host'])) return false;
    if (!isset($a['scheme'])) {
        echo "$url => http://$a[host]$a[path]\n";
        return "http://$a[host]$a[path]";
    }
    return $url;
}