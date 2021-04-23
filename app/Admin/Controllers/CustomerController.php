<?php

namespace App\Admin\Controllers;

use App\Models\Customer;
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
    protected $title = 'Customer';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Customer());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('phone_number', __('Phone number'));
        $grid->column('company_name', __('Company name'));
        $grid->column('address', __('Address'));
        $grid->column('is_VIP', __('Is VIP'));
        $grid->column('is_incu', __('Is incu'));
        $grid->column('is_bench', __('Is bench'));
        $grid->column('photo', __('Photo'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show->field('is_VIP', __('Is VIP'));
        $show->field('is_incu', __('Is incu'));
        $show->field('is_bench', __('Is bench'));
        $show->field('photo', __('Photo'));
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

        $form->text('name', __('Name'));
        $form->text('phone_number', __('Phone number'));
        $form->text('company_name', __('Company name'));
        $form->text('address', __('Address'));
        $form->switch('is_VIP', __('Is VIP'));
        $form->switch('is_incu', __('Is incu'));
        $form->switch('is_bench', __('Is bench'));
        $form->text('photo', __('Photo'));

        return $form;
    }
}
