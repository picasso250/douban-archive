# douban-archive
豆瓣电影棺材

## 使用方法

首先，安装PHP

然后，使用脚本拉取电影页面。

再然后，开启Server。

最后，就可以打开浏览器观看了。

**使用脚本拉取电影页面**

1. 打开cmd（或者terminal）
2. `cd script`
3. `php fetch_movie.php "https://movie.douban.com/subject/4920389/?from=showing"`
4. 直到看到拉取完成的提示

PS：重复对同一个URL运行脚本会造成被覆盖。

**开启Server**

1. 打开cmd（或者terminal）
2. `php -S 0.0.0.0:80 -t public` （在项目根目录下运行）
3. 打开浏览器，输入 localhost/subject/4920389/ 即可访问。
