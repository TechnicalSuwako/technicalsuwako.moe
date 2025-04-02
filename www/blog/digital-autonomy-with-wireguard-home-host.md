title: 【デジタル自主】自宅サーバーからWireGuardを使ったホスティング方法
uuid: b4bfa3f3-f53b-4f41-8f75-11619e2346f0
author: 諏訪子
date: 2023-07-03 00:00:00
category: server,security
----
WireGuardは非常に便利ですね。\
自宅からウェブサイトをホスティングしたり、どこからでも自宅のサーバーやパソコンにアクセスしたりといった事が可能です。\
今回はウェブホスティングの方法についてお伝えします。

## 材料
食べ物じゃないけど（ジョーダンダヨー）、必要な物は下記で御座います。

* 好みのVPSホスティング会社から最も安いVPS（日本のOpenBSDサーバーはオススメ、ConoHaまたはVultrは良い）
* 自宅にあるサーバー（1台でも複数台でも可）
* 高速光回線インターネット接続

静的IPは不要です。\
動的IPでも問題がありません。

OS等は何でも良いですが、この記事ではVPSはConoHaのOpenBSDで、自宅サーバーは2台のThinkCentreとOpenBSD、及びNECとFreeBSDを使用しています。\
また、どこへでも持ち運べるThinkPad（Artix）というノートパソコンも用いています。\
FreeBSDでPeerTubeをインストールし、URLロリはOpenBSDで行い、さまざまな静的なホームページはOpenBSDで実行しています。\
インターネット接続サービスは何でも良いですが、高速光回線インターネット接続が推奨されます。

## WireGuardのインストール

|           OpenBSD            |              FreeBSD             |               Artix (runitの場合)              |
| ---------------------------- | -------------------------------- | ---------------------------------------------- |
| doas pkg_add wireguard-tools | sudo pkg install wireguard-tools | sudo pacman -S wireguard-tools wireguard-runit |

Artixの場合、インストール後に再起動が必要な場合があります。\
インストールしたlinux-kernelのバージョンが現在実行中のバージョンと異なると、WireGuardが起動出来なくなります。

## WireGuardの設定
### VPS側

```sh
doas su
mkdir /etc/wireguard
chmod 600 /etc/wireguard
cd /etc/wireguard
wg genkey | tee private.key | wg pubkey > public.key

nvim wg0.conf
```

```
[Interface]
Address = 192.168.10.1/24
PrivateKey = (VPSのprivate.keyの内容)
ListenPort = 51820

[Peer]
PublicKey = (ThinkPadのpublic.keyの内容)
PreSharedKey = (ThinkPadのpreshared.keyの内容)
AllowedIPs = 192.168.10.100/32
PersistentKeepalive = 25

[Peer]
PublicKey = (ThinkCentreのpublic.keyの内容)
PreSharedKey = (ThinkCentreのpreshared.keyの内容)
AllowedIPs = 192.168.10.101/32
PersistentKeepalive = 25

[Peer]
PublicKey = (NECのpublic.keyの内容)
PreSharedKey = (NECのpreshared.keyの内容)
AllowedIPs = 192.168.10.102/32
PersistentKeepalive = 25
```

```sh
nvim /etc/pf.conf
```

```
set skip on lo
exsrv1 = (VPSのIPアドレス)
insrv1 = 192.168.10.101
insrv2 = 192.168.10.102

block return
pass

pass in on wg0
pass in inet proto udp from any to any port 51820

# PeerTube
pass in inet proto udp from any to $insrv2 port 9000
pass in on egress proto tcp from any to $insrv2 port {1935, 1936} rdr-to $insrv2

# URLロリ
pass in inet proto udp from any to $insrv1 port 9910

# Gemini
pass in  on egress proto tcp from any to $insrv1 port { 1965 } rdr-to $insrv1

# HTML
pass out on egress inet from (wg0:network) nat-to (vio0:0)

# SSHはWireGuardネットワーク内のみ許可する
pass in on wg0 proto tcp from 192.168.10.0/24 to any port 22
block in on egress proto tcp from any to any port 22
...
```

```sh
pfctl -f /etc/pf.conf
```

最高レベルのセキュリティを保つ為に：

```sh
nvim /etc/ssh/sshd_config
```

```
...
AllowUsers (貴方のユーザー名)@192.168.10.0/24
PermitRootLogin no
AuthorizedKeysFile .ssh/authorized_keys
PasswordAuthentication no
...
```

```sh
exit
ssh-keygen -t ed25519
(最後までそのままEnterキーを押して)
cat ~/.ssh/id_ed25519.pub
```

出力内容をコピーし、各サーバーとノートパソコンの「~/.ssh/authorized_keys」ファイルに貼り付けて下さい。\
次に、上記のステップを各サーバー及びノートパソコンで実行し、その結果をVPSの「/.ssh/authorized_keys」ファイルに貼り付けて下さい。

```sh
doas rcctl restart sshd
wg-quick up wg0
```

### 自宅サーバーとノートパソコンの設定

各デバイスの設定は基本的に同じです。

```sh
doas su
mkdir /etc/wireguard
chmod 600 /etc/wireguard
cd /etc/wireguard
wg genkey | tee private.key | wg pubkey > public.key
wg genpsk > preshared.key

nvim wg0.conf
```

```
[Interface]
PrivateKey = (現在のデバイスのprivate.keyの内容)
Address = 192.168.10.10x/24

[Peer]
PublicKey = (VPSのpublic.keyの内容)
PreSharedKey = (現在のデバイスのpreshared.keyの内容)
Endpoint = (VPSの公開IPアドレス):51820
AllowedIPs = 192.168.10.0/24
PersistentKeepalive = 25
```

Address部分の「x」は、各サーバーで設定した通りです。\
この記事では、Artix＝0、OpenBSD＝1、FreeBSD＝2となります。

#### OpenBSDサーバー

```sh
cd
wget https://lab.abiscuola.org/gmnxd/tarball/v1.2.0/gmnxd-v1.2.0.tar.gz
tar zxfv gmnxd-v1.2.0.tar.gz
cd gmnxd-v1.2.0/src
make
make install
cd
useradd -g '=uid' -L daemon -s /sbin/nologin  -c 'Gmnxd user' -d /var/gemini _gmnxd
chown -R _gmnxd:_gmnxd /var/gemini
nvim /etc/inetd.conf
```

```
0.0.0.0:11965 stream  tcp     nowait  _gmnxd  /usr/local/libexec/gmnxd        gmnxd
```

```sh
rcctl enable inetd
rcctl start inetd
nvim /etc/pf.conf
```

```
set skip on lo

block return
pass

# HTTP
pass in inet proto tcp from any to (self) port {80, 443}

# URLロリ
pass in inet proto tcp from any to (self) port 9910

# Gemini
pass in inet proto tcp from any to (self) port 11965
...
anchor "relayd/*"
```

```sh
pfctl -f /etc/pf.conf
wg-quick up wg0
mkdir -p /var/www/htdocs/{076,minmi}.moe/www
mkdir -p /var/gemini/076.moe
echo "わーい" >> /var/www/htdocs/{076,minmi}.moe/www/index.html
echo "わーい" >> /var/gemini/076.moe/index.gmi
nvim /etc/httpd.conf
```

```
eth_addr=*
wg0_addr=192.168.10.103

## 076.moe
server "076.moe" {
  listen on $wg0_addr port 8080
  root "/htdocs/076.moe/www"
  directory index "index.html"
  location "/repo/*" {
    directory auto index
  }
}

server "l3nbzyxgrkmd46nacmzf2sy6tpjrwh4iv3pgacbrbk72wcgxq5a.b32.i2p" {
  listen on $eth_addr port 8450
  root "/htdocs/076.moe/www"
  directory index "index.html"
  location "/repo/*" {
    directory auto index
  }
}

server "7dt6irsmfvbrtgn4nuah56kky6mvr472fbwwaltuxpf26qdqkdhfvnqd.onion" {
  listen on $eth_addr port 8500
  root "/htdocs/076.moe/www"
  directory index "index.html"
  location "/repo/*" {
    directory auto index
  }
}

## minmi.moe
server "minmi.moe" {
  listen on $wg0_addr port 8087
  root "/htdocs/minmi.moe/www"
  directory index "index.html"
}
```

```sh
mkdir -p /var/www/htdocs/urlo.li
cd /var/www/htdocs/urlo.li
git clone https://gitler.moe/suwako/urloli.git .
nvim Makefile
```

一部編集は必要となります。

```
# Linux、Cruxの場合は必須。他のディストリビューションはどうでも良い
#PREFIX=/usr
# FreeBSDとOpenBSD
PREFIX=/usr/local
```

```sh
rcctl enable httpd
rcctl start httpd
make
make install
nvim /etc/urloli/config.json
```

```
{
  "domain": "https://(ドメイン名)",
  "webpath": "/var/www/htdocs/urlo.li"
}
```

#### FreeBSDサーバー
公式ガイドに従って、nginxとcertbotをインストールが必要とされていますが、今回の場合は不要です。

```sh
wg-quick up wg0
pkg install -y sudo bash wget git python pkgconf postgresql13-server postgresql13-contrib redis openssl node npm yarn ffmpeg unzip
visudo
```

```
%wheel ALL=(ALL) ALL
```

```sh
sysrc postgresql_enable="YES"
sysrc redis_enable="YES"
sysrc nginx_enable="YES"

sudo pw useradd -n peertube -d /var/www/peertube -s /usr/local/bin/bash -m
sudo passwd peertube
cd /var/www/peertube
sudo -u postgres createuser -P peertube
sudo -u postgres createdb -O peertube -E UTF8 -T template0 peertube_prod
sudo -u postgres psql -c "CREATE EXTENSION pg_trgm;" peertube_prod
sudo -u postgres psql -c "CREATE EXTENSION unaccent;" peertube_prod
VERSION=$(curl -s https://api.github.com/repos/chocobozzz/peertube/releases/latest | grep tag_name | cut -d '"' -f 4) && echo "Latest Peertube version is $VERSION"
cd /var/www/peertube
sudo -u peertube mkdir config storage versions
sudo -u peertube chmod 750 config/
cd /var/www/peertube/versions
sudo -u peertube wget -q "https://github.com/Chocobozzz/PeerTube/releases/download/${VERSION}/peertube-${VERSION}.zip"
sudo -u peertube unzip -q peertube-${VERSION}.zip && sudo -u peertube rm peertube-${VERSION}.zip
cd /var/www/peertube
sudo -u peertube ln -s versions/peertube-${VERSION} ./peertube-latest
cd ./peertube-latest && sudo -H -u peertube yarn install --production --pure-lockfile
cd /var/www/peertube
sudo -u peertube cp peertube-latest/config/default.yaml config/default.yaml
cd /var/www/peertube
sudo -u peertube cp peertube-latest/config/production.yaml.example config/production.yaml
nvim config/production.yaml
```

```
listen:
  hostname: '0.0.0.0'
  port: 9000

webserver:
  https: true
  hostname: '(ドメイン名)'
  port: 443
...
```

```sh
sudo install -m 0555 /var/www/peertube/peertube-latest/support/freebsd/peertube /usr/local/etc/rc.d/
sudo sysrc peertube_enable="YES"
sudo service peertube start
```

#### Artixノートパソコン

```sh
wg-quick up wg0
```

### 再度、VPSの設定
```sh
nvim /etc/acme-client.conf
```

```
authority letsencrypt {
  api url "https://acme-v02.api.letsencrypt.org/directory"
  account key "/etc/acme/letsencrypt-privkey.pem"
}

domain 076.moe {
  alternative names { www.076.moe }
  domain key "/etc/ssl/private/076.moe.key"
  domain full chain certificate "/etc/ssl/076.moe.crt"
  sign with letsencrypt
}

domain video.076.moe {
  domain key "/etc/ssl/private/video.076.moe.key"
  domain full chain certificate "/etc/ssl/video.076.moe.crt"
  sign with letsencrypt
}

domain urlo.li {
  domain key "/etc/ssl/private/urlo.li.key"
  domain full chain certificate "/etc/ssl/urlo.li.crt"
  sign with letsencrypt
}

domain minmi.moe {
  domain key "/etc/ssl/private/minmi.moe.key"
  domain full chain certificate "/etc/ssl/minmi.moe.crt"
  sign with letsencrypt
}
```

```sh
nvim /etc/httpd.conf
```

```
server "default" {
  listen on * port 80
  root "/htdocs"

  location "/.well-known/acme-challenge/*" {
    root "/acme"
    request strip 2
  }
}
```

```sh
nvim /etc/relayd.conf
```

```
relayd_addr="0.0.0.0"
insrv1_addr="192.168.10.101"
insrv2_addr="192.168.10.102"

table <home> { $insrv1_addr }
table <urloli> { $insrv1_addr }
table <minmi> { $insrv1_addr }

table <video> { $insrv2_addr }

http protocol reverse {
  tcp { nodelay, sack }
  tls ciphers "TLS_AES_256_GCM_SHA384:TLS_CHACHA20_POLY1305_SHA256:TLS_AES_128_GCM_SHA256"
  tls keypair "076.moe"
  tls keypair "video.076.moe"
  tls keypair "urlo.li"
  tls keypair "minmi.moe"

  match request header append "X-Forwarded-For" value "$REMOTE_ADDR"
  match request header append "X-Forwarded-Port" value "$REMOTE_PORT"

  match response header set "Referrer-Policy" value "same-origin"
  match response header set "X-Frame-Options" value "deny"
  match response header set "X-XSS-Protection" value "1; mode=block"
  match response header set "X-Content-Type-Options" value "nosniff"
  match response header set "Strict-Transport-Security" value "max-age=31536000; includeSubDomains; preload"
  match response header set "Cache-Control" value "max-age=86400"

  pass request quick header "Host" value "076.moe" forward to <home>
  pass request quick header "Host" value "www.076.moe" forward to <home>
  pass request quick header "Host" value "video.076.moe" forward to <video>
  pass request quick header "Host" value "urlo.li" forward to <urloli>
  pass request quick header "Host" value "minmi.moe" forward to <minmi>
  return error
  pass
}

http protocol reverse80 {
  match request header append "X-Forwarded-For" value "$REMOTE_ADDR"
  match request header append "X-Forwarded-Port" value "$REMOTE_PORT"

  match response header set "Referrer-Policy" value "same-origin"
  match response header set "X-Frame-Options" value "deny"
  match response header set "X-XSS-Protection" value "1; mode=block"
  match response header set "X-Content-Type-Options" value "nosniff"
  match response header set "Strict-Transport-Security" value "max-age=31536000; includeSubDomains; preload"
  match response header set "Cache-Control" value "max-age=86400"

  pass request quick header "Host" value "076.moe" forward to <home>
  pass request quick header "Host" value "www.076.moe" forward to <home>
  pass request quick header "Host" value "video.076.moe" forward to <video>
  pass request quick header "Host" value "urlo.li" forward to <urloli>
  pass request quick header "Host" value "minmi.moe" forward to <minmi>
  return error
  pass
}

protocol gemini {
  tcp { nodelay, sack }
  tls ciphers "TLS_AES_256_GCM_SHA384:TLS_CHACHA20_POLY1305_SHA256:TLS_AES_128_GCM_SHA256"
  tls keypair "076.moe"
}

relay www_tls {
  listen on $relayd_addr port 443 tls
  protocol reverse

  forward to <video> port 9000 check tcp

  forward to <home> port 8080 check tcp
  forward to <urloli> port 9910 check tcp
  forward to <minmi> port 8087 check tcp
}

relay www_http {
  listen on $relayd_addr port 80
  protocol reverse80

  forward to <video> port 9000 check tcp

  forward to <home> port 8080 check tcp
  forward to <urloli> port 9910 check tcp
  forward to <minmi> port 8087 check tcp
}

relay gemini {
  listen on $relayd_addr port 1965 tls
  protocol gemini

  forward to <home> check tcp port 11965
}
```

```sh
rcctl enable httpd
rcctl start httpd

acme-client -v 076.moe
acme-client -v video.076.moe
acme-client -v urlo.li
acme-client -v minmi.moe

rcctl enable relayd
rcctl start relayd
```

SSL証明書を更新するには、下記のコマンドをVPS上で実行して下さい：

```sh
rcctl stop relayd
acme-client -v 076.moe
acme-client -v video.076.moe
acme-client -v urlo.li
acme-client -v minmi.moe
rcctl start relayd
```

![](https://ass.technicalsuwako.moe/kanrinya.jpg)

ねぇー！簡単でしょー！

以上
