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
        'customer_id'=>'customer_id',
        'customer_id'=>'customer_id',
        'customer_id'=>'customer_id',
        'customer_id'=>'customer_id',
    ];

    public function map($seminarCustomer) : array
    {
        return [
            $seminarCustomer->id,
            data_get($seminarCustomer, 'seminar.name'),
            data_get($seminarCustomer, 'customer.name'),
            data_get($seminarCustomer, 'customer.phone_number'),
            data_get($seminarCustomer, 'customer.company_name'),
            data_get($seminarCustomer, 'customer.adviser.name'),
        ];
    }
}