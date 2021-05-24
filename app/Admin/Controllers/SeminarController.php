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
            $filter->like('name', '课程名称');
            $filter->expand();
        });
        $grid->model()->orderBy('start_date_at', 'desc');
        $grid->column('id', __('Id'));
        $grid->column('name', '课程名称');
        $grid->column('lecturer', __('Lecturer'));
        $grid->column('名额')->showEnrollBySeminar();
        $grid->column( __('Start at'))->display(function () {
            return $this->start_date_at . ' ' . $this->start_time_at;
        });
        $grid->column( __('End at'))->display(function () {
            return $this->end_date_at . ' ' . $this->end_time_at;
        });
        $grid->column( __('Closing at'))->display(function () {
            return $this->closing_date_at . ' ' . $this->closing_time_at;
        });
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
        // $form->hidden('occupied_quota');
        $form->hidden('group');
        $form->dateRange('start_date_at', 'end_date_at', 'Date Range')->required();
        $form->date('closing_date_at', __('Closing at'))->required();
        $form->hidden('start_time_at');
        $form->hidden('end_time_at');
        $form->hidden('closing_time_at');
        $form->switch('is_online', __('Is online'));
        $form->text('classroom', __('Classroom'));
        $form->image('qrcode', __('Qrcode'))->move('qrcodes')->uniqueName();

        $form->saving(function (Form $form) {
            if($form->start_date_at==''){
                return $form;
            }
            
            if(Course::where('id', $form->course_id)->first()->privilege_id == 2){
                $form->start_time_at = '09:30:00';
            }else{
                $form->start_time_at = '09:00:00';
            }
            $form->end_time_at = '18:00:00';
            $form->closing_time_at = '00:00:00';
            
            $start_month = ltrim(substr($form->start_date_at, 5, 2), '0') . '月';
            $start_day = ltrim(substr($form->start_date_at, 8, 2), '0') . '日';
            $end_month = ltrim(substr($form->end_date_at, 5, 2), '0') . '月';
            $end_day = ltrim(substr($form->end_date_at, 8, 2), '0') . '日';
            $name = Course::where('id', $form->course_id)->value('name');
            if($start_month !== $end_month){
                $form->name = $start_month . $start_day . '-' . $end_month . $end_day . $name;
            }else if($start_day == $end_day){
                $form->name = $start_month . $start_day . $name;
            }else{
                $form->name = $start_month . $start_day . '-' . $end_day . $name;
            }

            $form->group = substr($form->start_date_at, 0, 7) . '-' . $form->course_id;

            // $form->occupied_quota = $form->model()->customers->count();
        });

        return $form;
    }
}
