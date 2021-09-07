<?php

namespace App\Admin\Actions\Finance;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;
use Encore\Admin\Facades\Admin;

class Close extends RowAction
{
    public $name = '关闭';

    public function handle(Model $model)
    {
        if ($model->admin_user_id != Admin::user()->id && !Admin::user()->isRole('treasurer') && !Admin::user()->isRole('manager')) {
            return $this->response()->error('您无权关闭')->refresh();
        }
        if ($model->step == 1) {
            $model->step = -1;
            $model->save();
            return $this->response()->success('已关闭')->refresh();
        } else {
            return $this->response()->error('该状态下不可关闭')->refresh();
        }
    }

}