title: 【Mobian】MyMoneroウォレットのインストール方法
uuid: f65acfd6-b5d1-40d6-af2a-55b8bd1eddc6
author: 諏訪子
date: 2022-03-12 00:00:00
category: smartphone,unix,crypto
----
LinuxスマホでまだXMRウォレットがありませんので、パソコン向けウォレットをインストールしましょう！

まずはGit、NPM、及びNodeJSをインストールして下さい。\
「electron-builder」でビルド出来ませんが、npmでmymoneroソフトを設置するには、インストールする事が必要です。

```sh
sudo apt install git npm nodejs
sudo npm i -g electron-builder
```

gitから受け取ると、npmでインストールして下さい。\
JSですので、時間がかかります。

```sh
git clone https://github.com/mymonero/mymonero-app-js.git && cd mymonero-app-js
npm i
```

やっと完了したら、下記のエラーが出ますが、心配しないで下さい。\
builder-utilもelectron-builderも要りません。

```
Error: Cannot find module 'fs/promises'
Require stack:
- /home/mobian/dev/mymonero-app-js/node_modules/builder-util/out/fs.js
- /home/mobian/dev/mymonero-app-js/node_modules/builder-util/out/util.js
- /home/mobian/dev/mymonero-app-js/node_modules/electron-builder/out/cli/cli.js
- /home/mobian/dev/mymonero-app-js/node_modules/electron-builder/cli.js
    at Function.Module._resolveFilename (internal/modules/cjs/loader.js:815:15)
    at Function.Module._load (internal/modules/cjs/loader.js:667:27)
    at Module.require (internal/modules/cjs/loader.js:887:19)
    at require (internal/modules/cjs/helpers.js:74:18)
    at Object.<anonymous> (/home/mobian/dev/mymonero-app-js/node_modules/builder-util/src/fs.ts:4:1)
    at Module._compile (internal/modules/cjs/loader.js:999:30)
    at Object.Module._extensions..js (internal/modules/cjs/loader.js:1027:10)
    at Module.load (internal/modules/cjs/loader.js:863:32)
    at Function.Module._load (internal/modules/cjs/loader.js:708:14)
    at Module.require (internal/modules/cjs/loader.js:887:19)
    at require (internal/modules/cjs/helpers.js:74:18)
    at Object.<anonymous> (/home/mobian/dev/mymonero-app-js/node_modules/builder-util/src/util.ts:25:1)
    at Module._compile (internal/modules/cjs/loader.js:999:30)
    at Object.Module._extensions..js (internal/modules/cjs/loader.js:1027:10)
    at Module.load (internal/modules/cjs/loader.js:863:32)
    at Function.Module._load (internal/modules/cjs/loader.js:708:14)
npm ERR! code 1
npm ERR! path /home/mobian/dev/mymonero-app-js
npm ERR! command failed
npm ERR! command sh -c npm run rollup-transpile; electron-builder install-app-deps

npm ERR! A complete log of this run can be found in:
npm ERR!     /home/mobian/.npm/_logs/2022-03-12T02_43_55_923Z-debug-0.log
```

実行しましょう！！\
ARMでelectronソフトをビルド出来ませんので、mymoneroソフトを使うには、毎回下記のコマンドを実行する事が必要となります。

```sh
npm start
```

![](https://ass.technicalsuwako.moe/mymonero1.png)
![](https://ass.technicalsuwako.moe/mymonero2.png)
![](https://ass.technicalsuwako.moe/mymonero3.png)
![](https://ass.technicalsuwako.moe/mymonero4.png)
![](https://ass.technicalsuwako.moe/mymonero5.png)

アップデートするには、「git pull」で十分です。

以上
