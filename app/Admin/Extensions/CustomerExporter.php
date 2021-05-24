<?php

namespace App\Admin\Extensions;

use Encore\Admin\Grid\Exporters\ExcelExporter;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomerExporter extends ExcelExporter implements WithMapping
{
    protected $fileName = 'Customers.xlsx';

    protected $columns = [
        'id'=>'id',
        'name'=>'name',
        'phone_number'=>'phone_number',
        'company_name'=>'company_name',
        'adviser_id'=>'adviser_name',
        'remark'=>'remark',
    ];

    public function map($customer) : array
    {
        return [
            $customer->id,
            $customer->name,
            $customer->phone_number,
            $customer->company_name,
            data_get($customer, 'adviser.name'),
            $customer->remark,
        ];
    }
}