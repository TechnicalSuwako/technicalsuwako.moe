title: 【デジタル自主】０７６動画ホスティングの解決策
author: 凛
date: 2023-12-11
category: server
----
PeerTubeのホスティングと言えば、「高額」というイメージが浮かびます。\
しかし、デジタル自主を理解すれば、高額な費用は必要ありません。\
あたしはストレージやトラフィックの使用量に関わらず、毎月1,200円を支払っています。

## 必要な物
* 古いパソコン３台（秋葉原でのジャンク品を選ぶ事を勧めます）
* ２枚1 TiBのSSD（勧め： [https://www.amazon.co.jp/gp/product/B0BYZFB8D6](https://www.amazon.co.jp/gp/product/B0BYZFB8D6) (LNQ100X960G-RNNNG) 残念ですが、最近はCRUCIAL MX-500のSSDが凄く高くなりましたので、もう勧めれません）
* ２枚の大きさがどうでも良いSSD
* ルータ（勧め： [https://www.amazon.co.jp/gp/product/B08MH4VLR3](https://www.amazon.co.jp/gp/product/B08MH4VLR3) (TP-Link Omada ER605) ）
* KVMスイッチ（勧め： [https://www.amazon.co.jp/gp/product/B094N5LWKZ](https://www.amazon.co.jp/gp/product/B094N5LWKZ) (MT-VIKI KVM VGAスイッチ 8入力) ）
* キーボード（勧め： [https://www.amazon.co.jp/gp/product/B08M3BQ1TS](https://www.amazon.co.jp/gp/product/B08M3BQ1TS) (ロジクール K835OWB 有線 メカニカルキーボード 青軸) ）
* VGAを対応するモニタ
* インターリンク フレッツ接続ZOOT NEXT（固定IPの為）
* （沢山サーバがあれば）ハブスイッチ（勧め： [https://www.amazon.co.jp/gp/product/B08YKSR6R8](https://www.amazon.co.jp/gp/product/B08YKSR6R8) (リンクシスLGS108-JP 8ポート) ）
* ２つのUSBメモリ（一つはFreeBSDと、一つはOpenBSD）

特にKVMスイッチが必要はありませんが、１つのモニタとキーボードを持ったら、とても便利です。\

OpenBSDを使う理由はセキュリティで、FreeBSDを使う理由はパフォーマンスです。\
一緒に完璧なコンビネーションとなります。

## USBメモリを準備して、インストールして下さい
FreeBSDとOpenBSDのイメージをダウンロードして下さい。\
[FreeBSD 14.0-RELEASE](https://download.freebsd.org/releases/amd64/amd64/ISO-IMAGES/14.0/)\
[OpenBSD 7.4](https://cdn.openbsd.org/pub/OpenBSD/7.4/amd64/install74.img)

この記事の目的の為に、USBメモリのフラッシュとFreeBSD及びOpenBSDのインストール方法を既に知っていると仮定します。

## あたしのセットアップ
あたしのセットアップは下記のイメージです。
ネットワーク以内のIPは：
* 192.168.0.1 = ルータ
* 192.168.0.104 = PeerTubeサーバ
* 192.168.0.106 = relaydサーバ
* 192.168.0.143 = NAS

![](https://ass.technicalsuwako.moe/DKp6BGXWTYu4JnBZ1biZPw.jpg)
![](https://ass.technicalsuwako.moe/4CUzz0i5QI2FoYuKotvAAQ.jpg)
![](https://ass.technicalsuwako.moe/JePPSZafQn25uCJxvFaKGw.jpg)
![](https://ass.technicalsuwako.moe/5VLgmL3DSTyoItyvOILtRw.jpg)

ちなみに、このネットワークのIPセグメントは下記のイメージです：
* 1 = ルータ
* 2〜99 = DHCP
* 100 = メインパソコン
* 101〜120 = サーバ
* 121〜130 = ノートパソコン（イーサネット）
* 131〜140 = ノートパソコン（WiFi）
* 141〜160 = ネットワーク機器（NAS、WiFiアクセスポイント、防犯カメラ等）
* 161〜180 = ゲーム機（ニンテンドースイッチ、ニンテンドー3DS等）
* 181〜200 = スマホ、タブパソコン等
* 201〜254 = 何もない

## サーバ１：relaydサーバ（OpenBSD）
このサーバでOpenBSDを使う理由はセキュリティです。\
ネットワーク外からの接続は全部このサーバに通じます。

### /etc/acme-client.conf
```
#
# $OpenBSD: acme-client.conf,v 1.4 2020/09/17 09:13:06 florian Exp $
#
authority letsencrypt {
  api url "https://acme-v02.api.letsencrypt.org/directory"
  account key "/etc/acme/letsencrypt-privkey.pem"
}

domain 076.moe {
  alternative names {
    www.076.moe,
    stopsdgs.076.moe,
    stopsmaho.076.moe,
    mitra.076.moe,
    mixi.076.moe,
    video.076.moe,
    imgproxy.076.moe
  }
  domain key "/etc/ssl/private/076.moe.key"
  domain full chain certificate "/etc/ssl/076.moe.crt"
  sign with letsencrypt
}
```

### /etc/httpd.conf（SSL証明書を受け取る為）
```
# $OpenBSD: httpd.conf,v 1.22 2020/11/04 10:34:18 denis Exp $

server "default" {
  listen on * port 80
  root "/htdocs"
  location "/.well-known/acme-challenge/*" {
    root "/acme"
    request strip 2
  }
}
```

### /etc/relayd.conf
```
# $OpenBSD: relayd.conf,v 1.5 2018/05/06 20:56:55 benno Exp $
#
# Macros
#
relayd_addr="0.0.0.0"
insrv4_addr="192.168.0.104"
router_addr="192.168.0.106"

table <video> { $insrv4_addr }

http protocol reverse {
  tcp { nodelay, sack }
  tls ciphers "TLS_AES_256_GCM_SHA384:TLS_CHACHA20_POLY1305_SHA256:TLS_AES_128_GCM_SHA256"
  tls keypair "076.moe"

  match request header append "X-Forwarded-For" value "$REMOTE_ADDR"
  match request header append "X-Forwarded-Port" value "$REMOTE_PORT"

  match response header set "X-Frame-Options" value "deny"
  match response header set "X-XSS-Protection" value "1; mode=block"
  match response header set "X-Content-Type-Options" value "nosniff"
  match response header set "Strict-Transport-Security" value "max-age=31536000; includeSubDomains; preload"
  match response header set "Permissions-Policy" value "accelerometer=()"

  pass request quick header "Host" value "video.076.moe" forward to <video>

  return error
  pass
}

relay www_tls {
  listen on $relayd_addr port 443 tls
  protocol reverse

  forward to <video> port 9000 check tcp
}

relay www_http {
  listen on $relayd_addr port 80
  protocol reverse

  forward to <video> port 9000 check tcp
}

relay pt {
  listen on $relayd_addr port 1935 tls
  protocol reverse

  forward to <video> port 19355 check tcp
}
```

ポート1935番号は生配信の為です。

```sh
rcctl enable httpd
rcctl enable relayd
rcctl start httpd

acme-client -v 076.moe

rcctl start relayd
```

## サーバ２：NAS（FreeBSD）
FreeBSDを使う理由はZFSです。\
インストールしながら、是非「ZFS」→「stripe」を選択して下さい。

まずはSSDを確認して下さい：
```sh
$ dmesg | grep ada
ada0 at ahcich0 bus 0 scbus0 target 0 lun 0
ada0: <CT1000MX500SSD1 M3CR046> ACS-3 ATA SATA 3.x device
ada0: Serial Number 〇〇
ada0: 600.000MB/s transfers (SATA 3.x, UDMA6, PIO 512bytes)
ada0: Command Queueing enabled
ada0: 953869MB (1953525168 512 byte sectors)
ada1 at ahcich1 bus 0 scbus1 target 0 lun 0
ada1: <Lexar SSD NQ100 960GB SN11873> ACS-4 ATA SATA 3.x device
ada1: Serial Number 〇〇
ada1: 600.000MB/s transfers (SATA 3.x, UDMA6, PIO 512bytes)
ada1: Command Queueing enabled
ada1: 915715MB (1875385008 512 byte sectors)
ses0: ada0,pass0 in 'Slot 00', SATA Slot: scbus0 target 0
ses0: ada1,pass1 in 'Slot 01', SATA Slot: scbus1 target 0
```

ada1を作成しましょう。
```sh
$ gpart create -s gpt ada1
$ gpart add -t freebsd-zfs -l disk1 ada1
```

zpoolを作って下さい。
```sh
$ zpool create backup ada1p1
$ zpool list
NAME     SIZE  ALLOC   FREE  CKPOINT  EXPANDSZ   FRAG    CAP  DEDUP    HEALTH  ALTROOT
backup   888G   396K   888G        -         -     0%     0%  1.00x    ONLINE  -
zroot    920G   180G   740G        -         -     0%    19%  1.00x    ONLINE  -
$ zfs create zroot/peertube
```

ada1はバックアップ用に使用しますが、具体的な方法についてはこの記事では説明しません。

peertubeユーザーとグループを創作して下さい。
```sh
$ pw groupadd peertube
$ pw useradd peertube -g peertube -s /usr/sbin/nologin -d /nonexistent -c "PeerTube User"
```

### /etc/exports
```
/zroot/peertube -maproot=peertube:peertube 192.168.0.104
```

### /etc/rc.conf
```
clear_tmp_enable="YES"
syslogd_flags="-ss"
hostname="freebsdnas"
keymap="jp.kbd"
ifconfig_re0="inet 192.168.0.143 netmask 255.255.255.0"
defaultrouter="192.168.0.1"
local_unbound_enable="YES"
sshd_enable="YES"
ntpd_enable="YES"
rsyncd_enable="YES"
moused_nondefault_enable="NO"
# Set dumpdev to "AUTO" to enable crash dumps, "NO" to disable
dumpdev="AUTO"
zfs_enable="YES"
zfs_enable="YES"
rpcbind_enable="YES"
nfs_server_enable="YES"
nfsd_flags="-u -t -n 2"
mountd_enable="YES"
```

サービスを起動して下さい。
```sh
$ service rpcbind start
$ service mountd start
$ service nfsd start
```

## サーバ３：PeerTubeサーバ（FreeBSD）
FreeBSDを使う理由は、PeerTubeの開発者がOpenBSDをサポートしていない為です。\
いつでも通りにPeerTubeをインストールして下さい（nginxの設定が不要です）。

### /etc/rc.conf
```sh
clear_tmp_enable="YES"
syslogd_flags="-ss"
hostname="peertubesrv"
keymap="jp.kbd"
ifconfig_re0="inet 192.168.0.104 netmask 255.255.255.0"
defaultrouter="192.168.0.1"
local_unbound_enable="YES"
ifconfig_re0="DHCP"
sshd_enable="YES"
# Set dumpdev to "AUTO" to enable crash dumps, "NO" to disable
dumpdev="AUTO"
zfs_enable="YES"
shavit_enable="YES"
postgresql_enable="YES"
redis_enable="YES"
postgresql_class="postgres"
peertube_enable="YES"
nginx_enable="NO"
nfs_server_enable="YES"
nfs_client_enable="YES"
```

```sh
$ cd /var/www/peertube
$ rm -rf storage
$ mkdir storage
$ mount -t nfs 192.168.0.143:/zroot/peertube /var/www/peertube/storage
$ df -h               
Filesystem                       Size    Used   Avail Capacity  Mounted on
zroot/ROOT/default                92G     63G     29G    68%    /
devfs                            1.0K      0B    1.0K     0%    /dev
zroot/tmp                         29G    3.1M     29G     0%    /tmp
zroot/usr/home                    36G    7.2G     29G    20%    /usr/home
zroot                             29G     96K     29G     0%    /zroot
zroot/var/audit                   29G     96K     29G     0%    /var/audit
zroot/var/log                     29G     90M     29G     0%    /var/log
zroot/usr/ports                   31G    2.0G     29G     6%    /usr/ports
zroot/var/crash                   29G     96K     29G     0%    /var/crash
zroot/var/tmp                     29G    136K     29G     0%    /var/tmp
zroot/var/mail                    29G    444K     29G     0%    /var/mail
zroot/usr/src                     29G     96K     29G     0%    /usr/src
192.168.0.143:/zroot/peertube    815G    103G    712G    13%    /var/www/peertube/storage
```

以上
