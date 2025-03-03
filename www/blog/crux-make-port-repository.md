title: 【Crux】自分のポートとレポジトリを作り方
author: 凛
date: 2023-04-18
category: unix
----
原則して、Cruxはパッケージマネージャが入らなくて、FreeBSD、OpenBSD、NetBSD等とみたいにポーツコレクションが有ります。\
あたしは20年間ぐらいDebianベースディストリビューションを使いましたが、2017〜2021年はMan(ko)jaroが気に入りました。\
2021年よりArtix Linuxに移行しました、その間にサーバーでDebianからDevuan、FreeBSD、及びOpenBSDに変更しました。\
最近は特にCruxに気に入りました。

しかし、デフォルトでインストール出来るソフトウェアは少なくて、ドキュメンテーションが殆どありません。\
でも、Crux向けポーツを作るのは本当に簡単です。\
Gentooと同じく、Cruxはソースベース的なディストリビューションです。

## ポートを創作する

まずは新しいフォルダを創作しましょう。

```sh
mkdir -p ~/.local/src/ports && cd ~/.local/src/ports
```

後はソフトウェアのフォルダを創作し、これ内に`Pkgfile`というファイルを作成して下さい。\
例えば、i2pd。

```sh
mkdir i2pd && cd i2pd
nvim Pkgfile
```

一番上部分で４つのコメントで御座います。\
Descriptionは説明（例：C++系I2P）、URLはソフトの公式ページ、Meintainerはあなたの名前とメールアドレス（偽名等もOK）、Depends onは従属ソフト（ライブラリー等）。\
従属ソフトがなければ、不要です。

後はname（パッケージの名前、小文字ローマ字のみ）、version（バージョン）、release（普通に1でOKです）、及びsource（ダウンロードURL、.tar.gz、.tar.bz2、か.tar.xzは必要）。\
最後にbuild（創作）関数があります。

[自分でソースをホスティングするのは必要ではありませんが、あたしがそれが好みます](https://076.moe/repo/src/i2pd/)。

```
# Description: PurpleI2P i2pd
# URL:         https://i2pd.website/
# Maintainer:  Suwako Moriya, suwako at 076 dot moe
# Depends on:  gcc clang boost openssl cmake zlib

name=i2pd
version=2.47.0
release=1
source=(https://github.com/PurpleI2P/$name/archive/refs/tags/$version.tar.gz)

build() {
  cd $name-$version/build
  cmake .
  make
  make DESTDIR=$PKG install
  mv $PKG/usr/local/bin $PKG/usr/bin
  mv $PKG/usr/local/lib64 $PKG/usr/lib
  rm -rf $PKG/usr/local
}
```

i2pdの問題は、デフォルトで`/usr/local`にインストールされています。\
しかし、Cruxはこのフォルダが全然使いませんので、`make install`の後で手動で`/usr`に移動します。

開発者に教えられるインストールする方法と殆ど同じですが、違いは$PKGです。\
`make install` は `make DESTDIR=$PKG install` となります。\
$PKGは仮に作られているフォルダパスです。\
それの中に普通のパスと同じです。\
例えば`$PKG/etc/nginx`、`$PKG/home/suwako/.xinitrc`、`$PKG/usr/bin/zsh`等。

次はポートパッケージを作ります。\
あたしは「doas」を使っていますが、「sudo」を使ったら、これを使って下さい。

```sh
doas pkgmk -d
```

`-d`は「ソースをダウンロードして」と意味です。\
既に同じフォルダで`.tar.gz`ファイルがあれば、このオプションは不要となります。\
[成功にコンパイル出来たら、新しい「.pkg.tar.gz」ファイルが出てきます](https://076.moe/repo/crux/ports/i2pd/)。

## レポジトリの作成

ポーツツリーのルートフォルダに帰って下さい。

```sh
cd ~/.local/src/ports
```

そこは`httpup`レポジトリを作りましよう。

```sh
httpup-repgen .
```

新しい`REPO`ファイルが創作されています。\
これを確認して下さい。\
下記みたいな結果があれば、良いです。

```sh
cat REPO
```

```
d:i2pd
f:1afa91184220d16c5431efab3919118e:i2pd/.footprint
f:edd3f864018c2c87a99b395d75d87c55:i2pd/.md5sum
f:71a4616aeec73486d4e5c350c20cf9fd:i2pd/Pkgfile
```

ホストがご自由に決めて下さい。\
あたしは勿論自分のサーバーでホスティングしています。\
ちなみに、一般ネットサーバーとダークネットサーバーはrsyncで同期していますので、どっちでも使っては良いです。

```sh
cd ..
rsync -rtvzP ports (貴方のホスト名かIPアドレス):(webrootのパス)
```

ところで、次のステップは１回だけが必要となります。

```sh
doas nvim /etc/ports/myports.httpup
```

```
ROOT_DIR=/usr/ports/myports
URL=(貴方のホスト名かIPアドレス＋パス、あたしの場合＝https://076.moe/repo/crux/ports/)
```

このファイルをホスティングするのは必要はありませんが、他人は貴方のレポジトリを使うにはとても便利です。\
次は`prt-get.conf`に有効にしましょう。

```sh
doas nvim /etc/prt-get.conf
```

```
...
## configure directories prt-get will source ports from
## note: the order matters: the package found first is used
prtdir /usr/ports/core
prtdir /usr/ports/opt
prtdir /usr/ports/xorg
prtdir /usr/ports/myports
...
```

ところで、この同じファイルで、`prtdir /usr/ports/contrib`を有効にするのは勧めます。\
デフォルトで、必要な従属ソフトが公式レポジトリが入っていませんので、`contrib`を有効にするのは凄く便利となります。\
比べたら、CruxでcontribレポジトリはArchかArtix等でのAURみたいなイメージがあります。

終わったら、ポーツツリーを更新しましょう。

```sh
doas ports -u
```

問題がなければ、下記みたいに出力が発生します：

```
...
Updating file list from (貴方のホスト名かIPアドレス)/ports/
Updating collection myports
 Checkout: myports/i2pd
 Checkout: myports/i2pd/.footprint
 Checkout: myports/i2pd/.signature
 Checkout: myports/i2pd/Pkgfile
Finished successfully
...
```

## ポートのインストール

じゃ、i2pdをインストールしてみましょう。

```sh
doas prt-get depinst i2pd
```

installコマンドもありますが、それが従属ソフトがインストールしていませんので、depinstの方が勧めます。\
prt-getの使い方については次回書いてみます。

この記事を書きながらCruxにfcitx5、mozc等をコンパイル中ですので、それはそろそろあたしのレポジトリに入ると思います。

以上
