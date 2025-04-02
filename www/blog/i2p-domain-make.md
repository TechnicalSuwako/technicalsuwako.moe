title: 【I2P】ドメインを作成方法
uuid: 6d646f94-7cb3-4d1d-a162-3bfbd7090d26
author: 諏訪子
date: 2022-12-12 00:00:00
category: server,security
----
ダークネットといえば、各サイトは覚えにくい（無理）ドメイン名をついているイメージがありますね。\
でも、I2Pの場合、短いドメイン名を登録するのは可能です。\
例えば、http://technicalsuwako.i2p/、http://076.i2p/等。

## イープサイトをつくりましょう！！

[詳しくはこちらへどうぞ。](/blog/darknet-4-i2p-hosting-way.xhtml)

### Linux + OpenBSD: /etc/i2pd/tunnels.conf
### FreeBSD: /usr/local/etc/i2pd/tunnels.conf

```
[WATASINOPAGE]
type = http
host = 127.0.0.1
port = 7001
keys = loliloli.dat
```

```sh
lynx http://127.0.0.1:7070/?page=i2p_tunnels
```

上記からドメインをコピーして下さい。\
例の出力：

> WATASINOPAGE ⇐ lolilolimankounkochinkooppaitinkotinpobakamonochirno.b32.i2p

### nginx (LinuxとFreeBSDのみ)

Linuxの場合：/etc/nginx/nginx.conf\
FreeBSDの場合：/usr/local/etc/nginx/nginx.conf

```sh
server {
  listen 127.0.0.1:7001;
  root /var/www/htdocs;
  index index.html;
}
```

### httpd (OpenBSDのみ)

/etc/httpd.conf\
注意：「/htdocs」と意味は「/var/www/htdocs」ですので、このフォルダでウエブページを貼って下さい。

```sh
server "lolilolimankounkochinkooppaitinkotinpobakamonochirno.b32.i2p" {
  listen on * port 7001
  root "/htdocs"
  directory { no auto index, index "index.html" }
}
```

## 従属ライブラリのインストール

### Linux
|                               Devuan GNU/Linux                               |                   Artix Linux                  |
| ---------------------------------------------------------------------------- | ---------------------------------------------- |
| apt install build-essential cmake git libssl-dev libboost-all-dev zlib1g-dev | pacman -S gcc g++ git openssl boost boost-libs |

### BSD
|                 OpenBSD                 |                      FreeBSD                      |
| --------------------------------------- | ------------------------------------------------- |
| pkg_add gmake gcc g++ git openssl boost | pkg install gmake gcc git openssl-devel boost-all |

## コンパイル

```sh
git clone --recursive https://github.com/purplei2p/i2pd-tools && cd i2pd-tools
```

| Linux |  BSD  |
| ----- | ----- |
| make  | gmake |

## オースストリングの作成

.datファイルを見つけて下さい。\
Devuan、Artix、及びOpenBSDの場合は `/var/lib/i2pd/loliloli.dat` で、FreeBSDの場合は `/var/db/i2pd/loliloli.dat` で御座います。\
下記の例えはDevuanのパスを使っております。

```sh
./regaddr /var/lib/i2pd/loliloli.dat kerololi.i2p > auth.txt && cat auth.txt
```

出力をコピーして、下記のサイトの１つで貼って下さい。
[http://reg.i2p/add]()\
[http://stats.i2p/i2p/addkey.html]()\
reg.i2pの場合、出力は「Auth string」で貼って下さい。\
「Description」はご自由に。

## サブドメインの場合

ほしければ、サブドメインを登録するのは可能です。\
また、下記の例えばDevuanのパスです。

```sh
./regaddr_3ld step1 /var/lib/i2pd/suwaloli.dat suwa.kerololi.i2p > step1.txt
./regaddr_3ld step2 step1.txt /var/lib/i2pd/loliloli.dat kerololi.i2p > step2.txt
./regaddr_3ld step3 step2.txt /var/lib/i2pd/suwaloli.dat > step3.txt
cat step3.txt
```

また、出力はreg.i2pまたはstats.i2pで貼って下さい。

以上
