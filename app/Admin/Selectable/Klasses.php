<?php

namespace App\Admin\Selectable;

use App\Models\Klasse;
use Encore\Admin\Grid\Filter;
use Encore\Admin\Grid\Selectable;

class Klasses extends Selectable
{
    public $model = Klasse::class;

    public function make()
    {
        $this->model()->where('is_online', 1);
        $this->column('name', __('课程名'));
        $this->column('lecturer', __('Lecturer'));
        $this->column('closing_time', __('Closing time'));
    }

}