<?php
namespace Site\Controller;

use Site\Lib\Template;
use Site\Controller\Mods;

class Page extends Mods {
  public function about(array $params): void {
    try {
      $tmpl = new Template('/');
      $pagetit = '私について';
      $description = 'テクニカル諏訪子ちゃんについて';

      $tmpl->assign('pagetit', $pagetit);
      $tmpl->assign('curPage', 'about');
      $tmpl->assign('custCss', true);
      $tmpl->assign('sns', $this->getSns());
      $tmpl->assign('support', $this->getSupport());
      $tmpl->assign('menu', $this->getMenu());
      $tmpl->assign('description', $description);

      $tmpl->render('about');
    } catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    }
  }

  public function monero(array $params): void {
    try {
      $tmpl = new Template('/');
      $pagetit = 'モネロ（XMR）で支援♡';
      $description = 'テクニカル諏訪子ちゃんをモネロで支援♡';

      $tmpl->assign('pagetit', $pagetit);
      $tmpl->assign('curPage', 'company');
      $tmpl->assign('custCss', true);
      $tmpl->assign('sns', $this->getSns());
      $tmpl->assign('support', $this->getSupport());
      $tmpl->assign('menu', $this->getMenu());
      $tmpl->assign('description', $description);

      $tmpl->render('monero');
    } catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    }
  }
}
?>
