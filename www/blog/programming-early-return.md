title: 【プログラミング】アドバイス２：早期リターン
uuid: d1ccf2bf-9977-443f-adbb-d21ef45c8786
author: 諏訪子
date: 2023-12-21 00:00:00
category: programming
----
[前回は運命のピラミッドを避ける事について話しました。](/blog/programming-pyramid-of-doom.xhtml)\
運命のピラミッドを避けるる際、早期リターンをよく使用します。

## 早期リターンとは？
早期リターンは、特定の条件が真（true）の場合に、関数の実行を直ぐに終了させる事です。\
例えば、エラーが発生した場合、関数の最後まで実行する意味はありませんので、早期リターンを使う方が良いでしょう。

C言語の例（早期リターンを使わない場合）：
```c
const char *get_token() {
  char *token = malloc(64);
  json_object *res = curl_con("auth/login", "POST", data, "");

  if (res == NULL) {
    printf("エラー：ログインに失敗。");
    token = "";
  }

  json_object *data_obj, *token_obj;
  if (!json_object_object_get_ex(res, "data", &data_obj)) {
    printf("JSONで'data'を見つけられませんでした。");
    token = "";
  }

  if (!json_object_object_get_ex(data_obj, "token", &token_obj)) {
    printf("JSONの'data'鍵で、'token'を見つけられませんでした。");
    token = "";
  }

  token = json_object_get_string(token_obj);
  json_object_put(res);

  return token;
}
```

上記の問題は、３つの異なるエラーチェックがあるにも関わらず、エラーが発生しても最後まで処理を続ける事です。\
それは非効率的です。\
さらに、上記の例ではメモリリークが発生する可能性がある為、この方法はお勧め出来ません。\
そこで、早期リターンを使って問題を解決しましょう。

```c
const char *get_token() {
  char *token = malloc(64);
  json_object *res = curl_con("auth/login", "POST", data, "");

  if (res == NULL) {
    printf("エラー：ログインに失敗。");
    free(token); // メモリ割当しましたが、エラーが発生したから、開放して下さい
    return NULL; // エラーが発生したから、終了しよう
  }

  json_object *data_obj, *token_obj;
  if (!json_object_object_get_ex(res, "data", &data_obj)) {
    printf("JSONで'data'を見つけられませんでした。");
    json_object_put(res); // エラーが発生したから、JSONオブジェクトを開放する
    free(token);
    return NULL;
  }

  if (!json_object_object_get_ex(data_obj, "token", &token_obj)) {
    printf("JSONの'data'鍵で、'token'を見つけられませんでした。");
    json_object_put(res);
    free(token);
    return NULL;
  }

  token = json_object_get_string(token_obj);
  json_object_put(res);

  return token;
}
```

結果は同じですが、処理が少なくなる為、効率が向上します。

以上
