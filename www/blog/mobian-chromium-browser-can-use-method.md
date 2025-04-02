title: 【Mobian】Chromiumブラウザを使える方
uuid: 22a9cda1-72ae-4bd5-b5fb-93e349a48e5e
author: 諏訪子
date: 2021-02-19 00:00:00
category: smartphone,unix
----
やっとモービアンでChromiumを使えます！！\
それで、LINEも使えます！！

使えるには：\
「`/etc/chromium/local.conf`」という新しいファイルを作って、下記のものを貼って下さい：

```sh
unset GDK_BACKEND
```

保存して、Chromiumを実行してみて：\
![](https://ass.technicalsuwako.moe/21-02-1915-09-582727.jpg)\
![](https://ass.technicalsuwako.moe/21-02-1915-10-372728.jpg)

以上
