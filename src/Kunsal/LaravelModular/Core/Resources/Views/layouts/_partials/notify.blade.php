
@if($errors->any())
    <div class="alert alert-callout alert-danger alert-dismissable" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>

            @foreach($errors->all() as $error)
                {{ $error }}<br>
            @endforeach

    </div>
@endif
@if($status)
    <div class="alert alert-callout alert-success alert-dismissable" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        {{ $status }}
    </div>
@endif

