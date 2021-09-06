<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('courses', CourseController::class);
    $router->resource('customers', CustomerController::class);
    $router->resource('privileges', PrivilegeController::class);
    $router->resource('privilege-customers', PrivilegeCustomerController::class);
    $router->resource('seminars', SeminarController::class);
    $router->resource('seminar-customers', SeminarCustomerController::class);
    $router->resource('banners', BannerController::class);
    $router->resource('advisers', AdviserController::class);
    
    $router->resource('orders', OrderController::class);
    $router->resource('attachments', AttachmentController::class);

});
