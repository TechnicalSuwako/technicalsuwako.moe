@include(common/header)
      <div class="search-form">
        <form action="/" method="GET">
          <input type="text" name="q" value="{! echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; !}" placeholder="キーワードを入力して下さい" />
          <input type="submit" value="検索" />
        </form>
      </div>

      <div class="news-grid">
        @foreach ($posts as $post)
          <article class="news-card">
            <?php if (isset($post['thumbnail']) && $post['thumbnail'] != ''): ?>
            <div class="news-image">
              <a href="/blog/{{ $post['slug'] }}">
                <img src="/static/article/{{ $post['thumbnail'] }}" alt="{{ $post['title'] }}" />
              </a>
            </div>
            <?php endif; ?>
            <div class="news-content">
              <div class="news-meta">
                <span class="news-date">{{ $post['date'] }}</span>
                {# @if (isset($post['category']) && is_array($post['category'])) #}
                  <?php foreach ($post['category'] as $cat): ?>
                    <span class="news-category">{{ $cat }}</span>
                  <?php endforeach; ?>
                {# @endif #}
              </div>
              <h2 class="news-title">
                <a href="/blog/{{ $post['slug'] }}{! echo isset($_GET['q']) ? '?q='.urlencode($_GET['q']) : '' !}">{! echo $post['title'] !}</a>
              </h2>
              <p class="news-preview">{! echo $post['preview'] !}</p>
            </div>
          </article>
        @endforeach
      </div>

      @if (isset($totalPages) && $totalPages > 1)
      <div class="pagination">
        {# 検索クエリがある場合はページネーションリンクに含める #}
        {$ $queryParams = [] $}
        <?php if (isset($_GET['q']) && !empty($_GET['q'])): ?>
          {$ $queryParams['q'] = $_GET['q'] $}
        <?php endif; ?>

        {# 前のページへのリンク #}
        <?php if (isset($currentPage) && $currentPage > 1): ?>
        {$ $prevParams = $queryParams $}
        {$ $prevParams['page'] = $currentPage - 1 $}
        {$ $prevQueryString = http_build_query($prevParams) $}
        <a href="?{{ $prevQueryString }}" class="page-link">&laquo; 前</a>
        <?php endif; ?>

        {# 表示するページ番号の範囲を計算（モバイル対応の為） #}
        {# 最大表示ページ数 #}
        {$ $rangeSize = 2 $}
        {$ $startPage = max(1, $currentPage - floor($rangeSize / 2)) $}
        {$ $endPage = min($totalPages, $startPage + $rangeSize - 1) $}

        {# 範囲の調整 #}
        <?php if ($endPage - $startPage + 1 < $rangeSize && $startPage > 1): ?>
        {$ $startPage = max(1, $endPage - $rangeSize + 1) $}
        <?php endif; ?>

        {# 最初のページへのリンク（多数のページがある場合） #}
        <?php if ($startPage > 1): ?>
        {$ $firstParams = $queryParams $}
        {$ $firstParams['page'] = 1 $}
        {$ $firstQueryString = http_build_query($firstParams) $}
        
        <a href="?{{ $firstQueryString }}" class="page-link">1</a>
        <?php if ($startPage > 2): ?>
        <span class="page-ellipsis">...</span>
        <?php endif; ?>
        <?php endif; ?>
  
        @for ($i = $startPage; $i <= $endPage; $i++)
          {$ $pageParams = $queryParams $}
          {$ $pageParams['page'] = $i $}
          {$ $pageQueryString = http_build_query($pageParams) $}
      
          <?php if ($i == $currentPage): ?>
            <span class="page-current" aria-current="page">{{ $i }}</span>
          <?php else: ?>
            <a href="?{{ $pageQueryString }}" class="page-link">{{ $i }}</a>
          <?php endif; ?>
        @endfor

        <?php if ($endPage < $totalPages): ?>
          {# 最後のページへのリンク（多数のページがある場合） #}
          {$ $lastParams = $queryParams $}
          {$ $lastParams['page'] = $totalPages $}
          {$ $lastQueryString = http_build_query($lastParams) $}
          <?php if ($endPage < $totalPages - 1): ?>
          <span class="page-ellipsis">...</span>
          <?php endif; ?>
          <a href="?{{ $lastQueryString }}" class="page-link">{{ $totalPages }}</a>
        <?php endif; ?>

        {# 次のページへのリンク #}
        <?php if (isset($currentPage) && $currentPage < $totalPages): ?>
          {$ $nextParams = $queryParams $}
          {$ $nextParams['page'] = $currentPage + 1 $}
          {$ $nextQueryString = http_build_query($nextParams) $}
          <a href="?{{ $nextQueryString }}" class="page-link">次 &raquo;</a>
        <?php endif; ?>
      </div>
      @endif
@include(common/footer)
