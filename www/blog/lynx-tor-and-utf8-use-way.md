title: 【Lynx】TorとUTF-8で使い方
author: 凜
date: 2022-03-25
category: unix,security
----
Javascriptは要りません！！\
毎月新しいパソコン・スマホを購入必要はありません！！\
ですから、コマンドライン用ブラウザを使いましょう！！

最近まで、好みなコマンドライン用ブラウザはw3mでした。\
理由は、画像を見える、及び日本語文字を表示出来る事です。\
でも、画像は見にくいです。\
そうして、lynxで日本語文字を表示出来る様に出来ましたので、lynxは第一になりました。

# インストール

まずはlynx及びtorsocksをインストールして下さい。

|          Artix Linux          |        Devuan GNU/Linux       |              Void Linux             |
| ----------------------------- | ----------------------------- | ----------------------------------- |
| pacman -S lynx proxychains-ng | apt install lynx proxychains4 | xbps-install -S lynx proxychains-ng |

|           OpenBSD           |                 Crux                |
| --------------------------- | ----------------------------------- |
| pkg_add lynx proxychains-ng | prt-get depinst lynx proxychains-ng |

[Cruxの場合は、suwaportsをご利用下さい。](https://crux.ninja/portdb/collection/suwaports/)

ところで、現在はOpenBSDを確認中ですので、後は初めてBSDについて記事を書きつもりです。（多分）

# コンフィグファイル

初めて使うする前、まずはコンフィグファイルを受け取りましょう。

```sh
mkdir ~/.config/lynx
sudo cp /etc/lynx.cfg ~/.config/lynx && sudo chown -R $(whoami):$(whoami) ~/.config/lynx
```

.zshrcファイルを編集して下さい。\
bashを使ったら、.bashrcを編集して下さい。

私はzshを使っていますので、bashを使ったら、自分で「.zshrc」は「.bashrc」に変えて下さい。

```sh
nvim ~/.zshrc
```

下記の文字を貼って下さい。

```
export LYNX_CFG=~/.config/lynx/lynx.cfg
```

「ZZ」又は「:wq」で保存して閉じて下さい。\
次：

```sh
source ~/.zshrc
```

lynxのコンフィグファイルを編集して下さい。

```sh
nvim ~/.config/lynx/lynx.cfg
```

「/CHARACTER_SET」で検索して、下記に変更して下さい。

```
CHARACTER_SET:utf-8
```

「/ASSUME_LOCAL_CHARSET」で検索して、下記に変更して下さい。

```
ASSUME_LOCAL_CHARSET:utf-8
```

「/PREFERRED_LANGUAGE」で検索して、下記に変更して下さい。

```
PREFERRED_LANGUAGE:ja
```

「ZZ」又は「:wq」で保存して閉じて下さい。

# Tor通して実行して

```sh
torsocks lynx 6qiatzlijtqo6giwvuhex5zgg3czzwrq5g6yick3stnn4xekw26zf7qd.onion
```

[![](https://ass.technicalsuwako.moe/Screenshot_20220325_092848.png)](https://ass.technicalsuwako.moe/Screenshot_20220325_092848.png)

左はqutebrowserで、右はlynxです。

以上
