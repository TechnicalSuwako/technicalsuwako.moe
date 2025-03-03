title: 【PHP】正しい連絡フォームの作り方（クライアント側をぜったいに信用するな！！）
author: 凛
date: 2023-08-04
category: programming
----
## 問題
現在の「モダン」ウェブ開発で、連絡フォームはJavascriptで制御されていますが、これは大きなリスクがあります。\
その理由について、直ぐに説明します。

以下のスクショをご覧いただいたら、何が問題は何だと思いますか？\
[![](https://ass.technicalsuwako.moe/fuanform1.png)](https://ass.technicalsuwako.moe/fuanform1.png)

正解は：送信ボタンは`<form>`タグの外にある事です。\
これでは、Javascriptを無効にした場合、送信ボタンをクリックする事が出来ません。

このフォームを送信する為には、この送信ボタンをフォーム内に移動し、`type="button"`を`type="submit"`に変更する事で、Javascriptなしでもフォームを送信する事が可能になります。\
そんな感じ：\
[![](https://ass.technicalsuwako.moe/fuanform2.png)](https://ass.technicalsuwako.moe/fuanform2.png)

そうして、入力画面で「required=""」というパラメータがあり、これによりJavascriptが無効であってもフィールドが入力されているかどうかを確認できます。\
例：\
[![](https://ass.technicalsuwako.moe/fuanform3.png)](https://ass.technicalsuwako.moe/fuanform3.png)

しかし、このパラメータを削除すると、どのような事態が起こると思いますか？\
正解はこちら：\
[![](https://ass.technicalsuwako.moe/fuanform4.png)](https://ass.technicalsuwako.moe/fuanform4.png)

また、確認画面ではフォームが`<input type="hidden" />`タグを沢山含んでいます。\
その中の「value=""」部分を変更する事が可能です。\
これにより、MySQLインジェクションも可能となります。

## 解決策

上述の問題を解決する為には、サーバー側でのチェックが必要です。\
勿論、クライアント側とサーバー側の両方でチェックを行う事も可能です。

例として、PHPの場合を紹介します（PHPを使用するフォームが多い為）：

```php
<?php
  session_name("formvals");
  session_start([
    "cookie_httponly" => true,
  ]);

  if (empty($_SESSION["csrf_token"])) $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
  if (!isset($_SESSION["step"])) $_SESSION["step"] = 1;
  $errmes = [];
  $reqvals = [
    "name" => $_SESSION["name"] ?? "",
    "kana" => $_SESSION["kana"] ?? "",
  ];
  $optvals = [
    "url" => $_SESSION["url"] ?? "",
  ];

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!hash_equals($_SESSION["csrf_token"], $_POST["csrf_token"])) {
      die("不正なCSRFトークン");
    }

    if ($_SESSION["step"] == 1) {
      foreach ($reqvals as $k => $v) {
        $_SESSION[$k] = filter_input(INPUT_POST, $k, FILTER_SANITIZE_STRING);
        if ($_SESSION[$k]) $reqvals[$k] = $_SESSION[$k];
        else $errmes[] = $k."をご入力下さい。";
      }

      foreach ($optvals as $k => $v) {
        $_SESSION[$k] = filter_input(INPUT_POST, $k, FILTER_SANITIZE_STRING);
        $optvals[$k] = $_SESSION[$k];
      }

      if (empty($errmes)) $_SESSION["step"] = 2;
    }
    else if ($_SESSION["step"] == 2) {
      $_SESSION["step"] = 1;
      session_destroy();
      header("Location: /success.html");
      die();
    }
  }
  else {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
    $_SESSION["step"] = 1;
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>連絡フォーム</title>
  </head>
  <body>
<?php
  if ($_SESSION["step"] == 1) {
    if (count($errmes) != 0) {
?>
    <ul style="font-width: bolder; color: #f00; list-style: none;">
<?php
      foreach ($errmes as $e) {
        echo "<li>".$e."</li>";
      }
?>
    </ul>
<?php
    }
?>
    <form method="POST" action="/contact.php">
      <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
      <table>
        <tbody>
          <tr>
            <td>お名前 (必須):</td>
            <td><input placeholder="山田 太郎" required="" name="name" type="text" value="<?= $reqvals["name"] ?>" /></td>
          </tr>
          <tr>
            <td>お名前 (かな) (必須):</td>
            <td><input placeholder="やまだ たろう" required="" name="kana" type="text" value="<?= $reqvals["kana"] ?>" /></td>
          </tr>
          <tr>
            <td>御社又は関連サイトのURL:</td>
            <td><input placeholder="https://076.moe/" name="url" type="text" value="<?= $optvals["url"] ?>" /></td>
          </tr>
        </tbody>
      </table>
      <button>確認画面へ</button>
    </form>
<?php
  } else if ($_SESSION["step"] == 2) {
?>
    <form method="POST" action="/contact.php">
      <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
      お名前 (必須): <?= $reqvals["name"] ?><br />
      お名前 (かな) (必須): <?= $reqvals["kana"] ?><br />
      御社又は関連サイトのURL: <?= $optvals["url"] ?><br /><br />
      <button>送信する</button>
    </form>
<?php
  } else {
?>
    <p>不明なエラー。</p>
<?php
  }
?>
  </body>
</html>
```

結果：\
[![](https://ass.technicalsuwako.moe/anzenform1.png)](https://ass.technicalsuwako.moe/anzenform1.png)

[![](https://ass.technicalsuwako.moe/anzenform2.png)](https://ass.technicalsuwako.moe/anzenform2.png)

[![](https://ass.technicalsuwako.moe/anzenform3.png)](https://ass.technicalsuwako.moe/anzenform3.png)

[![](https://ass.technicalsuwako.moe/anzenform4.png)](https://ass.technicalsuwako.moe/anzenform4.png)

[![](https://ass.technicalsuwako.moe/anzenform5.png)](https://ass.technicalsuwako.moe/anzenform5.png)

[![](https://ass.technicalsuwako.moe/anzenform6.png)](https://ass.technicalsuwako.moe/anzenform6.png)

ねぇねぇー！\
簡単でしょー！

以上
