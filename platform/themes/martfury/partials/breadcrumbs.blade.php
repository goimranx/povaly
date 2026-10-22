<ul class="breadcrumb">
    @foreach ($crumbs = Theme::breadcrumb()->getCrumbs() as $i => $crumb)
        @if (! $loop->last)
            <li>
                <a href="{{ $crumb['url'] }}" itemprop="item">{!! BaseHelper::clean($crumb['label']) !!}</a>
                <span class="breadcrumb-separator"><i class="icon-chevron-right"></i></span>
            </li>
        @endif
    @endforeach
</ul>
