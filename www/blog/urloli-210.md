title: 【０７６】URLロリ 2.1.0登場
author: 凛
date: 2023-05-13
category: software release
----
URLロリ は2.1.0にバージョンアップしました！！

## URLロリって何？
URLロリはクッソ小さいURL短縮作成ソフトだわ〜♡

## 変更
* ローカライズは関数化
* API機能性
* サーバーのソースコードを短くに

## APIについて
[/api](https://urlo.li/api)\
インスタンスURL及びバージョンを確認（GET）

### 例の結果

```
{
  "url": "https://urlo.li",
  "version": "2.1.0"
}
```

[/api/lolify](https://urlo.li/api/lolify)\
URLを短縮する（既に存在する場合、短縮済みURLを表示） (POST)

### 必須のパラメートル

* url

### 例の結果

既に存在する場合

```
curl -d url=https://technicalsuwako.moe https://urlo.li/api/lolify
{
  "code": 200,
  "error": "",
  "url": "https://urlo.li/yoWJx",
  "origin": "https://technicalsuwako.moe",
  "isnew": false
}
```

新しく追加された場合

```
curl -d url=https://technicalsuwako.moe/about https://urlo.li/api/lolify
{
  "code": 200,
  "error": "",
  "url": "https://urlo.li/fiW3B",
  "origin": "https://technicalsuwako.moe/about",
  "isnew": true
}
```

エラーの場合

```
curl -d url=technicalsuwako.moe https://urlo.li/api/lolify
{
  "code": 400,
  "error": "URLは「http://」又は「https://」で始めます。",
  "url": "",
  "origin": "",
  "isnew": false
}%
```

```
curl -d url=https://technicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moetechnicalsuwako.moe https://urlo.li/api/lolify
{
  "code": 400,
  "error": "URLは500文字以内です。",
  "url": "",
  "origin": "",
  "isnew": false
}
```

## ソースコード
[Gitler](https://gitler.moe/suwako/urloli)

## 公式インスタンス
[https://urlo.li/](https://urlo.li/)

## ダウンロード
[リリースページ](https://gitler.moe/suwako/urloli/releases)

以上
