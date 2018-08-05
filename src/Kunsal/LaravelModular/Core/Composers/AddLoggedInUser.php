<?php

namespace Kunsal\LaravelModular\Core\Composers;

use Illuminate\View\View;

class AddLoggedInUser {
    public function compose(View $view)
    {
        $view->with('user', auth()->user());
    }
}