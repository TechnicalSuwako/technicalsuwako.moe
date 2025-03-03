title: 【デジタル自主】ダークネットの解説　第３部：Torでウエブページをホスティングする方法
author: 凜
date: 2022-07-20
category: security
----
こちらはダークネットの使い方の解説シリーズです。\
ダークネットはデジタル自主のインターネットの未来ですので、早くわかった方が良いですね。♡

一般ネットよりダークネットの方がメリットは：
* 中央管理がない
* ウエブサイトを中止させる（キャンセルカルチャー等）のは無理
* 実際に検閲するのは無理
* 個人情報（電話番号、本名、住所等）無しで匿名ですべてのサービスを使える
* 「ネット上での侮辱」の法律（実は表現の自由に反対の法律、日本国憲法第二十一条によるこの法律は憲法違反だ）の心配がない
* [イラストの検閲（モザイク等。また、日本国憲法第二十一条による検閲法律は憲法違反だは不要だ](/blog/dejital-jisyu-censorship-law-is-illegal.xhtml)）

デメリットは：
* ドメイン名はハッシュとして創作されていますので、見つけにくいです。ですから、他のダークネットのウエブページで知り合いになるのは必須です。

じゃ、最初のTorサービスを設置しましょう！！\
**注意：CentOSのレポジトリーでi2pdがありませんので、そうしてTorのバージョンは非常に古いですので、CentOSを使うのは全然勧めません。**

## Torのインストール

### Debian、Devuanの場合

```sh
$ sudo apt install tor nginx
```

### OpenBSDの場合

```sh
$ doas pkg_add tor nginx
```

## Torの設定

Debian、Devuanの場合：

```sh
$ nvim /etc/tor/torrc
```

OpenBSDの場合：

```sh
$ nvim /usr/local/etc/tor/torrc
```

下記を追加して下さい。

```
HiddenServiceDir /var/lib/tor/sarvice1/
HiddenServicePort 6001

HiddenServiceDir /var/lib/tor/sarvice2/
HiddenServicePort 6002

...
```

```sh
$ /etc/init.d/tor restart
```

新規創作された.onionドメイン名を受け取って：

```sh
$ cat /var/lib/tor/sarvice1/hostname
hogehogehogehoge.onion
$ cat /var/lib/tor/sarvice2/hostname
gohegohegohegohe.onion
```

## nginxの設定

```sh
$ mkdir /var/www/sarvice{1,2}
$ touch /var/www/sarvice{1,2}/index.html
$ nvim /etc/nginx/sites-available/sarvice1.conf
```

```
server {
  listen 127.0.0.1:6001;
  root /var/www/sarvice1;
  index index.html index.htm;
  server_name hogehogehogehoge.onion;
}
```

```sh
$ nvim /etc/nginx/sites-available/sarvice2.conf
```

```
server {
  listen 127.0.0.1:6002;
  root /var/www/sarvice2;
  index index.html index.htm;
  server_name gohegohegohegohe.onion;
}
```

```sh
$ ln -s /etc/nginx/sites-available/sarvice1.conf /etc/nginx/sites-enabled
$ ln -s /etc/nginx/sites-available/sarvice2.conf /etc/nginx/sites-enabled
```

「/var/www/sarvice1/index.html」と「/var/www/sarvice2/index.html」ファイルでご自由に入力して下さい。

```sh
$ /etc/init.d/nginx restart
```

２つの.onionドメインにアクセスして、出来たら成功です！

続く
