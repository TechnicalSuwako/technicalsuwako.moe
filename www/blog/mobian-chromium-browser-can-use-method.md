title: 【Mobian】Chromiumブラウザを使える方
author: 凜
date: 2021-02-19
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
