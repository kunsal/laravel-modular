<?php

namespace Kunsal\LaravelModular\Core\Composers;


use Illuminate\View\View;

class AddSuccessAndErrorMessage {

    public function compose(View $view)
    {
        $view->with(['status' => session('status'), 'error' => session('error')]);
    }
}