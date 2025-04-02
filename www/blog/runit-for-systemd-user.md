title: 【Linux】systemdユーザー向けrunit使い方
uuid: 2a508052-dbc0-470d-8b11-84926a000d28
author: 諏訪子
date: 2022-09-08 00:00:00
category: unix
----
コマンド | runit | systemd
-- | -- | --
サービスの有効化 | ln -s /etc/runit/sv/<サービス名> /run/runit/service | systemctl enable <サービス名>
サービスの無効化 | rm /run/runit/service/<サービス名> | systemctl disable <サービス名>
サービスの起動 | sv start <サービス名> | systemctl start <サービス名>
サービスの終了 |  sv stop <サービス名> | systemctl stop <サービス名>
サービスの再起動 |  sv restart <サービス名> | systemctl restart <サービス名>
サービスの状況の確認 |  sv status <サービス名> | systemctl status <サービス名>
サービスの有効済みスクリプト一覧 | ls -thal /run/runit/service | systemctl list-units
