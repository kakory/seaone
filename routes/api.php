<?php

use Illuminate\Http\Request;
use App\Http\Resources\KlasseResource;
use App\Models\Customer;
use App\Models\klasse;
use App\Models\Enroll;

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
Route::get('/Class', function () {
    return KlasseResource::collection(Klasse::all())->where('is_online','=','1');
});

//获取客户个人信息-手机号
Route::post('/getCustomerInfo', function (Request $request) {
    return Customer::where('phone_number',$request['phone'])->first();
});

//获取客户报课记录-手机号
Route::post('/getEnrollHistory', function (Request $request) {
    return Customer::where('phone_number',$request['phone'])->first()->classes;
});

//新增报课记录
Route::post('/createEnroll', function (Request $request) {
    $kid = $request['kid'];
    $cid = Customer::where('phone_number',$request['phone'])->first()->id;
    return Enroll::create(['klasse_id' => $kid, 'customer_id' => $cid, 'status' => 0]);
});

//取消报课记录
Route::post('/deleteEnroll', function (Request $request) {
    
});

/*签到-手机号-课程id
  TODO: status 为 已审核，时间为start_time当天
 */
Route::post('/signIn', function (Request $request) {
    $kid = $request['kid'];
    $cid = Customer::where('phone_number',$request['phone'])->first()->id;
    return DB::update('update enroll set status = ? where klasse_id = ? AND customer_id = ?',[2,$kid,$cid]);
});