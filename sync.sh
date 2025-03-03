#!/bin/sh

SRV=192.168.10.106
RSYNCOPT="-rtvzP"
HTTPHOME=/var/www/htdocs/technicalsuwako.moe

# 画像など
rsync ${RSYNCOPT} ass ${SRV}:${HTTPHOME}

# ウエブサイト
rsync ${RSYNCOPT} www ${SRV}:${HTTPHOME}
