<?php

namespace App\Admin\Controllers;

use App\Models\PrivilegeCustomer;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PrivilegeCustomerController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'PrivilegeCustomer';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PrivilegeCustomer());

        $grid->column('id', __('Id'));
        $grid->column('customer_id', __('Customer id'));
        $grid->column('privilege_id', __('Privilege id'));
        $grid->column('limit', __('Limit'));
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
        $show = new Show(PrivilegeCustomer::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('customer_id', __('Customer id'));
        $show->field('privilege_id', __('Privilege id'));
        $show->field('limit', __('Limit'));
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
        $form = new Form(new PrivilegeCustomer());

        $form->number('customer_id', __('Customer id'));
        $form->number('privilege_id', __('Privilege id'));
        $form->datetime('limit', __('Limit'))->default(date('Y-m-d H:i:s'));

        return $form;
    }
}
