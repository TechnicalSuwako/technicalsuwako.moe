title: 【Pinephone】fcitx5+mozcで日本語入力方法（漢字も！！）
uuid: 8b87a112-e02d-4503-a0bc-baa6f623ecfc
author: 諏訪子
date: 2020-12-18 00:00:00
category: smartphone,unix
----
本当に難しかったですが、やっとMobianで仮名も漢字も入力できます！！\
ちょっと不思議ですので、下記に教えてくれてみます。

**fcitxかfcitx5？**
fcitxはX11だけで使えますが、fcitx5はwaylandでも使えますので、fcitx5を使っております。\
でも、Mobianでfcitx5-mozcがありません。\
知っていた？fcitx-mozcはfcitx5でも使えますよ！

ですから、インストールして：

```sh
sudo apt install fcitx fcitx5 fcitx5-config-qt fcitx5-data fcitx5-frontend-gtk2 fcitx5-frontend-gtk3 \
fcitx5-frontend-qt5 fcitx5-table gir1.2-fcitxg-1.0 fcitx5-module-wayland fcitx5-modules \
fcitx5-module-ibus fcitx5-module-fullwidth fcitx5-module-dbus fcitx5-module-emoji fcitx-mozc \
ibus-mozc mozc-data mozc-server mozc-utils-gui
```

後は`/home/mobian/.profile`というファイルを編集すると、一番下の列行に貼って下さい。\
fcitx5を使っているのに、正しい文字は「fcitx」ですので、ご注意下さい。

```sh
export XMODIFIERS=@im=fcitx
export QT_IM_MODULE=fcitx
export GTK_IM_MODULE=fcitx
```

次は`sudo`で`/usr/share/fcitx5/addon/fcitx-mozc.conf`という入るを創作しましょう。

```conf
[Addon]
Name=fcitx-mozc
GeneralName=Mozc
Comment=Mozc support for Fcitx
Category=InputMethod
Enabled=True
Library=fcitx-mozc.so
Type=SharedLibrary
SubConfig=
IMRegisterMethod=ConfigFile
LoadLocal=True
```

ネクスト、sudoで`/usr/share/fcitx5/inputmethod/mozc.conf`という入るを創作しましょう。

```conf
[InputMethod]
UniqueName=mozc
Name=Mozc
IconName=/usr/share/fcitx5/mozc/icon/mozc.png
Priority=1
LangCode=ja
Parent=fcitx-mozc
```

よし！\
sudoでfcitxからmozcというフォルダをコピーします。\
後は再起動しましょう。

```sh
sudo cp -rf /usr/share/fcitx/mozc/ /usr/share/fcitx5/
sudo reboot
```

最後ですが、fcitx5を有効しましょうか？

```sh
fcitx -r
```

エラーメッセージも出ると可能性がありますが、氣にしなくてもいいですよ。\
後は「`terminal`」というキーボードでCTRL+Cを押したら、すぐに入力できます。\
日本語↔英語に変えるには、パソコンと同じく、CTRL+スペースを押したら、変えられます。

![](https://ass.technicalsuwako.moe/20201218_22h21m02s_grim.png)

[動画](https://ass.technicalsuwako.moe/mobian-fcitx5-nihongo.mp4)

以上
