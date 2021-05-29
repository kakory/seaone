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
Route::get('seminar', 'Api\EnrollController@getSeminar');
Route::get('banner', 'Api\EnrollController@getBanner');
//获取客户个人信息-手机号
Route::get('customerInfo', 'Api\EnrollController@getCustomerInfo');
Route::get('customerPrivilege', 'Api\EnrollController@getCustomerPrivilege');
//获取客户报课记录-手机号
Route::get('enrollHistory', 'Api\EnrollController@getEnrollHistory');
//新增报课记录
Route::post('createEnroll', 'Api\EnrollController@createEnroll');
//取消报课记录
Route::post('deleteEnroll', 'Api\EnrollController@deleteEnroll');
//签到-手机号-课程id    404:未注册，1:成功，2:未报课，3：重复签到
Route::get('dailySeminar', 'Api\EnrollController@getDailySeminar');
Route::post('signIn', 'Api\EnrollController@signIn');

Route::get('basicAndAdvance', 'Api\EnrollController@checkBasicAndAdvance');
Route::get('start', 'Api\EnrollController@start');

//小程序-获取openid
Route::get('wxlogin', 'Api\MiniProgramController@wxlogin');
Route::get('phoneNumber', 'Api\MiniProgramController@getPhoneNumber');

//消息推送
// Route::get('subscribeMessage', 'Api\MiniProgramController@checkSignature');
// Route::post('subscribeMessage', 'Api\MiniProgramController@subscribeMessage');

//上传头像
//Route::post('photo', 'Api\MiniProgramController@wxlogin');
