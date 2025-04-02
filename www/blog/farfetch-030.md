title: 【０７６】farfetch 0.3.0登場
uuid: 7677bf78-4160-4843-8ffc-146fcc3898ce
author: 諏訪子
date: 2024-08-17 00:00:00
category: software release
----
## farfetchって何？
farfetchはneofetchみないにC言語で書いたシステム情報表示ツールです。

## 変更
* macOSでOS情報の修正
* macOS: 解像度の追加
* コンフィグのエラーの修正（osとhostのコンフリクト）
* Linux: 全てのケースに解像度を受け取る様に
* Linux: 「/proc/cpuinfo」で使ってCPU速さの受け取る様に
* Kubuntu、Xubuntu、Lubuntu、Arco、Hyperbola、Parabola、とPop OSロゴの追加
* Ubuntu MATE、Ubuntu Budgie、Ubuntu Lomiri、Ubuntu Unity、とUbuntu Cinnamonロゴの追加
* Linux: 「hostname」コマンドがなければ、「cat /etc/hostname」を実効する様に
* Linux: pacman、rpm対応
* LinuxとBSD: 今から、静的リンクがデフォルトになりました（動的リンクには「make LDFLAGS=-lc」をご利用下さい）
* 色コンフィグの追加
* カスタムロゴコンフィグの追加
* モジュールのキャッシングの追加（それでスピードアップする）
* コンフィグでロゴの設定の追加（隠し・表示、大・小、カスタムロゴ、LinuxとIllumosの場合：ディストロロゴ）
* マンページの英訳

## ソースコード
[Gitler](https://gitler.moe/suwako/farfetch)\
[レポジトリ](https://076.moe/repo/src/farfetch)

## バイナリ
[レポジトリ](https://076.moe/repo/bin/farfetch)

## マニュアル
[レポジトリ](https://076.moe/repo/man/farfetch)

## 寄付してダウンロード
[itch.io](https://technicalsuwako.itch.io/farfetch)

以上
