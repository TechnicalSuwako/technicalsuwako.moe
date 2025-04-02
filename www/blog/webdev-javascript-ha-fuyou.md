title: 【ウエブ開発】ウエブ開発者様へ、JavaScriptは不要
uuid: 7d3f44c9-e449-43e2-9daa-16e9e0743ef5
author: 諏訪子
date: 2022-03-28 00:00:00
category: webdev,rant
----
私の建前は本音と同じですので、投稿を書いたらあんま丁寧じゃないと思いますね。\
正直に要点に触れないと、社会又はデジタルの問題が大きくなりますから。\
何で上記の事を教えるの？と思ったら、これはネットの重体の問題について記事ですので、今回は特に甘くないです。

なお、下記は本当の会社のウエブサイトを論いますが、これは責めるためじゃなくて、磨きに手伝う為です。\
[欲しければご連絡下さい](/contact.xhtml)。\
私は15年間ぐらいウエブ開発の経験がありますので、正しい開発方法でやります。

実は、ネット上90%のウエブサイトはJSは全然要らないです。\
[Ameboみたいなブログを読むにはJSは必須だったら、ウエブ開発者は正しくウエブページの作り方を全然わからないという意味です。](https://youtube.owacon.moe/watch?v=OHLp0xEYKXY)

## 株式会社インターリンク
[https://www.interlink.or.jp/service/flets/index.html]()

![](https://ass.technicalsuwako.moe/Screenshot_20220328_003758.png)\
![](https://ass.technicalsuwako.moe/Screenshot_20220328_004131.png)\
上はJavaScriptはON、下はJavaScriptはOFF

JSはONの場合、1:24分でページが読み込みました。\
何故！？\
JSを使う場所は一体何処ですか？

比べて、JSはOFFの場合、同じページは2.66秒で読み込みました。\
所で、JSはONのブラウザでプロクシーを使わなくて、JSはOFFのブラウザでTorプロクシーを使いました。\
この問題はそんなに大変です！！

読者は株式会社インターリンクの社長だったら、欲しければフリーランス契約者として修正出来ます。\
ウエブサイトはスピードアップしたら、確かにお客様数アップにされます。

## 総務省
[https://www.soumu.go.jp/senkyo/senkyo_s/data/index.html]()

![](https://ass.technicalsuwako.moe/Screenshot_20220328_010026.png)\
![](https://ass.technicalsuwako.moe/Screenshot_20220328_010616.png)\
上はJavaScriptはON、下はJavaScriptはOFF

これは政府のウエブサイトですので、確かにうまく動いていませんね。\
全部の政府に反対で、我々日本人に政府無くた方が良いと思うのに、殆ど全ての老人様は投票（選挙＝奴隷向け提案箱）したいですので、これも含めます。\
政府もフリーランス契約で募集したら迎えです。\
政府か自由市場か、どっちでもから報酬を受け取ったら良いです。

JSはONの場合、2:12分で読み込みました。「favicon.ico」含めては4:24分になりました。「000392553.gif」が読み込んだ後は結局7:36分になりました。\
JSはOFFの場合は14秒で、「000392553.gif」が読み込んだ後は結局52秒で読み込みました。

でも、アクセシビリティツールがありますので、JSは必要な点がありますが、JS無しでもアクセス出来ますので、良かったです。\
でも、JSはONの場合は本当に本当に遅すぎます。

## DÖBRÖGI Hungarian Bar & dining
[https://dobrogi.business.site/]()

![](https://ass.technicalsuwako.moe/Screenshot_20220328_011906.png)\
[最近このレストランに行きましたので、ホームページを確認します。](/blog/cryptocurrency-kakunin-with-cointop.xhtml)\
JSはONにしたらも、メニューボタン及び「お問い合わせ」リンクが動いていません。\
理由はちょっと違いますが、それも不味いです：CDNの利用。\
24件リソースのうちに、22件はグーラグから来ます。

CDNを使う事が本当に悪習です。\
プライバシーを守りたったらどう？\
CDNのサーバーは重くなったらどう？\
CDNからウイルスを受け取ったらどう？\
いつでも自分でホスティングした方が良いです！！

## CLIP STUDIOのイラスト・漫画描き方ナビ
[https://www.clipstudio.net/oekaki/archives/151661]()

![](https://ass.technicalsuwako.moe/Screenshot_20220328_013327.png)\
JSがONとOFFの違いが無いみたいですが、JS無しでも広告を見えるのは怖すぎますね。\
20秒で読み込みますが、これは沢山JPEGとPNGファイルがありますから。\
DOMだけは2~4秒で読み込みますので、悪くないです。\
確認した時、フェイクブックの「sdk.js」、グーラグの「conversation.js」と「gtm.js」、及びツイッターの「widgets.js」だけを受け取れませんでした。\
でも、受け取れなくた方が良いですので、これは良い物です。

なお、Clipboard.jsは要りません。\
コピペ機能性の無いブラウザが存在しませんので、全然不要です。

## 食べログ
[https://tabelog.com/tokyo/]()

![](https://ass.technicalsuwako.moe/Screenshot_20220328_025444.png)\
食べログは結構面白いです。\
JSはOFFにしたら、「エリアから探す」でホバーオーバーしたら、メニューが来ます。\
でも、このメニューで「市区町村から探す」をクリックしたら、少しだけページダウンにされます。\
そうして、各レストランの最初の写真以外、JS無しで読み込みません。

トップページで、「エリアから探す」部分で都市をクリックしたら、何も起こっていません。\
理由はこれです：\
![](https://ass.technicalsuwako.moe/Screenshot_20220328_030147.png)\
![](https://ass.technicalsuwako.moe/Screenshot_20220328_030251.png)\
まだわからない場合：「a href」タグは何処！？\
バカみたいに凄いデカイdivタグがありますが、リンクタグがありません…

所で、貴方はレストランのランキングサイトですので、「新型コロナウイルス拡大における対応のお願い」というプロパガンダは要りません。\
もう2022年ですよ、コロナはオワコンです！！

でも、これ好き：\
![](https://ass.technicalsuwako.moe/Screenshot_20220328_031659.png)

## Uta-Net
[https://www.uta-net.com/movie/316351/]()

![](https://ass.technicalsuwako.moe/Screenshot_20220328_014847.png)\
JSはONだったら、歌詞をコピペ出来ません。\
これは著作権の為だと思いますが、著作権はただの詐欺ですので、自由にコピペ出来る様にした方が良いです。\
又は、JSはOFFにすると、コピペを出来る様になります！！\
![](https://ass.technicalsuwako.moe/Screenshot_20220328_015043.png)\
所で、JS無しで動画を見えませんので、URLを貼って下さい。\
その場合は自分でVLCで開ける様になります。

## PeerTubeの説明書
[https://docs.joinpeertube.org/install-any-os]()

![](https://ass.technicalsuwako.moe/Screenshot_20220328_015413.png)\
一体何で説明書を読む為JSは必須ですか！？\
普通本を読む為、水着を着用しないと開けませんか？\
マジで辞めなさい…

## メルカリ
[https://jp.mercari.com/]()

![](https://ass.technicalsuwako.moe/Screenshot_20220328_020056.png)\
真っ白ページです。\
何故！？\
市場サイトですよ！！\
JavaScriptは必須だったらおかしいでしょ！！

下記は市場にJavaScriptは不要の証拠です：\
![](https://ass.technicalsuwako.moe/Screenshot_20220328_020230.png)\
ジモティーはJSが無しでもOKです。\
![](https://ass.technicalsuwako.moe/Screenshot_20220328_020423.png)\
アマゾンさえもJS無しでもOKです。\
一体何でアマゾンを使うかと思ったら、ねぇ、沢山の買いたい漫画や電子機器はアマゾンだけで買えますから。

## DeepL
[https://www.deepl.com/translator]()

![](https://ass.technicalsuwako.moe/Screenshot_20220328_022615.png)\
わからなければ、DeepLはGoolag翻訳みたいな翻訳機です。\
まずは、Linuxですので、IEを使えません。\
でも、JSはOFFだったら、UAをわかりませんので、しょうがないです。\
酷い点は、全然ポップアップを閉じれません。\
手動で「ie11Modal_backdrop」クラスを持っているDIVを削除したら、閉じれます。\
でも、JS無しで使えませんので、意味がありません。ｗｗ\
[JSの無い翻訳機が存在しないよと思ったら、これは何？](https://simplytranslate.org/)

以上
