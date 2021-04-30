<?php

namespace App\Admin\Controllers;

use App\Models\Klasse;
use App\Models\Course;
use App\Models\Customer;
use App\Models\Enroll;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Widgets\Table;

class KlasseController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '班级';

    public function edit($id, Content $content)
    {
        return $content
            ->title('header')
            ->description('description')
            ->row(Admin::grid(Enroll::class, function (Grid $grid) use ($id) {

                $grid->setName('enroll')
                    ->resource('/admin/enrolls');

                $grid->model()->where('klasse_id', '=', $id);
                $grid->column('姓名')->display(function() {
                    return Customer::find($this->customer_id)->name;
                });
                $grid->column('手机号')->display(function() {
                    return Customer::find($this->customer_id)->phone_number;
                });
                $grid->column('公司名')->display(function() {
                    return Customer::find($this->customer_id)->company_name;
                });
                $grid->column('created_at', __('Created at'));
                $grid->column('updated_at', __('Updated at'));
                $grid->column('status', __('Status'))->editable('select', [0 => '待审核', 1 => '已审核', 2 => '已打卡']);

                $grid->actions(function ($actions) {
                    $actions->disableEdit();
                    $actions->disableView();
                });

            }))
            ->row($this->form()->edit($id))
            ;
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Klasse());

        //$grid->model()->orderBy('start_time', 'asc');

        $grid->column('id', __('Id'))->hide();
        $grid->column('name', '课程名称');
        $grid->column('lecturer', __('Lecturer'));
        $grid->column('start_time', __('Start time'));
        $grid->column('end_time', __('End time'));
        $grid->column('报名人数')->display(function () {
            return $this->customers->count().'/'.$this->quota;
        })->modal('报名记录', function ($model) {

            $customers = $model->customers()->get()->map(function ($customer) {
                return $customer->only(['company_name', 'name', 'phone_number', 'pivot']);
            });
        
            return new Table(['公司名', '姓名', '手机号', '状态'], $customers->toArray());
        });
        $grid->column('closing_time', __('Closing time'));
        $grid->column('is_online', __('Is online'))->filter([
            0 => '未上线',
            1 => '已上线',
        ])->switch();
        $grid->column('classroom', __('Classroom'))->hide();
        $grid->column('qrcode', __('Qrcode'))->hide();
        $grid->column('created_at', __('Created at'))->hide();
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
        $show = new Show(Klasse::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('course_id', __('Course id'));
        $show->field('name', '课程名称');
        $show->field('lecturer', __('Lecturer'));
        $show->field('quota', __('Quota'));
        $show->field('start_time', __('Start time'));
        $show->field('end_time', __('End time'));
        $show->field('closing_time', __('Closing time'));
        $show->field('is_online', __('Is online'));
        $show->field('classroom', __('Classroom'));
        $show->field('qrcode', __('Qrcode'))->image();
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

        $form->select('course_id', __('Course id'))->options(Course::all()->pluck('name', 'id'))->required();
        $form->hidden('name');
        $form->text('lecturer', __('Lecturer'))->required();
        $form->number('quota', __('Quota'))->required();
        $form->datetime('start_time', __('Start time'))->required();
        $form->datetime('end_time', __('End time'))->required();
        $form->datetime('closing_time', __('Closing time'))->required();
        $form->switch('is_online', __('Is online'));
        $form->text('classroom', __('Classroom'))->required();
        $form->image('qrcode', __('Qrcode'))->move('qrcodes')->uniqueName();
        
        $form->saving(function (Form $form) {
            if($form->start_time==''){
                return $form;
            }
            $start_month = ltrim(substr($form->start_time, 5, 2), '0') . '月';
            $start_day = ltrim(substr($form->start_time, 8, 2), '0') . '日';
            $end_month = ltrim(substr($form->end_time, 5, 2), '0') . '月';
            $end_day = ltrim(substr($form->end_time, 8, 2), '0') . '日';
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
