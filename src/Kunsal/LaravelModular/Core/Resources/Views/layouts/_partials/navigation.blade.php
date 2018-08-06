
@foreach($pages as $menu)

    @php $page = \App\Modules\Pages\Models\Page::find($menu['page_id']) @endphp
    @if(is_null($page)) @continue @endif
    @if($page->status == 0) @continue @endif

    <li>

        @if(!is_child($menu))
            <span class="{{ Request::is($page->present()->uriWildCard) || Request::is($page->slug) ? 'current' : '' }}">
                <a href="{{ url($page->uri) }}">{{ $page->title }}</a>
            </span>
            @else
            <a href="{{ url($page->uri) }}">{{ $page->title }}</a>
        @endif
        @if(isset($menu['children']))
            <ul>
                @include('layouts._partials.navigation', ['pages' => $menu['children']])
            </ul>
        @endif
    </li>

@endforeach
