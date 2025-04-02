title: 【０７６】sp 1.5.0及びsimpas 1.1.0登場
uuid: 7c40e893-a0ff-4655-8f72-2e16d221d23d
author: 諏訪子
date: 2024-12-17 00:00:00
category: software release
----
## spって何？
spはシンプルなパスワードマネージャです。\
simpasはspのGUI版です。

## 変更
### sp 1.5.0
* パスワード表示で、「OpenPGP」かどうかの確認の追加
* 侵害されたパスワードの確認の追加 (**sp -b**)
* 複数サイトで同じパスワードを利用かどうか、パスワードの長さ、又はパスワードの強さの確認 (**sp -c all|length|strengh|dupplicate**)
* パスワードコピーの期間を設定出来る様に (**sp -y パスワードパス [秒]**)
* ワンタイムパスワード（OTP）を表示せずにコピー機能性の追加 (**sp -O パスワードパス [秒]**)
* Wayland対応の追加
* コピータイムアウトの間にCTRL+Cを押したら、クリップボードから取り消す様に
* コンフィグファイルの追加

### simpas 1.1.0
* パスワード表示で、「OpenPGP」かどうかの確認の追加
* 侵害されたパスワードの確認の追加
* 複数サイトで同じパスワードを利用かどうか、パスワードの長さ、又はパスワードの強さの確認
* パスワードを表示・非表示にする機能性の追加（デフォルトは非表示）
* ワンタイムパスワード（OTP）の場合、自動的に更新する様に
* アプリケーションアイコンの追加
* ライトモードの追加
* コンフィグファイルの追加

## ソースコード
### sp
[Gitler](https://gitler.moe/suwako/sp)\
[レポジトリ](https://076.moe/repo/src/sp)

### simpas
[Gitler](https://gitler.moe/suwako/simpas)\
[レポジトリ](https://076.moe/repo/src/simpas)

## マニュアル
### sp
[レポジトリ](https://076.moe/repo/man/sp)

## デスクトップファイル
### simpas
[レポジトリ](https://076.moe/repo/desktop)

## バイナリ
### sp
[レポジトリ](https://076.moe/repo/bin/sp)

### simpas
[レポジトリ](https://076.moe/repo/bin/simpas)

以上
