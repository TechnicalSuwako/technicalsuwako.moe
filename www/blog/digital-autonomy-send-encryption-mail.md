title: 【デジタル自主】GnuPGでメールを暗号化する方法
uuid: 856616d5-2f72-4c99-9351-96c9baee617a
author: 諏訪子
date: 2022-04-05 00:00:00
category: security
----
メールを送ると言えば、非常に不安全とイメージが思い出しますね。\
でも、暗号化は可能で知っていますか？\
今回はメールの暗号化する方法を教えくれます。

# 何故暗号するのは大切？

メールはプレインテキストとして送りますので、暗号化しないと、誰かWireShark等でメールを読めますので、中間者攻撃（MitM）に非常に弱いです。\
特に政治家、諜報機関、大企業、ISP等は貴方のメールを読んでいます。\
結局、暗号化はデジタル自主の基本的な対策です。

# メールクライエントを選びましょう

良いメールソフトを使うのは非常に大切ですが、これを考える方は少ないです。\
Linuxを使ったら、Claws Mailを勧めます。\
他のOSの場合はわかりませんので、自分で検索して下さい。\
でも、フリーとオープンソースのメールソフトで、公式Gitレポジトリーで最後コミットは１週間以内があれば、良いソフトだと思います。

# ウェブメールを使っていますが…

辞めた方が良いですよ。

# GPGかPGP…違いは何？

PGP（Pretty Good Protection）はNorton社に作られた暗号化ソフトでした。\
でも、有料ソフトですので、GnuPG（GPG）はPGPのFOSS版ですので、無料で自由に使えます。

# パブリック鍵とプライベート鍵の違い

パブリックキー（公開鍵）は他人と共有出来る鍵です。\
[例えば、私の鍵は連絡ページで受け取れます。](/contact.xhtml)\
SNSプロフィール、自分のブログ、動画SNSのチャンネル等で貼っても良いです。\
初めてメールを送ったら、この鍵で暗号化すると、メールの添付で自分のパブリックキーを入れて下さい。

プライベートキー（秘密鍵）は秘密です。\
これを共有するのは駄目です。\
パソコンかスマホで秘密鍵が異なったら、復号する事が無理となります。

# Claws Mailの設置

まずはPGPプラグインをインストールする事が必要です。\
設定→プラグイン→ロード

![](https://ass.technicalsuwako.moe/Screenshot_20220405_225553.png)\
![](https://ass.technicalsuwako.moe/Screenshot_20220405_225644.png)\
![](https://ass.technicalsuwako.moe/Screenshot_20220405_225724.png)

次は設定→アカウントを編集→Edit→プライバシーです。\
「デフォルトのプライバシーシステム」は「PGP MIME」に設定して下さい。\
そうして、下記のスクショ通り、チェックボックスを有効にして下さい。

![](https://ass.technicalsuwako.moe/Screenshot_20220405_230133.png)

# 暗号化したメールを送信方法

![](https://ass.technicalsuwako.moe/Screenshot_20220405_231054.png)\
![](https://ass.technicalsuwako.moe/Screenshot_20220405_231124.png)

同じメールに複数キーがあれば、下記の画面も出ますが、１つだけの場合はスキップされます。\
そうして、相手の鍵は複数があれば、同じ様な画面が来ます。\
![](https://ass.technicalsuwako.moe/Screenshot_20220405_231158.png)

所で、暗号化したメールで鍵アイコンが付いています。\
![](https://ass.technicalsuwako.moe/Screenshot_20220405_231307.png)

コワス様は諏訪子様の鍵で暗号しましたので、そのままメールを読めます。\
そうして、返事する時コワス様は既に諏訪子様の鍵を持っていますので、添付に入るのは不要です。\
でも、返事する前に、まずはGPGプログラムに保存する事が必要です。

![](https://ass.technicalsuwako.moe/Screenshot_20220405_232042.png)\
![](https://ass.technicalsuwako.moe/Screenshot_20220405_232428.png)

# 新しい鍵の創作

```sh
gpg --generate-key
```

```
gpg (GnuPG) 2.2.32; Copyright (C) 2021 Free Software Foundation, Inc.
This is free software: you are free to change and redistribute it.
There is NO WARRANTY, to the extent permitted by law.

注意: 全機能の鍵生成には "gpg --full-generate-key" を使います。

GnuPGはあなたの鍵を識別するためにユーザIDを構成する必要があります。

本名: （入力）
電子メール・アドレス: （入力）
次のユーザIDを選択しました:
    "山田太郎 <yamada@tar.ou>"

名前(N)、電子メール(E)の変更、またはOK(O)か終了(Q)? （入力）
```

# PGP鍵一覧

## パブリック鍵

```sh
gpg -k | less
```

## プライベート鍵

```sh
gpg -K | less
```

# 自分のパブリック鍵の取り出し

```sh
gpg --export --armor フィンガープリントID > ファイルパス/ファイル名.asc
```

# 他人のパブリック鍵の取り込み

```sh
gpg --import ファイルパス/ファイル名.asc
```

# データの暗号化

```sh
gpg -e ファイル名
```

# データの復号

```sh
gpg -d ファイル名
```

以上
