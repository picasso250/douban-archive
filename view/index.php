<!DOCTYPE html>
<html lang="zh-cmn-Hans" class="">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="renderer" content="webkit">
    <meta name="referrer" content="always">
    <title>豆瓣的电影保鲜棺材</title>

    <meta name="baidu-site-verification" content="cZdR4xxR7RxmM4zE" />
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="Sun, 6 Mar 2005 01:00:00 GMT">

    <link rel="apple-touch-icon" href="https://img3.doubanio.com/f/movie/d59b2715fdea4968a450ee5f6c95c7d7a2030065/pics/movie/apple-touch-icon.png">
    <link href="https://img3.doubanio.com/f/shire/420c6a4b676c73bc6af48dfcdd18b662f5c223d7/css/douban.css" rel="stylesheet" type="text/css">
    <link href="https://img3.doubanio.com/f/shire/ae3f5a3e3085968370b1fc63afcecb22d3284848/css/separation/_all.css" rel="stylesheet" type="text/css">
    <link href="https://img3.doubanio.com/f/movie/8864d3756094f5272d3c93e30ee2e324665855b0/css/movie/base/init.css" rel="stylesheet">
    <script type="text/javascript">var _head_start = new Date();</script>
    <script type="text/javascript" src="https://img3.doubanio.com/f/movie/0495cb173e298c28593766009c7b0a953246c5b5/js/movie/lib/jquery.js"></script>
    <script type="text/javascript" src="https://img3.doubanio.com/f/shire/1efae2c2d48b407a9bed76b9dd5dd8de37a8dbe1/js/douban.js"></script>
    <script type="text/javascript" src="https://img3.doubanio.com/f/shire/0efdc63b77f895eaf85281fb0e44d435c6239a3f/js/separation/_all.js"></script>

    <meta name="keywords" content="水形物语,The Shape of Water,水形物语影评,剧情介绍,电影图片,预告片,影讯,在线购票,论坛">
    <meta name="description" content="水形物语电影简介和剧情介绍,水形物语影评、图片、预告片、影讯、论坛、在线购票">
    <meta name="mobile-agent" content="format=html5; url=http://m.douban.com/movie/subject/26752852/"/>
    <link rel="alternate" href="android-app://com.douban.movie/doubanmovie/subject/26752852/" />
    <link rel="stylesheet" href="https://img3.doubanio.com/dae/cdnlib/libs/LikeButton/1.0.5/style.min.css">
    <script type="text/javascript" src="https://img3.doubanio.com/f/shire/77323ae72a612bba8b65f845491513ff3329b1bb/js/do.js" data-cfg-autoload="false"></script>


    <style type="text/css">img { max-width: 100%; }</style>
    <script type="text/javascript"></script>
    <link rel="stylesheet" href="https://img3.doubanio.com/misc/mixed_static/523d8e8145eb5bf.css">

    <link rel="shortcut icon" href="https://img3.doubanio.com/favicon.ico" type="image/x-icon">

    <style media="screen">
        body {
            padding: 1em;
        }
    </style>
</head>

<body>

    <script type="text/javascript">var _body_start = new Date();</script>
    <h1>豆瓣的电影保鲜棺材</h1>
    <div class="">
        <p>这里的介绍由瞳瞳来填写</p>
        <p>...</p>
    </div>
    <ul>
        <?php foreach (glob(ROOT.'/_movie/*') as $file): ?>
            <?php if (preg_match('/(\d+)$/', $file, $mm)): ?>
                <?php if (preg_match('/<title>(.+?)<\/title>/sm', file_get_contents($file), $m)): ?>
                    <li>
                        <a href="/subject/<?= $mm[1] ?>/"><?= htmlspecialchars($m[1]) ?></a>
                    </li>
                <?php endif; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>

    <h2>将电影埋入棺材</h2>
    <div class="">
        <p>添加电影</p>
        <p>只会拉取第一页的影评</p>
        <p>请注意，如果此电影在棺材里已经存在，则会覆盖。请慎用。</p>
    </div>
    <form class="" action="?" method="post">
        <div class="">
            <span>请输入豆瓣电影地址</span>
            <input type="text" name="url" value="">
        </div>
        <input type="submit" name="" value="埋入棺材">
    </form>

</body>

</html>
