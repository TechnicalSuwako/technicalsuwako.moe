title: 【OpenBSD】systemdユーザー向けrc.d使い方
uuid: 93bd2aa5-0942-46fa-8609-f89344dbfe6c
author: 諏訪子
date: 2022-11-17 00:00:00
category: unix
----
コマンド | rc.d | systemd
-- | -- | --
サービスの有効化 | rcctl enable <サービス名> | systemctl enable <サービス名>
サービスの無効化 | rcctl disable <サービス名> | systemctl disable <サービス名>
サービスが有無の確認 | rcctl ls on | grep <サービス名> | systemctl is-enabled <サービス名>
サービスの起動 | rcctl start <サービス名> | systemctl start <サービス名>
サービスの終了 |  rcctl stop <サービス名> | systemctl stop <サービス名>
サービスの再起動 |  rcctl restart <サービス名> | systemctl restart <サービス名>
サービスの状況の確認 |  rcctl check <サービス名> | systemctl status <サービス名>
サービスの有効済みスクリプト一覧 | rcctl ls on | systemctl list-unit-files --type=service
