title: 【Zig言語】第１部～基本的な紹介・セットアップ・「こんにちは、世界」
author: 凛
date: 2023-08-01
category: programming
----
## Zig言語シリーズ
このブログでは様々なプログラミング言語の使い方を紹介します。\
主にZig、PHP、Go言語、C言語について解説し、更にはFLTK、Raylib、OpenGL等も取り上げます。

## Zig言語とは？
Zigは非常に新しい言語です。\
Goが新しい形のPHP、Carbonが新しいC++、Kotlinが新しいJava、TypeScriptが新しいJavascript、Swiftが新しいObjective-Cのように、Zigは新しい形のC言語と考える事が出来ます。\
初めて見ると、Zig言語は難しそうに見えますが、約1週間使ってみれば、そう難しくは感じなくなります。\
現在、最新バージョンは0.10.1ですが、今週中には0.11.0のリリースが予定されています。\
このシリーズではそのバージョンを使用します。

## C言語とZig言語の違いは？
C言語は16ビットの世代で作られましたが、Zigは64ビットの世代で作られたため、Zigの方がモダンな言語といえます。\
Zig言語は、Rustのような安全性とGo言語のようなシンプルさを持っています。\
コードの違いを以下で示します：

### C
```c
#include <stdio.h>

int tuika (int a, int b) {
  return a + b;
}

int main () {
  int a = 1;
  int b = 2;
  printf("%d\n", tuika(a, b));

  return 0;
}
```

### Zig
```zig
const std = @import("std");

fn tuika(a: u8, b: u8) u8 {
  return a + b;
}

pub fn main() !void {
  const a: u8 = 1;
  const b: u8 = 2;
  std.debug.print("{d}\n", .{tuika(a, b)});
}
```

よく見ると、スタイルは殆ど同じです。\
ただし、main関数の戻り値はintではなくvoidになります。\
そして、Zigではintが一つの型ではなく、様々なサイズ（u8～u128、i8～i128）があります。\
また、C言語で「型名 変数名 = 値」や「戻り値の型名 関数名 (パラメータ)」と表現するところを、Zig言語では「変更可否 変数名: 型名 = 値」や「公開・非公開 fn 関数名(パラメータ) 戻り値の型名」と表現します。

## セットアップ
バージョン0.11.0がリリースされれば、パッケージマネージャからインストール出来る様になると思います。\
そうでない場合は、以下のコマンドを実行してください。\
まず、LLVM16以上が必要です。\
それをインストールしたら、Zigをコンパイルする方法は：

```sh
cd zig-*
mkdir build
cd build
cmake .. -DZIG_STATIC_LLVM=ON -DCMAKE_PREFIX_PATH=/usr
make install -DPREFIX=/usr
```

注意：あたしはCRUXでしかコンパイル出来ませんでした。\
Artix、OpenBSD、FreeBSDでは失敗しました。\
Devuanは確認していません。

インストール後、新しいフォルダを作り、新しいプロジェクトを作成しましょう：

```sh
mkdir hello
cd hello
zig init-exe
```

現在のファイルは以下の様になります：

```
.
├── build.zig
└── src
    └── main.zig
```

そのまま`zig build run`を実行すると：

```
# zig build run
All your codebase are belong to us.
Run `zig build test` to run the tests.
```

## 「こんにちは、世界！」
build.zigについては次の記事で紹介します。\
まず、src/main.zigを開き、全て削除し、以下のコードを書いて下さい。

```zig
const std = @import("std");
const std.io = io;

pub fn main() !void {
    const stdout_file = io.getStdOut().writer();
    var bw = io.bufferedWriter(stdout_file);
    const stdout = bw.writer();

    try stdout.print("こんにちは、世界！\n", .{});

    try bw.flush();
}
```

保存すると、以下のエラーが表示されます：

```
  1 main.zig|2 col 10| : error: expected ';' after declaration
```

はい、エラーがあると、それを修正するまでテキストエディターを閉じる事が出来ません。\
エラーを直しましょう！

```zig
const io = std.io; // ioとstd.ioを交換しましょう。
```

### ビルドと実行すると

```
# zig build run
こんにちは、世界！
```

### コードの解説

```zig
const std = @import("std");
```

これにより、Zigの公式標準ライブラリを使用出来る様になります。

```zig
const io = std.io;
```

これにより、ioコマンドをより簡単に実行出来る様になります。\
例えば、「std.io.getStdOut().writer();」を「io.getStdOut().writer();」に短縮出来ます。\
勿論、「const writer = std.io.getStdOut().writer();」と書く事も可能ですが、一度しか実行しないならばそれはもったいないです。

```zig
pub fn main() !void {}
```

pubは公開を意味し、fnは関数を意味します。\
JavaやC#を使った経験があれば、「public function」の様な物です。\
興味深い部分は「!void」です。\
この「!」は「anyerror」と同じ意味を持ちます。\
「void」だけであれば、戻り値の型はいつでもvoidですが、「!void」の場合は「エラーがあれば、そのエラーを返し、なければvoidになる」という意味になります。\
とても便利だわー！！

```zig
    try stdout.print("こんにちは、世界！\n", .{});
```

最後に、この「try」は「このコマンドがメモリ上で安全であれば、実行してください」という意味を持ちます。\
また、この「.{}」は常に必要です。\
値を使う場合は、それを「.{}」の中に入れましょう。\
例えば：

```zig
const age: u8 = 20;
const name: []const u8 = "田中";

try stdout.print("{s}さん、{d}歳になったら、大人ですよ。\n", .{ name, age });
```

```
田中さん、20歳になったら、大人ですよ。
```

生成されるバイナリはzig-out/binフォルダに格納されます。

続く
