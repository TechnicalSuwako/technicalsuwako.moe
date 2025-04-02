title: 【Tor】すべてのソフトウェアをいつでもTorを通じて使用する方法
uuid: 74f056b3-c36c-4061-84ae-c48521f2e9c4
author: 諏訪子
date: 2022-08-03 00:00:00
category: privacy
----
[4ヶ月前、LynxをTorで使用する方法についての記事を書きました。](/blog/lynx-tor-and-utf8-use-way.xhtml)\
今回は、すべてのソフトをいつでもTorを通じて使用する方法を説明します。

## まずはtorとproxychains-ngをインストールして下さい

|          Artix Linux         |       Devuan GNU/Linux       |                     Gentoo                    |             Fedora             |
| ---------------------------- | ---------------------------- | --------------------------------------------- | ------------------------------ |
| pacman -S tor proxychains-ng | apt install tor proxychains4 | emerge --ask net-vpn/tor net-misc/proxychains | dnf install tor proxychains-ng |

|             FreeBSD            |           OpenBSD          |             Void Linux             |                 Crux               |
| ------------------------------ | -------------------------- | ---------------------------------- | ---------------------------------- |
| pkg install tor proxychains-ng | pkg_add tor proxychains-ng | xbps-install -S tor proxychains-ng | prt-get depinst tor proxychains-ng |

[Cruxを使用している場合は、初めに「suwaports」というポーツコレクションを追加して下さい。](https://crux.ninja/portdb/collection/suwaports/)

## proxychains-ngのコンフィグファイルを変更する

`/etc/proxychains.conf`（FreeBSDの場合は`/usr/local/etc/proxychains.conf`、Devuanの場合は`/etc/proxychains4.conf`）を開いて、`socks4 127.0.0.1 9050`を`socks5 127.0.0.1 9050`に変更して下さい。\
この行列がない場合は、`socks5 127.0.0.1 9050`を追加して下さい。

## コマンドライン用ソフト

.zshrcを編集して下さい。\
Bashを使用している場合は、「.bashrc」ファイルを編集します。

```sh
nvim ~/.zshrc
```

以下のようなエイリアスを追加して下さい。\
例：

```sh
alias tut="proxychains-ng tut"
alias neomutt="proxychains-ng neomutt"
alias lynx="proxychains-ng lynx -lss ~/.config/lynx/lynx.lss"
alias pacman="proxychains-ng pacman"
alias cointop="proxychains-ng cointop"
alias newsboat="proxychains-ng newsboat"
```

zshを再起動して下さい。

```sh
source ~/.zshrc
```

これで、上記のソフトを起動すると自動的にTorを通じて使用するようになります。
例えば、sudo pacman -Syyuを実行すると、Torを通じてパッケージが更新されます。

## GUIソフト

まず、.desktopファイルをローカルディレクトリにコピーして下さい。\
例：

```sh
sudo cp /usr/share/applications/io.github.Hexchat.desktop ~/.local/share/applications
sudo cp /usr/share/applications/wine.desktop ~/.local/share/applications
sudo cp /usr/share/applications/dillo.desktop ~/.local/share/applications

sudo chown -R $(whoami):$(whoami) ~/.local/share/applications
```

例えば、`wine.desktop`ファイルを編集しましょう！

```sh
nvim ~/.local/share/applications/wine.desktop
```

`Exec=wine start /unix %f`を見つけて、`Exec=proxychains -q wine start /unix %f`に変更して下さい。

Exec部分でコマンド前に`proxychains -q `をコマンドの前に追加すると、メニューからソフトを選択して起動する時にもTorを通じて使用する事が出来ます。

以上
