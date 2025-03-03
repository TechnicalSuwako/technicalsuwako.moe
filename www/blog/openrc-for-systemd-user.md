title: 【Linux】systemdユーザー向けOpenRC使い方
author: 凛
date: 2022-11-12
category: unix
----

コマンド | OpenRC | systemd
-- | -- | --
サービスの有効化 | rc-update add <サービス名> | systemctl enable <サービス名>
サービスの無効化 | rc-update del <サービス名> | systemctl disable <サービス名>
サービスの起動 | rc-service <サービス名> start | systemctl start <サービス名>
サービスの終了 |  rc-service <サービス名> stop | systemctl stop <サービス名>
サービスの再起動 |  rc-service <サービス名> restart | systemctl restart <サービス名>
サービスの状況の確認 |  rc-service <サービス名> status | systemctl status <サービス名>
サービスの有効済みスクリプト一覧 | rc-update show | systemctl list-units
