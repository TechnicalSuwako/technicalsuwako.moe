title: 【OpenBSD】xmodmapを使って英数（Caps Lock）キーをスーパーキーに変更する方法
uuid: 296a2b68-1534-4d95-aa1d-76e9ad602b7f
author: 諏訪子
date: 2023-04-01 00:00:00
category: unix
----
[２ヶ月前はThinkPad T43を購入しましたが、覚えますか？](/blog/thinkpad-t43-ssd-install-way.xhtml)\
写真を確認したら、スーパーキーがないのですね。\
それでDWMを使うのは無理となります。\
でも、英数キーを全然使わないから、これはスーパーキーになったら、使えるようになりますわね。

`~/.Xmodmap`ファイルを創作して、下記のコマンドを実行すると、変わります。

```sh
clear Lock
add Mod4 = Eisu_toggle
```

`xmodmap ~/.Xmodmap`を実行したら、英数（又はCaps Lock）＋Dを押すと、dmenuが出てきます！！

次は、下記を貼って下さい。\
ZSHを使ったら、`~/.zshrc`ですね。

```
if [ ! "$KBINIT" = 'OK' ]; then
  xmodmap ~/.Xmodmap
  export KBINIT="OK"
fi
```

以上
