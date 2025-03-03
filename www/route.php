<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__.DIRECTORY_SEPARATOR.'/autoload.php';

use Site\Lib\Route;

use Site\Controller\Home;
use Site\Controller\Notfound;
use Site\Controller\Page;

define('ROOT', realpath(__DIR__));

$routes = [
  Route::add('GET', 'blog/{page}', Home::class.'@article'),
  Route::add('GET', 'blog.atom', Home::class.'@rss'),
  Route::add('GET', 'about', Page::class.'@about'),
  Route::add('GET', 'monero', Page::class.'@monero'),
  Route::add('GET', '', Home::class.'@show'),
];

Route::init($routes);
Route::setFallback([new Notfound(), 'show']);

$uri = urldecode($_SERVER['REQUEST_URI']);

// .xhtmlで終わるURLのリダイレクト処理
if (preg_match('/(.+)\.xhtml$/', $uri, $matches)) {
  // 末尾の.xhtmlを取り除く
  $newUri = $matches[1];
  header('HTTP/1.1 301 Moved Permanently');
  header('Location: ' . $newUri);
  exit();
}

$uri = urldecode($_SERVER['REQUEST_URI']);
if ($uri == '/index.php' || $uri == '/index.html') {
  header('Location: /');
  exit();
}

Route::dispatch($uri);
?>
