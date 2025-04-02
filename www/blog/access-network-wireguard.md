title: 【セキュリティ】Wireguardを使って安全に自宅のネットワークをアクセスする方法
uuid: d52e40e5-d52e-4541-8058-e5eb7b34f1a7
author: 諏訪子
date: 2023-01-17 00:00:00
category: server,security
----
また会社員になったから、あんま自宅にいない状況となりました。\
セキュリティのため、私のサーバーは:80と:443以外自宅だけからアクセス出来る様に設定しました。\
でも、会社にいながらアクセス出来たら良いなぁと思いましたので、今回はWireguardで安全に自宅のネットワークをアクセスする方法を教えると思います。

ここの記事で：
* VPSはOpenBSD
* ゲートウェイはDevuan
* ノートパソコンとゲームパソコンはArtix Linux

記事の場合、「ゲートウェイ」は自宅のネットワーク内のサーバーで、VPSはネットワーク外のサーバーと意味です。

ソフトのインストールコマンド以外全部のコマンドは全部のLinuxディストリビューション及びBSD OSで同じです。\
うまく出来たら、ノートパソコン→VPS→ゲートウェイ→ゲームパソコンのログインは可能となります。\
ここの場合、Artix→OpenBSD→Devuan→Artixですね。

ノート→VPS→ゲーム（Artix→OpenBSD→Artix）も可能ですが、自宅ネットワークで複数パソコンやサーバー（あたしは１０台ぐらい）があれば、１台のゲートウェイがあった方が良いです。\
そうしてセキュリティの為、SSHとWireguardしか何も実行されていないサーバーの方が安全ですね。

# VPS

OpenBSDを使ってVPSなら、ConoHa又はVultrを勧めます。\
ConoHaのOpenBSDイメージは古いバージョンですので、まずは最新バージョンまで「pkg_add -ui」及び「sysupgrade」コマンドを実行する事が必要です。\
どっちでもで、一番安いVPSは十分です。

# 買い物

まずは専用サーバーを買ってみよっか！\
あれば、Lenovo ThinkCentre又はNECのジャンク品は一番勧めますが、結局何でも良いです。\
東京に近く住んだら、秋葉原のジャンク通りで2000~4000円で買えます。\
あとはSSDを買うのは必要ですが、小さいSSDは大丈夫です。\
ACケーブル及びイーサネットケーブルも必要です、なければキーボードとモニタも必要ですが、すでに持ってるやつを使いましょう。\
まとめて5千円でサーバーの購入が可能です。

# ゲートウェイでDevuanのインストール

いつでも通りDevuanをインストールしましょう。\
でもサーバーですので、XFCEじゃなくて、ベースインストーラーを使って下さい。

## SSHのセキュリティ対策

インストールする後、セキュリティ対策の為、下記のステップは必要です。

### VPSとゲートウェイの側

普通ユーザー作って下さい。\
注意：Devuanの場合、wheelじゃなくて、sudoです。

```sh
useradd -m （ユーザー名）
passwd （ユーザー名）
usermod -G wheel （ユーザー名）
su -l （ユーザー名）
mkdir ~/.ssh
touch ~/.ssh/authorized_keys
```

### すべての側

初めての場合、SSHキーを作成して下さい。

```sh
ssh-keygen -t ed25519
```

そのままEnterキーを押して下さい。\
パスワードを入らないで下さい。

```sh
cat ~/.ssh/id_ed25519.pub
```

出力をコピーして、
* Artixのノートパソコンの内容はOpenBSDのVPSの「~/.ssh/authorized_keys」に貼って
* OpenBSDのVPSの内容はDevuanのゲートウェイのものに貼って
* Devuanのゲートウェイの内容はArtixのゲームパソコンのものに貼って

「/etc/ssh/sshd_config」ファイルを編集して下さい。

```
...
PermitRootLogin no
...
PasswordAuthentication no
...
```

SSHサービスの再起動。

| Devuan              | Artix (runitの場合) | OpenBSD            |
|---------------------|---------------------|--------------------|
| service ssh restart | sv restart sshd     | rcctl restart sshd |

まだVPS→ゲートウェイのログインは不可能ですが、そろそろ可能となります。

# Wireguardの設置

## VPS側

じゃ、始めましょう！\
まずはパッケージをインストールしましょう。

```sh
doas pkg_add wireguard-tools
```

rootになって、Wireguardのコンフィグを作成しましょう。

```sh
doas su
mkdir /etc/wireguard
chmod 700 /etc/wireguard
cd /etc/wireguard

wg genkey | tee private.key | wg pubkey > public.key
```

## ゲートウェイ側

大体同じステップですね。

```sh
sudo apt install wireguard-tools
```

```sh
sudo su
mkdir /etc/wireguard
chmod 700 /etc/wireguard
cd /etc/wireguard

wg genkey | tee private.key | wg pubkey > public.key
vi /etc/wireguard/wg0.conf
```

```sh
[Interface]
PrivateKey = （ゲートウェイの「/etc/wireguard/private.key」の内容）
Address = 192.168.10.2/24

[Peer]
PublicKey = （VPSの「/etc/wireguard/public.key」の内容）
Endpoint = （VPSのIPアドレス）:443
AllowedIPs = 0.0.0.0/0
```

## VPS側

```
vi /etc/wireguard/wg0.conf
```

```
[Interface]
PrivateKey = （VPSの「/etc/wireguard/private.key」の内容）
ListenPort = 443

[Peer]
PublicKey = （ゲートウェイの「/etc/wireguard/public.key」の内容）
AllowedIPs = 0.0.0.0/0
PersistentKeepalive = 25
```

```sh
sysctl net.inet.ip.forwarding=1
vi /etc/sysctl.conf
```

```
net.inet.ip.forwarding=1
```

```sh
vi /etc/pf.conf
```

```
...
pass            # establish keep-state

pass in on wg0
pass in inet proto udp from any to any port 443
pass out on egress inet from (wg0:network) nat-to (vio0:0)
...
```

```sh
pfctl -f /etc/pf.conf
```

```sh
vi /etc/hostname.wg0
```

```
inet 192.168.10.1 255.255.255.0 NONE
up
!/usr/local/bin/wg setconf wg0 /etc/wireguard/wg0.conf
```

```sh
sh /etc/netstart wg0
```

## ゲートウェイ側

```sh
wg-quick up wg0
```

# 確認しましょう

お疲れ様でした！！\
じゃ、ノートパソコンは違うネットワーク（例えば、スマホのWiFiホットスポット又はスタバの無料WiFi）に接続して、ノートパソコンからVPSにログインして下さい。\
VPSからゲートウェイにログインして下さい。\
ゲートウェイからネットワーク以内のゲームパソコンにログインして下さい。\
VPS→ゲートウェイにログインするには、IPアドレスは「192.168.10.2」となります。

今から世界中でどこでもから自宅のゲームパソコンをアクセス出来ます！！

以上
