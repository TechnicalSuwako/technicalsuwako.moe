<?php
namespace Site\Controller;

use Site\Controller\Mods;

class Af extends Mods {
  /**
   * ライトモード
   *
   * @param array $params ページ番号等
   * @return void
   */
  public function lighttoggle(): void {
    try {
      if (!isset($_COOKIE['lightmode'])) {
        setcookie('lightmode', 1, time() + 2629746);
      } else {
        setcookie('lightmode', 0, time() - 3600);
      }

      $rdrto = isset($_GET['rdr']) ? $_GET['rdr'] : '/';
      header('Location: '.$rdrto);
      exit();
    } catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    }
  }
}
?>
