title: 【バージョン管理】Git、SVN、Mercurialの比較
author: 凛
date: 2024-12-24
thumbnail: P3SEpa.png
thumborient: bottom
category: programming,server,rant
----
メリークリスマス！\
ずっとGitを使いましたが、最近Giteaが壊れちゃった為、自分の「Gitler」を0から作ってみました。\
しかし、Git自体が非常に複雑だった為、途中で断念しました。\
そして、ちょうど今076がウェブ/デスクトップ開発者やウェブサービス提供者からゲーム開発スタジオへと移行している事もあり、公のGitリポジトリホスティングサービスはもう必要ありません。\
その為、MercurialとSVNを調査し、自分に合う物を探し始めました。

尚、Gitlerは終了予定ですが、既に公開している全てのソースコードは076リポジトリで引き続き利用可能です。\
オープンソースプロジェクトのソースコードも、今後もそこに公開されます。\
唯一の違いは、リポジトリにはコミット履歴が含まれておらず、タールボール（tarball）のみが存在する点です。

## Git vs Mercurial: 使い心地
Mercurial（別名（レイザーラモン）HG）は驚く程使い易いです。\
基本的な使い方はGitと非常に似ており、サーバー側でのリポジトリ作成がGitよりも簡単で、大規模なプロジェクトでも非常にスケーラブルです。

GitもMercurialも分散型VCSシステムである為、リポジトリは自分のコンピューター上に存在します。\
その為、オフラインでコードを作業し、オンラインに成った時にコミットする事が出来ます。\
何方も、ユーザーがコミットする中央リポジトリを従います。\
違いとして、Gitでは中央リポジトリをベアリポジトリとして初期化する必要がありますが、Mercurialではクライアント側と同じ方法で中央リポジトリを初期化出来ます。

しかし、一番の問題は、Unix系OSやHaikuでは非常に上手く動作するにも関わらず、Windowsではバグが多い点です。\
例えば、TortoiseHgを使ってpullやcheckoutを試みると、間違ったコミットのファイルを取得する事が頻繁にあります。\
「最新のコミット」が実際には最新ではないのに最新と表示される為、Windows上でMercurialを正常に動作させる唯一の方法は、Linuxサブシステム（WSL2）を介して操作する事です。

## Git vs Mercurial: 操作性
Gitユーザーにとって一番混乱するのは、Mercurialでの変更のコミット方法です。\
Gitではコミットする度に個々のファイルを追加する必要がありますが、Mercurialではリポジトリ履歴にまだ存在しないファイルだけを追加する必要があります。\
全てコミットされた事があるファイルは、Mercurialでは変更が自動的に適用されますが、Gitでは変更されたファイルを再度手動で追加する必要があります。\
以下に、両者の一般的なフローを示します：

Git:
```sh
$ git clone git@git.suwa:suwako/simpas.git
$ cd simpas
$ touch penis
$ git add .
$ git commit -m "ちんこ"
[master (root-commit) c435de9] ちんこ
 1 file changed, 0 insertions(+), 0 deletions(-)
 create mode 100644 penis
$ git push

$ echo "うんこ" >> penis
$ git add .
$ git commit -m "テキストの追加"
[master 0c2023b] テキストの追加
 1 file changed, 1 insertion(+)
$ git push
```

HG:
```sh
$ hg clone ssh://hg@hg.suwa//home/hg/repos/suwako/simpas/
$ cd simpas
$ touch penis
$ hg add
penis を追加登録中
$ hg commit -m " ちんこ"
$ hg push

$ echo "うんこ" >> penis
$ hg commit -m "テキストの追加"
$ hg push
```

また、Gitでは最初にプッシュする際に、 `git push -u origin <branch>` の様に指定しなければなりませんが、Mercurialではその必要はなく、最初でも10万回目でも `hg push` だけで済みます。

Gitユーザーが混乱するもう一つの点は、サーバーから変更をプルする方法です。\
Gitでは `git pull` だけで済みますが、Mercurialでは `hg pull` を実行した後に `hg update` でリポジトリを更新する必要があります。\
Gitは自動的にリポジトリを更新しますが、Mercurialでは手動で行わなければなりません。\
サーバー上でも同様で、Gitはリポジトリ自体のファイルを表示しませんが、Mercurialは表示する為、サーバー上でも `hg update` を実行して最新のファイルを反映させる必要があります。\
尚、 サーバー上で`hg pull` を実行する必要はありません。

因みに、 `hg update` の省略形は `hg up` です。

GitもMercurialもエイリアスを対応しています。\
Gitでは `~/.gitconfig` に、Mercurialでは `~/.hgrc` に追加出来ます。

例:

```sh
# .gitignore
[alias]
  aa = add .
  cmn = commit -m
  d = diff
  go = checkout
  pl = pull
  ps = push
  s = status

# .hgrc
[alias]
aa = add
cmn = commit -m
d = diff
go = checkout
pl = pull
ps = ps
s = status
```

これにより、 `git cmn` や `hg cmn` の様に短縮してコマンドを実行出来る様に成ります。

サーバー上で新しいGitリポジトリを作成するには、次の様にします：
```sh
$ cd repos/suwako
$ git init --bare simpas.git
```

Mercurialの場合：
```sh
$ cd repos/suwako
$ hg init simpas
```

更に、Gitでは正当な理由があれば履歴を書き換える事が出来ますが、Mercurialではそれが許されておらず、履歴を書き換えないというベストプラクティスに従う必要があります。\
例えば、Gitでは誤ってコミットした平文のパスワードを完全に削除出来ますが、Mercurialではそのパスワードが永遠に残ります。\
一方で、Mercurialではリポジトリを大混乱にする事が難しいですが、Gitではプロジェクトが壊れる程の混乱が発生する可能性があります。\
但、Mercurialではマージコンフリクトを解決するのがGitよりも難しく成る為、コミットする前に必ずプルする必要があります。

最後に、ブランチの扱い方の違いがあります。\
Gitではブランチを何時でも作成・削除出来ますが、Mercurialではブランチが作成された時点で履歴の一部となり、削除出来ません。\
Gitのブランチに近い機能としてMercurialにはブックマークがありますが、あたしは未だ使った事がないので詳しい違いは分かりません。

## Git vs SVN: 使い心地
SVNは、ベテランのゲーム開発者の間で依然として非常に人気があります。\
少なくとも大手開発者達は今でも使用しています。\
2007年にシステム管理者をしていた頃、あたし達が管理していたゲーム開発スタジオは全てSVNを使用していました。\
そして現在でも、家庭用ゲーム機メーカーは独自のツールでSVNを直接対応しています。

SVNはGitやMercurialとは大きく異なります。\
GitやMercurialが分散型VCSであるのに対し、SVNは集中型VCSです。\
これは詰まり、SVNは完全にサーバー上に存在し、アクセスするにはオンラインである必要があります。\
一方、GitとMercurialはローカルコンピューターにもリポジトリが存在する為、オフラインでも作業が可能で、オンラインに成った時に中央リポジトリに同期出来ます。

SVNはオープンソースコミュニティで屡々批判されたり、ミニマリストソフトウェアのファンによって貶される事が多いですが、実際にはあたしはSVNが一番好みます。\
使い方が分かり易く、Unix、Haiku、Windowsでも非常に上手く動作し、速度も非常に速いです。

唯一厄介なのは、Unix上で無視リストにファイルを追加するのが非常に難しい点です。\
WindowsではTortoiseSVNを使う事で非常に簡単に出来ます。\
TortoiseSVNはTortoiseHgとは異なり、Windows Explorerと上手く統合されています。

## Git vs SVN: 操作性
Mercurialと同様に、リポジトリに既に存在するファイルを変更した場合、それを明示的に追加する必要はありません。\
Mercurialとは異なり、プルした後に明示的に更新する必要もありません。\
更に、GitやMercurialとは異なり、プッシュする必要もありません。

基本的なワークフローは以下の通りです：

Git:
```sh
$ git clone git@git.suwa:suwako/simpas.git
$ cd simpas
$ touch penis
$ git add .
$ git commit -m "ちんこ"
[master (root-commit) c435de9] ちんこ
 1 file changed, 0 insertions(+), 0 deletions(-)
 create mode 100644 penis
$ git push

$ echo "うんこ" >> penis
$ git add .
$ git commit -m "テキストの追加"
[master 0c2023b] テキストの追加
 1 file changed, 1 insertion(+)
$ git push
```

SVN:
```sh
$ svn checkout svn+ssh://svn@svn.suwa/home/svn/repos/suwako/simpas
$ cd simpas
$ touch penis
$ svn add *
A         penis
$ svn commit -m "ちんこ"
追加しています              penis
ファイルのデータを送信しています .done
Committing transaction...
リビジョン 1 をコミットしました。

$ echo "うんこ" >> penis
$ svn commit -m "テキストの追"
送信しています              penis
ファイルのデータを送信しています .done
Committing transaction...
リビジョン 2 をコミットしました。
```

SVNにはプッシュコマンドがないのと同様に、プルコマンドもありません。\
サーバーから変更を取得するには、 `svn update` を実行するだけで、GitやMercurialよりもずっと速く完了します。\
あたしの経験では、Gitはプッシュやプルが最も遅く、SVNが最も速いです。\
MercurialはSVNより少し遅いだけで、Gitよりは明らかに速いです。

新しいリポジトリをサーバー上に作成するには、以下を実行します：

```sh
$ svnadmin create repos/soft/simpas
```

リポジトリを作成した後、2つのファイルを設定する必要があります： `~/<path-to-repo>/conf/passwd` と `~/<path-to-repo>/conf/svnserve.conf` 。\
SSH経由でSVNを使用する場合は設定の必要はありませんが、SSHを使わずにSVNを使用する場合は、匿名ユーザーのアクセスを防ぎ、チームメンバーが使用出来る様に設定する必要があります。\
`passwd` ファイルでは、ユーザーとパスワードを設定します。

例：

```
[users]
suwako = #a7h1$'"bCiYa!S1QD~{p*iEVI~srx1#P:.I_?v?1aI=s=DT,_.kss5ExPJUYc()
rumia = tMh1wM[;qm"Jl82qikcwt8s$fQXx3uEL\Z%18wDCM!%MaV~e}yr0E|M&armRS[5o
cirno = Kk7!R[G;vI%YZJOf1HX4_D.:_iI#fDz%IorPb`1TIWiwQT'yG46k.lH7&V[bLW6x
koishi = 9Wx~P}"oU:5|pWok1UqEM+gnteLdS+`ZInA`Q]J75G!eWVj6.Qa5W/QVl[kx`d}F
```

SSHを使わずにSVNを使用する方がWindowsでは便利です。\
SSHで設定する事も可能ですが、とても面倒で時間の無駄に成ります。\
更に、Unixではコマンドラインで行う必要がありますが、WindowsではTortoiseSVNを使用して認証情報を記憶させる事が出来る為、毎回入力する手間が省けます。

## 何れを選ぶ可きか？
どのVCSを選ぶ可きかは、完全に自分のニーズに依存します。\
もしゲーム開発スタジオやWindowsが利用されているチームで作業しているなら、SVNが最適です。\
一人で作業している場合や、全員がUnix又はHaikuを使用している小規模チームで作業している場合は、Mercurialが最適です。\
Github、GitLab、Codeberg、又は他の大規模なホスティングプラットフォームでホストされている有名なオープンソースプロジェクトに取り組む場合は、Gitが最適です。

これら3つのVCSは全て企業ネットワークインフラ上で使用可能です（その為、非常に秘密のプロジェクトをインターネットに公開してしまい、コードを流出させて一般に公開されるリスクを負う必要はありません）。\
然し、Gitは公開リポジトリに適している一方で、MercurialやSVNは企業やプライベートチームにより適していると感じます。

あたし個人としては、今後SVNを使用する事を選びました。\
単純にSVNが一番好みだからです。\
また、3つの中で唯一GPLライセンスではない為（それ程重要ではありませんが）、プロプライエタリなプロジェクトでSVNを使用する方が、GitやMercurialを使うよりも罪悪感が少ないと感じます。

因みに、「svn.suwa」、「hg.suwa」、「git.suwa」というドメイン名に気づいたかもしん。\
.suwa TLDは076の内部ネットワーク専用に指定された特別なTLDであり、WireGuardネットワークに接続していない限りアクセスする事は出来ません。\
その為、あたし達の公開ウェブサーバーもこれに接続出来ない様に構成されています。\
もし保存サイトを通じてSuwanetにアクセスしようとしても、機能しないので試みないで下さい。\
将来はこれのやり方を解説すると思います。

以上
