title: 【Neovim】テンプレートと作り方
uuid: 13f47701-b841-4a4e-9c74-37fc3a110657
author: 諏訪子
date: 2024-01-31 00:00:00
category: programming
----
[Zigの使い方第１部を投稿した後、この言語を辞めました。](/blog/zig-gengo-1.xhtml)\
理由は自由にフォーマット出来なかった事です。\
しかし、解決方法を見つけましたが、それが結構面倒くさいと思います：

```zig
// zig fmt: off
// vim: set ts=2 sts=2 sw=2 et:
```

各「.zig」ファイルで上記のコメントを自分で貼る事が必要為、「neovimは自動で追加されたら良いなぁ」と思ったら、やっぱりそれが可能です！

## ~/.config/nvim/init.lua

```lua
vim.api.nvim_exec([[
  autocmd BufNewFile *.zig 0r ~/.config/nvim/template.zig
]], false)
```

## ~/.config/nvim/template.zig

```zig
// zig fmt: off
// vim: set ts=2 sts=2 sw=2 et:
const std = @import("std");

```

それで、毎回「`nvim ほげほげ.zig`」で使って新しい「.zig」ファイルを作ったら、自動で上記の行列を追加されます。\
これを好みでしたので、他の言語のテンプレートを作りました。

## ~/.config/nvim/init.lua

```lua
-- テンプレート
vim.api.nvim_exec([[
  autocmd BufNewFile *.c 0r ~/.config/nvim/template.c
  autocmd BufNewFile *.cc 0r ~/.config/nvim/template.cc
  autocmd BufNewFile *.h 0r ~/.config/nvim/template.h
  autocmd BufNewFile *.hh 0r ~/.config/nvim/template.hh
  autocmd BufNewFile *.go 0r ~/.config/nvim/template.go
  autocmd BufNewFile *.md 0r ~/.config/nvim/template.md
  autocmd BufNewFile *.zig 0r ~/.config/nvim/template.zig
  autocmd BufNewFile *.php 0r ~/.config/nvim/template.php
]], false)
```

### template.c

```c
#include <stdio.h>

```

### template.cc

```cpp
#include <iostream>

```

### template.h

```c
#ifndef _H
#define _H



#endif
```

### template.hh

```cpp
#ifndef _HH
#define _HH

class i {
  public:

  private:

};

#endif
```

### template.go

```go
package main

import (
)

```

### template.php

```php
<?php

?>
```

以上
