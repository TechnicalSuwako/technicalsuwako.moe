title: 【nginx】Torユーザーは自動で.onionリンクに移転方法
uuid: 74b831a2-b973-41e8-a6e0-e27effb33b5d
author: 諏訪子
date: 2022-11-13 00:00:00
category: server,security
----
Torを使って「technicalsuwako.moe」にアクセスしてみたら、自動で「6qiatzlijtqo6giwvuhex5zgg3czzwrq5g6yick3stnn4xekw26zf7qd.onion」に移転させると気づきましたか？\
今回はやり方を解決すると思います。

## コンフィグファイルの作成

まずは２つのファイルを作成して下さい。\
`nginx-tor-geo.conf`で何も入らなくてはOKです。\
`generate-geo-file.sh`で自動で入力させられますから。

```sh
touch /etc/nginx/conf.d/nginx-tor-geo.conf
nvim /etc/nginx/generate-geo-file.sh
```

```sh
IPADDR="$(curl -s https://check.torproject.org/exit-addresses | \
        grep ExitAddress | \
        awk '{print "\t" $2 " 1;"}' | \
        sort -u)"
cat > /etc/nginx/nginx-tor-geo.conf <<EOF
geo \$torUsers {
    default 0;
$IPADDR
}
EOF
```

実行出来る様にして、スクリプトを実行して下さい。

```
chmod +x /etc/nginx/generate-geo-file.sh && /etc/nginx/generate-geo-file.sh
```

## Crontab

毎時間の21分で自動で`generate-geo-file.sh`というスクリプトを実行する様にして下さい。

```sh
crontab -e
```

```
# m h  dom mon dow   command
 21 *  *   *   *     /etc/nginx/generate-geo-file.sh
```

## サイトのコンフィグの編集

ウエブサイトのコンフィグを変更して下さい。\
貴方は設定した次第、ファイルは`/etc/nginx/conf.d/`か`/etc/nginx/sites-enabled/`にあります。\
あたしの場合、`/etc/nginx/conf.d`です。

```sh
nvim /etc/nginx/conf.d/technicalsuwako.moe.conf
```

```
server {
  ...
  add_header Onion-Location http://（ほげほげ）.onion$request_uri;
  ...
  location / {
    if ($torUsers) {
      return 301 http://（ほげほげ）.onion$request_uri;
    }

    add_header Onion-Location http://（ほげほげ）.onion$request_uri;
    ...
  }

}
```

### 例

```
server {
  server_name www.technicalsuwako.moe technicalsuwako.moe;

  root  /www/active/technicalsuwako.moe/www;
  index  index.html index.htm;

  location / {
    if ($torUsers) {
      return 301 http://6qiatzlijtqo6giwvuhex5zgg3czzwrq5g6yick3stnn4xekw26zf7qd.onion$request_uri;
    }

    add_header Onion-Location http://6qiatzlijtqo6giwvuhex5zgg3czzwrq5g6yick3stnn4xekw26zf7qd.onion$request_uri;
    add_header Permissions-Policy interest-cohort=();
    add_header X-Content-Type-Options nosniff;
    add_header X-XSS-Protection "1; mode=block";
    add_header Strict-Transport-Security 'max-age=31536000; includeSubDomains; preload';
    try_files $uri $uri/ =404;
  }

  location ~*  \.(jpg|jpeg|png|gif|ico|woff|webp)$ {
    expires 365d;
  }

  location ~*  \.(css|js|json)$ {
    expires 7d;
  }

  listen 443 ssl http2;
  ssl_certificate /etc/letsencrypt/live/technicalsuwako.moe/fullchain.pem;
  ssl_certificate_key /etc/letsencrypt/live/technicalsuwako.moe/privkey.pem;
  include /etc/letsencrypt/options-ssl-nginx.conf;
}

server {
  server_name www.technicalsuwako.moe technicalsuwako.moe;
  listen 80;

  add_header Onion-Location http://6qiatzlijtqo6giwvuhex5zgg3czzwrq5g6yick3stnn4xekw26zf7qd.onion$request_uri;

  if ($host = technicalsuwako.moe) {
    return 301 https://$host$request_uri;
  }

  if ($host = www.technicalsuwako.moe) {
    return 301 https://$host$request_uri;
  }

  return 404;
}
```

じゃ、nginxを再起動して下さい。

| INITシステム | コマンド                 |
| ------------ | ------------------------ |
| runit        | sv restart nginx         |
| OpenRC       | rc-service nginx restart |
| SysV         | service nginx restart    |
| systemd      | systemctl restart nginx  |
| rc.d         | rcctl restart nginx      |

今はTorプロクシーでブラウジングしたら、自動で.onionドメインに移転されます。\
その同じ方法でTorユーザーに禁止する様に可能ですので（例えば、Torユーザーは全部ポルノページに移転される等）、ご注意下さい。

以上
