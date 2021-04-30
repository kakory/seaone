<?php

namespace App\Admin\Controllers;

use App\Models\Enroll;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class EnrollController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Enroll';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Enroll());

        $grid->column('id', __('Id'));
        $grid->column('klasse_id', __('Klasse id'));
        $grid->column('customer_id', __('Customer id'));
        $grid->column('status', __('Status'));
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
        $show = new Show(Enroll::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('klasse_id', __('Klasse id'));
        $show->field('customer_id', __('Customer id'));
        $show->field('status', __('Status'));
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
        $form = new Form(new Enroll());

        $form->number('klasse_id', __('Klasse id'));
        $form->number('customer_id', __('Customer id'));
        $form->switch('status', __('Status'));

        return $form;
    }
}
