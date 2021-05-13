<?php

namespace App\Admin\Extensions;

use Encore\Admin\Grid\Exporters\ExcelExporter; 

class CustomerExporter extends ExcelExporter
{
    protected $fileName = 'Customers.xlsx';

    protected $headings = ['id', 'name', 'phone_number', 'company_name', 'photo', 'remark', 'created_at', 'updated_at'];
}