title: 【OpenBSD】機動する時に、「ERR M」が表示される問題の修正方法
author: 凛
date: 2024-09-20
category: unix,server
----
あたしは馬鹿なな事をしてしまいました。\
ThinkPad P50で、別のSSDにFreeBSDをインストールしたのですが、設定をコピーする為にFreeBSDパーティションにOpenBSDパーティションをマウントしました。\
しかし、マウントする前にfsckを実行する必要がありました。\
その結果、FreeBSDがOpenBSDインストールを破壊してしまい、OpenBSDを起動しようとすると次の画面が表示される様になりました：

```
Using drive 0, partition 3.
Loading...
ERR M
```

再び起動出来る様にするには、起動可能なOpenBSD USBインストーラーが必要で、そこから起動します。

Sキーを押してシェルを起動します。\
大文字でも小文字でも構いません。

## コマンド

注意：OpenBSDのデフォルトのパーティションスキームを使用している場合、`/usr`パーティションがどれかを特定する必要があります。\
まず、`/dev/sd0a`をマウントしてから`cat /mnt/etc/fstab`を実行し、それを確認します。\
その後、`umount /mnt`でアンマウントし、`/usr`パーティションをマウントします。\
この時、`/dev/sd0a`の`a`を`/usr`パーティションに置き換えて下さい。\
また、OpenBSDのパーティションスキームを使用している場合は、`cd /mnt/usr/mdec`ではなく、`cd /mnt/mdec`と入力します。

```sh
# cd /dev
# chmod +x MAKEDEV
# doas ./MAKEDEV sd0
# mount /dev/sd0a /mnt
# cd /mnt/usr/mdec
# installboot -v -r /mnt sd0 biosboot boot
Using /mnt as root
installing bootstrap on /dev/rsd0c
using first-stage biosboot, second-stage boot
copying boot to /mnt/boot
looking for superblock at 65536
found valid ffs2 superblock
/mnt/boot is 3 blocks x 32768 bytes
fs block shift 3; part offset 64; inode block 40, offset 1392
expecting 64-bit fs blocks (incr 4)
master boot record (MBR) at sector 0
        partition 3: type 0xA6 offset 64 size 50118128
biosboot will be written at sector 64
# exit
```

## アップグレード

次に、Uキーを押して、画面の指示に従います。\
これは、欠落している可能性のあるファイルを再構築する為の作業です。\
データについて心配する必要はありません。\
デフォルトのバイナリやライブラリだけがデフォルトの物に置き換えられるだけです。

作業が完了したら、Rキーを押して再起動します。

以上
