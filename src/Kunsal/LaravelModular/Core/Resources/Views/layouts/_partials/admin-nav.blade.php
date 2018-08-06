@foreach($links as $link)
    @if(isset($link['ability']) && !is_null($link['ability']))
        @cannot($link['ability'])
            @continue
        @endcannot

        <li class="{{ (is_active($link['active'])) ? 'active' : '' }} nav-item">
            <a href="{{ isset($link['route']) ? route($link['route']) : url($link['uri']) }}" class="{{ is_child($link) ? 'menu-item' :'' }}">
                @if(!is_child($link['children']))
                    <i class="{{ $link['icon'] }}"></i><span data-i18n="" class="menu-title">{!! $link['title']  !!}</span>
                @else
                    {!! $link['title']  !!}
                @endif
            </a>
            @if(is_parent($link))
                <ul class="menu-content">
                    @include('layouts._partials.admin-nav', ['links' => $link['children']])
                </ul>
            @endif
        </li>

    @else
        @continue
    @endif

@endforeach
