<?php

namespace App\Admin\Controllers;

use App\Models\Customer;
use App\Models\Klasse;
use App\Admin\Selectable\Klasses;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Str;


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
        $grid->column('name', __('Name'));
        $grid->column('phone_number', __('Phone number'));
        $grid->column('company_name', __('Company name'));
        $grid->column('address', __('Address'));
        $grid->column('tags', __('Tags'));
        //$grid->column('photo', __('Photo'));
        $grid->column('remark', __('Remark'));
        $grid->column('created_at', __('Created at'))->hide();
        $grid->column('updated_at', __('Updated at'))->hide();

        //$grid->column('classes', '报名记录')->belongsToMany(Klasses::class);
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
        $show->field('address', __('Address'));
        $show->field('tags', __('Tags'));
        $show->field('photo', __('Photo'))->image();
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
            $form->belongsToMany('classes', Klasses::class, __('报名记录'));
        }
        $form->text('name', __('Name'))->required();
        $form->mobile('phone_number', __('Phone number'))->required();
        $form->text('company_name', __('Company name'))->required();
        $form->text('address', __('Address'))->default('广东省中山市')->required();
        $form->text('tags', __('Tags'));
        $form->image('photo', __('Photo'))->move('photos')->uniqueName();
        $form->text('remark', __('Remark'));
        //$form->multipleSelect('classes', '报名记录')->options(Klasse::all()->pluck('start_time', 'id'));
        return $form;
    }
}
