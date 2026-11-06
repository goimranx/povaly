{!! Theme::partial('header-meta') !!}
<body {!! Theme::bodyAttributes() !!} @if (Theme::get('pageId')) id="{{ Theme::get('pageId') }}" @endif>
    {!! apply_filters(THEME_FRONT_BODY, null) !!}
    <div id="alert-container"></div>

    {!! Theme::content() !!}

    {!! Theme::footer() !!}
</body>
</html>
