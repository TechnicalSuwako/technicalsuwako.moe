title: 【SSH】バックスペース、矢印等の変な表示の修正方法
uuid: 443c74a1-3c5d-45ab-9f0a-27f65b61ad80
author: 諏訪子
date: 2022-06-22 00:00:00
category: server,unix
----
昨日は古いMacBookProでFreeBSDをインストールしてみました。\
でも、バックスペースを押したら、スペースとして表示されました。\
そうして、矢印を押したら、全然おかしい表示が出ました。\
CentOS及びVoid Linuxで同じ問題があります。\
Debian、Devuan、Ubuntu、Manjaro、Arch Linux、及びArtix Linuxでその問題がありません。

# シェルのコンフィグファイルの編集

あたしはzshを使っていますので、 `~/.zshrc` ファイルを編集します。\
違うシェルを使ったら（例：bash、ash、csh等）、このシェルのコンフィグファイルを編集して下さい。\
bashの場合は `~/.bashrc` となります。

```sh
# nvim ~/.zshrc
```

```
...
export TERM=xterm
...
```

ZZで保存してファイルを閉じて下さい。\
CTRL+A+Dでログアウトして、再度ログインして下さい。

![](https://ass.technicalsuwako.moe/Peek202206220436.gif)

以上
