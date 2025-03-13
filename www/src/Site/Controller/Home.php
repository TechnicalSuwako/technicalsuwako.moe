<?php
namespace Site\Controller;

use Site\Lib\Markdown;
use Site\Lib\Template;
use Site\Controller\Mods;

class Home extends Mods {
  private array $searchKeywords = [];

  //------------------------------------------
  // ページ
  //------------------------------------------

  /**
   * ブログ投稿ページ
   *
   * @param array $params ページ番号等
   * @return void
   */
  public function show(array $params): void {
    try {
      $page = isset($_GET['page']) ? $_GET['page'] : 1;
      $postsPerPage = 20;

      $tmpl = new Template('/');
      $pagetit = 'トップページ';

      $description = 'テクニカル諏訪子ちゃんの個人ブログ';

      $posts = $this->getPosts();
      if (!is_array($posts)) $posts = [];

      // 検索機能が使用されている場合
      if (isset($_GET['q']) && !empty($_GET['q'])) {
        $this->searchKeywords = array_map('trim', explode(',', $_GET['q']));
        $posts = $this->searchPosts($this->searchKeywords, $posts);
        $pagetit = '検索結果: ' . htmlspecialchars($_GET['q']);

        // 検索結果にキーワードをハイライト
        $posts = $this->highlightKeywords($posts);
      }

      // ページネーション
      $totalPosts = count($posts);
      $totalPages = ceil($totalPosts / $postsPerPage);
      $page = min($page, $totalPages);
      $currentPosts = array_slice(
        $posts, 
        ($page - 1) * $postsPerPage, 
        $postsPerPage
      );

      $tmpl->assign('currentPage', $page);
      $tmpl->assign('totalPages', $totalPages);
      $tmpl->assign('posts', $currentPosts);
      $tmpl->assign('pagetit', $pagetit);
      $tmpl->assign('curPage', 'blog');
      $tmpl->assign('custCss', false);
      $tmpl->assign('sns', $this->getSns());
      $tmpl->assign('support', $this->getSupport());
      $tmpl->assign('menu', $this->getMenu());
      $tmpl->assign('description', $description);
      $tmpl->assign('searchActive', !empty($this->searchKeywords));

      $tmpl->addCss('news');
      $tmpl->addCss('search');
      $tmpl->addCss('pagination');
      $tmpl->render('home');
    } catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    }
  }

  /**
   * ブログ投稿ページ
   *
   * @param array $params マークダウンファイル等
   * @return void
   */
  public function article(array $params): void {
    $page = '';
    if (isset($params['page'])) $page = $params['page'];

    try {
      $tmpl = new Template('/');
      $md = new Markdown($page);

      $meta = $md->getMetadata();
      $pagetit = $meta->title;
      $article = $md->parse();
      $description = 'テクニカル諏訪子ちゃんの個人ブログ';

      // 検索からの遷移の場合、記事内のキーワードをハイライト
      if (isset($_GET['q']) && !empty($_GET['q'])) {
        $keywords = array_map('trim', explode(',', $_GET['q']));
        $article = $this->highlightTextContent($article, $keywords);
        $meta->title = $this->highlightTextContent($meta->title, $keywords);
      }

      $tmpl->assign('pagetit', $pagetit);
      $tmpl->assign('curPage', 'blog');
      $tmpl->assign('custCss', false);
      $tmpl->assign('sns', $this->getSns());
      $tmpl->assign('support', $this->getSupport());
      $tmpl->assign('menu', $this->getMenu());
      $tmpl->assign('article', $article);
      $tmpl->assign('meta', $meta);
      $tmpl->assign('description', $description);

      $tmpl->addCss('news-article');
      $tmpl->addCss('search');
      $tmpl->render('article');
    } catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    }
  }

  /**
   * 最新の5記事のAtomフィードを生成する
   * 
   * @param array $params パラメータ配列
   * @return void
   */
  public function feed(array $params): void {
    try {
      // 最新の投稿を取得
      $posts = $this->getPosts();
      // 最新の5件に制限
      $posts = array_slice($posts, 0, 5);
      
      // サイトのドメインを取得
      $domain = $_SERVER['HTTP_HOST'];
      $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ?
        'https' : 'http';
      $baseUrl = $protocol . '://' . $domain;
      
      // 現在の日時（RFC3339形式）
      $published = date('c');
      
      // XMLヘッダーとコンテンツタイプを設定
      header('Content-Type: application/atom+xml; charset=utf-8');
      
      // Atomフィードの開始部分
      echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
      echo '<feed xmlns="http://www.w3.org/2005/Atom">' . "\n";
      
      // フィードの基本情報
      echo '  <title>テクニカル諏訪子</title>' . "\n";
      echo '  <link href="' . $baseUrl . '" />' . "\n";
      echo '  <link href="' . $baseUrl . '/blog.atom" rel="self" />' . "\n";
      echo '  <id>' . $baseUrl . '/</id>' . "\n";
      echo '  <published>' . $published . '</published>' . "\n";
      echo '  <author>' . "\n";
      echo '    <name>諏訪子</name>' . "\n";
      echo '  </author>' . "\n";
      
      // 各エントリー（記事）
      foreach ($posts as $post) {
        // 記事の本文を取得（プレーンテキスト）
        $path = ROOT . '/blog/' . $post['slug'] . '.md';
        $content = '';
        $postPublished = date('c', strtotime($post['date']));
        
        if (file_exists($path)) {
          $fileContent = file_get_contents($path);
          $parts = explode('----', $fileContent, 2);
          if (count($parts) > 1) {
            // 本文をHTMLとして準備
            $md = new Markdown($post['slug']);
            $content = $md->parse();
            // HTMLタグを取り除かないようにCDATAで囲む
            $content = '<![CDATA[' . $content . ']]>';
          }
        }
        
        echo '  <entry>' . "\n";
        echo '    <title>' . htmlspecialchars($post['title']) . '</title>' . "\n";
        echo '    <link href="' . $baseUrl . '/blog/' . $post['slug'] . '" />' . "\n";
        echo '    <id>' . $baseUrl . '/blog/' . $post['slug'] . '</id>' . "\n";
        echo '    <published>' . $postPublished . '</published>' . "\n";
        
        // カテゴリ（タグ）
        if (isset($post['category']) && is_array($post['category'])) {
          foreach ($post['category'] as $category) {
            echo '    <category term="' . htmlspecialchars($category) . '" />' . "\n";
          }
        }
        
        // 本文（要約または全文）
        echo '    <content type="html">' . $content . '</content>' . "\n";
        echo '  </entry>' . "\n";
      }
      
      // フィードの終了
      echo '</feed>';
      exit;
    } catch (\Exception $e) {
      header('Content-Type: text/plain; charset=utf-8');
      echo 'Error generating feed: ' . $e->getMessage();
      exit;
    }
  }

  //------------------------------------------
  // 機能性
  //------------------------------------------

  /**
   * ブログ投稿を取得する
   * 
   * @return array 投稿の配列
   */
  private function getPosts(): array {
    $path = ROOT.'/blog/';
    $posts = [];

    if (!is_dir($path)) return $posts;
    $files = glob($path.'/*.md');

    foreach ($files as $file) {
      $content = file_get_contents($file);
      $parts = explode('----', $content, 2);
      if (count($parts) != 2) continue;

      $metadata = [];
      $meta = explode("\n", trim($parts[0]));

      foreach ($meta as $line) {
        $line = trim($line);
        if (empty($line)) continue;

        $colonPos = strpos($line, ':');
        if ($colonPos === false) continue;

        $key = trim(substr($line, 0, $colonPos));
        $value = trim(substr($line, $colonPos + 1));
        $value = trim($value, '"\'');

        if ($key == 'category') {
          $metadata[$key] = array_map('trim', explode(',', $value));
        } else {
          $metadata[$key] = $value;
        }
      }

      $articleBody = trim($parts[1]);
      $preview = mb_substr(strip_tags($articleBody), 0, 50) . '...';
      $slug = basename($file, '.md');

      $posts[] = [
        'title' => $metadata['title'] ?? '',
        'date' => $metadata['date'] ?? '',
        'thumbnail' => $metadata['thumbnail'] ?? '',
        'thumborient' => $metadata['thumborient'] ?? '',
        'category' => $metadata['category'] ?? [],
        'preview' => $preview,
        'slug' => $slug,
      ];
    }

    // 日付でソート（新しい順）
    usort($posts, function($a, $b) {
      return strtotime($b['date']) - strtotime($a['date']);
    });

    return $posts;
  }

  /**
   * キーワードに基づいて投稿を検索する
   * 
   * @param array $keywords 検索キーワードの配列
   * @param array $posts 検索対象の投稿記事の配列
   * @return array 検索条件に一致する投稿記事の配列
   */
  private function searchPosts(array $keywords, array $posts): array {
    if (empty($keywords) || empty($posts)) {
      return $posts;
    }

    $foundPosts = [];
    $path = ROOT.'/blog/';

    foreach ($posts as $post) {
      $matched = false;
      
      // タイトルで検索
      foreach ($keywords as $keyword) {
        $keyword = trim($keyword);
        if (empty($keyword)) continue;
        
        // タイトル内でキーワードが見つかった場合
        if (mb_stripos($post['title'], $keyword) !== false) {
          $foundPosts[] = $post;
          $matched = true;
          break;
        }
      }
      
      // すでにマッチしていれば次の記事へ
      if ($matched) continue;
      
      // 記事の本文をチェック
      $slug = $post['slug'];
      $filePath = $path . $slug . '.md';
      
      if (file_exists($filePath)) {
        $content = file_get_contents($filePath);
        $parts = explode('----', $content, 2);
        if (count($parts) > 1) {
          $articleBody = trim($parts[1]);
          
          foreach ($keywords as $keyword) {
            $keyword = trim($keyword);
            if (empty($keyword)) continue;
            
            // 本文内でキーワードが見つかった場合
            if (mb_stripos($articleBody, $keyword) !== false) {
              $foundPosts[] = $post;
              break;
            }
          }
        }
      }
    }
    
    return $foundPosts;
  }

  /**
   * 検索結果の投稿内のキーワードをハイライトする
   * 
   * @param array $posts 検索結果の投稿配列
   * @return array ハイライト処理後の投稿配列
   */
  private function highlightKeywords(array $posts): array {
    if (empty($this->searchKeywords) || empty($posts)) {
      return $posts;
    }
    
    foreach ($posts as &$post) {
      // タイトルのハイライト
      if (!empty($post['title'])) {
        $post['title'] =
          $this->highlightTextContent($post['title'], $this->searchKeywords);
      }
      
      // プレビューのハイライト
      if (!empty($post['preview'])) {
        $post['preview'] =
          $this->highlightTextContent($post['preview'], $this->searchKeywords);
      }
    }
    
    return $posts;
  }
  
  /**
   * テキスト内のキーワードをハイライトする
   * 
   * @param string $text ハイライト対象のテキスト
   * @param array $keywords ハイライトするキーワード配列
   * @return string ハイライト処理後のテキスト
   */
  private function highlightTextContent(string $text, array $keywords): string {
    if (empty($keywords) || empty($text)) {
      return $text;
    }
    
    $highlightedText = $text;
    
    foreach ($keywords as $keyword) {
      $keyword = trim($keyword);
      if (empty($keyword)) continue;
      
      // キーワードを大文字小文字を区別せずに置換
      $highlightedText = preg_replace(
        '/(' . preg_quote($keyword, '/') . ')/iu',
        '<span class="search-highlight">$1</span>',
        $highlightedText
      );
    }
    
    return $highlightedText;
  }
}
?>
