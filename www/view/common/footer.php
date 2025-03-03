    </main>
    <footer>
      <p>
@foreach ($support as $s)
  @if ($s['show'])
        <a class="nodeco" href="{{ $s['href'] }}">
          <img class="{{ $s['class'] }}" src="{{ $s['img'] }}" alt="{{ $s['alt'] }}" />
        </a>
  @endif
@endforeach
      </p>
      <p>
@foreach ($sns as $s)
  @if ($s['show'])
        <a class="nodeco" href="{{ $s['href'] }}">
          <img class="{{ $s['class'] }}" src="{{ $s['img'] }}" alt="{{ $s['alt'] }}" />
        </a>
  @endif
@endforeach
      </p>
      <address>Copyright © 076</address>
    </footer>
  </div>
</body>
</html>
