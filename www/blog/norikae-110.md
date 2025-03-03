title: 【０７６】乗換 1.1.0登場
author: 凛
date: 2024-06-29
category: software release
----
## 乗換って何？
乗換はCLIでの路線情報を確認ツールです。\
Yahooでスパイウェアが多すぎるため、CLI用のフロントエンドを作りました。

## 変更
* GNU Make → BSD Make
* GPLv2 → ISC
* 「--no-」のオプションの変更
* help → usage
* 「同駅内徒歩」表示のバグの修正
* 「gookit/color」という従属ソフトの取消
* 「当駅始発」がなければ、全角空白文字で使って「◯◯線」と「◯◯行」を分けて

## ソースコード
[Gitler](https://gitler.moe/suwako/norikae)\
[レポジトリ](https://076.moe/repo/bin/norikae)

## バイナリ
[レポジトリ](https://076.moe/repo/bin/norikae)

## マニュアル
[レポジトリ](https://076.moe/repo/man/norikae)

以上
