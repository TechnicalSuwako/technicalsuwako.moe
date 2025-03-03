@include(common/header)
      <div class="search-form">
        <form action="/" method="GET">
          <input type="text" name="q" value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>" placeholder="キーワードを入力して下さい" />
          <input type="submit" value="検索" />
        </form>
      </div>

      <div class="news-article">
        <?php if (isset($meta->thumbnail) && $meta->thumbnail != ''): ?>
        <div class="thumbnail{{ isset($meta->thumborient) && $meta->thumborient != 'center' ? ' '.$meta->thumborient : '' }}">
          <img src="/static/article/{{ $meta->thumbnail }}" alt="" />
        </div>
        <?php endif; ?>
        <div class="meta">
          <div class="meta-date">{{ $meta->date }}</div>
          <div class="meta-author">{{ $meta->author }}</div>
          @if (isset($meta->category))
          @foreach ($meta->category as $cat)
          <div class="meta-category">
            {{ $cat }}
          </div>
          @endforeach
          @endif
        </div>

        <h1 class="title">{{ $meta->title }}</h1>
        {! echo $article; !}
      </div>
@include(common/footer)
