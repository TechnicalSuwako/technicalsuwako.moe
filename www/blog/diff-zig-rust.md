title: 【プログラミング】RustとZigの違い
author: 凛
date: 2023-05-24 21:00
category: programming
----
## Zigとは？
[Zigは、隠れた制御フローや隠れたメモリ割り当て、プリプロセッサやマクロがないシステムプログラミング用言語です。](https://ziglang.org/ja/)\
アセンブリ言語よりも読みやすく、C言語よりも細かい制御が可能なため、アセンブリ言語よりは高水準でありながらC言語よりは低水準な言語と言えます。\
エラーを修正するまでエディタを終了することを防ぐので、C言語より安全だと言えます。\
新世代のC言語になり得るポテンシャルが非常に高いと思います。\
新しい言語ながら、特に日本人と中国人の開発者に人気があります。\
[また、日本発の派生言語であるZen言語もありますが、](https://web.archive.org/web/20220201164721/https://zen-lang.org/ja-JP/community/)
[プロプライエタリ・ソフトウェアであるため、特に推奨はできません。](https://www.gnu.org/proprietary/proprietary.ja.html)

## Rustとは？
[Rustは、メモリ安全性とスレッド安全性を保証するシステムプログラミング用の言語です。](https://www.rust-lang.org/ja/)\
Rustではメモリ安全性とスレッド安全性が重要なポイントとなっています。\
Linux Kernel 6.1以降ではカーネルにRustコンパイラが含まれています。\
低水準言語でありながら、Rustは非常に高水準な言語であるPythonで作られているようです。\
比較すると、Zigが新世代のC言語に、Rustが新世代のC++言語になろうとしていると言えます。\
[Rustは、特にLGBTというテロリストが多くの欧米で大人気です。](https://archive.is/IF1yS)

## Rustのメリット
* 安定して動作するソフトウェアを作りやすい
[* 豊富なコミュニティと充実したサポート（いっぱいおっぱい、僕元気♪）](https://youtube.owacon.moe/watch?v=DsBaC3_S-As)
* 海外で働くフリーランサーにとって、仕事が多い

## Rustのデメリット
* BSD（特にOpenBSDとNetBSD）に対応していない
* 全ての機能性が従属ソフトとして扱われる
* コンパイルが非常に遅い
* バイナリサイズが大きい（Go言語のバイナリも大きいが、それは高水準言語である為理解出来る）
* 奇妙なライブラリでリンクされている（Zigのデメリットの後で説明する）
* 大きな変化が速すぎる
* 大きな変更が頻繁に行われる
* [行動規範が存在する](https://www.rust-lang.org/ja/policies/code-of-conduct)
* [法律に関する問題が多い](https://www.rust-lang.org/ja/policies)

## Zigのメリット
* 書きやすい
* テスト機能が付いている
* [CとC++のライブラリを利用出来る](/blog/c-lib-in-zig-use.xhtml)
* ZigコンパイラはC言語やC++言語のコードもコンパイル出来る
* エラーを保存しながら報告する

## Zigのデメリット
* 実験段階のソフト
* 利用者が少ない為、サポートも少ない
* リンターが強制される
* [行動規範が存在する](https://raw.githubusercontent.com/ziglang/zig/master/.github/CODE_OF_CONDUCT.md)

## Rustのバイナリ VS Zigのバイナリ
一緒にやりましょう！！

```sh
mkdir -p ~/tmp/{rust,zig}
cd ~/tmp/rust
cargo init
cargo build
cd ../zig
zig init-exe
zig build
```

### まずはZigのバイナリを確認します

```sh
# ./zig-out/bin/zig
All your codebase are belong to us.
Run `zig build test` to run the tests.

# ls -thal ./zig-out/bin/zig
-rwxr-xr-x 1 suwako suwako 962K  5月 24 20:36 ./zig-out/bin/zig

# file ./zig-out/bin/zig
./zig-out/bin/zig: ELF 64-bit LSB executable, x86-64, version 1 (SYSV), statically linked, with debug_info, not stripped

# ldd ./zig-out/bin/zig
        動的実行ファイルではありません
```

静的バイナリ（バイナリだけを別のコンピュータにコピーすれば、実行できる）でありながら、サイズはわずか962キビバイトです。\
しかし、まだデバッグ情報が含まれています。\
それを削除すると、サイズはどうなるでしょう？

```sh
# strip ./zig-out/bin/zig && ls -thal ./zig-out/bin/zig
-rwxr-xr-x 1 suwako suwako 374K  5月 24 20:42 ./zig-out/bin/zig
```

驚くほど小さいですね！

### 次はRustのバイナリ

```sh
# ls -thal ./target/debug/rust
-rwxr-xr-x 2 suwako suwako 9.5M  5月 24 20:16 ./target/debug/rust

# file ./target/debug/rust
./target/debug/rust: ELF 64-bit LSB pie executable, x86-64, version 1 (SYSV), dynamically linked, interpreter /lib64/ld-linux-x86-64.so.2, BuildID[sha1]=4fd652bc3852eda0ef3d5281c51ef947e4ecb740, for GNU/Linux 4.4.0, with debug_info, not stripped

# ldd ./target/debug/rust
        linux-vdso.so.1 (0x00007ffefa4f9000)
        libgcc_s.so.1 => /usr/lib/libgcc_s.so.1 (0x00007fade07fd000)
        libc.so.6 => /usr/lib/libc.so.6 (0x00007fade0616000)
        /lib64/ld-linux-x86-64.so.2 => /usr/lib64/ld-linux-x86-64.so.2 (0x00007fade08a4000)
```

なぜlibcとlibgccを含めるのでしょうか？\
そして、「linux-vdso」とは何でしょうか？\
先に「奇妙な依存ソフトウェア」と述べた時、これを指していました。\
また、なぜ「Hello, World!」だけを出力するソフトウェアが9.5メビバイトも必要なのでしょう？\
Zigのバイナリは静的であることを覚えていますか？\
Rustは動的であるため、さらに奇妙に感じます。

デバッグ情報を削除すると…

```sh
# strip ./target/debug/rust && ls -thal ./target/debug/rust
-rwxr-xr-x 2 suwako suwako 319K  5月 24 20:50 ./target/debug/rust
```

結果はそこまで悪くありませんね。\
しかし、バイナリは動的なので、他のコンピュータにコピーした場合、ライブラリが異なるフォルダに入っていたり、存在しない場合、実行できません（例えば、ArtixでコンパイルしたものがDevuanで実行できない可能性がある）。

以上
