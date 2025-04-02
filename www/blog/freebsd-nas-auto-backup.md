title: 【FreeBSD】簡単にNASの自動的にバックアップする方法
uuid: f1084678-fb91-4e6e-8414-0fbfc70c5448
author: 諏訪子
date: 2024-01-30 00:00:00
category: server,storage
----
[先月は「０７６動画ホスティングの解決策」という投稿を書きました。](/blog/digital-autonomy-076video-hosting.xhtml)\
先週の週末、このNASに4 TiBのSSDを追加しましたので、それによりGitlerと０７６動画のストレージ容量が増大出来ました。\
でも、バックアップならどうすれば良いですか？\
今回はこれを解決すると思いますが、ちゃんと理解するには、まずは「０７６動画ホスティングの解決策」をご覧下さい。

## 必要なソフト

今回必要なソフトは1つだけで、rsyncです。

```sh
# doas pkg install rsync
```

## rootになれ！！

そこからrootアカウントで行います。

```sh
# doas su -l
$
```

## ZFSで使ってディスクを確認して

```sh
$ zpool list               
NAME     SIZE  ALLOC   FREE  CKPOINT  EXPANDSZ   FRAG    CAP  DEDUP    HEALTH  ALTROOT
backup   888G   259G   629G        -         -     0%    29%  1.00x    ONLINE  -
xroot   3.62T   259G  3.37T        -         -     0%     6%  1.00x    ONLINE  -
zroot    920G   277G   643G        -         -     0%    30%  1.00x    ONLINE  -

$ ls -thal /zroot /backup /xroot
/xroot:
total 52
drwxr-xr-x  19 peertube peertube   19B  1月 29 23:34 peertube
drwxr-xr-x   7 root     wheel       7B  1月 28 23:30 .
drwxr-xr-x  22 root     wheel      28B  1月 28 20:32 ..
drwxr-xr-x  17 suwako   suwako     17B  1月 23 16:14 repo
drwxr-xr-x   6 git      git         8B  1月 18 16:14 git
drwxr-x---   8 git      git         8B  1月 18 15:59 gitler

/backup:
total 43
drwxr-xr-x  19 peertube peertube   19B  1月 29 23:34 peertube
drwxr-xr-x  22 root     wheel      28B  1月 28 20:32 ..
drwxr-xr-x  17 suwako   suwako     17B  1月 23 16:14 repo
drwxr-xr-x   6 root     wheel       6B  1月 23 14:26 .
drwxr-xr-x   6 git      git         8B  1月 18 16:14 git
drwxr-x---   8 git      git         8B  1月 18 15:59 gitler

/zroot:
total 43
drwxr-xr-x  19 peertube peertube   19B  1月 29 23:34 peertube
drwxr-xr-x  22 root     wheel      28B  1月 28 20:32 ..
drwxr-xr-x  17 suwako   suwako     17B  1月 23 16:14 repo
drwxr-xr-x   6 root     wheel       6B  1月 23 14:26 .
drwxr-xr-x   6 git      git         8B  1月 18 16:14 git
drwxr-x---   8 git      git         8B  1月 18 15:59 gitler

$ du -lsh /zroot/* /backup/* /xroot/*
6.2G  /zroot/git
142G  /zroot/gitler
104G  /zroot/peertube
6.9G  /zroot/repo
6.2G  /backup/git
142G  /backup/gitler
104G  /backup/peertube
6.9G  /backup/repo
6.2G  /xroot/git
142G  /xroot/gitler
512B  /xroot/mainpc
104G  /xroot/peertube
6.9G  /xroot/repo
```

## シェルスクリプトを作る

```sh
$ nvim sync-backups.sh && chmod +x sync-backups.sh
```

この「 && chmod +x sync-backups.sh」の部分は、「sync-backups.sh」を保存して終了したら、直ぐに実行可能にして下さいという意味です。

```sh
#!/bin/sh

# 1 TiB
rsync -vaHzop --delete /zroot/* /backup

# 4 TiB
rsync -vaHzop --delete /zroot/* /xroot
```

「rsync -vaHzop --delete /zroot/* /backup」の意味は：
* rsync = ソフト
* -vaHzop = 何をコピーするか表示する、アーカイブモード (1:1のコピーを作る事)、ハードリンクを保管、、オーナーを保管、許可を保管
* --delete = もう存在しないファイルやディレクトリがあれば、消す
* /zroot/* /backup = /zrootの内容の全部を/backupにコピーする

## crontabを変更して

最後にcrontabを変更しましょう。

```sh
$ crontab -e
```

```
0 0,6,12,18 * * * /root/sync-backups.sh
```

それで、毎日4回 (0:00、6:00、12:00、そうして18:00で) バックアップのスクリプトを実行されます。

以上
