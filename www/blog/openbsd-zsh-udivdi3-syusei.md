title: 【OpenBSD】更新後「zsh: undefined symbol '_udivdi3'」というエラーの修正する方法
author: 凛
date: 2023-04-19
category: server,unix
----
OpenBSD 7.3は公開されましたので、直ぐ全てのサーバー（64-bit）を更新しました。\
それは問題無しで出来ました。\
でも、ThinkPad T43（32-bit）で下記のエラーが発生しました：

```
ssh 192.168.0.123
Last login: Tue Apr 18 22:32:51 2023
OpenBSD 7.3 (GENERIC) #660: Sat Mar 25 11:17:44 MDT 2023

Welcome to OpenBSD: The proactively secure Unix-like operating system.

Please use the sendbug(1) utility to report bugs in the system.
Before reporting a bug, please try to reproduce it with the latest
version of the code.  With bug reports, please try to ensure that
enough information to reproduce the problem is enclosed, and if a
known fix for it exists, include that as well.

Cannot open X display!
xmodmap:  unable to open display ''
-zsh:-zsh: undefined symbol '__udivdi3'
ld.so: -zsh: lazy binding failed!
Connection to 192.168.0.123 closed.
```

これのせいで、ノートでもttyでログイン出来なくなったり、端末を開かなくなった。\
１週間後、やっと修正する方法を見つけました。

まずは再起動して、起動画面で「boot -s」を入力して下さい。

```
Using drive 0, partition 3.
Loading…
probing : pc0 apm pci mem[632K 1533M a20=on]
disk: fd0 hd0+
>> OpenBSD/i386 BOOT 3.44
boot> boot -s

Enter pathname of shell or RETURN for sh: sh
```

それでシングルユーザーモードに起動します。\
しかし、シングルユーザーモードは読み取り専用モードですので、まずはディスクをマウントするのは必要となります。\
それ後で、ルートユーザーのシェルはshに交換しましょう。

```sh
mount -a
chsh -s /bin/sh root
reboot
```

今はいつでも通りに起動して、CTRL+Alt+F2を押して、rootアカウントにログインして下さい。\
パッケージを更新しましょう。

```sh
pkg_add -ui
```

その後でzshに戻してはOKです。

```sh
chsh -s /usr/local/bin/zsh root
exit
```

CTRL+Alt+F5を押したら、GUI系ログイン画面に帰ります。\
ここから普通にパソコンを使えます。

以上
