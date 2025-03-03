<?php
namespace Site\Controller;

class Mods {
  public function getMenu(): array {
    return [
      [
        'class' => 'menu-item',
        'href' => '/',
        'page' => 'blog',
        'text' => 'トップ',
        'show' => true,
      ],
      [
        'class' => 'menu-item',
        'href' => '/about',
        'page' => 'about',
        'text' => '自己紹介',
        'show' => true,
      ],
      [
        'class' => 'menu-item',
        'href' => 'https://code.076.moe/',
        'page' => 'repo',
        'text' => 'レポジトリ',
        'show' => true,
      ],
    ];
  }

  public function getSupport(): array {
    return [
      [
        'alt' => 'Monero',
        'class' => 'sns',
        'img' => '/static/support/monero.png',
        'href' => '/monero',
        'show' => true,
      ],
      [
        'alt' => 'Ko-Fi',
        'class' => 'sns',
        'img' => '/static/support/kofi.png',
        'href' => 'https://ko-fi.com/technicalsuwako',
        'show' => true,
      ],
      [
        'alt' => 'Patreon',
        'class' => 'sns',
        'img' => '/static/support/patreon.png',
        'href' => 'https://www.patreon.com/c/technicalsuwako',
        'show' => true,
      ],
      [
        'alt' => 'itch',
        'class' => 'sns',
        'img' => '/static/support/itch.png',
        'href' => 'https://technicalsuwako.itch.io',
        'show' => true,
      ],
    ];
  }

  public function getSns(): array {
    return [
      [
        'alt' => 'RSS',
        'class' => 'sns',
        'img' => '/static/sns/rss.png',
        'href' => '/blog.atom',
        'show' => true,
      ],
      [
        'alt' => 'Fediverse',
        'class' => 'sns',
        'img' => '/static/sns/mitra.png',
        'href' => 'https://sns.076.moe/@suwako',
        'show' => true,
      ],
      [
        'alt' => 'X',
        'class' => 'sns',
        'img' => '/static/sns/x.png',
        'href' => 'https://x.com/techsuwako',
        'show' => true,
      ],
      [
        'alt' => 'PeerTube',
        'class' => 'sns',
        'img' => '/static/sns/peertube.png',
        'href' => 'https://video.076.moe/a/suwako',
        'show' => true,
      ],
    ];
  }
}
?>
