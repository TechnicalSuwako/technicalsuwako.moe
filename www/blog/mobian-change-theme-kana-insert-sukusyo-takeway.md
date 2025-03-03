title: 【Mobian】テーマの変更、日本語（今は仮名だけ）を入力、スクショを取り方
author: 凜
date: 2020-12-19
category: smartphone,unix,rant
----
スマホでMobian（モービアン）を使っていますが、日常使えるにはもう少し変更しないといけません。

# まずはパソコン用のソフトを強制スケールを一番必要な直しだと思います。

```sh
gsettings set sm.puri.phoc scale-to-fit true
```

# 後はテーマを変更しました。

## まずは背景専用フォルダを作った方が良い

```sh
mkdir .local/share/wallpapers
```

後は何でも画像をこのフォルダに貼って下さい。\
[「suikabg.jpg」という画像を使いました。](https://ass.technicalsuwako.moe/suikabg.jpg)

## パソコン→スマホの場合

```sh
scp suikabg.jpg mobian@スマホのIPアドレス:~/.local/share/wallpapers
```

## 背景を変えるコマンドは

```sh
gsettings set org.gnome.desktop.background picture-uri 'file:///home/mobian/.local/share/wallpapers/suikabg.jpg'
```

![](https://ass.technicalsuwako.moe/20201130_15h33m25s_grim.png)

# Manjaroを使ったら、デフォルトテーマはダークテーマですが、Mobianはライトテーマです。

## ダークテーマの方が好きですので、変更しました：

```sh
gsettings set org.gnome.desktop.interface gtk-theme Adwaita-dark
```

## Geditも

```sh
gsettings set org.gnome.gedit.preferences.editor scheme solarized-dark
```

## そうして、ターミナル（Mobianのみ）

```sh
gsettings set org.gnome.zbrown.KingsCross theme 'hacker'
```

![](https://ass.technicalsuwako.moe/20201130_15h34m40s_grim.png)

## まずはキーボードレイアウトをダウンロードして

```sh
cd ~/.local/share/squeekboard/keyboards/
wget https://source.puri.sm/Librem5/squeekboard/-/raw/master/data/keyboards/jp+kana.yaml
wget https://source.puri.sm/Librem5/squeekboard/-/raw/master/data/keyboards/jp+kana_wide.yaml
```

スマホ画面で、「設定」→「地域と言語」に行って、「入力ソース」で「＋」ボタンを押して、「日本語」を押して「日本語 (かな)」を押して下さい。\
入力には、キーボードでグローブアイコンを押して、「日本語 (かな)」を選択して下さい。

![](https://ass.technicalsuwako.moe/20201130_15h37m38s_grim.png)
![](https://ass.technicalsuwako.moe/20201130_15h38m19s_grim.png)

# 最後はスクショを取り方です。

うまく取るには、SSHで取りがオススメです。

```sh
sudo apt install grim
```

取るには、SSHからパソコンで「grim」だけと入力して下さい。\
スマホから入力したら、ターミナル画面だけを取れますので、ご注意下さい。

以上
