title: 【Postfix】スパム踏み台になった場合、mailq削除方法です
author: 凜
date: 2020-01-24
category: server,hosting
----
毎日皆さんはメールを送りますが、簡単なパスワードを使い方又はパスワードハッキングが多すぎます。\
だから、メールサーバーはスパム踏み台になる率が高いです。

スパム踏み台になったかならないかどうかを確認して。

```sh
mailq | tail
```

```
AD00A13E5C22     8564 Fri Jan 24 09:33:48  21-420159-8257-0-19-7992-1-contact@example2.co.jp
(host alt1.gmail-smtp-in.l.google.com[108.000.000.000] said: 421-4.7.28 [52.000.000.000      15] Our system has
detected an unusual rate of 421-4.7.28 unsolicited mail originating from your IP address. To protect our
421-4.7.28 users from spam, mail sent from your IP address has been temporarily 421-4.7.28 rate limited.
Please visit 421-4.7.28  https://support.google.com/mail/?p=UnsolicitedRateLimitError to 421 4.7.28 review
our Bulk Email Senders Guidelines. なんとかなんとか - gsmtp (in reply to end of DATA command))
                                         yamada@gmail.com

-- 5,808 Kbytes in 726 Requests.
```

726リクエストがあります。\
確かにスパム踏み台ですよね…

まずはFROMメールアドレスを調べてみましょう。\
下記コマンドで最新のmailqをログファイルにバックアップします。

```sh
mailq > /tmp/(お好みファイル名).log
```

調べてみましょうか？

```sh
cat /tmp/(お好みファイル名).log | less
```

「/」文字を押して、「FROM command」を書いて、IDと始める列を見つけてみて。

```
                                         taro@yahoo.co.jp
                                         taro@yahoo.com.au
                                         taro@gmail.com
                                         taro@hotmail.com

11F8D155CE9D     3947 Fri Jan 24 06:02:11  yamada.taro@example.co.jp
```

次は先に作ったログファイルを確認したメールアドレスにフィルタします。

```sh
perl -ne 'print "$1\n" if (/^(\w+).+ yamada\.taro\@example\.co\.jp/);' /tmp/(お好みファイル名).log > /tmp/(お好み削除IDに入れたファイル名).txt
```

やっとmailqに入ったスパムを削除しましょう。

```sh
cat /tmp/(お好み削除IDに入れたファイル名).txt | postsuper -d -
```

もう一回mailqを確認して

```sh
mailq | tail
```

```
398D6A03A8D     8564 Fri Jan 24 09:33:48  21-420159-8257-0-19-7992-1-contact@example2.co.jp
(host alt1.gmail-smtp-in.l.google.com[108.000.000.000] said: 421-4.7.28 [52.000.000.000      15] Our system has
detected an unusual rate of 421-4.7.28 unsolicited mail originating from your IP address. To protect our
421-4.7.28 users from spam, mail sent from your IP address has been temporarily 421-4.7.28 rate limited.
Please visit 421-4.7.28  https://support.google.com/mail/?p=UnsolicitedRateLimitError to 421 4.7.28 review
our Bulk Email Senders Guidelines. なんとかなんとか - gsmtp (in reply to end of DATA command))
                                         yamada@gmail.com

-- 24 Kbytes in 3 Requests.
```

未送信スパムメールを削除しました！\
お疲れ様！

以上
