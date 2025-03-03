title: 【デジタル自主】ダークネットの解説　第２部：I2Pにアクセスする方法
author: 凜
date: 2022-07-10
category: security
----
こちらはダークネットの使い方の解説シリーズです。\
ダークネットはデジタル自主のインターネットの未来ですので、早くわかった方が良いですね。♡

一般ネットよりダークネットの方がメリットは：
* 中央管理がない
* ウエブサイトを中止させる（キャンセルカルチャー等）のは無理
* 実際に検閲するのは無理
* 個人情報（電話番号、本名、住所等）無しで匿名ですべてのサービスを使える
* 「ネット上での侮辱」の法律（実は表現の自由に反対の法律、日本国憲法第二十一条によるこの法律は憲法違反だ）の心配がない
* [イラストの検閲（モザイク等。また、日本国憲法第二十一条による検閲法律は憲法違反だは不要だ](/blog/dejital-jisyu-censorship-law-is-illegal.xhtml)）

デメリットは：
* ドメイン名はハッシュとして創作されていますので、見つけにくいです。ですから、他のダークネットのウエブページで知り合いになるのは必須です。

I2Pに接続しましょう！

## I2Pのインストール

### Arch、Artix、Manjaro、Alter Linuxの場合

```sh
sudo pacman -S i2pd
```

### Debian、Devuan、Ubuntu、Linux Mint、MX Linuxの場合

```sh
sudo apt install i2pd
```

### FreeBSD、GhostBSD、NomadBSDの場合

```sh
doas pkg install i2pd
```

### OpenBSDの場合

```sh
doas pkg_add i2pd
```

### Void Linuxの場合

```sh
sudo xbps-install -S i2pd
```

### Gentooの場合

```sh
sudo emerge --ask net-vpn/i2pd
```

### Fedoraの場合

```sh
sudo yum install i2pd
```

### Windows、macOS、iOS、ChromeOS、Androidの場合

大至急Linux又はBSDに更新して下さい。\
Windows、macOS、iOS、ChromeOS、又はAndroidでダークネットにアクセスするのは危険です。

## コンフィグファイルの編集

Linuxの場合：
```sh
sudo nvim /etc/i2pd/i2pd.conf
```

BSDの場合：
```sh
doas nvim /usr/local/etc/i2pd/i2pd.conf
```

「i2cp」の部分は、下記で交換して下さい：

```
[i2cp]
## Uncomment and set to 'true' to enable I2CP protocol
enabled = true
## Address and port service will listen on
address = 127.0.0.1
port = 7654
```

「upnp」の部分は、下記で交換して下さい：

```
[upnp]
## Enable or disable UPnP: automatic port forwarding (enabled by default in WINDOWS, ANDROID)
enabled = true
## Name i2pd appears in UPnP forwardings list (default = I2Pd)
name = I2Pd
```

保存して閉じて下さい。

## ブラウザの設定

### qutebrowser

コンフィグファイルを変更して：

```sh
nvim ~/.config/qutebrowser/config.py
```

下記の行列を貼って下さい：

```
config.bind('x1', 'set content.proxy system')
config.bind('x2', 'set content.proxy socks://localhost:9050/')
config.bind('x3', 'set content.proxy socks://localhost:4447/')
```

それで「x1」は一般ネット、「x2」はTor、そうして「x3」はI2Pとなります。\
[Torにアクセスする方法は第１部で説明しました。](/blog/darknet-1-tor-access-way.xhtml)

### Pale Moon

ツール→アドオン→アドオン入手の検索ボックスで、「FoxyProxy」を入力して、「FoxyProxy Basic」をインストールして下さい。\
下記の設定をコピーして下さい。
![](https://ass.technicalsuwako.moe/Screenshot_20220709_212303.png)\
![](https://ass.technicalsuwako.moe/Screenshot_20220709_212328.png)\
![](https://ass.technicalsuwako.moe/Screenshot_20220709_212344.png)

接続するには、FoxyProxyのアイコンで右クリックして、「すべてのURLでプロキシ「127.0.0.1:4447」を使用」をクリックして下さい。

### Luakit

「:proxy」を入力して、「a」を押して、「i2p socks://127.0.0.1:4447」を入力して下さい。\
接続するには、「:proxy」を入力すると、「i2p」までスクロールしたら、エンターキーを押して下さい。

### Otter Browser

ツール→環境設定→詳細設定→ネットワーク\
「プロキシ」の下で、「追加」→「プロキシを追加…」をクリックして、「一般」→「マニュアル」で、「プロトコル」は「SOCKS5」をチェックして、「サーバー」は「127.0.0.1」、と「ポート」は「4447」を入力して下さい。\
接続するには、追加したプロキシをチェックして下さい。

### ungoogled-chromium、Brave

```sh
mkdir -p ~/.local/opt/chromiumext
cd ~/.local/opt/chromiumext/
wget https://github.com/henices/Chrome-proxy-helper/archive/refs/tags/v1.3.3.tar.gz
tar zxfv v1.3.3.tar.gz
rm -rf v1.3.3.tar.gz
```

「chrome://extensions/」にアクセスして、「デベロッパーモード」を有効にして、「パッケージ化されていない拡張機能を読み込む」をクリックして下さい。\
「ディレクトリ：」で「~/.local/opt/chromiumext/Chrome-proxy-helper-1.3.3」を入力して、エンターキーを押して下さい。

「Proxy servers」で、「SOCKS PROXY:」で「127.0.0.1」を入力して、「PORT:」で「4447」を入力して、「SOCKS5」をチェックして下さい。\
そうして、「Advanced settings」で、「Proxy mode:」はそのまま「singleProxy」で良いです。

### Firefox、Librewolf

三→設定→ネットワーク設定→「接続設定…(E)」\
「手動でプロキシーを設定する(M)」を選択して、「SOCKS ホスト(C)」で「127.0.0.1」を入力して、「ポート(T)」で「4447」を入力して、「SOCKS v5(V)」をチェックして下さい。

そうして、「SOCKS v5 を使用するときは DNS もプロキシーを使用する(D)」を有効にして、「DNS over HTTPS を有効にする(B)」を無効にして下さい。

### Chrome、Edge、Opera、Safari

危険ですので、上記のブラウザに乗り換えて下さい。

次回はホスティング方法を説明します。\
続く
