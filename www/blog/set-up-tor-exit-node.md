title: 【Tor】出口ノードの設置方法
author: 凛
date: 2022-11-10
category: unix,server,security
----
今回はOpenBSD及びDevuanのみでの設置方法を説明すると思います。\

## サーバーについて

まずは、自宅でホスティングするのは勧めません。\
貴方のIPアドレスは出口ノードになると、Tor無しでも殆ど全ての一般ネットにアクセス出来なく成ります。\
ですから、VPSを買った方が良いと思います。

[モネロ（XMR）で払いたいから、Hostryを勧めます。](https://hostry.com/?ref=H6D3S9A5)\
[他の仮想通貨（例えば、ビットコインカッシュ（BCH））の場合、Vultrを勧めます。](https://www.vultr.com/?ref=9060114)\
[日本円の場合、ConoHaです。](https://www.conoha.jp/referral/?token=Jagdwdh2uUPVnw62cL.e7diizT5YPzYy0yNQ1riFIutuuqB5YzY-70Y)

HostryとVultrはクラウドフレアを使って、ConoHaはreCAPTCHAを使っていますので、VPNでアクセスした方が良いと思います。

Debianを使ったら、Devuanと殆ど同じコマンドを使えます。\
上記のプロバイダーで全部Debianを選べますが、Devuanを選べませんので、まずはDebian→Devuanの交換は必要と成ります。\
[交換方法はこちらまでご覧下さい。](/blog/debian-to-devuan-koukan.xhtml)

## パッケージのインストール

### OpenBSD

```sh
pkg_add tor nyx
```

### Devuan

```sh
apt install tor unbound nyx
```

## Torの設定

/etc/tor/torrcバックアップして、新しいやつを作って、下記のものをご入力下さい。

```sh
mv /etc/tor/torrc /etc/tor/torrc-BCKP && nvim /etc/tor/torrc
```

```
Log notice syslog
RunAsDaemon 1
DataDirectory /var/tor
ControlPort 9051
CookieAuthentication 1
ORPort 9001
Nickname （お好み名前）

# PGPキーがなければ
#ContactInfo 連絡先 <hogehoge アットマーク keronet ドット jp>
# PGPキーがあれば
ContactInfo 0x（PGPキー） 連絡先 <hogehoge アットマーク keronet ドット jp>

DirPort 80
DirPortFrontPage /etc/tor/tor-exit-notice.html
ExitRelay 1
User _tor

ExitPolicy accept *:*
ExitPolicy reject private:*
IPv6Exit 1
ExitPolicy accept6 *:*
ExitPolicy reject6 [FC00::]/7:*
ExitPolicy reject6 [FE80::]/10:*
ExitPolicy reject6 [2002::]/16:*
```

## Unboundの設定

OpenBSDを使ったら、unboundが既にインストール済みですが、Devuanの場合はインストールするのは必要です。

### OpenBSD

`/var/unbound/etc/unbound.conf`を編集して下さい。

```
server:
  interface: 127.0.0.1
```

↓

```
server:
  verbosity: 1
  qname-minimisation: yes
  interface: 127.0.0.1
```

### Devuan

`/etc/unbound/unbound.conf.d/root-auto-trust-anchor-file.conf`を削除して、`/etc/unbound.conf.d/torexit.conf`を創作して下さい。

```
server:
  verbosity: 1
  qname-minimisation: yes
  interface: 127.0.0.1
  interface: ::1

  access-control: 0.0.0.0/0 refuse
  access-control: 127.0.0.0/8 allow
  access-control: ::0/0 refuse
  access-control: ::1 allow

  hide-identity: yes
  hide-version: yes

  auto-trust-anchor-file: "/var/lib/unbound/root.key"
  val-log-level: 2

  aggressive-nsec: yes

remote-control:
  control-enable: yes
  control-interface: /var/run/unbound.sock
```

## resolv.conf

`/etc/resolv.conf`を編集して下さい。\
そうして、自動編集を出来ない様にするには、許可を変更して下さい。

```sh
cp /etc/resolv.conf /etc/resolv.conf-BCKP
echo nameserver 127.0.0.1 > /etc/resolv.conf
chflags schg /etc/resolv.conf
```

## サービスの再起動

### OpenBSD

```sh
rcctl enable tor
rcctl enable unbound
rcctl restart tor
rcctl restart unbound
```

### Devuan

OpenRCの場合：

```sh
rc-update tor enable
rc-update unbound enable
rc-update restart tor
rc-update restart unbound
```

runitの場合：

```sh
ln -s /etc/runit/sv/tor /run/runit/service
ln -s /etc/runit/sv/unbound /run/runit/service
sv restart tor
sv restart unbound
```

SysVの場合：

```sh
service restart tor
service restart unbound
```

systemd（Debian）の場合：

```sh
systemctl tor enable
systemctl unbound enable
systemctl tor restart
systemctl unbound restart
```

## モニタリング

うまく設置出来たか確認するには、`nyx`を実行して下さい。\
イメージは下記の感じですね。

![](https://ass.technicalsuwako.moe/Screenshot_20221110_143307.png)

ところで、アジアでのTorとI2Pネットワークを延長するには、匿名自営業０７６は複数TorとI2Pノードを設置予定です。\
[ですから、モネロで支援、または上記のVPSプロバイダーのURLを使って登録して自分でアジア地方でTorかI2Pノードを設置したら、凄く助かります。](/monero.xhtml)

以上
