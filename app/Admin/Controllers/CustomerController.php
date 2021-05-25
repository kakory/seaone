<?php

namespace App\Admin\Controllers;

use App\Models\Customer;
use App\Models\Adviser;
use App\Admin\Selectable\Seminars;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Admin\Extensions\CustomerExporter;

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
        $grid->exporter(new CustomerExporter());

        $grid->column('id', __('Id'));
        $grid->column('photo', __('Photo'))->image(env('APP_URL').'/upload',50,50)->hide();
        $grid->column('name', __('Name'));
        $grid->column('phone_number', __('Phone number'));
        $grid->column('company_name', __('Company name'));
        $grid->column('adviser.name', '顾问');
        $grid->column('合约')->showPrivileges('Customer')->help('灰色为已过期');
        $grid->column('remark', __('Remark'));
        $grid->column('created_at', __('Created at'))->hide();
        $grid->column('updated_at', __('Updated at'))->hide();
        $grid->column('报名历史')->showEnrollByCustomer();

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
        $show = new Show(Customer::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('phone_number', __('Phone number'));
        $show->field('company_name', __('Company name'));
        $show->field('photo', __('Photo'));
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
            $form->column(1/2, function ($form) {
                $form->belongsToMany('enroll', Seminars::class, __('当前报名记录'));
            });
            $form->column(1/2, function ($form) {
                $form->text('name', __('Name'))->rules('required|unique:customer,name,{{id}}');
                $form->text('phone_number', __('Phone number'))->rules('required|digits:11|unique:customer,phone_number,{{id}}');
                $form->text('company_name', __('Company name'))->required();
                $form->select('adviser_id', '顾问')->options(Adviser::all()->pluck('name', 'id'));
                $form->image('photo', __('Photo'))->resize(null, 300, function($constraint){		// 调整图像的高到200，并约束宽高比(宽自动)
                    $constraint->aspectRatio();
                })->move('photos')->uniqueName();
                $form->text('remark', __('Remark'));
            });
        }
        if($form->isCreating()){
            $form->text('name', __('Name'))->rules('required|unique:customer')->help('重名请在后面加公司首字，例：陈丽嫦（圣）、李艳（个）');
            $form->text('phone_number', __('Phone number'))->rules('required|digits:11|unique:customer');
            $form->text('company_name', __('Company name'))->required()->help('个人请填客户姓名');
            $form->select('adviser_id', '顾问')->options(Adviser::all()->pluck('name', 'id'));
            $form->image('photo', __('Photo'))->move('photos')->uniqueName();
            $form->text('remark', __('Remark'));
        }

        return $form;
    }
}
