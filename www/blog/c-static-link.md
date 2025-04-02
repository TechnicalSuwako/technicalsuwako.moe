title: 【C言語】静的リンクする方法
uuid: c03d61db-815e-4c06-86a3-ee60f7fde458
author: 諏訪子
date: 2024-04-16 00:00:00
category: programming
----
## 注意点
もし貴方のLinuxディストリビューションがglibcライブラリを使用している場合、この投稿は恐らく動作しません。\
muslを使用したLinuxディストリビューションか、BSD OSをご検討下さい。\
muslを使用した人気のあるLinuxディストリビューションには、Void Linux、Gentoo、Alpine Linuxがあります。

## C言語でのソフトを静的リンクは可能！？
はい、可能です。\
前述の通り、glibcでは恐らく不可能です。\
しかし、muslを使用したLinuxディストリビューションで静的リンクを行うと、glibcを使用したLinuxディストリビューションでも実行出来るという事です。\
必要なのは、リンクする全てのライブラリの 「.a」 バージョンです。\
コンパイルするコマンドは、動的リンクとは非常に異なる見た目になります。\
例として、自分の「sp」を取り上げましょう。

動的リンクでは、コマンドは次の様になります：
```sh
cc -L/usr/local/lib -I/usr/local/include -o sp *.c -lgpgme -lassuan
```

しかし、静的リンクでは、このコマンドは次の様になります（OpenBSDの場合）：
```sh
cc -L/usr/local/lib -I/usr/local/include -o sp *.c -static -lgpgme -lcrypto -lc -lassuan -lgpg-error -lthr -lintl
```

## 必要なライブラリを受取方
どのライブラリをリンクする必要があるかを知るには、まず動的リンクされたバイナリをコンパイルする必要があります。\
その後、`ldd` コマンドを使用して必要なライブラリを調べる事が出来ます。

### OpenBSD
```sh
$ ldd ./sp
sp:
  Start            End              Type  Open Ref GrpRef Name
  00000664e28c2000 00000664e28cd000 exe   1    0   0      sp
  000006678e2b6000 000006678e317000 rlib  0    1   0      /usr/local/lib/libgpgme.so.24.2
  00000666f3610000 00000666f3824000 rlib  0    1   0      /usr/lib/libcrypto.so.53.0
  000006672dfe7000 000006672e0de000 rlib  0    1   0      /usr/lib/libc.so.99.0
  000006670aadd000 000006670aaf5000 rlib  0    1   0      /usr/local/lib/libassuan.so.2.1
  000006670b08d000 000006670b0b7000 rlib  0    2   0      /usr/local/lib/libgpg-error.so.3.26
  0000066744e3e000 0000066744e62000 rlib  0    3   0      /usr/local/lib/libintl.so.8.0
  0000066705496000 00000667055a8000 rlib  0    4   0      /usr/local/lib/libiconv.so.7.1
  00000667c6522000 00000667c6522000 ld.so 0    1   0      /usr/libexec/ld.so

$ file ./sp
./sp: ELF 64-bit LSB shared object, x86-64, version 1
```

### FreeBSD
```sh
$ ldd ./sp
./sp:
  libgpgme.so.43 => /usr/local/lib/libgpgme.so.43 (0x15272fd71000)
  libcrypto.so.30 => /lib/libcrypto.so.30 (0x15272e58e000)
  libc.so.7 => /lib/libc.so.7 (0x15273063b000)
  libassuan.so.8 => /usr/local/lib/libassuan.so.8 (0x15272ee04000)
  libgpg-error.so.0 => /usr/local/lib/libgpg-error.so.0 (0x152731580000)
  libthr.so.3 => /lib/libthr.so.3 (0x1527317bb000)
  libintl.so.8 => /usr/local/lib/libintl.so.8 (0x15273205b000)
  [vdso] (0x15272e207000)

$ file ./sp
./sp: ELF 64-bit LSB executable, x86-64, version 1 (FreeBSD), dynamically linked, interpreter /libexec/ld-elf.so.1, for FreeBSD 14.0 (1400097), FreeBSD-style, stripped
```

### NetBSD
```sh
$ export LD_LIBRARY_PATH=/usr/pkg/lib:$LD_LIBRARY_PATH
$ ldd ./sp
./sp:
  -lgpgme.11 => /usr/pkg/lib/libgpgme.so.11
  -lassuan.0 => /usr/pkg/lib/libassuan.so.0
  -lgpg-error.0 => /usr/pkg/lib/libgpg-error.so.0
  -lintl.1 => /usr/lib/libintl.so.1
  -lc.12 => /usr/lib/libc.so.12
  -lcrypto.15 => /usr/lib/libcrypto.so.15
  -lcrypt.1 => /lib/libcrypt.so.1

$ file ./sp
./sp: ELF 64-bit LSB executable, x86-64, version 1 (SYSV), dynamically linked, interpreter /usr/libexec/ld.elf_so, for NetBSD 10.0, stripped
```

### Void Linux
```sh
$ ldd ./sp    
  /lib/ld-musl-x86_64.so.1 (0x7f7689c5a000)
  libgpgme.so.11 => /lib/libgpgme.so.11 (0x7f7689bfb000)
  libcrypto.so.3 => /lib/libcrypto.so.3 (0x7f7689600000)
  libc.so => /lib/ld-musl-x86_64.so.1 (0x7f7689c5a000)
  libassuan.so.0 => /lib/libassuan.so.0 (0x7f7689be6000)
  libgpg-error.so.0 => /lib/libgpg-error.so.0 (0x7f7689bbe000)

$ file ./sp
./sp: ELF 64-bit LSB pie executable, x86-64, version 1 (SYSV), dynamically linked, interpreter /lib/ld-musl-x86_64.so.1, BuildID[sha1]=(), stripped
```

## 必要なライブラリをインストールする方法
OpenBSDとNetBSDでは、リポジトリからgpgmeのみをインストールすると、必要な全ての「.a」ファイルが既に入手出来ます。\
流石ね、OppaiBSDとNyuuBSD！

FreeBSDとVoid Linuxでは、libassuan.a と libgpgme.a のみが不足しています。\
特にVoid Linuxでは、libgpg-error.a も不足しています。

しかし、ソースからコンパイルすれば簡単に取得出来るという事です。\
それではやってみましょう！\
手順はFreeBSDとVoid Linuxの両方で有効ですので、コマンドは1度だけ提供します。

### libgpgme-error

```sh
wget https://www.gnupg.org/ftp/gcrypt/libgpg-error/libgpg-error-1.50.tar.gz
tar xfv libgpg-error-1.50.tar.gz
cd libgpg-error-1.50
./configure --enable-static
make
doas make install
```

Void Linuxのみ：
```sh
doas mv /usr/local/lib/libgpg-error.a /usr/lib
doas mv /usr/local/lib/libgpg-error.la /usr/lib
```

### libassuan
```sh
wget https://www.gnupg.org/ftp/gcrypt/libassuan/libassuan-3.0.1.tar.bz2
tar xvf libassuan-3.0.1.tar.bz2
cd libassuan-3.0.1
./configure --enable-static
make
doas make install
```

Void Linuxのみ：
```sh
doas mv /usr/local/lib/libassuan.a /usr/lib
doas mv /usr/local/lib/libassuan.la /usr/lib
```

### libgpgme
```
wget https://www.gnupg.org/ftp/gcrypt/gpgme/gpgme-1.23.2.tar.bz2
tar xvf gpgme-1.23.2.tar.bz2
cd gpgme-1.23.2
./autogen.sh
mkdir build && cd build
../configure --enable-maintainer-mode --enable-static
make
doas make install
```

Void Linuxのみ：
```sh
doas mv /usr/local/lib/libgpgme.a /usr/lib
doas mv /usr/local/lib/libgpgme.la /usr/lib
doas mv /usr/local/lib/libgpgmepp.a /usr/lib
doas mv /usr/local/lib/libgpgmepp.la /usr/lib
```

必要な全ての「.a」ファイルを取得したら、この投稿の冒頭で提供したコマンドを使用してコンパイルを開始出来ます。

## 結果
### OpenBSD
```sh
$ ldd ./sp
./sp:
  Start            End              Type  Open Ref GrpRef Name
  00000a9c31dc9000 00000a9c32131000 dlib  1    0   0      /home/suwako/dev/finish/sp/sp

$ file ./sp
./sp: ELF 64-bit LSB shared object, x86-64, version 1
```

### FreeBSD
```sh
$ ldd ./sp
ldd: ./sp: not a dynamic ELF executable

$ file ./sp
./sp: ELF 64-bit LSB executable, x86-64, version 1 (FreeBSD), statically linked, for FreeBSD 14.0 (1400097), FreeBSD-style, stripped
```

### NetBSD
```sh
$ export LD_LIBRARY_PATH=/usr/pkg/lib:$LD_LIBRARY_PATH
$ ldd ./sp
ldd: /home/suwako/dev/finish/sp/./sp-: invalid ELF class 2; expected 1

$ file ./sp
./sp: ELF 64-bit LSB executable, x86-64, version 1 (SYSV), statically linked, for NetBSD 10.0, stripped
```

### Void Linux
```sh
$ ldd ./sp
ldd: ./sp: Not a valid dynamic program

$ file ./sp
./sp: ELF 64-bit LSB executable, x86-64, version 1 (SYSV), statically linked, BuildID[sha1]=(), stripped
```

## ファイルサイズが大き過ぎ！！
静的リンクを行うと、必要な全てのコードがバイナリに含まれる為、再コンパイルする必要なしに異なるコンピュータに単一のバイナリをコピー出来ます。\
その為、バイナリのサイズは、動的リンクされたバイナリよりもかなり大きくなります。\
動的リンクされたバイナリはシステム上のどこかにある「.so」ファイルを指すだけであり、システム間で異なる可能性があり、更に使用されているバージョンが異なる可能性がある為、バイナリは全く移植性がありません。\
ローリングリリースのLinuxディストリビューションを使用している方は、ある時点で自分のプログラムが突然機能しなくなった経験があるかもしれません。\
これは、「.so」ファイルが新しいバージョンに更新された為に発生しますが、プログラム自体は未だ同じバイナリです。\
これがあたしが静的リンクを好む理由です！

但し、コンパイル後に単純に `strip` コマンドを使用する事で、バイナリサイズを大幅に削減できます。\
警告に気にしないで下さい。\
これはGNUの開発者の問題です（彼らはlibassuanとgpgmeを作成しました）。\
あたしの「sp」プログラムは100％のコードが正しいです。

```sh
$ cc -Wall -Wextra -O3 -I/usr/local/include -L/usr/local/lib -o sp *.c  -static -lgpgme -lcrypto -lc -lassuan -lgpg-error -lintl -liconv
engine-assuan.c(engine-assuan.o:(llass_set_engine_flags) in archive /usr/local/lib/libgpgme.a): warning: strcpy() is almost always misused, please use strlcpy()
stringutils.c:107(libgpg_error_la-stringutils.o:(_gpgrt_vfnameconcat) in archive /usr/local/lib/libgpg-error.a): warning: stpcpy() is dangerous; do not use it
estream-printf.c:1114(libgpg_error_la-estream-printf.o:(do_format) in archive /usr/local/lib/libgpg-error.a): warning: sprintf() is often misused, please use snprintf()
assuan-handler.c(libassuan_la-assuan-handler.o:(assuan_write_status) in archive /usr/local/lib/libassuan.a): warning: strcat() is almost always misused, please use strlcat()

$ ls -thal sp
-rwxr-xr-x  1 suwako  suwako   8.9M Apr 16 14:37 sp

$ strip sp
$ ls -thal sp
-rwxr-xr-x  1 suwako  suwako   3.4M Apr 16 14:39 sp
```

以上
