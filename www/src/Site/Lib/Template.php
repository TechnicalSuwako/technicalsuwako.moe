<?php
namespace Site\Lib;

class Template {
  private string $tmplPath;
  private array $vars = [];
  private array $blocks = [];
  private array $custFunc = [];
  private array $custCss = [];

  /**
   * テンプレートクラスのコンストラクタ
   *
   * @param string $tmplPath テンプレートのパス
   * @return void
   */
  public function __construct(string $tmplPath) {
    $this->tmplPath = rtrim($tmplPath, '/');
  }

  /**
   * テンプレート変数に値を割り当てる
   *
   * @param string $name 変数名
   * @param mixed $value 値
   * @return void
   */
  public function assign(string $name, mixed $value): void {
    $this->vars[$name] = $value;
  }

  /**
   * カスタムCSSファイルを追加する
   *
   * @param string $name CSSファイル名
   * @return void
   */
  public function addCss(string $name): void {
    $this->custCss[] = 
      '<link rel="stylesheet" type="text/css" href="/static/style-'.$name.'.css" />';
    $this->assign('custCss', $this->custCss);
  }

  /**
   * カスタム関数を登録する
   *
   * @param string $name 関数名
   * @param callable $callback コールバック関数
   * @return void
   */
  public function registerFunction(string $name, callable $callback): void {
    $this->custFunc[$name] = $callback;
  }

  /**
   * テンプレートブロックを定義する
   *
   * @param string $name ブロック名
   * @param string $content ブロック内容
   * @return void
   */
  public function defineBlock(string $name, string $content): void {
    if (!isset($this->blocks[$name]))
      $this->blocks[$name] = $content;
  }

  /**
   * テンプレートをレンダリングする
   *
   * @param string $tmplName テンプレート名
   * @return void
   */
  public function render(string $tmplName): void {
    $tmplPath = ROOT.'/view'.$this->tmplPath.'/'.$tmplName.'.php';
    if (!file_exists($tmplPath))
      throw new \RuntimeException("テンプレートファイルを見つけません：{$tmplPath}");

    extract($this->vars);

    $content = file_get_contents($tmplPath);

    // インクルードディレクティブを処理
    while (preg_match('/@include\((.*?)\)/s', $content)) {
      $content = preg_replace_callback('/@include\((.*?)\)/s', function($m) {
        $inclPath = ROOT.'/view/'.trim($m[1], "'\" ").'.php';
        if (!file_exists($inclPath))
          throw new \RuntimeException("ファイルを見つけません： {$inclPath}");
        return file_get_contents($inclPath);
      }, $content);
    }

    $content = $this->procDirs($content);
    $content = $this->procVars($content);
    $content = $this->procFuncs($content);

    $tmpFile = tempnam(sys_get_temp_dir(), 'tmpl_');
    file_put_contents($tmpFile, $content);

    include $tmpFile;
    unlink($tmpFile);
  }

  /**
   * テンプレートディレクティブを処理する
   *
   * @param string $content テンプレート内容
   * @return string|null 処理後の内容
   */
  private function procDirs(string $content): string|null {
    // includeディレクティブの処理
    $content = preg_replace_callback('/@include\((.*?[\)]*)\)/s', function($m) {
      return "<?php include(\"{ROOT}/view/{$m[1]}.php\"); ?>";
    }, $content);

    // kysディレクティブの処理
    $content = preg_replace_callback('/@kys\((.*?[\)]*)\)/s', function($m) {
      return "<?php die({$m[1]}); ?>";
    }, $content);

    // forループの処理
    $content = preg_replace_callback(
      '/@for\s*\((.*?)\)(.*?)@endfor/s',
      function($m) {
        return '<?php for ('.trim($m[1]).'): ?>'
               .$m[2]
               .'<?php endfor; ?>';
      },
      $content
    );

    // foreachループとネストした内容の処理
    $content = preg_replace_callback(
      '/@foreach\s*\((.*?)\)(.*?)@endforeach/s',
      function($m) {
        $innerContent = $this->procDirs($m[2]);
        return '<?php foreach ('.trim($m[1]).'): ?>'
               .$innerContent
               .'<?php endforeach; ?>';
      },
      $content
    );

    // if-elif-else-endifの処理
    $content = preg_replace_callback(
      '/@if\s*\(((?:[^()]*|\((?:[^()]*|\([^()]*\))*\))*)\)(.*?)(?:@elif\s*\((.*?)\)(.*?))*(?:@else(.*?))?@endif/s',
      function($m) {
        $output = '<?php if ('.trim($m[1]).'): ?>'
                  .$this->procDirs($m[2]);
            
        if (preg_match_all('/@elif\s*\((.*?)\)(.*?)(?=@elif|@else|@endif)/s', 
                           $m[0], $em)) {
          foreach ($em[1] as $index => $condition) {
            $output .= '<?php elseif ('.trim($condition).'): ?>'
                       .$this->procDirs($em[2][$index]);
          }
        }
            
        if (preg_match('/@else(.*?)@endif/s', $m[0], $em)) {
          $output .= '<?php else: ?>'
                     .$this->procDirs($em[1]);
        }
            
        $output .= '<?php endif; ?>';
        return $output;
      },
      $content
    );

    return $content;
  }

  /**
   * テンプレート変数を処理する
   *
   * @param string $content テンプレート内容
   * @return string 処理後の内容
   */
  private function procVars(string $content): string {
    // 変数の出力（エスケープ処理あり）
    $content = preg_replace_callback('/\{\{\s*(.*?)\s*\}\}/', function($m) {
      return '<?= htmlspecialchars('.trim($m[1]).', ENT_QUOTES, \'UTF-8\') ?>';
    }, $content);

    // 変数の代入
    $content = preg_replace_callback('/\{\$\s*(.*?)\s*\$\}/', function($m) {
      $parts = explode('=', $m[1], 2);
      if (count($parts) !== 2)
        throw new \RuntimeException("不正な値の形式");
      return '<?php '.trim($parts[0]).' = '.trim($parts[1]).'; ?>';
    }, $content);

    // コメント
    $content = preg_replace_callback('/\{#\s*(.*?)\s*#\}/', function($m) {
      return '<?php /*'.trim($m[1]).'*/ ?>';
    }, $content);

    // PHPコードの実行
    $content = preg_replace_callback('/\{\!\s*(.*?)\s*\!\}/', function($m) {
      return '<?php '.trim($m[1]).' ?>';
    }, $content);

    return $content;
  }

  /**
   * カスタム関数を処理する
   *
   * @param string $content テンプレート内容
   * @return string 処理後の内容
   */
  private function procFuncs(string $content): string {
    foreach ($this->custFunc as $name => $cb) {
      $pattern = "@{$name}\((.*?)\)/";
      $content = preg_replace_callback($pattern, function($m) use ($cb) {
        $args = explode(',', $m[1]);
        $args = array_map('trim', $args);
        return call_user_func_array($cb, $args);
      }, $content);
    }

    return $content;
  }
}
?>
