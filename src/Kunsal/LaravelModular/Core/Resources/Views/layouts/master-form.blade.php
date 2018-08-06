@extends('layouts.backend')
@section('content')
    <div class="content-body">
        <!-- Basic Elements start -->
        <section class="basic-elements">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">@yield('page-name')</h4>
                        </div>
                        <div class="card-body">
                            <div class="card-block">
                                @yield('page-content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('general-css')
<link rel="stylesheet" type="text/css" href="{{ asset('/') }}app-assets/vendors/css/forms/selects/select2.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('/') }}app-assets/vendors/css/forms/icheck/icheck.css">
<link rel="stylesheet" type="text/css" href="{{ asset('/') }}app-assets/vendors/css/forms/icheck/custom.css">
@stack('page-css')
@endpush

@push('general-js')
    <script src="{{ asset('/') }}app-assets/vendors/js/forms/tags/form-field.js" type="text/javascript"></script>
    <script src="{{ asset('/') }}app-assets/vendors/js/tinymce/tinymce.min.js" type="text/javascript"></script>
    <script src="{{ asset('/') }}app-assets/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="{{ asset('/') }}app-assets/vendors/js/forms/icheck/icheck.min.js" type="text/javascript"></script>
    {{--<script src="{{ asset('/') }}app-assets/js/scripts/forms/checkbox-radio.min.js" type="text/javascript"></script>
--}}
<script>
    tinymce.init({
        selector: '.wysiwig',
        height: 500,
        theme: 'modern',
        plugins: [
            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality',
            'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc help spellchecker'
        ],
        toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        toolbar2: 'print preview media | forecolor backcolor emoticons | codesample help | spellchecker',
        image_advtab: true,
        templates: [
            { title: 'Test template 1', content: 'Test 1' },
            { title: 'Test template 2', content: 'Test 2' }
        ],
        content_css: [
            '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
            '//www.tinymce.com/css/codepen.min.css'
        ]
    });
</script>

<script>
    $('select').select2();

    $('input[type=checkbox]').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '40%' // optional
    });

</script>
    @stack('page-js')
@endpush

