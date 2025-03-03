title: 【PostmarketOS】自分のレポジトリを作り方
author: 凛
date: 2023-10-15
category: smartphone,unix
----
[半年前に言った通り、あたしのパソコンでのLinuxを使用歴が非常に長いですが、スマホでの使用歴はそれ程長くないのです。](/blog/crux-make-port-repository.xhtml)\
2020年にはMan(ko)jaroを使用していましたが、毎回パッケージの更新後に度々問題が生じた為、Mobian（PinePhone用Debian）に切り替えました。\
Mobianは2022年まで使用していましたが、２年経ってもLinuxスマホの進歩があんまなかった為、再びPixel 3でGrapheneOS（Google非依存のAndroid）に戻りました。\
しかし、2023年10月現在、ついに大きな進展が見られる様になった為、PinePhoneを処理して、PostmarketOSを試してみたところ、Androidを使用する意欲がなくなりました。

ちなみに、PostmarketOSはスマホ専用Alpine Linuxベースのディストリビューションです。

## レポジトリの作り方

### 初回のみ

以下のステップは初回だけ実行して下さい。\
まず、必要なツールをインストールします。

```sh
doas apk update
doas apk add alpine-sdk
doas addgroup $(whoami) abuild
doas reboot
```

再起動後、新しいディレクトリと鍵ペアを生成して下さい。

```sh
mkdir -p ~/.local/src/repo
abuild-keygen -a -i
```

### 新しいパッケージを作成する

次のステップは、APKBUILDファイルを作成する事です。

```sh
cd ~/.local/src/repo
mkdir urloli
cd urloli
nvim APKBUILD
```

例：

```
# Maintainer: Suwako Moriya <suwako at 076 dot moe>
pkgname=urloli
pkgver=2.2.0
pkgrel=1
pkgdesc="$pkgname"
url="https://076.moe"
arch="all"
license="GPL"
source="https://076.moe/repo/src/$pkgname/$pkgname-$pkgver.tar.gz"
makedepends="go"
options="!check !strip"

package() {
  mkdir -p $pkgdir/etc/urloli $pkgdir/usr/bin $pkgdir/etc/init.d $pkgdir/www/active/urlo.li
  mv -i config.json $pkgdir/etc/urloli/config.json
  make
  mv urloli $pkgdir/usr/bin/urloli
  curl https://076.moe/repo/init/openrc/init.d/urloli > $pkgdir/etc/init.d/urloli
  mv view $pkgdir/www/active/urlo.li
  mv static $pkgdir/www/active/urlo.li
  chmod +x $pkgdir/etc/init.d/urloli
  chmod +x $pkgdir/usr/bin/urloli
  echo "Change the domain name in \"/etc/$pkgname/config.json\"."
}
```

URLロリはGo以外従属ソフトがありませんが、Goはコンパイル時だけで必要ですので、「makedepends」に追加しました。\
実行するには必要であれば、「depends」に追加して下さい。

**注意：PostmarketOSやAlpineで、「ninja」をインストールする場合、`apk add ninja`ではなく`apk add samurai`を使用して下さい。**

次は「sha512sum」を生成し、ビルドを行って下さい。

```sh
abuild checksum
abuild
```

Alpineではパッケージの署名が必要ですが、PostmarketOSでは自動で署名される為、これは不要です。

## レポジトリサーバーの準備

次のステップは、サーバーを準備です。\
サーバーはOpenBSDの場合：

```sh
doas nvim /etc/httpd.conf
```

```
...
server "076.moe" {
  listen on * port 443 tls
  tls certificate "/etc/ssl/076.moe.crt"
  tls key "/etc/ssl/private/076.moe.key"
  root "/htdocs/076.moe/www"
  directory index "index.html"
  location "/repo/*" {
    directory auto index
  }
  location "/.well-known/acme-challenge/*" {
    root "/acme"
    request strip 2
  }
}

server "www.076.moe" {
  listen on * port 443 tls
  tls certificate "/etc/ssl/076.moe.crt"
  tls key "/etc/ssl/private/076.moe.key"
  block return 301 "https://076.moe$REQUEST_URI"
}

server "www.076.moe" {
  alternative { 076.moe }
  listen on * port 80
  block return 301 "https://076.moe$REQUEST_URI"
}

server "l3nbzyxgrkmd46nacmzf2sy6tpjrwh4iv3pgacbrbk72wcgxq5a.b32.i2p" {
  listen on * port 8450
  root "/htdocs/076.moe/www"
  directory index "index.html"
  location "/repo/*" {
    directory auto index
  }
}

server "7dt6irsmfvbrtgn4nuah56kky6mvr472fbwwaltuxpf26qdqkdhfvnqd.onion" {
  listen on * port 8500
  root "/htdocs/076.moe/www"
  directory index "index.html"
  location "/repo/*" {
    directory auto index
  }
}
...
```

```sh
doas mkdir -p /var/www/htdocs/076.moe/www/repo/alpine
doas chown -R $(whoami):$(whoami) /var/www/htdocs/076.moe
doas rcctl restart httpd
```

### パッケージを公開

公開鍵をアップロードした後、パッケージを公開して下さい。

```sh
rsync -rtvzP ~/.abuild/*.rsa.pub (君のIPアドレス):/var/www/htdocs/076.moe/www/repo/alpine
cd ~/packages
rsync -rtvzP repo (君のIPアドレス):/var/www/htdocs/076.moe/www/repo/alpine
```

## レポジトリの確認

最後のステップは、自分のパッケージをインストールする事です。

```sh
cd /etc/apk/keys
doas wget https://(ドメイン名)/repo/alpine/(.rsa.pubのファイル名)
cd ..
doas nvim repositories
```

```
http://mirror.postmarketos.org/postmarketos/v23.06
http://dl-cdn.alpinelinux.org/alpine/v3.18/main
http://dl-cdn.alpinelinux.org/alpine/v3.18/community
http://(ドメイン名)/repo/alpine/repo # これを追加して下さい
```

```sh
doas apk update
doas apk add urloli
```

以上
