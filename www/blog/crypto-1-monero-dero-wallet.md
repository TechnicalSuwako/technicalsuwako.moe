title: 【仮想通貨】第１部～MoneroとDeroウォレットを設置する方法
author: 凜
date: 2024-03-30
category: crypto
----
## 仮想通貨とは？
仮想通貨は、日本円や米ドル、ユーロなどの政府管理通貨に対する代替通貨です。\
注意すべきは、仮想通貨のうち99%が詐欺であるため、信頼してはなりません。\
あたしは特にMonero（XMR）、Dero（DERO）、Bitcoin（BTC）、Litecoin（LTC）をお勧めしますが、その中でもMoneroが最もお勧めです。\
なぜなら、既に確立されており、最高のプライバシー、最低の手数料、最高の分散型であり、多くのオンラインショップで既に受け入れられているからです。\
[また、あたしは貴方らの寄付もMoneroで受け付けています！](/monero.xhtml)

特にエロゲーおよびエロ漫画クリエイターが支払いプロセッサー、更には銀行から資金の流れを抑制される様になっている今、並行経済でのMoneroとDeroの使用が益々重要になっていると考えられます。\
そして、今からそれらについて学ぶ方が遅過ぎるよりも良いと考えられます。

## MoneroとDeroとは？
MoneroとDeroはプライバシー通貨であり、BitcoinやEtheriumとは異なり、誰もが貴方の完全な取引履歴を見る事が出来る、つまりクレジットカードよりも悪い、状況ではなく、MoneroとDeroは共に追跡不可能であり、従ってキーを持っている人だけが特定の取引を見る事が出来、ウォレットの所有者だけが全体の履歴を見る事が出来ます。\
基本的に、MoneroはBitcoinのプライバシーバージョンであり、DeroはEtheriumのプライバシーバージョンです。\
特に興味深いのは、DeroがEtheriumと同様にスマートコントラクトを持っているが、Etheriumとは異なり、実際にはセキュアである事です。\
但し、Deroを入手するのはかなり難しいですが、後でその方法を説明します。

## BitcoinとEtheriumを使わない理由
先述の通り、BitcoinとEtheriumは完全に透明です。\
Bitcoinを貯金や増やす方法として持っているのは問題ありませんが、商品を購入する為には全く適していません。\
殆どのダークネットマーケットでもBitcoinを受け入れていないのはその為です。

あたしは現在、Bitcoinを保持しているのは、間もなく行われる半減期の為ですが、Bitcoinがピークに達したらMoneroに換金するつもりです。\
MoneroとDeroの他の利点は、日本円でのMoneroとDeroの価格が遥に安定している事です。\
つまり、Amazon等の法定通貨のみを受け入れる場所に支払う必要がある場合、価値が大きく変動するリスクが遥に低くなります。\
[例えば、Coinsbeeでギフトカードを購入する事で、AmazonでMoneroで支払う事が出来ます。](https://www.coinsbee.com/jp/Amazon-bitcoin)\
あたしは彼らにスポンサーされていませんが、以前に彼らのサービスを何度も使用した事があるので、信頼する事を出来ます。

## ウォレットの選択
あたし達はここではUNIX系OSの大ファンですので、最も多くのプラットフォームをサポートする物を使用するのが最善です。\
これ自体が既に大きな問題です！\
macOSやLinux、Windows（UNIX系ではないけど）のユーザーは、選択肢が豊富ですが、FreeBSDの場合、僅かしか選択肢がなく、OpenBSDの場合は全くありません。\
現在、あたしはFreeBSDを使用しているので、両通貨のCLIウォレット及び特にFreeBSD向けのFeather Walletの手順を示します。

### Monero CLI
これは最も簡単な方法で、FreeBSDではリポジトリからインストールできます。
```sh
doas pkg install monero-cli
```

CRUXでは、最初に`suwaports`コレクションを追加し、次の何れかのコマンドを使用します：
```sh
doas prt-get depinst monero
```

又は：
```sh
cd /usr/ports/suwaports/monero
doas pkgmk -d
doas pkgadd monero#0.18.3.2-1.pkg.tar.gz
```

より一貫性のある手順として、Monero Projectから直接事前にコンパイルされたバイナリパッケージをダウンロードするだけです。\
Linuxの場合：
```sh
mkdir -p ~/.local/bin && cd ~/.local/bin
wget https://downloads.getmonero.org/cli/monero-linux-x64-v0.18.3.2.tar.bz2
bsdtar -xfv monero-linux-x64-v0.18.3.2.tar.bz2
mv monero-x86_64-linux-gnu-v0.18.3.2/monero* .
rm -rf monero-x86_64-linux-gnu-v0.18.3.2/
```

FreeBSDの場合：
```sh
mkdir -p ~/.local/bin && cd ~/.local/bin
wget https://downloads.getmonero.org/cli/monero-freebsd-x64-v0.18.3.2.tar.bz2
tar xfv monero-freebsd-x64-v0.18.3.2.tar.bz2
mv monero-x86_64-unknown-freebsd-v0.18.3.2/monero* .
rm -rf monero-x86_64-unknown-freebsd-v0.18.3.2/
```

### FeatherWallet
Linuxでは、FeatherWalletの公式ウェブサイトからダウンロードしてそのまま使用出来ます。\
[一般ネット](https://featherwallet.org/download/)\
[Tor](http://featherdvtpi7ckdbkb2yxjfwx3oyvr3xjz3oo4rszylfzjdg6pbm3id.onion/download/)\
[I2P](http://rwzulgcql2y3n6os2jhmhg6un2m33rylazfnzhf56likav47aylq.b32.i2p/download/)

FreeBSDの場合、ソースからダウンロードする必要があります。
```sh
doas pkg install qt5 qt6-base libsodium libzip libqrencode unbound cmake boost-libs hidapi openssl lua54-luaexpat libunwind protobuf pkgconf vulkan-headers doxygen

mkdir -p ~/.local/src && cd ~/.local/src
git clone https://github.com/feather-wallet/feather.git
cd feather
git submodule update --init --recursive --progress
mkdir build && cd build
cmake -DSTACK_TRACE:BOOL=OFF -DDCHECK_UPDATES=OFF -DDONATE_BEG=OFF -DUSE_DEVICE_TREZOR=OFF -DWITH_SCANNER=OFF -DWITH_PLUGIN_REDDIT=OFF -DWITH_PLUGIN_LOCALMONERO=OFF -DWITH_PLUGIN_REVUO=OFF -DWITH_PLUGIN_BOUNTIES=OFF -DWITH_PLUGIN_CROWDFUNDING=OFF -DWITH_PLUGIN_TICKERS=OFF -DWITH_PLUGIN_XMRIG=OFF -DWITH_PLUGIN_EXCHANGE=OFF -DWITH_PLUGIN_LOCALMONERO=OFF -DPLATFORM_INSTALLER=OFF ..
cmake --build . -j $(nproc

doas mv bin/feather /usr/local/bin
```

### Dero CLI
Deroウォレットの場合、LinuxではEngram GUIウォレットを使用出来ますが、FreeBSDでは使用出来ません。\
従って、あたし達は両方で動作する物のみを望んでいるので、代わりにCLIウォレットを使用します。

Linuxの場合：
```sh
mkdir -p ~/.local/bin && cd ~/.local/bin
wget https://github.com/deroproject/derohe/releases/latest/download/dero_linux_amd64.tar.gz
tar zxfv dero_linux_amd64.tar.gz
rm -rf dero_linux_amd64/Start.md
mv dero_linux_amd64/* .
rm -rf dero_linux_amd64.tar.gz
```

FreeBSDの場合：
```sh
mkdir -p ~/.local/bin && cd ~/.local/bin
wget https://github.com/deroproject/derohe/releases/latest/download/dero_freebsd_amd64.tar.gz
tar zxfv dero_freebsd_amd64.tar.gz
rm -rf dero_freebsd_amd64/Start.md
mv dero_freebsd_amd64/* .
rm -rf dero_freebsd_amd64.tar.gz
```

## ディーモンの起動
フルブロックチェーンをダウンロードするので、1 TiB以上の別のSSD又はNVMeを使用する事を強くお勧めします。\
あたしは独自のNASを使用していますので、ZSHを使用していると仮定して、エイリアスを追加します：
```
export XDG_CONFIG_HOME="$HOME/.config"
export XDG_CACHE_HOME="$HOME/.cache"
export XDG_BIN_HOME="$HOME/.local/bin"
export XDG_DATA_HOME="$HOME/.local/share"
export XDG_SRC_HOME="$HOME/.local/src"
export WALLETB_HOME="/mnt/nfs/wallets"
export PATH=~/.local/bin:$PATH
...
alias monerod="monerod --data-dir $WALLETB_HOME/bitmonero"
alias derod="derod --data-dir=$WALLETB_HOME/dero"
```

また、ディレクトリを作成します：
```sh
mkdir -p $WALLETB_HOME/{bitmonero,dero/mainnet}
```

## Moneroを購入する方法
日本からMoneroを購入する事は不可能の様ですが、可能です。\
最も簡単な方法は、取引所からLitecoinを購入し、一時的なLitecoinウォレットを作成し、そこに送金し、ChangeNOWを使用してLTCからXMRにスワップする事です。\
[ChangeNOW](https://changenow.io/ja)\
再度、あたしはChangeNOWに因って スポンサーされている訳ではなく、関連していませんが、このサービスを沢山使用した事があり、それが信頼出来ます。\

他の方法はBisqやLocalMoneroを使用する事ですが、あたしは以前使用した事がないので、その動作方法はわかりません。\
[但し、チャノさんにはBisqに関する動画があります。](https://peertube.anon-kenkai.com/w/oQCQ91fznejiMq2nEKdmup)

勿論、Moneroをマイニングする事も出来ますが、それについては第2部で説明します。

## Deroを購入する方法
Deroを購入するのはかなり難しいです。\
それを取得する唯一の方法は、Moneroを取得する方法と同様にLitecoinを購入し、その後TradeOgreを使用してDeroを購入する事です。\
[TradeOgre（警告：ClownFlareを使用）](https://tradeogre.com/exchange/DERO-LTC)

Moneroと同様に、勿論Deroもマイニングする事が出来ます。

## 実行する方法
Monero又はDeroウォレットを使用するには、別途デーモンを実行する必要があります。\
新しいターミナルウィンドウを開き、「monerod」をMoneroの場合、「derod」をDeroの場合に実行します。\
これにより、全体のブロックチェーンがローカルPCに同期されますので、完了までに最大1週間かかる場合があります。

完了したら、デーモンがまだ実行されている状態で、別のターミナルウィンドウを開き、「monero-wallet-cli」をMoneroの場合、「dero-wallet-cli」をDeroの場合に入力します。

![](https://ass.technicalsuwako.moe/monero-cli-wallet.png)

![](https://ass.technicalsuwako.moe/dero-cli-wallet.png)

第2部ではマイニングについて説明し、第3部ではトランザクションの作成方法について説明します。

以上
