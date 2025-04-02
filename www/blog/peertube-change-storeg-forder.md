title: 【PeerTube】ストレージフォルダを変更方法
uuid: 482a09a5-83bd-4e9b-85c6-66aacffbac47
author: 諏訪子
date: 2021-05-28 00:00:00
category: server,hosting,storage
----
PeerTubeインスタンスをインストールしましたが、もう100GBのSSDを超えました。\
ですから、500GBの外部ストレージを付かないといけませんでした。

でも、ストレージを移動するのはとても大変でした。\
そうして、ネットで全然ハウツーがありません。\
ですから、自分が教えます。

新作ストレージを付く後、フォーマットしましょう。\
まず、ストレージを調べましょう。

```sh
lsblk
```

```
NAME   MAJ:MIN RM  SIZE RO TYPE MOUNTPOINT
sr0     11:0    1  482K  0 rom  
vda    254:0    0  100G  0 disk 
├─vda1 254:1    0    2M  0 part 
└─vda2 254:2    0  100G  0 part /
vdb    254:16   0  500G  0 disk
```

```sh
fdisk /dev/vdb
```

nを押して。\
pを押して。\
1を押して。\
ここから最後まで「enter」キーばかりを押して下さい。

次はパーティションを作って下さい。

```sh
mkfs.ext4 /dev/vdb1
```

もう一回lsblkで確認して。

```sh
lsblk
```

```
NAME   MAJ:MIN RM  SIZE RO TYPE MOUNTPOINT
sr0     11:0    1  482K  0 rom  
vda    254:0    0  100G  0 disk 
├─vda1 254:1    0    2M  0 part 
└─vda2 254:2    0  100G  0 part /
vdb    254:16   0  500G  0 disk
└─vdb1 254:17   0  500G  0 part 
```

新しいフォルダを作って、マウントしましょう。

```sh
cd /mnt
mkdir /mnt/vidstore1
mount /dev/vdb1 /mnt/vidstore1
```

PeerTubeを実行中の場合、止めて。

```sh
systemctl stop peertube
```

フォルダを移動して、オーナーと許可を変更しましょう。

```sh
cd /mnt/vidstore1
mv /var/www/peertube/storage/* .
chown -R peertube:peertube .
chmod -R 777 .
```

次は「`/etc/nginx/sites-available/peertube`」を編集しましょう。\
「`# Performance optimizations`」という列行を探して、「`root /var/www/peertube/storage;`」を「`root /mnt/vidstore1;`」に編集して下さい。

「`# Should be consistent with client-overrides assets list in /server/controllers/client.ts`」という列行を探して、下にある「`root /var/www/peertube;`」を消して下さい。\
そうして、「`try_files /storage/client-overrides/$1 /peertube-latest/client/dist/$1 @api;`」を「`try_files /client-overrides/$1 $uri;`」に編集して下さい。

次は「`/var/www/peertube/config/production.yaml`」を編集しましょう。\
「`storage:`」という列行を探して、「`/var/www/peertube/storage`」と全部は「`/mnt/vidstore1`」に編集しましょう。\
うまく編集するには、「`vim`」で「`:s/\/var\/www\/peertube\/storage/\/mnt\/vidstore1/g`」をオススメです。

最後、PeerTubeを起動して。

```sh
systemctl start peertube && journalctl -feu peertube
```

エラーが出なければ、大丈夫です。\
PeerTubeで動画を見て、F12を押して、赤文字を出るかどうか確認して下さい。\
なければ成功です。

以上
