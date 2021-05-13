<?php

namespace App\Admin\Extensions;

use Encore\Admin\Grid\Exporters\ExcelExporter; 
use Maatwebsite\Excel\Concerns\WithMapping;

class SeminarCustomerExporter extends ExcelExporter implements WithMapping
{
    protected $fileName = 'SeminarCustomer.xlsx';

    protected $columns = [
        'id'=>'id',
        'seminar_id'=>'seminar_id',
        'seminar.name'=>'seminar.name',
        'customer_id'=>'customer_id',
        'customer.name'=>'customer.name',
        'customer.phone_number'=>'customer.phone_number',
        'customer.company_name'=>'customer.company_name',
        'created_at'=>'created_at',
        'status'=>'status',
    ];

    public function map($seminarCustomer) : array
    {
        return [
            $seminarCustomer->id,
            $seminarCustomer->seminar_id,
            data_get($seminarCustomer, 'seminar.name'),
            $seminarCustomer->customer_id,
            data_get($seminarCustomer, 'customer.name'),
            data_get($seminarCustomer, 'customer.phone_number'),
            data_get($seminarCustomer, 'customer.company_name'),
            $seminarCustomer->created_at,
            $seminarCustomer->status,
        ];
    }
}