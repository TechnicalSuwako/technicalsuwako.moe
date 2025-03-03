title: 【Linux】Debian 11からDevuan 4に交換方法
author: 凛
date: 2022-11-11
category: unix
----
Debianはバージョン8まで良かったと思います。\
あたしも最近（バージョン11）まで使いましたが、systemdを使うのは危険ですので、Devuanに変更した方が良いです。

## Devuanって何？
DevuanはDebianと殆ど同じディストリビューションですが、
「systemd」を使わなくて、下記にあるINITシステムの１つを使っています。
[SysV](https://sysv.com/)（デフォルト）\
[OpenRC](https://wiki.gentoo.org/wiki/Project:OpenRC)\
[runit](http://smarden.org/runit/)\
[sinit](http://core.suckless.org/sinit/)\
[s6](http://skarnet.org/software/s6/)\
66-devuan\
[shepherd](https://www.gnu.org/software/shepherd/)

## systemdを使ってないのはDevuanだけなの？

いいえ。\
下記のディストリビューションも他のINITシステムを使っています。\
[antiX](https://antixlinux.com/about/#) (sysvinit、runit)\
[Artix](https://artixlinux.org/index.html) (openrc、runit、s6)\
[Dragora](http://dragora.org/en/index.html) (sysvinit + perp)\
[Gentoo](https://www.gentoo.org/) (openrc)\
[Guix](https://guix.gnu.org/) (shepherd)\
[Hyperbola](https://www.hyperbola.info/) (openrc)\
[KNOPPIX](http://www.knopper.net/knoppix/index-en.html) (knoppix-autoconfig)\
[MX Linux](https://mxlinux.org/wiki/system/systemd/index.html) (sysvinit)\
[Obarun](https://web.obarun.org/) (s6)\
[Parabola](https://www.parabola.nu/) (openrc、sysvinit、s6)\
[PCLinuxOS](http://www.pclinuxos.com/) (sysvinit)\
[Slackware](http://www.slackware.com/) (sysvinit)\
[Stali](https://sta.li/) (sinit)\
[Void Linux](https://voidlinux.org/) (runit)

Linux以外、BSDは全部systemdを使っていません。\
[FreeBSD](https://www.freebsd.org/)\
[OpenBSD](http://www.openbsd.org/)\
[NetBSD](https://www.netbsd.org/)\
[DragonFly BSD](https://www.dragonflybsd.org/)\
[GhostBSD](https://www.ghostbsd.org/index.html)

## 交換しましょう！！

### レポジトリーの変更

```sh
mv /etc/apt/sources.list /etc/apt/sources.list-bckp && nvim /etc/apt/sources.list
```

```
deb http://deb.devuan.org/merged chimaera main
deb http://deb.devuan.org/merged chimaera-updates main
deb http://deb.devuan.org/merged chimaera-security main
#deb http://deb.devuan.org/merged chimaera-backports main
```

### Devuanのキーリングをインストールして、更新して、SysVをインストールして、再起動する

エラーが出たら、心配しないで下さい。\
今回だけは大丈夫だわー♡

```sh
apt update --allow-insecure-repositories
apt install devuan-keyring --allow-unauthenticated
apt update
apt upgrade
apt install eudev sysvinit-core
apt -f install
reboot
```

### Devuanをようこそ！！

でも、まだ終わりません。\
`neofetch`がインストール済みの場合、DebianじゃなくてDevuanが出てきますが、まだsystemdのパッケージの全部を削除するのは必要です。

```sh
apt dist-upgrade
apt purge systemd libnss-systemd
apt autoremove --purge
apt autoclean
```

交換完了！！

以上
