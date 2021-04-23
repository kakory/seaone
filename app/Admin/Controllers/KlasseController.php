<?php

namespace App\Admin\Controllers;

use App\Models\Klasse;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class KlasseController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Klasse';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Klasse());

        $grid->column('id', __('Id'));
        $grid->column('course_id', __('Course id'));
        $grid->column('course.name');
        $grid->column('lecturer', __('Lecturer'));
        $grid->column('total_quota', __('Total quota'));
        $grid->column('remaining_quota', __('Remaining quota'));
        $grid->column('start_time', __('Start time'));
        $grid->column('end_time', __('End time'));
        $grid->column('closing_time', __('Closing time'));
        $grid->column('is_online', __('Is online'));
        $grid->column('classroom', __('Classroom'));
        $grid->column('qrcode', __('Qrcode'));
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
        $show = new Show(Klasse::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('course_id', __('Course id'));
        $show->field('lecturer', __('Lecturer'));
        $show->field('total_quota', __('Total quota'));
        $show->field('remaining_quota', __('Remaining quota'));
        $show->field('start_time', __('Start time'));
        $show->field('end_time', __('End time'));
        $show->field('closing_time', __('Closing time'));
        $show->field('is_online', __('Is online'));
        $show->field('classroom', __('Classroom'));
        $show->field('qrcode', __('Qrcode'));
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
        $form = new Form(new Klasse());

        $form->number('course_id', __('Course id'));
        $form->text('lecturer', __('Lecturer'));
        $form->number('total_quota', __('Total quota'));
        $form->number('remaining_quota', __('Remaining quota'));
        $form->datetime('start_time', __('Start time'))->default(date('Y-m-d H:i:s'));
        $form->datetime('end_time', __('End time'))->default(date('Y-m-d H:i:s'));
        $form->datetime('closing_time', __('Closing time'))->default(date('Y-m-d H:i:s'));
        $form->switch('is_online', __('Is online'));
        $form->text('classroom', __('Classroom'));
        $form->text('qrcode', __('Qrcode'));

        return $form;
    }
}
