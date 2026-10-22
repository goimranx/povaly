<ul class="breadcrumb">
    @foreach ($crumbs = Theme::breadcrumb()->getCrumbs() as $i => $crumb)
        @if (! $loop->last)
            <li>
                <a href="{{ $crumb['url'] }}" itemprop="item">{!! BaseHelper::clean($crumb['label']) !!}</a>
            </li>
        @endif
    @endforeach
</ul>
