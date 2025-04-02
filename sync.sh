#!/bin/sh

SRV=192.168.0.106
RSYNCOPT="-rtvzP"
HTTPHOME=/var/www/htdocs/technicalsuwako.moe

# 画像など
rsync ${RSYNCOPT} ass ${SRV}:${HTTPHOME}

# ウエブサイト
rsync ${RSYNCOPT} www/*.php ${SRV}:${HTTPHOME}/www
rsync ${RSYNCOPT} www/blog ${SRV}:${HTTPHOME}/www
rsync ${RSYNCOPT} www/config ${SRV}:${HTTPHOME}/www
rsync ${RSYNCOPT} www/doc ${SRV}:${HTTPHOME}/www
rsync ${RSYNCOPT} www/public ${SRV}:${HTTPHOME}/www
rsync ${RSYNCOPT} www/src ${SRV}:${HTTPHOME}/www
rsync ${RSYNCOPT} www/view ${SRV}:${HTTPHOME}/www
