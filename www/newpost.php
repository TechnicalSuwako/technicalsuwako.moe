<?php
if (!isset($argv[1])) die('usage: php newpost.php [slug]');

include('src/Site/Lib/Util.php');
$post = fopen("blog/{$argv[1]}.md", "w");
fwrite($post, "title: 【】\n");
fwrite($post, "uuid: ".Site\Lib\uuid()."\n");
fwrite($post, "author: 諏訪子\n");
fwrite($post, "date: ".date("Y-m-d H:i:s")."\n");
fwrite($post, "thumbnail: \n");
fwrite($post, "thumborient: center\n");
fwrite($post, "category: \n");
fwrite($post, "----\n");
fclose($post);
?>
