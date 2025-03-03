title: 【ハード】IBM ThinkPad T43にSSDを入る方法
author: 凛
date: 2023-02-03
category: unix
----
秋葉原のジャンク通りでやっとIBM ThinkPad T43のめちゃくちゃ古いノートパソコンを見つけました。\
勿論SSDなしで、金額は6,800円でした。

![](https://ass.technicalsuwako.moe/t43-ssd/qlWBD5gMQqqU8QAN2gEryQ.jpg)

でも、問題は一つがあります：SSDを入れないってことです。\
T43の時代のパソコンはSATAじゃなくて、IDEのハードディスクを使いましたから。\
SSDはSATAに変わった時後で作られましたので、IDE系SSDが存在しません。\
そうして、IDE系HDDを見つけるのは珍しいです。

でも、一つの方法がありますわ。\
mSATA→IDE交換アダプターを使って安くて簡単にSSDを使えます。

使ったやつは下記のです。
[KRHK-MSATA/I9](https://www.amazon.co.jp/gp/product/B00EUXS7WG)\
[Zheino M3 (256 GB)](https://www.amazon.co.jp/gp/product/B07GZFGD2B)\
買った時、金額は6,297円でした。

![](https://ass.technicalsuwako.moe/t43-ssd/PO3316IiQyiTgVLQWSesFA.jpg)\
![](https://ass.technicalsuwako.moe/t43-ssd/Lp9-nNNCTM6j4ZN4rwJg2A.jpg)\
ちなみに、SSDはサイズの比較ためです。\
それ以外特に関係がありません。

![](https://ass.technicalsuwako.moe/t43-ssd/ynKP6u4tRoqAkCExe2RkZg.jpg)\
![](https://ass.technicalsuwako.moe/t43-ssd/kRG-xU1uSlqvk6yhP3S1aQ.jpg)

次の問題は、普通に入るのは無理でした。\
しょうがないですが、完全に分解しないと、SSDを入れないらしい。（写真を取りませんでしたが）

でも、分解して、SSDを入って、再組み立ったら、ノートパソコンがやっとSSDを読められました！！\
![](https://ass.technicalsuwako.moe/t43-ssd/12GTLfI9RZiXZu-5Do1X3Q.jpg)

次はOSのインストールですね。\
メモリは2GB以上で無理ですので、古いOSを利用しか出来ない感じですね。\
でも、ネットで古いOSを使うのは凄く危険かしら。\
そうして、プロセサーは32-bitですので、新しいOSの選びは少ないのです。\
ですから、OpenBSDを入りました。

![](https://ass.technicalsuwako.moe/t43-ssd/IBlRnwW_SpqusPmb7zKrTQ.jpg)\
![](https://ass.technicalsuwako.moe/t43-ssd/JZfynYBUSkaaziwsj7ML1A.jpg)\
![](https://ass.technicalsuwako.moe/t43-ssd/D6YGneiMQRqoAX50IG7o6A.jpg)\
![](https://ass.technicalsuwako.moe/t43-ssd/Zc8inzNGQlKKPrOePtsRYw.jpg)\
![](https://ass.technicalsuwako.moe/t43-ssd/3HVQMjXPQviCwY-9dkugzA.jpg)\
![](https://ass.technicalsuwako.moe/t43-ssd/ba1d1zq7TNyHSxJ6nslMOQ.jpg)

出たー出た！！出ったーーー\
![](https://ass.technicalsuwako.moe/t43-ssd/pwpPRGKJS169B3GNYXOnNw.jpg)\
![](https://ass.technicalsuwako.moe/t43-ssd/Z5toPySHRu2HvaS2LzkKtg.jpg)

以上
