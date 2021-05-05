<?php

namespace App\Admin\Selectable;

use App\Models\Customer;
use Encore\Admin\Grid\Filter;
use Encore\Admin\Grid\Selectable;

class Customers extends Selectable
{
    public $model = Customer::class;

    public function make()
    {
        $this->column('name', __('Name'));
        $this->column('phone_number', __('Phone number'));
        $this->column('company_name', __('Company name'));

        $this->filter(function (Filter $filter) {
            $filter->disableIdFilter();
            $filter->like('name', __('Name'));
			$filter->like('phone_number', __('Phone number'));
			$filter->like('company_name', __('Company name'));
        });
    }
}