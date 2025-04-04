title: 【セキュリティ】複数回セキュリティについて話したのに、大企業の安全性はまだ弱い・・・
uuid: 4c375927-dcde-4a69-aa15-a474f5326300
author: 諏訪子
date: 2024-10-23 00:00:00
category: security,rant
----
2024年10月は、企業のサイバーセキュリティにおいて、彼らが可能な限り愚かなミスを犯したという意味で、とても混乱した月でした。\
一方では、ポケモン開発者である株式会社ゲームフリークが、ゲーム開発史上最大のデータ流出に見舞われ、他方ではインターネットアーカイブ（Wayback Machineで知られる）が先ずDDoS攻撃を受け、その後凡る種類のデータ侵害に遭いました。\
ゲームフリークが何らかのセキュリティ対策を講じたかどうかは不明ですが、インターネットアーカイブは既に広く知られている問題の修正を拒んでいる様です。

ポケモンファンとしてのあたしの一面は、流出した内容についてもっと詳しく話したい気持ちがありますが、０７６は任天堂スイッチ及びその後継機のライセンスを受けた第三者開発者でもある為、話すとNDAに違反してしまいますので、これ以上は言えません。\
任天堂スイッチ後継機のコードネームが既に公になっているとはいえ、それについて触れることもNDA違反になるため、ここでは触れません。

私自身サイバーセキュリティ研究者として、これまでこのブログで何度もセキュリティに関する記事を書いてきましたが、なぜかいくつの人（キャンセルカルチャーの洗脳した奴隷達だけのに）があたしを陰謀論者と呼ぶ為、誰もあたしの話を聞いてくれず、あたしが警告した事が全て現実になってしまいます。\
これには「あたしを真剣に受け止めなかった人達の顔に笑い飛ばしたい」と感じる事もありますが、それでももう一度繰り返し、皆さんのサイバーセキュリティを向上させる為のヒントを提供すべきだと思います。

## 1. UNIXを学ぶ
ゲームフリークの主な問題は、Gitlabサーバーの設定ファイルに誤った権限を設定し、それをCI/CDランナーに入れた事です。\
次のポイントで話すとあるファイルには`chmod 777`が設定されており、これは世界中の誰でも全ての権限を持つという意味です。\
これは非常に愚かなミスであり、そのファイルは`chmod 640`（所有者に読み書き権限、グループには読み取り専用、その他にはアクセス不可）に設定するのが望ましいです。

彼らの作業環境は全てWindowsを使用しています。
これは、全てのコンソールベンダーがSDKやツールをWindows向けにしかリリースしない為であり、Windows自体が巨大なセキュリティリスクとなります。\
しかし、Windowsの使用を強制される事で、従業員が基本的なセキュリティ対策に無頓着になる傾向も助長されます。\
通常であれば、BSD系OSや少なくともLinuxディストリビューションに切り替える事を勧めますが、彼らが使用しなければならない機密性の高いツールの性質上、この提案は不可能です。\
しかし、少なくともUNIXの権限の仕組みを理解しておく事をお勧めします。\
なぜなら、NTFSやFAT32では、全てのファイルが常に`chmod 777`になっており（マイクロソフトのみが制御出来る極秘ファイルを除く）、サーバーにアップロードした後は権限を確認する必要があるからです。\
また、最新のWindowsバージョンでは権限を変更出来る事は認識していますが、それらの権限はUNIX環境には適用されません。

## 2. 重要なサーバーをNATの背後に置く
これについては何度も話してきましたが、あたし自身の設定に誇りを持っているからだけでなく、これ以上に堅牢な方法はないからです。\
参考までに：\
[【セキュリティ】Wireguardを使って安全に自宅のネットワークをアクセスする方法](/blog/access-network-wireguard.xhtml)\
[【FreeBSD】簡単にNASの自動的にバックアップする方法](/blog/freebsd-nas-auto-backup.xhtml)\
[【デジタル自主】自宅サーバーからWireGuardを使ったホスティング方法](/blog/digital-autonomy-with-wireguard-home-host.xhtml)

両社の場合、コードリポジトリは公開アクセスを想定していない為、ネットワーク内部からのみアクセス出来る内部サーバーに配置する事が可能です。\
手順は非常にシンプルで、内部ネットワーク上にBSD又はLinuxサーバーをセットアップし、そこに任意のバージョン管理システム（VCS）をインストールして初期化します。\
従業員はその上で自由に作業する事が出来ます。\
リモートワーカーや契約社員にアクセスさせる必要がある場合は、WireGuardをインストールし、ルーター又はWireGuardサーバーの公開鍵を渡し、彼らの作業端末の公開鍵を取得する事で、インターネットに公開する事なくリモートでVCSサーバーにアクセス出来る様になります。

あたしはGitに多くの経験があり、また、動画ゲームのソースコード用にMercurialも試しています。\
WireGuardの設定方法については、上記の3つの記事をご覧ください。

### サーバー上でのGitの設定
```sh
$ doas su
# cd /var/db
# mkdir git-repos
# groupadd -g 2000 git
# useradd -m -d /var/db/git-repos -u 2000 -g 2000 git
# cd git-repos
# git init --bare pokemon-game.git
# chown -R git:git /var/db/git-repos
```

クライアント側では：
```sh
$ ssh-keygen -t ed25519
```

UNIX又はBeOSの場合：
```sh
$ cat ~/.ssh/id_ed25519.pub
```

Windowsの場合：
```ps
> cat .\.ssh\id_ed25519.pub
```

サーバー管理者にSSH公開鍵を渡して下さい。\
追加されたら、次に進みます。

``` sh
$ cd work
$ mkdir pokemon-game && cd pokemon-game
$ git clone git@{内部IP}:pokemon-game.git
$ git pull
```

### サーバー上でのMercurialの設定
```sh
$ doas su
# cd /var/db
# mkdir hg-repos
# groupadd -g 2001 hg
# useradd -m -d /var/db/hg-repos -u 2001 -g 2001 hg
# cd hg-repos
# hg init pokemon-game
# chown -R hg:hg /var/db/hg-repos
```

クライアント側では：
```sh
$ ssh-keygen -t ed25519
```

UNIX又はBeOSの場合：
```sh
$ cat ~/.ssh/id_ed25519.pub
```

Windowsの場合：
```ps
> cat .\.ssh\id_ed25519.pub
```

サーバー管理者にSSH公開鍵を渡して下さい。\
追加されたら、次に進みます。

``` sh
$ cd work
$ mkdir pokemon-game && cd pokemon-game
$ hg clone ssh://{内部IP}/pokemon-game
$ hg pull
$ hg update
```

リモートワーカーの場合、内部IPをWireGuardのIPに変更するだけです。\
WireGuardサーバーはGitリポジトリと同じサーバー上で動作している必要があります。\
データを1つのサーバーに集中させないための方法については、以下を参照してください：\
[【FreeBSD】簡単にNASの自動的にバックアップする方法](/blog/freebsd-nas-auto-backup.xhtml)

## 3. ユニークでランダムなパスワードを使い、定期的に変更する
[日本のウェブサイトや企業が特に弱いパスワードを強制する問題について以前に話しました。](/blog/security-weak-password-problem.xhtml)\
そしてもちろん、未だこの国の0%が耳を傾けています・・・\
強力なパスワードや良いパスワード習慣を強制する重要性、又は少なくともそれをユーザーに許可する事については、いくら強調しても仕切れません。\
今日のセキュリティ問題の大部分は、弱いパスワードの使用から生じており、今月はインターネットアーカイブへのサイバー攻撃によってそれが非常に明らかになりました。\
彼らが問題の根本原因を修正する代わりに、単にサービスを使えなくする事を選んだ事、そしてその後2週間後にサイバー攻撃者がZendeskアカウントを通じてメールを送る事が出来たのは驚きです。

ランダムに生成されたパスワードを作成し、それを保存して使う最良の方法は、あたし達独自のSPを使用する事です：\
[https://gitler.moe/suwako/sp](https://gitler.moe/suwako/sp)

コマンドラインが怖い方には、最近リリースした同じパスワードマネージャーのGUI版「SimPas」をお勧めします。\
こちらも同時に積極的にメンテナンスしています。\
[https://gitler.moe/suwako/simpas](https://gitler.moe/suwako/simpas)

機能セットは常に同等に保たれている為、優れたバージョンはありません。\
SPとSimPasは全てのUNIXライク及びBeOSライク（Haiku）OSと互換性があります。\
Windows（C# .NET）、macOS及びiOS（Swift Cocoa）、Android（Kotlin）向けのリライトも開発中です。\
これらのOSの使用には反対ですが、パスワードの安全性が優先されるからです！\
特にスマートフォン上では、手動で安全なパスワードを入力する必要がない、オプションの自分でホスティング可能なクラウド同期機能も提供予定です。

あたしのお勧めは、各サービスごとに新しいパスワードを生成し、それをSP又はSimPasに入力する事です（両方が互いのデータを使用出来る為、どちらを使用しても問題ありません）。\
その後、コピー機能を使用してパスワードを画面に表示せずにクリップボードにコピーします。

さらに、SP 1.5.0及びSimPas 1.1.0には、過去に流出した事があるかどうかをチェックする機能と、重複しているパスワードをチェックする機能が追加されます。\
これらは12月17日にリリース予定ですが、今すぐソースコードをコンパイルして試す事も出来ます。

## 4. 2FAを使う
[以前、ワンタイムパスワードには懐疑的でした。](/blog/more-safe-than-2fa-is-pass-pwgen.xhtml)\
その理由は、必ずしも技術自体ではなく、スマホやSMS、メールを使う必要がある事に重点が置かれているからであり、これらは全て安全ではありません。\
しかし、あたしはこの問題をSPとSimPasで解決しました：\
[https://gitler.moe/suwako/sp](https://gitler.moe/suwako/sp)\
[https://gitler.moe/suwako/simpas](https://gitler.moe/suwako/simpas)

SPとSimPasはどちらも、スマホや他の安全でない方法を使わずに2FAを利用出来ます。\
更に、SP 1.5.0からは2FAの使用が更に簡単になります。\
なぜなら、ワンタイムパスワードを手動でコピーする事なく、クリップボードに自動的に挿入出来る様になるからです。

先ずzbarを使ってQRコードに隠された情報を取得します。\
SPでは、`sp -a website.com/2fa`を実行してTOTPコードを追加し、`sp -o website.com/2fa`でワンタイムパスワードを表示出来ます。\
そして、SP 1.5.0からは`sp -O website.com/2fa`で、パスワードを表示せずにクリップボードにコピーする事が出来ます。\
SimPasでは、「パスワードの追加」をクリックしてTOTPコードを追加し、ウェブサイトのドメインを検索してクリックする事で、ワンタイムパスワードを表示し、クリップボードにコピー出来ます。\
SimPas 1.1.0からは、表示がデフォルトで隠される様になり、デフォルトで表示する為のチェックボックスが追加されます。\
[SP 1.6.0及びSimPas 1.2.0からzbarが不要になる予定です。](https://gitler.moe/suwako/sp/issues/22)

上記の4つのヒントに従う限り、企業の秘密が安全である事を安心して眠る事が出来るでしょう。

以上
