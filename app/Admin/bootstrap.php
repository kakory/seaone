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
use App\Models\Privilege;

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
    return "<a href= seminar-customers?seminar_id=$this->id>
                <i class='fa fa-chevron-right'/> $this->occupied_quota/$this->quota
            </a>";
});

Column::extend('showEnrollByCustomer', function () {
    return "<a href= seminar-customers?customer_id=$this->id>
                <i class='fa fa-eye'/> 查看
            </a>";
});

Column::extend('showPrivileges', function ($value, $table) {
    $result = '';
    $id = $table == 'Customer' ? $this->id : $this->customer_id;
    $privileges = PrivilegeCustomer::where('customer_id', $id)->get();
    foreach ($privileges as $privilege) {
        $bgcolor = $privilege->limit && $privilege->limit < date('Y-m-d') ? 'label-default' : 'label-success';
        $name = Privilege::where('id', $privilege->privilege_id)->first()->name;
        $result .= "<span class='label " . $bgcolor . "'>" . $name . "</span> ";
    }
    return $result;
});

Column::extend('checkExpire', function ($value) {
    $color = date('Y-m-d') > $value ? '#f00' : '#000';
    return "<span style='color: $color'>$value</span>";
});