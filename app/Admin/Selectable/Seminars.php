<?php

namespace App\Admin\Selectable;

use App\Models\Seminar;
use Encore\Admin\Grid\Filter;
use Encore\Admin\Grid\Selectable;

class Seminars extends Selectable
{
    public $model = Seminar::class;

    public function make()
    {
        $this->model()->where('is_online', 1);
        $this->column('name', __('课程名'));
        $this->column('lecturer', __('Lecturer'));
        $this->column('closing_date_at', __('Closing date at'));
    }

}