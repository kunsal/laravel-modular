@extends('layouts.backend')
@section('content')
    <div class="content-body">
        <!-- Basic Elements start -->
        <section class="basic-elements">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-block">
                                <div class="table-responsive">
                                    @yield('page-content')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('general-css')
    @include('layouts/_partials/datatable-styles')
    @stack('page-css')
    <style>
        tr .btn {
            line-height: 0.25;
        }
    </style>
@endpush

@push('general-js')
    @include('layouts/_partials/datatable-scripts')
    @stack('page-js')
@endpush
