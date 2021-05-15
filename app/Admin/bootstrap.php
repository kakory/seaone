<?php

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */
use Encore\Admin\Grid\Column;
use Encore\Admin\Form;
use App\Models\PrivilegeCustomer;

Encore\Admin\Form::forget(['map', 'editor']);

Form::init(function (Form $form) {

    $form->disableEditingCheck();
    $form->disableCreatingCheck();
    $form->disableViewCheck();

    $form->tools(function (Form\Tools $tools) {
        $tools->disableDelete();
        $tools->disableView();
    });
});

Column::extend('showEnrollBySeminar', function () {
	$remaining_quota = $this->customers->count();
    return "<a href= seminar-customers?seminar_id=$this->id>
                <i class='fa fa-chevron-right'/> $remaining_quota/$this->quota
            </a>";
});

Column::extend('showEnrollByCustomer', function () {
    return "<a href= seminar-customers?customer_id=$this->id>
                <i class='fa fa-eye'/> 查看
            </a>";
});

Column::extend('showPrivileges', function ($value, $table) {
    $result = '';
    if($table == 'Customer'){
        $id = $this->id;
    } else {
        $id = $this->customer_id;
    }
    $privileges = PrivilegeCustomer::where('customer_id', $id)->get();
    foreach ($privileges as $privilege) {
        $bgcolor = $privilege->limit > date('Y-m-d') ? 'label-success' : 'label-danger';
        $name = ($privilege->privilege_id == 1) ? 'VIP' : '标杆';
        $result .= "<span class='label " . $bgcolor . "'>" . $name . "</span> ";
    }
    return $result;
});

Column::extend('showLimit', function ($value) {
    if(date('Y-m-d') > $value){
        return "<span style='color: #f00'>$value</span>";
    }else{
        return "<span>$value</span>";
    }
});