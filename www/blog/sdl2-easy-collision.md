title: 【SDL2】簡単な3D衝突検出の解説
author: 凛
date: 2024-02-17
category: gamedev
----
最近、自作のゲームを作成していますが、衝突検出を実装する事に懸念を抱きました。\
しかし、作った後はそんなに難しくないと気づきました。\
その為、今回はこれについて説明します。\
記事での利用している言語はZig言語ですが、C言語やC++でも使えます。

## プログラミングタイム！！
3つのオブジェクトを設置します。

```zig
// オブジェクト
const Wall   = struct { x: f32, y: f32, z: f32, size: f32 };
const Floor  = struct { x: f32, y: f32, z: f32, size: f32 };
const Player = struct {
  xpos: f32,
  ypos: f32,
  zpos: f32,
  xsize: f32,
  ysize: f32,
  zsize: f32,
  velocity: f32,
  gravity: f32,
};

var wall   = Wall   { .x = -6.0, .y =  0.0, .z = -26.0, .size =  6.0 };
var floor  = Floor  { .x = -6.0, .y = -7.0, .z = -26.0, .size = 50.0 };
var player = Player {
  .xpos = 0.0,
  .ypos = 50.0,
  .zpos = 0.0,
  .xsize = 0.2,
  .ysize = 0.8,
  .zsize = 0.2,
  .velocity = 0.1,
  .gravity = 0.2,
};

fn draw() void {
  // ここは`glBegin`、`glVertex3f`等を設置しますが、長すぎる為、これはスキップします。
}
```

上記のコードで、主に「gravity」や「velocity」が大切です。\
次は衝突検出の関数を作ります。

```zig
fn checkPosToObj(xpos: f32, ypos: f32, zpos: f32, size: f32, obj: anytype) bool {
  const thresholdX: f32 = size;
  const thresholdY: f32 = size;
  const thresholdZ: f32 = size;

  const deltaX: f32 = @abs(xpos - obj.xpos);
  const deltaY: f32 = @abs(ypos - obj.ypos);
  const deltaZ: f32 = @abs(zpos - obj.zpos);

  return deltaX < thresholdX and
    deltaY < thresholdY and
    deltaZ < thresholdZ;
}
```

床と壁のサイズ値は1つだけで、プレイヤは3つ(X, Y, Z)がある為、上記を使った方が良いですね。\
両方のオブジェクトが3つのサイズ値があれば、下記の関数を使っては良いと思います。

```zig
fn checkObjToObj(a: anytype, b: anytype) bool {
  const thresholdX: f32 = a.xsize;
  const thresholdY: f32 = a.ysize;
  const thresholdZ: f32 = a.zsize;

  const deltaX: f32 = @abs(a.xpos - b.xpos);
  const deltaY: f32 = @abs(a.ypos - b.ypos);
  const deltaZ: f32 = @abs(a.zpos - b.zpos);

  return deltaX < thresholdX and
    deltaY < thresholdY and
    deltaZ < thresholdZ;
}
```

thresholdはいつでもプレイヤ以外のオブジェクトのサイズとなります。

```zig
pub fn main() !void {
  // ...
  // 衝突検出
  if (checkPosToObj(floor.x, floor.y, floor.z, floor.size, player)) {
    std.debug.print("床を触って良かった! ypos = {d}\n", .{player.ypos});
    player.ypos = floor.y+(player.ysize);
  } else {
    std.debug.print("ああああああ!! ypos = {d}\n", .{player.ypos});
    player.ypos -= player.gravity;
  }

  if (checkPosToObj(plane.x, plane.y, plane.z, plane.size, player)) {
    std.debug.print("壁だわ...\n", .{});
    player.zpos = plane.z+(player.zsize);
  }
  // ...
}
```

## 結果

<video src="https://ass.technicalsuwako.moe/zig-cd.ogv" controls="controls" style="max-height: 400px;"></video>

ねぇ！簡単でしょ〜！

以上
