title: Debian 9（stretch）→10（buster）バージョンアップする方法
author: 凜
date: 2019-07-19
category: unix
----
今月からDebian 10（buster）の安定版がリリースされました。\
すべての０７６のサーバーはDebianが使いますので、Gitサーバーでアップグレードしてみました。

まず、絶対にバックアップしてください！！

【準備】\
アップグレード前に現在のシステムをアップデートしましょう。

```sh
apt update && apt upgrade && apt dist-upgrade && apt autoremove
```

後で未インストールパッケージ、古いパッケージなどのお確認ください。

```sh
dpkg -C
```

問題あったら、未アップデートしたパッケージをお確認ください。

```sh
apt-mark showhold
```

問題あったら、ご修理しましょう。

```sh
dpkg --audit
```

# アップグレード第１部

まず現在の「sources.list」ファイルをバックアップしてください。

```sh
cp /etc/apt/sources.list /etc/apt/sources.list_BCKP
```

同じく「/sources.list.d」フォールだも…

```sh
cp -r /etc/apt/sources.list.d /etc/apt/sources.list.d_BCKP
```

後はsources.listファイルとすべてのsources.list.dに入ったファイルを編集しましょう。

```sh
sed -i 's/stretch/buster/g' /etc/apt/sources.list
sed -i 's/stretch/buster/g' /etc/apt/sources.list.d/*
```

その後でパッケージリストをアップデートして…

```sh
apt update
```

# アップグレード第２部

下記コマンドをお実行ください。

```sh
apt upgrade && apt dist-upgrade && apt autoremove
```

`Restart services during package upgrades without asking?`とことが出てきたら、「はい」、「いいえ」どっちでもいいです。

`cat /etc/issue`でDebianのバージョンが確認できます。

最後に、再起動しましょう。

```sh
reboot
```

以上
