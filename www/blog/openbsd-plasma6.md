title: 【OpenBSD】KDE Plasma 6 をインストールする方法
uuid: 79a7c801-f78b-4197-b887-96f6b0a167f5
author: 諏訪子
date: 2024-09-19 00:00:00
category: unix
----
注意：この記事執筆時点での最新のOpenBSDバージョンは7.5で、KDE Plasma 5のみが含まれています。\
OpenBSD 7.6ではPlasma 6が提供される為、OpenBSD 7.6の安定版リリースを待つ事が推奨されます。\
この記事では、OpenBSD 7.5から最新のスナップショットにアップグレードする必要がある為、慎重に進めて下さい。

最近、ウィンドウマネージャーに飽きてきました。\
ゲーム機向けのゲーム制作を再開したところ、任天堂がNintendo Switchのリリース以降、Wineを介してそのツールを使うのを非常に困難にしている為、Windows 10を使ってSwitchの開発キットを使用する為に別のPCを設定する必要がありました。\
Wii Uとニンテンドー3DSの時代以前にこの問題がありませんでした。\
初めてWindows 10を使った時、直ぐにKDEが懐かしく感じました。\
Windows 10はKDE Plasmaの様ですが、バグや遅延、不整合が更に多いです。

そこで、先ずVoid Linuxを動かしているゲームPCにKDE Plasma 6をインストールしました。\
次に、1台のFreeBSDノートPC（今は半分壊れた様に見える）と、1台のOpenBSDノートPC（Linuxよりも見た目が良い）にインストールし、驚いた事にとても気に入っています。

あたしは2009年にGnome 3が出た時にデスクトップ環境を使うのを辞めました。\
その後、Plasma 4や5、Gnome 3、Cinnamon、MATE、LXDE、LXQt、Xfce、Unity（Lomiri）、CDE、TDE等を短期間試しましたが、どれもGnome 2やKDE 3.5の様に良いと感じた物はありませんでした。\
MATEやTDEですら満足出来る物ではありませんでした。\
一方で、Plasma 6はとても快適なので、これをデフォルトのDEに設定しました。

## インストール手順

前述の様に、Plasma 6はOpenBSD 7.6で提供される予定で、現時点での最新バージョンは7.5です。\
その為、Plasma 6を手に入れるには、先ずスナップショット版にアップグレードする必要があります。

```sh
# doas pkg_add -ui && doas syspatch
# doas sysupgrade -s
```

システムが再起動されたら、先ずパッケージを更新する必要があります。\
リリースブランチから外れた為、パッケージの更新方法が異なります。

```sh
# doas pkg_add -D snapshot -ui
```

これからは毎回`pkg_add -D snapshot`を使用する必要があります。\
これが面倒な場合は、`.zshrc`又は`.kshrc`ファイルに`alias pkg_add="pkg_add -D snapshot"`を追加出来ます。

次に、KDE Plasma 6をインストールします。

```sh
# doas pkg_add -D snapshot kde kde-plasma kde-plasma-extras
```

これには少し時間がかかりますが、完了したらいくつかの設定を編集する必要があります。

```sh
# doas usermod -G _shutdown $(whoami)
# groups
suwako wheel _shutdown
# nvim ~/.xsession
```

```
export LANG=ja_JP.UTF8
export LC_CTYPE=ja_JP.UTF8
export LC_ALL=ja_JP.UTF8

export XDG_RUNTIME_DIR=/tmp/run/$(id -u)
if [ ! -d $XDG_RUNTIME_DIR ]; then
  mkdir -m 700 -p $XDG_RUNTIME_DIR
fi

export QT_FORCE_STDERR_LOGGING=1
export XDG_CURRENT_DESKTOP=KDE
export DESKTOP_SESSION=plasma
startplasma-x11 > ~/.startplasma-x11.log 2>&1
```

また、XenoDMが無効になっている場合は有効にし、その後再起動します。

```sh
# doas rcctl enable xenodm
# reboot
```

後はログインするだけで、KDE Plasma 6が利用出来る様になります。

![](https://ass.technicalsuwako.moe/openbsd-plasma6.png)

最初にFreeBSDでは見た目が悪いと言いましたが、証明の為にFreeBSDとLinuxのスクリーンショットも添付します。

![](https://ass.technicalsuwako.moe/freebsd-plasma6.png)

![](https://ass.technicalsuwako.moe/linux-plasma6.png)

以上
