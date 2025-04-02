<?php
define('REPO_ROOT', '/repos/');
define('TWITTER_HANDLE', '@techsuwako');
define('SITEINFO', [
  'title' => 'テクニカル諏訪子',
  'description' => 'テクニカル諏訪子ちゃんの個人ブログ',
  'tags' => 'game,tech,developer',
]);
define('MAILINFO', [
  'from' => 'hogehoge@ho.ge',
  'host' => 'smtp.ho.ge',
  'port' => 587,
  'user' => 'hogehoge@ho.ge',
  'pass' => 'hogehogehoge',
]);
define('FEDIINFO', [
  'actor' => 'suwako',
  'actorNick' => '諏訪子',
  'desc' => 'ロリゲーム開発者',
  'icon' => '/static/logo.png',
  'pubkey' => ROOT.'/public/static/pub.pem',
  'privkey' => ROOT.'/data/priv.pem',
]);

define('MAILER_ENABLED', false);
define('APRILFOOL_ENABLED', true);
define('LOGGING_ENABLED', true);
define('ATOM_ENABLED', true);
define('RSS_ENABLED', false);
define('ACTIVITYPUB_ENABLED', true);
?>
