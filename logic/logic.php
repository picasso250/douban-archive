<?php

/**
 * @return true|string 成功或错误信息
 */
function fetch_movie($url, $echo_func) {

    if (!preg_match('/\d+/', $url, $m)) {
        return "url not good";
    }

    $file = ROOT.'/_movie/'.$m[0];
    if (file_exists($file)) {
        echo "$file already\n";
        return;
    }
    $echo_func("$file");

    $url_info = parse_url($url);
    $html = http_GET($url);

    if (preg_match('#(https://.+&d=)"\+d\+"(&r=https.+&k=[^"]+)#', $html, $m)) {
        // sec
        echo "$m[0]\n";
        return;
        $d = "Win32|Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36|Google Inc.";
        $url = $m[1].urlencode($d).$m[2];
        $html = http_GET($url);
    }
    
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
    
    // 电影评论
    if (!preg_match_all('/id="toggle-(\d+)-copy" class="unfold"/', $html, $m)) {
        return "no comments";
    }
    foreach ($m[1] as $id) {
        $url = "https://movie.douban.com/j/review/$id/full";
        $echo_func("$url");
        $j = http_GET($url);
        file_put_contents(ROOT.'/_j_review/'.$id, $j);
    }

    // 电影链接 喜欢这部电影的人也喜欢 · · · · · ·
    $html = preg_replace_callback('#https://movie.douban.com/subject/\d+/#', function ($m)use($echo_func, $url_info) {
        echo "$m[0]\n";
        $a = parse_url($m[0]);
        fetch_movie($m[0], $echo_func);
        return $a['path'];
    }, $html);
    file_put_contents($file, $html);
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
    echo "GET $url\n";
    $ch = curl_init($url);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt ($ch, CURLOPT_HEADER, 1);
    curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36');
    $ip = '127.0.0.1';
    $port = 1080;
    curl_setopt($ch, CURLOPT_PROXY, "$ip:$port");
    $ret = curl_exec($ch);
    if (curl_errno($ch)) {
        echo "curl error ",curl_error($ch),"\n";
    }
    $info = curl_getinfo($ch);
    if ($info['http_code']!= 200) {
        echo "http_code {$info['http_code']}\n";
        if ($info['http_code'] == 302) {
            echo "302 $info[redirect_url]\n";
            exit(1);
        }
    }
    curl_close($ch);
    $header = substr($ret, 0, $info['header_size']);
    echo "$header";
    $body = substr($ret, $info['header_size']);
    return $body;
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