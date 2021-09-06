<?php

namespace App\Admin\Actions\Finance;

use App\Models\Order;
use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

class Accept extends RowAction
{
    public $name = ' 通过';

    public function handle(Model $model)
    {
        if ($model->type == 0) {
            $model->step = 3;
        } else if ($model->type == 1){
            $model->step = 2;
        }
        $model->save();

        return $this->response()->success('已通过')->refresh();
    }

}