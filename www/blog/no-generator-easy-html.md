title: 【HTML】静的サイトジェネレーター利用せず簡単に静的サイト管理する方法
author: 凛
date: 2023-02-07
category: programming,webdev
----
手動で静的サイトを作るのは簡単ですが、ちょっと面倒くさいですので、みんなはCMS（WordPress、fc2等）を使います。\
でも、CMSは凄く重くて、遅くて、不安ですから、あたしみたいな方は静的サイトジェネレーターを使っています。\
今回はジェネレーター利用せず静的サイトを作って、管理する方法を紹介します。

# インストール

今回は特に新しいソフトのインストールするのは不要ですが、公開するため「rsync」だけをインストールする事が必要となります。\
でも、本日の記事は公開ステップを紹介しませんので、インストールしなくてはOKです。

# フォルダーとファイルの創作

まずは新しいフォルダーとファイルを創作しましょう。\
HTMLと言えば、ヘッダー、メニュー、コンテンツ、フッターが思い出しますわね。\
ですから、ヘッダー、メニュー、及びフッターは別々のフォルダーに貼りますね。

```sh
mkdir -p sizutekipage/{src,www}
mkdir sizutekipage/src/include
touch sizutekipage/src/{index,toiawase}.html
touch sizutekipage/src/include/{header,footer,menu}.html
touch sizutekipage/src/style.css
cd sizutekipage/src
```

ヘッダー、フッター、とメニューを作りましょう！

## include/header.html

```html
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja">
<head>
  <meta content="text/html; charset=utf-8" http-equiv="content-type" />
  <title>クソガキ株式会社</title>
  <link rel="stylesheet" type="text/css" href="/style.css" />
</head>
<body>
```

## include/footer.html

```html
    <hr />
    <center>
      クソガキ株式会社
    </center>
  </div>
</body>
</html>
```

## include/menu.html

```html
  <div class="menu">
    <a href="/">トップ</a> |
    <a href="/toiawase.html">問い合わせ</a>
  </div>
  <div class="container">
```

## style.css

```css
body {
  background: #000;
  color: #fff;
  margin: 0;
}

a {
  color: #666;
}

.menu {
  background: #444;
  font-size: 14px;
  padding: 8px;
  margin-bottom: 12px;
}

.menu > a {
  color: #ea44fb;
}

.container {
  background: #00f;
  border: 1px solid #77a;
  margin: auto;
  width: 100%;
  max-width: 1200px;
  padding: 4px;
}
```

## index.html

```html
    <h1>クソガキ株式会社へようこそ</h1>
    私達のサービスはクソ物凄いだぜ！！
```

## toiawase.html

```html
    <h1>問い合わせ</h1>
    クソガキ株式会社<br />
    地獄県鬼市死亡街6丁目6-6　サタンパレス666階<br />
    最寄り駅：JL神様線　死後駅　徒歩約5ヶ月<br />
    エレベーターがありません。
```

# コンパイル

catコマンドを使って、ウエブサイトを作成しましょう！！\
まずはCSSファイルをコピーして下さい。

```sh
cd ..
cp src/style.css www
cat src/include/header.html src/include/menu.html src/index.html src/include/footer.html >> www/index.html
cat src/include/header.html src/include/menu.html src/toiawase.html src/include/footer.html >> www/toiawase.html
```

作成成功！！

# スクリプト化

しかし、毎回繰り返すのは面倒くさいですね。\
スクリプトを作りましょう！！

```sh
touch make.sh
chmod +x make.sh
nvim make.sh
```

## make.sh

```sh
#!/bin/sh
rm -rf www/*
cp -v src/style.css www
cd src

for name in *.html; do
  cat include/header.html include/menu.html $name include/footer.html >> ../www/$name
  echo "'src/$name' -> 'www/$name'"
done

cd ..
```

作成するには、「sizutekipage」フォルダーから`./make.sh`を実行して下さい。\
ね、簡単でしょ？

以上
