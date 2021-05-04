<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//获取班级信息
Route::get('getSeminar', 'Api\EnrollController@getSeminar');
Route::get('getBanner', 'Api\EnrollController@getBanner');
//获取客户个人信息-手机号
Route::post('getCustomerInfo', 'Api\EnrollController@getCustomerInfo');
//获取客户报课记录-手机号
Route::post('getEnrollHistory', 'Api\EnrollController@getEnrollHistory');
//新增报课记录
Route::post('createEnroll', 'Api\EnrollController@createEnroll');
//取消报课记录
Route::post('deleteEnroll', 'Api\EnrollController@deleteEnroll');
//签到-手机号-课程id
Route::post('signIn', 'Api\EnrollController@signIn');
