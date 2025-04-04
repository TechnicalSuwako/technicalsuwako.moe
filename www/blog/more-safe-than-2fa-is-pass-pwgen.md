title: 【Linux】2FAより安全？SimPas！
uuid: cdc08f3a-23df-44bb-a73f-0dcbfb5db496
author: 諏訪子
date: 2022-06-21 00:00:00
category: security,self promotion
----
2FAを使いますか？\
そうして、本当に安全だと思いますか？\
貴方のパスワードはすべてのウエブサイトで「114514」か「oboeyasui123」等だったら、勿論2FAを使った方は良いですね。\
でも、全部のウエブサイトで覚えにくくて全然違うパスワードを使ったら、2FAと意味がありません。\
多分その場合はOTP以外、2FAは不安全です。\
ショートメッセージ、メールトークン等はプレインテキストで届きますので、貴方に届く前に罪人は読める可能性が高いです。\
スマホアプリを使ったら、AppleやGoolag（又はフェイクブック）に簡単に読まれます。\
やっぱり、2FAでデジタル監視は可能です。

# もっと安全の方法の紹介

2FAよりバカみたいに強いパスワードだけの方が安全です。\
各ウエブサイトで違うパスワードを使ったら、もっと安全になります。

# おすすめ点

最強のパスワードは：
* 64文字以上
* 小文字（qwertyuiopasdfghjklzxcvbnm）を含める
* 大文字（QWERTYUIOPASDFGHJKLZXCVBNM）を含める
* 英数字（1234567890）を含める
* 特殊文字（!"#$%&'()-^=~|@{[}]+;*:,<.>\\_）を含める
* ２回同じパスワードを使うのは禁止

# パスワードマネージャーの紹介
## 作成
[まずは強いパスワードマネージャー出来るソフトをダウンロードしてインストールしましょう！！](https://076.moe/repo/bin/sp)

おすすめ強さはデフォルトですので、`sp -g`だけで十分です。

```sh
# sp -g
mnRMw,-p2OSU!>NdV8RLJ4p?d.4nlrHBpL1hu2:8,BRohpU'^NrsQ*MgEw,I:$B2
```

沢山日本産ソフトでまだ特殊文字を使えませんので、しょうがないですが、その場合 `pwgen -g 10 risk` で行いますね…

```sh
# sp -g 20 risk
Eca2NNIJLUPJMfO5V6J8
```

# 保存
勿論最強のパスワードは覚えにくいですので、パスワード管理ツールを使うのはおすすめです。\
パスワードを保存するには、おすすめパターンは「ドメイン名.TLD/ユーザ名」です。

```sh
# sp -a technicalsuwako.moe/idioticcirno
パスワード: （`sp -g`で作成したパスワードを入力して下さい）
パスワード（確認用）: （もう一回同じパスワードを入力して下さい）
#
```

パスワードを受け取るには：
```sh
# sp -s technicalsuwako.moe/idioticcirno
ohp4ey7Phe$in4Rie(s9 （例のパスワードです）
#
```

# 勧め：複数デバイスに同期を取る
複数デバイスがあれば（例えば、パソコン、ノートパソコン、及びLinuxスマホを持つ場合）、パスワードの同期は勧めます。\
まずはすべてのデバイスでrsyncをインストールして、sshサービスを有効にして下さい。\
後は：

```sh
rsync -rtvzP ~/.gnupg ユーザ名@デバイスのIPアドレス:~
rsync -rtvzP ~/.local/share/sp ユーザ名@デバイスのIPアドレス:~
```

同じユーザ名の場合、「ユーザ名@」を書かなくてもOKです。

以上
