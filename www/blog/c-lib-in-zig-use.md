title: 【プログラミング】Zig言語を使ってC向けのライブラリを利用する方法
author: 凛
date: 2023-05-18
category: programming
----
ゲーム開発といえば、C++やUnity、Unreal等がが頭に浮かびますね。\
しかし、新しい言語であるZigを使う事をお勧めします。\
新しい言語だからと言って、必要なライブラリ（SDL、VulkanやOpenGL等）は存在しないと思うかもしんが。心配は無用です。\
Zig言語の大きなメリットは、C言語及びC++のライブラリをそのまま利用出来る事です！

[まずはZigコンパイラをインストールしましょう。](https://repology.org/project/zig/versions)

```sh
mkdir zigdev && cd zigdev
nvim main.zig
```

以下に示すソースコードを使用します。\
まずは内容を確認し、何が起こるかを予想してみて下さい。

```zig
const std = @import("std");
const c = @cImport({
    @cInclude("SDL2/SDL.h");
});

pub fn main() !void {
    if (c.SDL_Init(c.SDL_INIT_VIDEO) != 0) {
        c.SDL_Log("開始失敗： %s", c.SDL_GetError());
        return error.SDLInitializationFailed;
    }
    // defer = 終了する時、実行すると意味だ。
    defer c.SDL_Quit();

    var window = c.SDL_CreateWindow("hellow", c.SDL_WINDOWPOS_CENTERED, c.SDL_WINDOWPOS_CENTERED, 640, 400, c.SDL_WINDOW_OPENGL) orelse {
        c.SDL_Log("ウィンドウ創作失敗： %s", c.SDL_GetError());
        return error.SDLInitializationFailed;
    };
    defer c.SDL_DestroyWindow(window);

    var renderer = c.SDL_CreateRenderer(window, 0, c.SDL_RENDERER_PRESENTVSYNC) orelse {
        c.SDL_Log("レンダー創作失敗： %s", c.SDL_GetError());
        return error.SDLInitializationFailed;
    };
    defer c.SDL_DestroyRenderer(renderer);

    mainloop: while (true) {
        var sdl_event: c.SDL_Event = undefined;
        while (c.SDL_PollEvent(&sdl_event) != 0) {
            switch (sdl_event.type) {
                c.SDL_QUIT => break :mainloop,
                else => {},
            }
        }

        _ = c.SDL_SetRenderDrawColor(renderer, 0xff, 0xff, 0xff, 0xff);
        _ = c.SDL_RenderClear(renderer);
        var rect = c.SDL_Rect{ .x = 0, .y = 0, .w = 60, .h = 60 };
        const a = 0.001 * @intToFloat(f32, c.SDL_GetTicks());
        const t = 2 * std.math.pi / 3.0;
        const r = 100 * @cos(0.1 * a);
        rect.x = 290 + @floatToInt(i32, r * @cos(a));
        rect.y = 170 + @floatToInt(i32, r * @sin(a));
        _ = c.SDL_SetRenderDrawColor(renderer, 0xff, 0, 0, 0xff);
        _ = c.SDL_RenderFillRect(renderer, &rect);
        rect.x = 290 + @floatToInt(i32, r * @cos(a + t));
        rect.y = 170 + @floatToInt(i32, r * @sin(a + t));
        _ = c.SDL_SetRenderDrawColor(renderer, 0, 0xff, 0, 0xff);
        _ = c.SDL_RenderFillRect(renderer, &rect);
        rect.x = 290 + @floatToInt(i32, r * @cos(a + 2 * t));
        rect.y = 170 + @floatToInt(i32, r * @sin(a + 2 * t));
        _ = c.SDL_SetRenderDrawColor(renderer, 0, 0, 0xff, 0xff);
        _ = c.SDL_RenderFillRect(renderer, &rect);
        c.SDL_RenderPresent(renderer);
    }
}
```

![](https://ass.technicalsuwako.moe/Screenshot_20230518_215802.png)

特に以下のコードをご覧下さい。

```zig
const std = @import("std");
const c = @cImport({
    @cInclude("SDL2/SDL.h");
});
```

@importはZig言語のライブラリーのインクルードする為の物で、@cImportはC言語のライブラリのインクルードする為の物です。\
`#DEFINE`と同じ役割を果たすのが、`@cDefine`です。\
例えば：

```zig
@cDefine("_GNU_SOURCE", {})
```

@cImportの後しろでは、「c.何々」でC言語の関数等を利用する事が出来ます。\
例えば：

```zig
    if (c.SDL_Init(c.SDL_INIT_VIDEO) != 0) {
        c.SDL_Log("開始失敗： %s", c.SDL_GetError());
        return error.SDLInitializationFailed;
    }
```

コンパイルするには：

```sh
zig build-exe main.zig -O ReleaseSmall --name rei --library SDL2 --library SDL2main --library c -isystem "/usr/include" -L "/usr/lib"
```

以上
