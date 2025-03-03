title: 【Pleroma】ホームタイムラインで500エラーの修正方法
author: 凜
date: 2022-05-31
category: server,hosting
----
[最近、あたしのSNSのホームタイムラインで500エラーが発生される事が多くなりました。](https://social.076.ne.jp)\
今回は修正方法を教えます。

# PostgreSQLのコンフィグファイルの編集

[まずは、PGTuneで創作して下さい。](https://pgtune.leopard.in.ua/)\
DB versionは、「psql --version」による正しいバージョンを選択して下さい。\
OS Typeはそのまま「Linux」で良いです。\
DB Typeはそのまま「Web application」で良いです。

Total Memory (RAM)を調べるには、「free -m」で確認して下さい。

```sh
free -m
              total        used        free      shared  buff/cache   available
Mem:            980         410          93         261         477         156
Swap:          2047         472        1575
```

こちらの場合は1 GBとなります。

Data StorageはSSDではない場合、変更して下さい。\
Number of CPUs及びNumber of Connectionsは空で良いです。\
「Generate」をクリックして下さい。

/etc/postgresql/（バージョン）/main/postgresql.conf ファイルを創作した値通り変更して下さい。

![](https://ass.technicalsuwako.moe/Screenshot_20220531_214940.png)

後はpostgresqlのサービスを再起動して下さい。

Debian、CentOS等の場合：
```sh
systemctl restart postgresql
```

Devuan、OpenBSD等の場合：
```sh
rc-update postgresql restart
```

下記はsystemdのコマンドを教えますので、OpenRC、runit、s6等を利用する場合、このinitシステムのコマンドで交換して下さい。

# VACUUM ANALYZEの実行

次はpostgresでVACUUM ANALYZEを実行する事が必要となります。\
初めて実行したら、まずはVACUUM FULLの実行する事が必要で可能性があります。\
その場合は「analyze」は「full」で交換して下さい。

ソースからインストールした場合：

```sh
cd /opt/pleroma
./bin/pleroma_ctl database vacuum analyze
systemctl restart pleroma
```

OTPでインストールした場合：

```sh
cd /opt/pleroma
systemctl stop pleroma
sudo -Hu pleroma MIX_ENV=prod mix pleroma.database vacuum analyze
systemctl start pleroma
```

crontabで自動化にしましょう。

/etc/crontabを編集すると、下記の行列を追加して下さい。

ソースからインストールした場合：

```sh
0  1    0 * *   root    cd /opt/pleroma && ./bin/pleroma_ctl database vacuum analyze && systemctl restart pleroma
```

OTPでインストールした場合：

```sh
0  1    0 * *   root    cd /opt/pleroma && systemctl stop pleroma && sudo -Hu pleroma MIX_ENV=prod mix pleroma.database vacuum analyze && systemctl start pleroma
```

それで毎日午前１時でVACUUM ANALYZEを実行します。

以上
