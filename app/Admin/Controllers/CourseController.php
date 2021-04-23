<?php

namespace App\Admin\Controllers;

use App\Models\Course;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CourseController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Course';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Course());

        $grid->column('id', __('ID'))->hide();
        $grid->column('name', __('课程名称'));
        $grid->column('note', __('备注'));
        $grid->column('Tags')->display(function () {
            $tags = array();
            if($this->is_VIP) array_push($tags,"会员");
            if($this->is_incu) array_push($tags,"孵化");
            if($this->is_bench) array_push($tags,"标杆");
            return $tags;
        })->label();
        $grid->column('created_at', __('Created at'))->hide();
        $grid->column('updated_at', __('Updated at'))->hide();

        $grid->actions(function ($actions) {
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
        $show = new Show(Course::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('name', __('课程名称'));
        $show->field('note', __('备注'));
        $show->field('is_VIP', __('会员'));
        $show->field('is_incu', __('孵化'));
        $show->field('is_bench', __('标杆'));
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
        $form = new Form(new Course());

        $form->text('name', __('课程名称'))->required();
        $form->text('note', __('备注'))->required();
        $form->switch('is_VIP', __('会员'));
        $form->switch('is_incu', __('孵化'));
        $form->switch('is_bench', __('标杆'));
        
        $form->footer(function ($footer) {
            $footer->disableViewCheck();
            $footer->disableEditingCheck();
            $footer->disableCreatingCheck();
        });


        return $form;
    }
}
