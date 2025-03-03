title: 【SSH】バックスペース、矢印等の変な表示の修正方法
author: 凜
date: 2022-06-22
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
