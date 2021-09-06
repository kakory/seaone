<?php

namespace App\Admin\Actions\Finance;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

class Refuse extends RowAction
{
    public $name = ' 拒绝';

    public function handle(Model $model)
    {
        $model->step = 0;
        $model->save();

        return $this->response()->success('已拒绝')->refresh();
    }

}