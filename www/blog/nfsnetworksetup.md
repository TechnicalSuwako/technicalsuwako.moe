title: 【BSD】NFSを設置する方法
uuid: 8919a747-11cc-4677-9c12-3c782e81a3bb
author: 諏訪子
date: 2024-08-16 00:00:00
category: unix,network,storage,server
----
2年前ぐらい、サーバでどんどんLinuxからOpenBSDに乗り換え始めた時に、NFSというファイルシステムを見つけました。\
ずっとこのブログを読んだら、NFSが大好きと知っているかもしん。\
[しかし、自動バックアップする方法について以外、これまではあんま詳しく設置方法を解説した事がありませんでした。](/blog/freebsd-nas-auto-backup.xhtml)\
今日の投稿では、NFSサーバとクライエントの設置方法を教え様と思います。

## NFSとは
NFS「Network File System」（ネットワークファイルシステム）は、名前の通りネットワーク上で使用出来るファイルシステムです。\
ネットワーク内の一台がサーバとして機能し、他の一台をクライアントとして設定する事が出来ます。\
これにより、USBメモリを使用したり、機密文書をメール、クラウドストレージ、又はインスタントメッセージで送信する等の危険な方法を使わずに、機械間でファイルをネイティブ速度で送信する事が可能になります。\
ここ０７６では、8 TiBのSSDを複数搭載したFreeBSDのワークステーションをNASとして設定し、次の様な重要なデータを保存しています：\
[Gitlerのレポジトリ](https://gitler.moe/)\
[076動画の動画](https://video.076.moe/)\
[076レポジトリ](https://076.moe/repo/)\
[保存サイトで使ってアーカイブしたウェブページ](https://hozon.site/)

これらのサービスは全て複数のOpenBSDサーバで稼働しており、これらのサーバが同じNAT内に存在する為、NASにインターネットアクセスを付与する事なく、FreeBSD上のディレクトリをOpenBSDサーバに簡単にマウントする事が出来ます。\
その結果、FreeBSD上のZFSのパフォーマンスとバックアップ機能性の利点を享受しながら、OpenBSD上のrelaydとhttpdのセキュリティと安定性を確保出来るという、正に美しい状況です！\
そして、利益を生まない個人として大量のデータを保存出来る理由は、どれだけデータを保存しても、支払うのは電気代、ISP代、及び固定IP代だけで、これらの金額は毎月同じである為です。\
LinuxではカーネルでNFS対応を明示的に有効にする必要がある（又はパッケージマネージャを通じてカーネルモジュールをインストールする必要がある）のに対し、BSD系OSではデフォルトで含まれている為、このガイドの為に何かをインストールする必要はありません。

## 【FreeBSD】NASの設置
あたしがNASにFreeBSDを使用する理由は、ZFSが標準で対応されているからです。\
もしFreeBSDが好きでなければ、同じ様にZFSがデフォルトで付いてくるIllumos系のディストリビューションを使う事も出来ます。\
Dragonfly BSDという選択肢もあり、こちらはHAMMER2が搭載されていて、HAMMER2を対応する唯一のOSでもあります。\
LinuxやNetBSDにもZFSの劣化版をインストール出来ますが、ネイティブではないのであんまお勧め出来ません。\
LinuxにはBtrfsもありますが、これはZFSを再現しようとした劣化版で、CDDLライセンスではないのが特徴です。\
CDDLこそが、IllumosやFreeBSD以外では採用されにくい大きな理由です。\
但し、BtrfsはRed Hat製品であり、過去にも何度も見られた様に、Red Hatから出てくるものはLinux全体にとって災難となる事が多いです。

NFSサーバを設定する前に、NFSパーティションを使用する全てのユーザーを作成し、ユーザー名、UID、GIDがウェブサーバと一致する事を確認して下さい。\
例えば、ウェブサーバ上でUIDとGIDが1001の「suwako」というユーザーがいて、NAS上で「suwako」というユーザーのUIDとGIDが1002である場合、又は両方でUIDが1001でも、NAS上でGIDが1002でウェブサーバー上でGIDが1001の場合、アクセス権限エラーが発生しますので、この点に注意して下さい！\
又、ルータを設定して、Ethernetに接続された各機械に固定のローカルIPアドレスを割り当てる様にして下さい。\
ルータのDHCPサーバがIPアドレスを変更すると、お互いに接続できなくなり、ウェブサーバが自動マウントされている場合、見つからないパーティションを探そうとして起動が殆ど出来なくなります。\
これが非常に重要です！

ユーザーを正しく作成するには、次のコマンドを使用します：
```sh
$ pw groupadd suwako -g 1001
$ pw useradd suwako -g suwako -s /bin/csh -m -u 1001 -c "NFS User"
```

何処かのディレクトリを作成します：
```sh
$ mkdir /zroot/www_data
$ chown -R suwako:suwako /zroot/www_data
```

次に、ディレクトリを/etc/exportsファイルに追加します。\
ウェブサーバのIPアドレスが192.168.0.106であると仮定します。\
又、複数の機械からマウント出来る事を示す為に、ノートパソコンも追加しましょう。\
ここでは、ノートパソコンのIPアドレスを192.168.0.125と仮定します。
```txt
/zroot/www -alldirs -maproot=suwako:suwako 192.168.0.106
/zroot/www -alldirs -maproot=suwako:suwako 192.168.0.125
```

次に、/etc/rc.confでサービスを有効にします。
```txt
zfs_enable="YES"
rpcbind_enable="YES"
nfs_server_enable="YES"
nfsd_flags="-u -t -n 6"
mountd_enable="YES"
```

`nfsd_flags`の説明ですが、`-u`は「UDPで提供」、`-t`は「TCPで提供」、`-n 6`は「6つのデーモンを使用する」という意味です。\
これらのフラグを選んだ理由は、単純にマンページで例として使用されていたからであり、必要に応じてフラグを調整して下さい。

次に、サービスを起動します：
```sh
$ service rpcbind start
$ service nfsd start
$ service mountd start
$ service nfsd restart
$ service mountd restart
```

サービスを起動後にnfsdとmountdを再起動する理由は、正しい起動順序がまだわからない為、動作確認の為に行っています。\
但し、rpcbindは設定変更時に再起動する必要はなく、nfsdとmountdのみ再起動が必要です。

次に、動作確認を行います：
```sh
$ showmount -e
Exports list on localhost:
/zroot/www                    192.168.0.106 192.168.0.125
```

IPアドレスの順序は関係ありません。\
リストに表示されていれば、パーティションのマウントが機能する事を確認出来ます。

## 【OpenBSD】ウェブサーバの設置
次は、ウェブサーバでマウントを機能させる楽しい部分です。

まずは、同じ名前、UID、GIDを持つ新しいユーザーを作成します。
```sh
$ groupadd -g 1001 suwako
$ useradd -u 1001 -g 1001 -m -s /bin/ksh suwako
```

次に、ウェブサーバ上にディレクトリを作成します。\
これはウェブサーバなので、/var/www/htdocsの下に置くのが最も理にかなっています。
```sh
$ mkdir -p /var/www/htdocs/076.moe/website_data
$ chown -R suwako:suwako /var/www/htdocs/076.moe
```

次に、この新しいディレクトリにNFSパーティションをマウントしましょう。\
NASのIPアドレスが192.168.0.143であると仮定します。
```sh
$ mount_nfs 192.168.0.143:/zroot/www /var/www/htdocs/076.moe/website_data
```

そうです、ウェブサーバではその為にサービスを有効にしたり起動したりする必要はありません。\
ウェブサーバはNASのクライアントだからです。\
エラーが出なければ、マウントは成功しています。\
ウェブサーバにファイルを置き、それがNASにも存在するか、又はその逆を確認する事で確認出来ます。

今後、自動マウントしたい場合は、/etc/fstabファイルを編集しましょう。
```txt
# ファイルシステム       マウントポイント                     FS類 RWオプション <dump>  <pass>
86c58f108204433b.b       none                                 swap sw
86c58f108204433b.a       /                                    ffs  rw,wxallowed 1       1
192.168.0.143:/zroot/www /var/www/htdocs/076.moe/website_data nfs  rw           0       0
```

## （任意）【Linux】ノートパソコンの設置
このステップは完全に任意ですが、別のOSや別の使用例を説明する為に示しておきます。\
また、自分のノートパソコン上でHTMLページを直接編集し、変更を即座にウェブサイトに反映させるのに便利だと感じるかもしん。\
あたしのノートパソコンは実際にはOpenBSDを動かしていますが、この投稿の目的の為に、代わりにVoid Linuxが動作していると仮定します。\
驚く事に、AppleのmacOSはBSD系OSと同様に、デフォルトでNFSが含まれています。\
その為、貴方のウェブデザイナーや3Dアーティストの同僚も、Linuxと同じ手順に従う事が出来ます。\
但し、何もインストールしたり有効にしたりする必要はありません。\
唯一の違いは、macOSではOpenBSDと同様に`mount_nfs`を使用出来ますが、Linuxでは`mount -t nfs`を使用する必要がある点です。\
`mount_nfs`は、Unixで`mount -t nfs`と書く代わりに、短縮した方法に過ぎません。

これはノートパソコンなので、UID 1001およびGID 1001のユーザーとグループ「suwako」が既に存在していると仮定します。\
存在しない場合は、OpenBSDと同じコマンドでユーザーを作成出来ます。
```sh
$ groupadd -g 1001 suwako
$ useradd -u 1001 -g 1001 -m -s /bin/bash suwako
```

BSD系のOSとは異なり、殆どのLinuxディストロではNFS対応を別途インストールするか、カーネルで有効にする必要があります。\
Void Linuxの場合、2つのパッケージをインストール出来ますが、例えばCRUXの場合は、カーネルで手動で有効にしてからコンパイルする必要があります。
```sh
$ xbps-install nfs-utils sv-netmount
```

再起動が必要になる可能性があるので、その点に注意して下さい。

何故か分かりませんが、OpenBSD、FreeBSD、macOSとは異なり、LinuxではNFSを動作させる為に3つのサーバデーモンを有効にする必要があります。\
しかし、これが現代のLinuxで、15年前程良くはありません。\
では、サービスを有効にしましょう。\
Void Linuxはrunitを使用しているので、この場合は次の様にします。
```sh
$ ln -s /etc/sv/rpcbind /var/service
$ ln -s /etc/sv/statd /var/service
$ ln -s /etc/sv/netmount /var/service
$ sv start statd rpcbind netmount
```

これでマウント出来ます。
```sh
$ mkdir -p /mnt/website
$ chown -R suwako:suwako /mnt/website
$ mount -t nfs 192.168.0.143:/zroot/www /mnt/website
```

自動マウントするには、OpenBSDウェブサーバと同じ/etc/fstab構造を使用出来ます。

以上
