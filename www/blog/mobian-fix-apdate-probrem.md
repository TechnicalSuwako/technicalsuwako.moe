title: 【Mobian】アップデート問題の修正方法
uuid: 9b9da9f6-7061-40fd-8f97-ba870cb0e943
author: 諏訪子
date: 2020-11-29 00:00:00
category: smartphone,unix
----
注意：Mobianをアップデートする前に、絶対にSSHサーバーをインストールしておいて下さい：

```sh
sudo apt install openssh-server
sudo systemctl enable ssh
```

失敗した場合、パソコンからアクセスできます。

Linuxが大好きですので、Pinephoneというスマホを買ってみました。\
PinephoneにあるOSはAndroidやiOSじゃなく、本物Linuxです。

DebianよりManjaroの方が好きなんですけれでも、スマホ向けManjaroは日本語に設定できませんが、Debian（スマホの場合はMobian）には日本語UIがあります。\
Mobianをインストールした後、古いバージョンだと気付いましたので、勿論アップデートしたいですね。

```sh
sudo apt-get update && sudo apt-get dist-upgrade -y
```

残念ですが、エラーが出ました：

```
Error: Sub-process /usr/bin/dpkg returned an error code (1)
```

「mobian-tweaks」及び「calls」というソフトと問題がありますので、うまく修正できませんでしたが、後下記のコマンドを実行したら、もうちょっとアップデートできました：

```sh
sudo mv /var/lib/dpkg/info/mobian-tweaks.* /tmp
sudo mv /var/lib/dpkg/info/calls.* /tmp
sudo apt autoremove
sudo apt --fix-broken install
sudo apt-get dist-upgrade -y
```

次のエラーは「`gnome-authenticator`」及び「`python3-yoyo`」というソフトについてです。

```sh
sudo mv /var/lib/dpkg/info/gnome-authenticator.* /tmp
sudo mv /var/lib/dpkg/info/python3-yoyo-migrations.* /tmp
sudo apt autoremove
sudo apt-get dist-upgrade -y
```

やっとアップデートできました！！\
まだエラーが出たら、「`dpkg --configure -a`」を実行すると、「`処理中にエラーが発生しました:`」という列行の下記のソフト名で上記のコマンドを実行して下さい。

以上
