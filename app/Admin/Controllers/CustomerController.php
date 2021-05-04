<?php

namespace App\Admin\Controllers;

use App\Models\Customer;
use App\Admin\Selectable\Seminars;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CustomerController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '客户';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Customer());

        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->column(1/3, function ($filter) {
                $filter->like('name', __('Name'));
            });
            $filter->column(1/3, function ($filter) {
                $filter->like('phone_number', __('Phone number'));
            });
            $filter->column(1/3, function ($filter) {
                $filter->like('company_name', __('Company name'));
            });
            $filter->expand();
        });
        $grid->column('id', __('Id'))->hide();
        $grid->column('photo', __('Photo'))->image(env('APP_URL').'/upload',50,50);
        $grid->column('name', __('Name'));
        $grid->column('phone_number', __('Phone number'));
        $grid->column('company_name', __('Company name'));
        $grid->column('remark', __('Remark'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'))->hide();

        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableView();
        });
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Customer::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('phone_number', __('Phone number'));
        $show->field('company_name', __('Company name'));
        $show->field('photo', __('Photo'));
        $show->field('remark', __('Remark'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Customer());

        if($form->isEditing()){
            $form->belongsToMany('enroll', Seminars::class, __('报名记录'));
        }
        $form->text('name', __('Name'))->required();
        $form->mobile('phone_number', __('Phone number'))->required();
        $form->text('company_name', __('Company name'))->required();
        $form->image('photo', __('Photo'))->move('photos')->uniqueName();
        $form->text('remark', __('Remark'));

        return $form;
    }
}
