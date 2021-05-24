<?php

namespace App\Admin\Controllers;

use App\Models\SeminarCustomer;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Admin\Extensions\SeminarCustomerExporter;

class SeminarCustomerController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '报名';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SeminarCustomer());

        $grid->disableCreateButton();

        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->equal('seminar_id', '课程ID');
            $filter->equal('customer_id', '客户ID');
        });
        $grid->disableFilter();
        $grid->exporter(new SeminarCustomerExporter());

        $grid->model()->orderBy('created_at', 'desc');
        $grid->column('id', __('Id'));
        $grid->column('seminar.name','课程名');
        $grid->column('customer.name','姓名');
        $grid->column('customer.phone_number','手机号');
        $grid->column('customer.company_name','公司名');
        $grid->column('顾问')->display(function () {
            if($this->customer->adviser){
                return $this->customer->adviser->name;
            }
        });
        $grid->column('合约')->showPrivileges('SeminarCustomer')->help('红色为已过期');
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'))->hide();
        $grid->column('status','状态')->radio([
            -1 => '审核失败',
            0 => '待审核',
            1 => '已审核-待打卡',
            2 => '已打卡',
        ])->dot([
            -1 => 'danger',
            0 => 'warning',
            1 => 'primary',
            2 => 'success',
        ]);

        $grid->disableRowSelector();
        $grid->actions(function ($actions) {
            $actions->disableEdit();
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
        $show = new Show(SeminarCustomer::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('customer_id', __('Customer id'));
        $show->field('seminar_id', __('Seminar id'));
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
        $form = new Form(new SeminarCustomer());

        $form->number('customer_id', __('Customer id'));
        $form->number('seminar_id', __('Seminar id'));
        $form->switch('status', __('Status'));

        return $form;
    }
}
