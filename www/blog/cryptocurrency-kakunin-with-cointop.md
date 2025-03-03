title: 【仮想通貨】cointopで確認する方法
author: 凜
date: 2022-03-18
category: crypto
----
２日前、東京でビットコインキャッシュ（BCH）のミートアップに参加しました。\
今回は赤坂のハンガリーレストランにありましたので、食べ物は凄く美味しかったです（でも、高い）。\
うまケロ！！（PinePhoneで撮った）\
![](https://ass.technicalsuwako.moe/0054b7e40cb0d79dafd4bc27ae66fdd191b365367099d340638fe93ebdc96af2.jpg) ![](https://ass.technicalsuwako.moe/c30d8df9aecbf7f118e17d08416f653b5a9d5ac4e688866074f9b11125695332.jpg)

<video src="https://ass.technicalsuwako.moe/majiyabakune.mp4" controls="controls" style="max-height: 400px;"></video>

このミートアップで、「スマホで、どのウォレットを使うの？」と聞いた方が居ました。\
BCHの場合は、Electron-Cashを使っていますね。\
![](https://ass.technicalsuwako.moe/elec1.png) ![](https://ass.technicalsuwako.moe/elec2.png)

みんなはExodus Walletというソフトを使っています。\
画面を見たら、cointopが思い浮かびました。

あたしがオススメ複数仮想通貨を比べられるプログラムはcointopです。\
インストールするには、Archの場合：

```sh
yay -S cointop
```

それ以外：

```sh
go get github.com/cointop-sh/cointop
```

[cointop.shでも試せます。](https://cointop.sh/)

メイン画面で、全ての通貨が表示されています。\
![](https://ass.technicalsuwako.moe/Screenshot_20220317_231702.png)

小文字「ｆ」を押したら、お気に入りリストに保存します。\
大文字「Ｆ」を押したら、お気に入り画面に変えます。\
![](https://ass.technicalsuwako.moe/Screenshot_20220317_232028.png)

「C」を押したら、比べる用政府通貨を変えられます。\
例えば、日本円は「C」→「g」。\
米ドルは「C」→「x」。\
ユーロは「C」→「9」。\
等\
![](https://ass.technicalsuwako.moe/Screenshot_20220317_234129.png)

「e」を押したら、今持っている仮想通貨の値を変更出来ます。\
「0」又は空の場合、ポートフォリオから消えます。\
![](https://ass.technicalsuwako.moe/Screenshot_20220317_232511.png)

「P」を押したら、ポートフォリオリストに変えます。\
![](https://ass.technicalsuwako.moe/Screenshot_20220317_233633.png)\
（XMRとLBC以外、全ては嘘数字ですので、XMRとLBCの値だけを隠します。）

「Q」を押したら、トップページに戻ります。\
「/」を押したら、仮想通貨を探します（vimと同じくね）。\
トップページで「Q」を押したら、プログラムを終了します。

以上
