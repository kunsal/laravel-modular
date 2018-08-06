<div class="row breadcrumbs-top">
    <div class="breadcrumb-wrapper col-xs-12">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a>
            </li>
            {{--{{ dd(Request::segments()) }}--}}
            <?php $link = '' ?>
            @foreach(Request::segments() as $segment)
                <?php $link = $link . '/' . $segment ?>
                @if(!preg_match('/^[0-9]/', $segment))
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}{{ $link }}">{{ $segment }}</a>
                    </li>
                @endif
            @endforeach
        </ol>
    </div>
</div>