title: 【Chromium】偽カメラ配信の作成方法
uuid: 7392b767-af52-4b88-a71e-9c9fa5879630
author: 諏訪子
date: 2022-09-06 00:00:00
category: security
----
仕事でカメラ配信機能性をテストする事が必要でしたが、カメラを付いていないノートパソコンを使っています。\
ですから偽カメラでテストしないといけないですね。

まずは動画をmjpegに交換して下さい。（初回のみ）

```sh
ffmpeg -i video.webm video.mjpeg
```

後はこの動画でブラウザを起動して下さい。

```sh
iridium-browser --use-fake-device-for-media-stream --use-file-for-fake-video-capture=video.mjpeg
```

他のchromium系ブラウザを使ったら、「iridium-browser」を変更して下さい。\
例えば、ungoogled-chromiumの場合：「iridium-browser」→「ungoogled-chromium」\
braveの場合：「iridium-browser」→「brave-browser」\
chromeの場合：「iridium-browser」→「google-chrome」\
edgeの場合：「iridium-browser」→「microsoft-edge」\
等

以上
