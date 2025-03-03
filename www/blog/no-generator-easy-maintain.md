title: 【HTML】静的サイトジェネレーター利用せず簡単に静的サイト編集する方法
author: 凛
date: 2023-02-08
category: programming,webdev
----
昨日は静的サイト管理する方法を教えました。\
でも、編集はどう？\
例えば、会社名が変わったら、一個ずつ編集は必要でしょうか？\
必要はないわ！

## sedで会社名の変更

`sed`コマンドですべてのページで変更出来ますよ！

```sh
cd src
find . -type f -name "*.html" -exec sed -i 's/クソガキ株式会社/テクニカル諏訪子開発サービス/g' {} +
```

上記コマンドですべてのページで「クソガキ株式会社」が「テクニカル諏訪子開発サービス」に変更されます。\
でも、「クソガキ株式会社」の方が良いですので：

```sh
find . -type f -name "*.html" -exec sed -i 's/テクニカル諏訪子開発サービス/クソガキ株式会社/g' {} +
```

## sedでマークダウンからHTMLに交換

この同じsedコマンドで、すべての「\」を「&lt;br /&gt;」に交換出来ます。\
そうして、「# ほげほげ」を「&lt;h1&gt;ほげほげ&lt;/h1&gt;」に交換し、「\[ほげほげ\]\(/hogehoge.html\)」を「&lt;a href="/hogehoge.html"&gt;ほげほげ&lt;/a&gt;」に交換します。\
例えば、`toiawase.html`ページで：

```md
# 問い合わせ
クソガキ株式会社\
地獄県鬼市死亡街6丁目6-6　サタンパレス666階\
最寄り駅：JL神様線　死後駅　徒歩約5ヶ月\
エレベーターがありません。\
[トップページへ](/index.html)
```

```sh
sed -i 's/^# \(.*\)/<h1>\0<\/h1>/g' toiawase.html #h1タグ
sed -i 's/\\/<br \/>/g' toiawase.html #brタグ
sed -i 's/^\(.*\)/    \0/g' toiawase.html #４つ空白を入る
sed -i 's/\[\(.*\)\](\(.*\))/<a href="\2">\1<\/a>/g' toiawase.html #リンクタグ
```

結果は下記ですね：

```html
    <h1>問い合わせ</h1>
    クソガキ株式会社<br />
    地獄県鬼市死亡街6丁目6-6　サタンパレス666階<br />
    最寄り駅：JL神様線　死後駅　徒歩約5ヶ月<br />
    エレベーターがありません。<br />
    <a href="/index.html">トップページへ</a>
```

その時から、マークダウンで書きましょう！！

```sh
mv index.{html,md}
mv toiawase.{html,md}
```

### index.md

```md
# クソガキ株式会社へようこそ
私達のサービスはクソ物凄いだぜ！！
```

### toiawase.md

```md
# 問い合わせ
クソガキ株式会社\
地獄県鬼市死亡街6丁目6-6　サタンパレス666階\
最寄り駅：JL神様線　死後駅　徒歩約5ヶ月\
エレベーターがありません。\
[トップページへ](/index.html)
```

## sedでメニューのリンクを「active」タグを追加する方法

まずはCSSで新しい行列を追加して下さい：

```css
...

.active {
  background: #ea44fb;
  color: #000 !important;
  padding: 4px;
}
```

`cat`でマージした後、下記の`sed`コマンドを使ってこのタグを追加出来ます。\
今回は凄く簡単だわ〜

```sh
sed -i "s/href=\"\/\"/href=\"\/\" class=\"active\"/g" index.html
sed -i "s/href=\"\/toiawase.html\"/href=\"\/toiawase.html\" class=\"active\"/g" toiawase.html
```

## make.shの変更

スクリプトはこれになりました：

```sh
#!/bin/sh
rm -rf www/*
cp -v src/style.css www
cd src

for name in *.md; do
  newname="$(echo "$name" | sed -ne 's/md/html/gp')"
  cp $name $newname #mdからhtmlにコピーする

  # マークダウンはHTML化
  sed -i 's/^# \(.*\)/<h1>\1<\/h1>/g' $newname #h1タグ
  sed -i 's/\\/<br \/>/g' $newname #brタグ
  sed -i 's/^\(.*\)/    \0/g' $newname #４つ空白を入る
  sed -i 's/\[\(.*\)\](\(.*\))/<a href="\2">\1<\/a>/g' $newname #リンクタグ

  # 合体
  cat include/header.html include/menu.html $newname include/footer.html >> ../www/$newname
  echo "'src/$name' -> 'www/$newname'"

  # HTMLファイルはもう不要だ
  rm -rf $newname
done

cd ../www

# 「active」タグを付く
for name in *.html; do
  if [ $name = 'index.html' ]; then
    sed -i "s/href=\"\/\"/href=\"\/\" class=\"active\"/g" $name
  else
    sed -i "s/href=\"\/$name\"/href=\"\/$name\" class=\"active\"/g" $name
  fi
done

cd ..
```

ところで、こちらのプロジェクトのファイルは全部Gitlerにコミットしました。\
[昨日のコミット](https://gitler.moe/suwako/sizutekipage/src/commit/11105976503e15388986a0d3b9cf211e6f9695cd)\
[今日のコミット](https://gitler.moe/suwako/sizutekipage/src/commit/b50a5b45c3915c32b856c7fc5b07cc7bc3aa70dd)

以上
