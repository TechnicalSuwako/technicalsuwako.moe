title: 【デジタル自主】ダークネットの解説　第４部：I2Pでウエブページをホスティングする方法
author: 凜
date: 2022-07-21
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

じゃ、最初のI2PでのEepsite（イープサイト）を設置しましょう！！\
**注意：CentOSのレポジトリーでi2pdがありませんので、そうしてTorのバージョンは非常に古いですので、CentOSを使うのは全然勧めません。**

## I2Pのインストール

### Debian、Devuanの場合

```sh
sudo apt install i2pd
```

### OpenBSDの場合

```sh
doas pkg_add i2pd
```

## I2Pの設定

```sh
$ useradd -m i2p -s /bin/bash
$ su -l i2p
$ mkdir ~/.i2pd
$ cd ~/.i2pd
$ nvim tunnels.conf
```

下記を追加して下さい。

```
[SARVICE1]
type = http
host = 127.0.0.1
port = 7001
keys = sarvice1.dat

[SARVICE2]
type = http
host = 127.0.0.1
port = 7002
keys = sarvice2.dat

...
```

rootユーザに戻るには、CTRL+Dを押して下さい。

```sh
$ /etc/init.d/i2pd restart
```

新規創作された.i2pドメイン名を受け取って：

```sh
$ printf "%s.b32.i2p
" $(head -c 391 /home/i2p/.i2pd/sarvice1.dat |sha256sum|xxd -r -p | base32 |sed s/=//g | tr A-Z a-z)
hogehogehogehoge.b32.i2p
$ printf "%s.b32.i2p
" $(head -c 391 /home/i2p/.i2pd/sarvice2.dat |sha256sum|xxd -r -p | base32 |sed s/=//g | tr A-Z a-z)
gohegohegohegohe.b32.i2p
```

## nginxの設定

```sh
$ mkdir /var/www/sarvice{1,2}
$ touch /var/www/sarvice{1,2}/index.html
$ nvim /etc/nginx/sites-available/sarvice1.conf
```

```
server {
  listen 127.0.0.1:7001;
  root /var/www/sarvice1;
  index index.html index.htm;
  server_name hogehogehogehoge.b32.i2p;
}
```

```sh
$ nvim /etc/nginx/sites-available/sarvice2.conf
```

```
server {
  listen 127.0.0.1:7002;
  root /var/www/sarvice2;
  index index.html index.htm;
  server_name gohegohegohegohe.b32.i2p;
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

２つの.i2pドメインにアクセスして、出来たら成功です！

以上
