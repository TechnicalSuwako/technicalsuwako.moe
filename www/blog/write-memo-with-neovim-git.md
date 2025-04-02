title: 【デジタル自主】NeovimとGiteaでメモの書き方
uuid: 25f46582-cf22-46db-8065-67dab1082211
author: 諏訪子
date: 2022-06-24 00:00:00
category: unix
----
OneNote、Evernote等みたいなソフトを使うのは重くて危険です。\
クラウド系ノートソフトを使ったら、第三者会社を信頼を持つのは必須となります。\
ですから自分のGiteaサーバ及びNeovimを使う事が勧めます。\
１台パソコンを持ったら、Giteaは不要です。

## Giteaのインストールする方法

まずはお好みのOSでサーバを設置して下さい。\
こちらの投稿でDebianサーバのコマンド通り教えます。

```sh
curl -sL -o /etc/apt/trusted.gpg.d/morph027-gitea.asc https://packaging.gitlab.io/gitea/gpg.key
echo "deb [arch=amd64] https://packaging.gitlab.io/gitea gitea main" > /etc/apt/sources.list.d/gitea.list

sudo apt update
sudo apt install mysql gitea nginx
```

MySQLで「gitea」というユーザ及び「gitea」というデータベースを設置して下さい。

certbotでSSL証明書を受け取って下さい。\
サブドメイン（例：git.ドメイン.tld）を設置して下さい。

```sh
sudo systemctl stop nginx
sudo certbot certonly -d git.ドメイン名.tld
```

次はnginxのコンフィグファイルで下記を書き込んで下さい。

```sh
sudo nvim /etc/nginx/sites-enabled/gitea.conf
```

```
server {
  server_name git.ドメイン名.tld;

  access_log off;
  error_log off;

  listen 443 ssl;
  listen [::]:443 ssl;
  ssl_certificate /etc/letsencrypt/live/git.ドメイン名.tld/fullchain.pem;
  ssl_certificate_key /etc/letsencrypt/live/git.ドメイン名.tld/privkey.pem;

  location / {
    proxy_pass http://localhost:3000;
  }
}

server {
  if ($host = git.ドメイン名) {
    return 301 https://$host$request_uri;
  }
  
  listen 80;
  listen [::]:80;
  server_name git.ドメイン名.tld;
  return 404;
}
```

nginxサービスを起動して、giteaを再起動して下さい。

```sh
sudo systemctl start nginx
sudo systemctl restart gitea
```

Giteaのコンフィグファイルを編集して下さい。

```sh
sudo nvim /etc/gitea/app.ini
```

```
APP_NAME = （何でも良い）
...
[database]
DB_TYPE = mysql
HOST = 127.0.0.1:3306
NAME = gitea
USER = gitea
PASSWD = （あなたに設定したパスワード）
...

[repository]
ROOT = /home/git/gitea-repositories
DEFAULT_PUSH_CREATE_PRIVATE = true

[server]
#PROTOCOL = https
SSH_DOMAIN = git.ドメイン名.tld
DOMAIN = git.ドメイン名.tld
ROOT_URL = http://git.ドメイン名.tld
...
```

なお、GiteaのUIで新しい非公開のレポジトリーを作成して下さい。\
レポジトリー名は自由ですが、「memo」みたいな名前はオススメです。

## VimWikiの設置

サーバじゃなくて、自分のパソコンで、メモのフォルダを準備して下さい。

```sh
mkdir ~/ドキュメント/memo
cd ~/ドキュメント/memo
```

Neovimをインストールして下さい。

### Arch、Artix、Manjaro、AlterLinuxの場合：

```sh
sudo pacman -S neovim
```

### Debian、Devuan、Ubuntu、MX Linuxの場合：

```
sudo apt install neovim neovim-runtime
```

### Gentooの場合：

```sh
sudo emerge -a app-editors/neovim
```

### Void Linuxの場合：

```sh
sudo xbps-install -S neovim
```

### FreeBSDの場合：

```sh
doas pkg install neovim
```

### OpenBSDの場合：

```sh
doas pkg_add neovim
```

### macOSの場合（Homebrewインストールした後）：

```sh
brew install neovim
```

### Windowsの場合（Chocolateyインストールした後）：

```sh
choco install neovim
```

それ以下、Windowsでの設定する方法は不対応となります。

Vim-Plugをインストールして下さい。

```sh
sh -c 'curl -fLo "${XDG_DATA_HOME:-$HOME/.local/share}"/nvim/site/autoload/plug.vim --create-dirs https://raw.githubusercontent.com/junegunn/vim-plug/master/plug.vim'
```

Neovimのコンフィグファイルを編集して下さい。

```sh
nvim ~/.config/nvim/init.vim
```

```
...
set nocompatible
filetype plugin on
syntax on
...
Plug 'vimwiki/vimwiki'
...
" VimWiki
let g:vimwiki_list = [{'path': '~/dev/docs/', 'syntax': 'markdown', 'ext': '.md'}]
...
```

ZZで保存して閉じて下さい。\
新しいファイルを作って下さい。

```sh
nvim ~/ドキュメント/memo/index.md
```

Neovimの中に、「:PlugInstall」を入力して下さい。\
それで自動でVimWikiをインストールされます。\
Neovimを終了して、もう一回上記のコマンドで起動して下さい。

## VimWikiの使い方

マークダウンでメモを作れる様になります。\
文字を入力したら、ESCを押したら、文字でEnterキーを押すと、新しいリンクを作れます。\
もう一回Enterキーを押すと、新しいファイルを作成されます。\
CTRL+6で元のファイルに戻れます。

HTMLに書き出すには、「:VimwikiAll2HTML」を入力して下さい。

保存するには、いつでも通りにGitレポジトリにコミットして下さい。\
また、１台パソコンだけを持ったら、必要はありません。

以上
