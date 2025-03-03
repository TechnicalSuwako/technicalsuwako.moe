title: 【デジタル自主】匿名者に必須なブラウザ拡張機能
author: 凛
date: 2022-09-18
category: privacy,security
----
## 2023年09月15日の更新
[chromiumのベースの中に、DRMが含められていますたも、Iridiumかungoogled-chromiumをもう勧め出来なくなりました。](https://gigazine.net/news/20230801-wei-free-software-foundation)

[この記事を見たら、また政府に我々のプライバシーを減らされると考えました。](https://news.yahoo.co.jp/articles/fc11049e57c3c2cd1b8b8d8da37ef7025e86b1a3)\
ですから、ネット上匿名化はもっと大切になりました。\
今回はブラウザ及び必須な拡張機能について話しております。

## おすすめブラウザ

プライバシーといえば、一番おすすめはLynx、Dillo、及びNetsurfですが、拡張機能がありません。\
そうして、たくさんウエブサイトが機能していません。\
あたしのサイトは大丈夫ですが、例えばヤフーとかをアクセス出来ません。\
二番おそそめはPaleMoonです。\
でも、開発者はTorが大嫌くて、CloudFlareが大好きの奴ですので、あんま勧めれません。

[FireFoxを使ったら、LibreWolfを勧めます。](https://librewolf.net/)\
普通FireFoxと普通Chromeが危険です！！

## LibreWolfのインストールする方法

### Crux

まずは「suwaports」のコレクションをダウンロードして下さい。

```sh
su
cd /etc/ports
wget https://076.moe/repo/crux/suwaports.httpup
echo "prtdir /usr/ports/suwaports" >> /etc/prt-get.conf

ports -u
prt-get depinst librewolf
```

### Artix

まずは「universe」のレポジトリを「/etc/pacman.conf」ファイルに追加して下さい。

```sh
echo "
[universe]
Server = https://universe.artixlinux.org/$arch
Server = https://mirror1.artixlinux.org/universe/$arch
Server = https://mirror.pascalpuffke.de/artix-universe/$arch
Server = https://artixlinux.qontinuum.space/artixlinux/universe/os/$arch
Server = https://mirror1.cl.netactuate.com/artix/universe/$arch
Server = https://ftp.crifo.org/artix-universe/" | sudo tee -a /etc/pacman.conf > /dev/null

sudo pacman -S librewolf
```

### OpenBSD

```sh
curl -O https://pkg.weird.cafe/pub/OpenBSD/weird-pkg.pub
doas mv weird-pkg.pub /etc/signify/

cat ~/.xsession
export PKG_PATH="$(cat /etc/installurl)/$(uname -r)/packages-stable/$(uname -p)/:$(cat /etc/installurl)/$(uname -r)/packages/
$(uname -p)/:https://pkg.weird.cafe/pub/OpenBSD/$(uname -r)/packages/$(uname -p)/"
export MOZ_ACCELERATED=1
export MOZ_WEBRENDER=1

cwm

doas pkg_add librewolf
```

### Devuan

```sh
distro=$(if echo " bullseye focal impish jammy uma una " | grep -q " $(lsb_release -sc) "; then echo $(lsb_release -sc); else echo focal; fi)

wget -O- https://deb.librewolf.net/keyring.gpg | sudo gpg --dearmor -o /usr/share/keyrings/librewolf.gpg

sudo tee /etc/apt/sources.list.d/librewolf.sources << EOF > /dev/null
Types: deb
URIs: https://deb.librewolf.net
Suites: $distro
Components: main
Architectures: amd64
Signed-By: /usr/share/keyrings/librewolf.gpg
EOF

sudo apt update

sudo apt install librewolf -y
```
## 日本語に設定する方法

[![](https://ass.technicalsuwako.moe/Screenshot_20220917_212415.png)](https://ass.technicalsuwako.moe/Screenshot_20220917_212415.png)\
[![](https://ass.technicalsuwako.moe/Screenshot_20220917_220329.png)](https://ass.technicalsuwako.moe/Screenshot_20220917_220329.png)\
[![](https://ass.technicalsuwako.moe/Screenshot_20220917_220405.png)](https://ass.technicalsuwako.moe/Screenshot_20220917_220405.png)\
[![](https://ass.technicalsuwako.moe/Screenshot_20220917_220432.png)](https://ass.technicalsuwako.moe/Screenshot_20220917_220432.png)\
[![](https://ass.technicalsuwako.moe/Screenshot_20220917_220656.png)](https://ass.technicalsuwako.moe/Screenshot_20220917_220656.png)\
[![](https://ass.technicalsuwako.moe/Screenshot_20220917_220730.png)](https://ass.technicalsuwako.moe/Screenshot_20220917_220730.png)

## おすすめブラウザの設定

「一般」で：
* デジタル著作権管理 (DRM) コンテンツ → オフ
* 自動スクロール機能を使用する → オフ
* スムーズスクロール機能を使用する → オフ

「検索」で：
* 既定の検索エンジン → searx.be（設定出来るには、まずはsearx.beにアクセスすると、アドレスバーで右クリックしたら、「"Searx Belgium"を追加」をクリックして下さい）。

「プライバシーとセキュリティ」で：
* ウェブサイトに “Do Not Track” 信号を送り、追跡されたくないことを知らせます。 → 常に送る
* 住所を自動入力する → オフ
* クレジットカード情報を自動入力する → オフ

「LibreWolf」で：
* Enable Firefox Sync → オフ
* Enable IPv6 → オフ
* Fingerprinting → Enable ResistFingerprinting → オン
* Fingerprinting → Enable letterboxing → オフ
* Fingerprinting → Silently block canvas access requests → オン
* Fingerprinting → Enable WebGL → オフ
* Security → Enforce OCSP hard-fail → オフ
* Security → Enable Google Safe Browsing → オフ

「about:config」で：
* browser.safebrowsing.provider.mozilla.updateURL → （かっこ）
* security.ssl.enable_ocsp_stapling → false
* security.OCSP.enabled → 0
* security.OCSP.require → false
* media.peerconnection.enabled → false
* privacy.firstparty.isolate → true
* privacy.trackingprotection.enabled → true
* geo.enabled → false
* media.navigator.enabled → false
* network.dns.disablePrefetch → true
* webgl.disabled → true
* dom.event.clipboardevents.enabled → false
* media.eme.enabled → false

### ungoogled-chromium

「自動入力」で：
* パスワード → パスワードを保存できるようにする → オフ
* パスワード → 自動ログイン → オフ
* お支払い方法 → お支払い方法の保存と入力 → オフ
* お支払い方法 → お支払い方法を保存しているかどうかの確認サイトに許可する → オフ
* 住所やそのほかの情報 → 住所の保存と入力 → オフ

「プライバシーとセキュリティ」で：
* Google の設定 → 同期と Google サービス →  検索語句や URL をオートコンプリートする→ オフ
* Google の設定 → 同期と Google サービス →  検索とブラウジングを改善する→ オフ
* Cookie と他のサイトデータ → 全般設定 → サードパーティの Cookie をブロックする
* Cookie と他のサイトデータ → 閲覧トラフィックと一緒に「トラッキング拒否」リクエストを送信する → オン
* セキュリティ → セーフ ブラウジング → 保護なし（推奨されません）
* セキュリティ → セキュア DNS を使用する → オフ
* 検索エンジン → 検索エンジンの管理 → 既定の検索エンジン → 追加：検索エンジン ＝ Searx Belgium、キーワード ＝ searx.be、URL（%s=検索語句） ＝ https://searx.be/search?q=%s
* 検索エンジン → 検索エンジンの管理 → 既定の検索エンジンで、「Searx Belgium」以外、全部削除して下さい。

## 必須の拡張機能

新しいブラウザでネットにアクセスする前、まずは絶対に下記の拡張をインストールと設定して下さい：

### LibreWolf
[uBlock Origin ([LibreWolf](https://addons.mozilla.org/ja/firefox/addon/ublock-origin/)
[uMatrix ([LibreWolf](https://addons.mozilla.org/ja/firefox/addon/umatrix/)
[LibRedirect ([LibreWolf](https://addons.mozilla.org/ja/firefox/addon/libredirect/)
[Decentraleyes ([LibreWolf](https://addons.mozilla.org/ja/firefox/addon/decentraleyes/)
[FoxyProxy Standard ([LibreWolf](https://addons.mozilla.org/ja/firefox/addon/foxyproxy-standard/)

## あれば良かった

* Vim Vixen ([LibreWolf](https://addons.mozilla.org/ja/firefox/addon/vim-vixen/))

## uBlock Originの設定

大きく見るには、画像をクリックして下さい。

[![](https://ass.technicalsuwako.moe/Screenshot_20220917_232519.png)](https://ass.technicalsuwako.moe/Screenshot_20220917_232519.png)\
[![](https://ass.technicalsuwako.moe/Screenshot_20220917_232552.png)](https://ass.technicalsuwako.moe/Screenshot_20220917_232552.png)\
[![](https://ass.technicalsuwako.moe/Screenshot_20220917_232634.png)](https://ass.technicalsuwako.moe/Screenshot_20220917_232634.png)\
[![](https://ass.technicalsuwako.moe/Screenshot_20220917_232651.png)](https://ass.technicalsuwako.moe/Screenshot_20220917_232651.png)

## uMatrixの設定

yahoo.co.jpにアクセスして、uMatrixのアイコンをクリックして、「www.yahoo.co.jp」が書いた部分の右側でクリックして下さい。\
「すべて」は赤色にして、「1st party」と「1st party」の「Frame」は緑色にして、「Script」、「XMR」、と「Frame」は赤色にして、「CSS」と「画像」は緑色にして下さい。\
保存するには、ロックアイコンをクリックして下さい。

その感じ：\
[![](https://ass.technicalsuwako.moe/Screenshot_20220917_234021.png)](https://ass.technicalsuwako.moe/Screenshot_20220917_234021.png)

## LibRedirectの設定

PeerTubeとMaps以外、全部は「Enable」はオンにして下さい。\
選択されたインスタンスはそのままは良いです。

## FoxyProxy Standardの設定

[【デジタル自主】ダークネットの解説　第１部：Torにアクセスする方法と](/blog/darknet-1-tor-access-way.xhtml#browser-setting)\
[【デジタル自主】ダークネットの解説　第２部：I2Pにアクセスする方法で説明した通りです。](/blog/darknet-2-i2p-access-way.xhtml#browser-setting)

以上
