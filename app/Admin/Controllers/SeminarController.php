<?php

namespace App\Admin\Controllers;

use App\Models\Seminar;
use App\Models\Course;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;

class SeminarController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '课程';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Seminar());

        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->column(1/3, function ($filter) {
                $filter->like('name', '课程名称');
            });
            $filter->column(1/3, function ($filter) {
                $filter->between('start_at', __('Start at'))->datetime();
            });
            $filter->column(1/3, function ($filter) {
                $filter->between('end_at', __('End at'))->datetime();
            });
            $filter->expand();
        });
        $grid->model()->orderBy('start_at', 'desc');
        $grid->column('id', __('Id'));
        $grid->column('name', '课程名称');
        $grid->column('lecturer', __('Lecturer'));
        $grid->column('名额')->showEnrollBySeminar();
        $grid->column('start_at', __('Start at'));
        $grid->column('end_at', __('End at'));
        $grid->column('closing_at', __('Closing at'));
        $grid->column('is_online', __('Is online'))->filter([
            0 => '未上线',
            1 => '已上线',
        ])->switch();
        $grid->column('classroom', __('Classroom'))->hide();
        $grid->column('qrcode', __('Qrcode'))->hide();
        $grid->column('created_at', __('Created at'))->hide();
        $grid->column('updated_at', __('Updated at'))->hide();

        $grid->disableRowSelector();
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
        $show = new Show(Seminar::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('course_id', __('Course id'));
        $show->field('name', __('Name'));
        $show->field('lecturer', __('Lecturer'));
        $show->field('quota', __('Quota'));
        $show->field('remaining_quota', __('Remaining quota'));
        $show->field('start_at', __('Start at'));
        $show->field('end_at', __('End at'));
        $show->field('closing_at', __('Closing at'));
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
        $form = new Form(new Seminar());

        $form->select('course_id', __('Course id'))->options(Course::all()->pluck('name', 'id'))->required();
        $form->hidden('name');
        $form->text('lecturer', __('Lecturer'))->required();
        $form->number('quota', __('Quota'))->required();
        $form->datetime('start_at', __('Start at'))->required();
        $form->datetime('end_at', __('End at'))->required();
        $form->datetime('closing_at', __('Closing at'))->required();
        $form->switch('is_online', __('Is online'));
        $form->text('classroom', __('Classroom'));
        $form->image('qrcode', __('Qrcode'))->move('qrcodes')->uniqueName();

        $form->saving(function (Form $form) {
            if($form->start_at==''){
                return $form;
            }
            $start_month = ltrim(substr($form->start_at, 5, 2), '0') . '月';
            $start_day = ltrim(substr($form->start_at, 8, 2), '0') . '日';
            $end_month = ltrim(substr($form->end_at, 5, 2), '0') . '月';
            $end_day = ltrim(substr($form->end_at, 8, 2), '0') . '日';
            $name = Course::where('id', $form->course_id)->value('name');
            if($start_month !== $end_month){
                $form->name = $start_month . $start_day . '-' . $end_month . $end_day . $name;
            }else if($start_day == $end_day){
                $form->name = $start_month . $start_day . $name;
            }else{
                $form->name = $start_month . $start_day . '-' . $end_day . $name;
            }
        });

        return $form;
    }
}
