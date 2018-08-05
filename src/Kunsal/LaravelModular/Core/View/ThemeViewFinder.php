<?php

namespace App\Modules\Core\View;


use Illuminate\View\FileViewFinder;

class ThemeViewFinder extends FileViewFinder{
    protected $basePath;

    protected $activeTheme;

    public function setBasePath($path)
    {
        $this->basePath = $path;
    }

    public function setActiveTheme($theme)
    {
        $this->activeTheme = $theme;
        array_unshift($this->paths, $this->basePath.'/'.$theme.'/views');
    }
}