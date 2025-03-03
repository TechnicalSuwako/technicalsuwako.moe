title: 【プログラミング】アドバイス１：運命のピラミッドを回避
author: 凛
date: 2023-12-05
category: programming
----
プログラミングしたら、気づかずに運命のピラミッドを作る可能性が非常に高いです。\
これを回避した方が良いと思います。

## 運命のピラミッドとは？

ブログラミングで、運命のピラミッドとは、ネストされたインデントの多くのレベルを使用し、コードが読みにくくなる事です。\
例えば、下記のC言語のコードは運命のピラミッドの問題は発生します。

```c
int main(int argc, char **argv) {
  if (argc >= 2) {
    Display *d = XOpenDisplay(NULL);
    if (d != NULL) {
      int src = DefaultScreen(d);
      Window w = XCreateSimpleWindow(d, RootWindow(d, scr), 0, 0, 500, 500, 1, BlackPixel(d, scr), WhitePixel(d, scr));
      XSelectInput(d, w, ExposureMask | KeyPressMask);
      XMapWindow(d, w);
      XFlush(d);

      GC gc = XCreateGC(d, w, 0, NULL);
      if (gc != NULL) {
        XImage *ximg = openimg(d, argv[1]):
        if (ximg != NULL) {
          // などなど
        }
      } else {
        fprintf(stderr, "グラフィックス内容を創作に失敗しました。\n");
      }
    } else {
      fprintf(stderr, "画像を開けられません。\n");
    }
  } else {
    printf("使用方法： %s <画像ファイル>\n", argv[0]);
  }

  return 0;
}
```

変わりに、下記のコードを書いた方が良いです。

```c
int main(int argc, char **argv) {
  if (argc < 2) { // argcは２つ以下の場合、続行する意味はありません。
    printf("使用方法： %s <画像ファイル>\n", argv[0]);
    return 1;
  }

  Display *d = XOpenDisplay(NULL);
  if (d == NULL) { // 同様に、DisplayがNULLの場合、続行する意味はありません。
    fprintf(stderr, "画像を開けられません。\n");
    return -1;
  }

  int src = DefaultScreen(d);
  Window w = XCreateSimpleWindow(d, RootWindow(d, scr), 0, 0, 500, 500, 1, BlackPixel(d, scr), WhitePixel(d, scr));
  XSelectInput(d, w, ExposureMask | KeyPressMask);
  XMapWindow(d, w);
  XFlush(d);

  GC gc = XCreateGC(d, w, 0, NULL);
  if (gc == NULL) { // 同じパターン
    fprintf(stderr, "グラフィックス内容を創作に失敗しました。\n");
    return -1;
  }

  XImage *ximg = openimg(d, argv[1]):
  if (ximg == NULL) { // また、同じパターン
    fprintf(stderr, "画像を開けられません： %s\n", argv[1]);
    XFreeGC(d, gc);
    XCloseDisplay(d);
    return -1;
  }

  // などなど

  return 0;
}
```

[実際、C言語の開発者は運命のピラミッドを作る事は珍しいです。](https://gitler.moe/suwako/mivfx/src/branch/master/main.c)\
この問題は主にWeb開発で多く起こります。\
[ですから、PHPの例えはご覧下さい。](https://gitler.moe/tak4/bibis/src/branch/master/data-post.php)

```php
	$thread_title = '';
	if ($thread_id > '') {
		$thread_title = load_post_title_by_id($thread_id);
		if (!$thread_title > '') {
			$thread_title = '無題#' . mb_substr($thread_id, 0, 7);
		}
	}
```

少しだけ変わります。

```php
  $thread_title = '';

  if ($threadid != '') { // 文字列で「>」を使用すると、バグが発生する可能性があります。
    $thread_title = load_post_title_by_id($threadid);
  }

  if ($thread_title != '') { // !$thread_title > '' はかなりおかしいので、これも修正しました。
    $thread_title = '無題#' . mb_substr($threadid, 0, 7);
  }
```

以上
