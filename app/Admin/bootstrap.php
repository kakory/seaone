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

Encore\Admin\Form::forget(['map', 'editor']);

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
