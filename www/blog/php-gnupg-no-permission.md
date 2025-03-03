title: 【PHP】gnupgの許可なし
author: 凜
date: 2021-12-03
category: programming
----
シェルでgpgを実行出来ますが、PHPから実行すると、「`false`」が出ました。

```php
$gpg = new \gnupg();
$info = $gpg->import($this->field['gpg']);
dd($info);
```

```
false
```

nginxとして「`gpg`」を実行すると、「`/var/www/.gnupg`」にアクセス出来ないみたいです。

```sh
su nginx -s /bin/bash -c "gpg"
```

```
gpg: failed to create temporary file '/var/www/.gnupg/.#lk0x0000555c891701e0.webserver.076.ne.jp.5216': 許可がありません
gpg: keyblock リソース'/var/www/.gnupg/pubring.kbx': 許可がありません
```

## ですから、「nginxとしてlsを実行出来るかな？」と思いましたら

```sh
su nginx -s /bin/bash -c "ls -thal ~/.gnupg"
```

```
ls: ディレクトリ '/var/www/.gnupg/S.gpg-agent.extra' を開くことが出来ません: 許可がありません
ls: ディレクトリ '/var/www/.gnupg/..' を開くことが出来ません: 許可がありません
ls: ディレクトリ '/var/www/.gnupg/random_seed' を開くことが出来ません: 許可がありません
ls: ディレクトリ '/var/www/.gnupg/.' を開くことが出来ません: 許可がありません
ls: ディレクトリ '/var/www/.gnupg/S.gpg-agent' を開くことが出来ません: 許可がありません
ls: ディレクトリ '/var/www/.gnupg/S.gpg-agent.browser' を開くことが出来ません: 許可がありません
ls: ディレクトリ '/var/www/.gnupg/pubring.kbx~' を開くことが出来ません: 許可がありません
ls: ディレクトリ '/var/www/.gnupg/S.gpg-agent.ssh' を開くことが出来ません: 許可がありません
ls: ディレクトリ '/var/www/.gnupg/.#lk0x000055cce51f19c0.webserver.076.ne.jp.15948' を開くことが出来ません: 許可がありません
ls: ディレクトリ '/var/www/.gnupg/pubring.kbx' を開くことが出来ません: 許可がありません
ls: ディレクトリ '/var/www/.gnupg/private-keys-v1.d' を開くことが出来ません: 許可がありません
ls: ディレクトリ '/var/www/.gnupg/trustdb.gpg' を開くことが出来ません: 許可がありません
合計 0
d????????? ? ? ? ?            ? .
-????????? ? ? ? ?            ? .#lk0x000055cce51f19c0.webserver.076.ne.jp.15948
d????????? ? ? ? ?            ? ..
s????????? ? ? ? ?            ? S.gpg-agent
s????????? ? ? ? ?            ? S.gpg-agent.browser
s????????? ? ? ? ?            ? S.gpg-agent.extra
s????????? ? ? ? ?            ? S.gpg-agent.ssh
d????????? ? ? ? ?            ? private-keys-v1.d
-????????? ? ? ? ?            ? pubring.kbx
-????????? ? ? ? ?            ? pubring.kbx~
-????????? ? ? ? ?            ? random_seed
-????????? ? ? ? ?            ? trustdb.gpg
```

「`chmod 700`」だけは十分だと思いますが、万が一解決しなければ、このフォルダを削除すると、nginxとして作成します。

```sh
rm -rf /var/www/.gnupg
su nginx -s /bin/bash -c "mkdir ~/.gnupg"
chmod 700 /var/www/.gnupg
```

## もう一回nginxとしてlsコマンドを実行すると

```sh
su nginx -s /bin/bash -c "ls -thal ~/.gnupg"
```

```
合計 28K
drwx------ 3 nginx nginx 4.0K Dec  3 14:08 .
-rw------- 1 nginx nginx  600 Dec  3 14:08 random_seed
srwx------ 1 nginx nginx    0 Dec  3 14:03 S.gpg-agent
srwx------ 1 nginx nginx    0 Dec  3 14:03 S.gpg-agent.browser
srwx------ 1 nginx nginx    0 Dec  3 14:03 S.gpg-agent.extra
srwx------ 1 nginx nginx    0 Dec  3 14:03 S.gpg-agent.ssh
drwx------ 2 nginx nginx 4.0K Dec  3 14:03 private-keys-v1.d
-rw-r--r-- 1 nginx nginx 2.0K Dec  3 14:03 pubring.kbx
-rw------- 1 nginx nginx   32 Dec  3 14:03 pubring.kbx~
-rw------- 1 nginx nginx 1.2K Dec  3 14:03 trustdb.gpg
drwxr-xr-x 4 nginx nginx 4.0K Dec  3 14:03 ..
```

PHPでも解決されました！！

以上
