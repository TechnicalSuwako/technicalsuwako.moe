title: 【デジタル自主】OpenBSDでGeminiサーバーを設置方法
uuid: 2f9380d7-5b48-4101-8949-32c18fb14b8d
author: 諏訪子
date: 2023-06-06 00:00:00
category: unix,server
----
HTTPは比較的重いため、最近ではGeminiが徐々に人気を集めています。\
Linuxサーバーでの設置は比較的容易ですが、OpenBSDの場合はやや複雑になります。\
そこで今回は、OpenBSDでGeminiサーバーを設置する方法について説明します。

## Geminiとは？

[Geminiは極めてシンプルなウェブプロトコルです。](gemini://gemini.circumlunar.space/)\
GeminiはGopherとウェブの間隙を埋める新しいインターネットプロトコルとして、\
一方の問題を避けながら他方の制約を解決する事を目指して共同設計されました。\
Geminiカプセルへのアクセスには、特別なブラウザが必要となります。\
オススメのブラウザは以下の通りです（推奨順）：\
[・Amfora（Go）](https://gh.akisblack.dev/makew0rld/amfora)\
[・Bombadillo（Go）](https://bombadillo.colorfield.space/)\
[・Lagrange（CとSDL）](https://git.skyjake.fi/gemini/lagrange)\
[・Elpher（Emacs）](https://thelambdalab.xyz/elpher/)\
[・Kristall（C++とQt）](https://gh.akisblack.dev/MasterQ32/kristall)\
[・Castor（RustとGTK）](https://git.sr.ht/~julienxx/castor)

## HTMLやCSSは使用可能？

いいえ、使用する事は出来ません。\
Geminiでは、Gemitextのみがサポートされています。\
Gemitextはマークダウンのような形式で、機能性は限定的です。\
[あたし自身もGeminiカプセルを運用していますので、ぜひご覧下さい。](gemini://technicalsuwako.moe/)\
[良いGemtextファイルの例はこちらです。](https://gitler.moe/suwako/technicalsuwako.moe/raw/branch/master/gemini/blog/c-lib-in-zig-use.gmi)

## 画像は？

画像の利用は可能ですが、ブラウザ上で表示する事は出来ません。\
しかし、画像へのリンクを提供すれば、外部の画像表示ソフトで開く事が出来ます。

## gmnxdとは？

[gmnxdはOpenBSD用のGeminiサーバーソフトウェアです。](https://lab.abiscuola.org/gmnxd/doc/trunk/www/index.wiki)\
ここではその設置方法を解説します。\
HTTPウェブサイトと同一のサーバーでホスティングする事も可能です。

## 基本的な設置

例としてのドメインは「unkotinko.jp」を用いて説明します。

```sh
doas su -l
wget https://lab.abiscuola.org/gmnxd/tarball/v1.2.0/gmnxd-v1.2.0.tar.gz
tar zxfv gmnxd-v1.2.0.tar.gz
cd gmnxd-v1.2.0/src
make
make install
mkdir -p /var/gemini/unkotinko.jp
useradd -g '=uid' -L daemon -s /sbin/nologin  -c 'Gmnxd user' -d /var/gemini _gmnxd
chown -R _gmnxd:_gmnxd /var/gemini
chown -R suwako:suwako /var/gemini/unkotinko.jp
```

## inetd

```sh
nvim /etc/inetd.conf
```

```
127.0.0.1:11965 stream  tcp     nowait  _gmnxd  /usr/local/libexec/gmnxd        gmnxd
```

```sh
rcctl enable inetd
rcctl start inetd
```

## pf

```sh
nvim /etc/pf.conf
```

```
...
# Gemini
pass in  on egress proto tcp from any to any port { 1965 }
...
anchor "relayd/*"
```

```sh
pfctl -f /etc/pf.conf
```

## relayd

```sh
nvim /etc/relayd.conf
```

```
...
protocol gemini {
  tcp { sack, backlog 128 }
  tls keypair "unkotinko.jp"
}
...
relay gemini {
  listen on 0.0.0.0 port 1965 tls
  protocol gemini

  forward to <home> check tcp port 11965
}
```

```sh
rcctl restart relayd
```

## カプセルの内容

新たなカプセルを追加するには、「/var/gemini」内にドメイン名のフォルダを作成して下さい。\
例えば、「dekkailolioppai.com」向けのカプセルを作成する場合、\
`mkdir /var/gemini/dekkailolioppai.com && touch /var/gemini/dekkailolioppai.com/index.gmi`を実行して下さい。

そのindex.gmiファイルの中に、サンプルページを作成しましょう。

```
# でっかいロリおっぱい
Hな日本人である。
こんちゃっす！！

> こんにちは！！
> あれあれあれ！？元気でないぞ！？
> もう一回、みんな！カンボジア！！

=> https://youtube.owacon.moe/watch?v=NXnI1Jj0h_8 元ネタ
```

Geminiブラウザで「gemini://dekkailolioppai.com」にアクセスすると、上記の内容が表示されます。

以上
