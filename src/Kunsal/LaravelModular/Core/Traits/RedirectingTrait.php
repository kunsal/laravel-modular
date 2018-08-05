<?php

namespace App\Modules\Core\Traits;


trait RedirectingTrait {
    protected function redirectRoute($route, $params = array(), $data = array())
    {
        return redirect()->route($route, $params)->with($data);
    }
    protected function redirectBack($data = array())
    {
        return back()->withInput()->withErrors($data);
    }

    protected function redirectRouteWithErrors($route, $data = array(), $params = array())
    {
        return redirect()->route($route, $params)->withErrors($data);
    }

    protected function redirectIntended($default = null)
    {
        return redirect()->intended($default);
    }

    protected function redirectTo($url)
    {
        return redirect($url);
    }

    protected function redirectGuest($url,$data=array())
    {
        return redirect()->guest($url)->withErrors($data);
    }
}