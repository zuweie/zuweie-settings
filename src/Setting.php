<?php

namespace Zuweie\Setting;

use Encore\Admin\Extension;

class Setting extends Extension
{
    public $name = 'setting';

    public $views = __DIR__.'/../resources/views';

    public $assets = __DIR__.'/../resources/assets';

    public $menu = [
        'title' => 'Setting',
        'path'  => 'setting',
        'icon'  => 'fa-gears',
    ];
}