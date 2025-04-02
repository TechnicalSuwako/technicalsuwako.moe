title: 【OpenBSD】Moneroウォレットとbtopのコンパイル方法
uuid: 773dcd47-90ff-4729-adc4-e71b0fa2e69a
author: 諏訪子
date: 2024-04-01 00:00:00
category: crypto,unix
----
## Monero CLI ウォレットのコンパイル方法
[前回は「ウォレットの選択」で「OpenBSDの場合は全くありません。」と述べましたが、それは誤りでした。](/blog/crypto-1-monero-dero-wallet.xhtml)
それじゃ、コンパイルしてみましょう！

```sh
doas pkg_add cmake gmake zeromq libiconv boost libunbound
mkdir -p ~/.local/src
cd ~/.local/src
git clone https://github.com/monero-project/monero.git
cd monero
ulimit -d 2000000
git submodule update --init --force
gmake
cd build/OpenBSD/master/release/bin
doas cp monero* /usr/local/bin
```

## btopのコンパイル方法
そして、あたしのお気に入りのシステムモニターであるbtopもコンパイルします。

```sh
doas pkg_add cmake g++%11 git ninja lowdown
mkdir -p ~/.local/src
cd ~/.local/src
git clone https://github.com/aristocratos/btop.git
cd btop
CXX=eg++ cmake -B build -G Ninja
cmake --build build
doas cmake --install build
```

![](https://ass.technicalsuwako.moe/2024-04-01-205103_1280x800_scrot.png)

## FreeBSDは？
FreeBSDの場合は、「pkg」で簡単にインストール出来ます。

```sh
doas pkg install monero-cli btop
```

![](https://ass.technicalsuwako.moe/2024-04-01-205146_1920x1080_scrot.png)

## NetBSDは？
残念ながら、Moneroウォレットもbtopもコンパイル出来ませんでした・・・

## CRUXは？
両方とも「suwaports」というコレクションに含まれています。

```sh
doas su
cd /etc/ports
wget https://076.moe/repo/crux/suwaports.httpup
ports -u
cd /usr/ports/suwaports/monero
pkgmk -d
pkgadd monero#0.18.3.1-1.pkg.tar.gz
cd ../btop
pkgmk -d
pkgadd btop#1.2.13-1.pkg.tar.gz
```

![](https://ass.technicalsuwako.moe/2024-04-01-210229_1440x900_scrot.png)

以上
