<?php

namespace App\Admin\Controllers;

use App\Models\PrivilegeCustomer;
use App\Models\Privilege;
use App\Models\Customer;
use App\Admin\Selectable\Customers;
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
    protected $title = '合约';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PrivilegeCustomer());

        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->column(1/3, function ($filter) {
                $filter->like('customer.name', '姓名');
            });
            $filter->column(1/3, function ($filter) {
                $filter->like('customer.phone_number','手机号');
            });
            $filter->column(1/3, function ($filter) {
                $filter->like('customer.company_name','公司名');
            });
            $filter->expand();
        });

        $grid->model()->orderBy('id', 'desc');
        $grid->column('id', __('Id'));
        $grid->column('customer.name','姓名');
        $grid->column('customer.phone_number','手机号');
        $grid->column('customer.company_name','公司名');
        $grid->column('privilege.name', '合约名');
        $grid->column('limit', __('Limit'))->checkExpire()->help('红色为已过期');
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

        if($form->isCreating()){
            $form->belongsTo('customer_id', Customers::class, '客户')->required();
            $form->radioButton('privilege_id', '合约名')->options(Privilege::all()->pluck('name', 'id'))->required()->default(1);
            $form->date('limit', __('Limit'));
        }
        if($form->isEditing()){
            $form->hidden('customer_id');
            $form->display('customer.name','客户');
            $form->hidden('privilege_id');
            $form->display('privilege.name', '合约名');
            $form->date('limit', __('Limit'));
        }
        
        $form->saving(function (Form $form) {
            if(PrivilegeCustomer::where('customer_id',$form->customer_id)->where('privilege_id',$form->privilege_id)->first()){
                if (!$id = $form->model()->id) {
                    admin_toastr('合约重复，请在原有合约上更新', 'error');
                    throw new \Exception('合约重复，请在原有合约上更新');
                }
            }
            if(Privilege::where('id', $form->privilege_id)->first()->type == 0 && !$form->limit){
                admin_toastr('请填写到期时间', 'error');
                throw new \Exception('请填写到期时间');
            }
        });

        return $form;
    }
}
