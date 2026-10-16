<aside class="widget widget--blog widget--search">
    <form class="ps-form--widget-search" action="{{ route('public.search') }}" method="GET">
        <input class="form-control" name="q" value="{{ BaseHelper::stringify(request()->query('q')) }}" type="text" placeholder="{{ __('Search...') }}">
        <button type="submit" aria-label="{{ __('Search') }}"><i class="icon-magnifier" aria-hidden="true"></i></button>
    </form>
</aside>
