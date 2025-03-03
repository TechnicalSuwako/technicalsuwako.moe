title: 【Linux】systemdユーザー向けSysV使い方
author: 凛
date: 2022-11-15
category: unix
----
コマンド | SysV | systemd
-- | -- | --
サービスの有効化 | chkconfig <サービス名> on | systemctl enable <サービス名>
サービスの無効化 | chkconfig <サービス名> off | systemctl disable <サービス名>
サービスが有無の確認 | chkconfig <サービス名> | systemctl is-enabled <サービス名>
サービスの起動 | service <サービス名> start | systemctl start <サービス名>
サービスの終了 |  service <サービス名> stop | systemctl stop <サービス名>
サービスの再起動 |  service <サービス名> restart | systemctl restart <サービス名>
サービスの状況の確認 |  service <サービス名> status | systemctl status <サービス名>
サービスの有効済みスクリプト一覧 | chkconfig --list | systemctl list-unit-files --type=service
