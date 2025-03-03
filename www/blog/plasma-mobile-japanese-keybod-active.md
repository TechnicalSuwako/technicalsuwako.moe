title: 【Plasma Mobile】日本語キーボードを有効にする方法
author: 凜
date: 2021-05-29
category: smartphone,unix
----
やっとManjaro ARMのPlasma MobileというDEで日本語キーボードを追加されました！！\
でも、有効にすると、キーボードの言語を日本語に変えたら、キーボードが死ぬみたいです。\
修正方法はとても簡単ですよ！

まずは、そのまま有効にして。\
日本語キーボードを有効にするには、「`Settings`」→「`Virtual Keyboard`」→「`Configure Languages`」に行きましょう。\
![](https://ass.technicalsuwako.moe/Screenshot_20210529_120440.png)

有効にすると、キーボードは永遠に消えますね。\
再起動の後直ぐ、「`journalctl -fe`」を実行しました。\
下記の赤文字で書いたエラーが出ました。

```
May 29 11:04:53 plasma-mobile systemd-coredump[4555]: [?] Process 4530 (maliit-keyboard) of user 1002 dumped core.
                                                      
Stack trace of thread 4530:
#0  0x0000ffff9b7bbb90 _ZN8QQmlData17isSignalConnectedEP24QAbstractDeclarativeDataPK7QObjecti (libQt5Qml.so.5 + 0x285b90)
#1  0x0000ffff9a66e7b8 n/a (libQt5Core.so.5 + 0x32e7b8)
#2  0x0000ffff929385ac _ZN22WesternLanguagesPlugin23setSpellPredictLanguageE7QStringS0_ (libenplugin.so + 0x95ac)
#3  0x0000ffff9293e9b0 _ZN22WesternLanguagesPlugin11setLanguageERK7QStringS2_ (libenplugin.so + 0xf9b0)
#4  0x0000aaaadbd886d8 _ZN14MaliitKeyboard5Logic10WordEngine17onLanguageChangedERK7QStringS4_ (maliit-keyboard + 0x486d8)
#5  0x0000ffff9a66ec84 n/a (libQt5Core.so.5 + 0x32ec84)
#6  0x0000aaaadbd66b9c _ZN11InputMethod21languagePluginChangedE7QStringS0_ (maliit-keyboard + 0x26b9c)
#7  0x0000aaaadbd718a8 _ZN11InputMethod17onLanguageChangedERK7QString (maliit-keyboard + 0x318a8)
#8  0x0000ffff9a66ec84 n/a (libQt5Core.so.5 + 0x32ec84)
#9  0x0000aaaadbd66964 _ZN11InputMethod21activeLanguageChangedE7QString (maliit-keyboard + 0x26964)
#10 0x0000aaaadbd71024 _ZN11InputMethodC1EP24MAbstractInputMethodHost (maliit-keyboard + 0x31024)
#11 0x0000aaaadbd650d4 _ZThn16_N20MaliitKeyboardPlugin17createInputMethodEP24MAbstractInputMethodHost (maliit-keyboard + 0x250d4)
#12 0x0000ffff9bff6d84 _ZN6Maliit21StandaloneInputMethodC2EPNS_7Plugins17InputMethodPluginE (libmaliit-plugins.so.2 + 0x4dd84)
#13 0x0000aaaadbd648f8 main (maliit-keyboard + 0x248f8)
#14 0x0000ffff99fc7538 __libc_start_main (libc.so.6 + 0x24538)
#15 0x0000aaaadbd64b84 _start (maliit-keyboard + 0x24b84)
#16 0x0000aaaadbd64b84 _start (maliit-keyboard + 0x24b84)
May 29 11:04:53 plasma-mobile systemd[1]: systemd-coredump@3-4554-0.service: Deactivated successfully.
May 29 11:04:53 plasma-mobile systemd[1]: systemd-coredump@3-4554-0.service: Consumed 5.974s CPU time.
May 29 11:04:54 plasma-mobile systemd[1]: Started Process Core Dump (PID 4603/UID 0).
May 29 11:05:02 plasma-mobile systemd-coredump[4604]: [?] Process 4589 (maliit-keyboard) of user 1002 dumped core.
                                                      
Stack trace of thread 4589:
#0  0x0000ffff8b3e4b90 _ZN8QQmlData17isSignalConnectedEP24QAbstractDeclarativeDataPK7QObjecti (libQt5Qml.so.5 + 0x285b90)
#1  0x0000ffff8a2977b8 n/a (libQt5Core.so.5 + 0x32e7b8)
#2  0x0000ffff825615ac _ZN22WesternLanguagesPlugin23setSpellPredictLanguageE7QStringS0_ (libenplugin.so + 0x95ac)
#3  0x0000ffff825679b0 _ZN22WesternLanguagesPlugin11setLanguageERK7QStringS2_ (libenplugin.so + 0xf9b0)
#4  0x0000aaaabf5e86d8 _ZN14MaliitKeyboard5Logic10WordEngine17onLanguageChangedERK7QStringS4_ (maliit-keyboard + 0x486d8)
#5  0x0000ffff8a297c84 n/a (libQt5Core.so.5 + 0x32ec84)
#6  0x0000aaaabf5c6b9c _ZN11InputMethod21languagePluginChangedE7QStringS0_ (maliit-keyboard + 0x26b9c)
#7  0x0000aaaabf5d18a8 _ZN11InputMethod17onLanguageChangedERK7QString (maliit-keyboard + 0x318a8)
#8  0x0000ffff8a297c84 n/a (libQt5Core.so.5 + 0x32ec84)
#9  0x0000aaaabf5c6964 _ZN11InputMethod21activeLanguageChangedE7QString (maliit-keyboard + 0x26964)
#10 0x0000aaaabf5d1024 _ZN11InputMethodC1EP24MAbstractInputMethodHost (maliit-keyboard + 0x31024)
#11 0x0000aaaabf5c50d4 _ZThn16_N20MaliitKeyboardPlugin17createInputMethodEP24MAbstractInputMethodHost (maliit-keyboard + 0x250d4)
#12 0x0000ffff8bc1fd84 _ZN6Maliit21StandaloneInputMethodC2EPNS_7Plugins17InputMethodPluginE (libmaliit-plugins.so.2 + 0x4dd84)
#13 0x0000aaaabf5c48f8 main (maliit-keyboard + 0x248f8)
#14 0x0000ffff89bf0538 __libc_start_main (libc.so.6 + 0x24538)
#15 0x0000aaaabf5c4b84 _start (maliit-keyboard + 0x24b84)
#16 0x0000aaaabf5c4b84 _start (maliit-keyboard + 0x24b84)
```

西洋の機能性を日本語キーボードで使えないみたいですので、「Text correction」の下あるチェックボックスの全部を無効にしてみました。\
![](https://ass.technicalsuwako.moe/Screenshot_20210529_120426.png)

でも、まだ修正できませんでした。\
この同じエラーを読んで、キーボードソフトの内部名は「`maliit-keyboard`」らしいです。

```sh
maliit-keyboard
```

```
wordengine.cpp plugin "/usr/lib/maliit/keyboard2/languages/en/libenplugin.so" loaded
inputmethod_p.h registerActiveLanguage(): activeLanguage is: "ja"
in inputMethod.cpp setActiveLanguage() activeLanguage is: "ja"
void MaliitKeyboard::Logic::WordEnginePrivate::loadPlugin(QString)  Loading plugin failed:  
"Cannot load library /usr/lib/maliit/keyboard2/languages/ja/libjaplugin.so: 
(libanthy.so.0: cannot open shared object file: No such file or directory)"
Segmentation fault (core dumped)
```

ねぇねぇ！！「`libanthy.so`」というライブラリーを開けないみたいです！\
じゃ、インストールしてみよっか！

```sh
sudo pacman -S anthy fcitx5-anthy && sudo reboot
```

再起動する後、確認して下さい。\
![](https://ass.technicalsuwako.moe/Screenshot_20210529_115239.png)\
![](https://ass.technicalsuwako.moe/Screenshot_20210529_115532.png)

出た！！\
でも、まだカタカナと漢字を入力できません、現在ひらがなのみです。\
そうして、「戻す」を押すと「戻す」と入力します。\
「記号」を押すと「記号」と入力します。\
「←」と「→」ボタンは反応していません。

[動画](https://ass.technicalsuwako.moe/detadeta.mp4)

以上
