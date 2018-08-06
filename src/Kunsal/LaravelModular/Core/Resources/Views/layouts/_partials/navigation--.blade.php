<?php
    $parents = [];
    foreach($pages as $p){
        if($p->pivot->parent_id != null){
            $parents[] = $p->pivot->parent_id;
        }
    }

?>
@foreach($pages as $page)
    <li class="{{ Request::is($page->present()->uriWildCard) || Request::is($page->slug) ? 'current' : '' }} {{ in_array($page->id, $parents) ? 'dropdown' : '' }}">
        <a href="{{ url($page->uri) }}">
            @if(is_null($page->parent))
                <i class="{{ $page->icon }}"></i>
            @endif
            {{ $page->title }}
        </a>

        @if(in_array($page->id, $parents))
            @php
                $children = [];
                foreach($pages as $page){
                    
                }
            @endphp
            <ul class="submenu">
                @include('layouts._partials.navigation', ['pages' => $page->children])
            </ul>
        @endif
    </li>

@endforeach
