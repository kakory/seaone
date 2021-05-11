<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\SeminarResource;
use App\Models\Customer;
use App\Models\Seminar;
use App\Models\SeminarCustomer;
use App\Models\PrivilegeCustomer;
use App\Models\Banner;

class EnrollController extends Controller
{
    //已上线&未截止（服务端） 有名额（客户端）
    public function getSeminar()
    {
        return SeminarResource::collection(Seminar::where('is_online', 1)->
            where('closing_date_at', '>', date('Y-m-d'))->orderBy('start_date_at', 'asc')->get());
    }

    public function getBanner()
    {
        return Banner::all();
    }

    public function getCustomerInfo(Request $request)
    {
        return Customer::where('phone_number',$request['phone'])->firstOrFail();
    }

    public function getCustomerPrivilege(Request $request)
    {
        $cid = Customer::where('phone_number',$request['phone'])->firstOrFail()->id;
        return PrivilegeCustomer::where('customer_id',$cid)->get();
    }

    public function getEnrollHistory(Request $request)
    {
        return Customer::where('phone_number',$request['phone'])->firstOrFail()->enroll;
    }

    public function createEnroll(Request $request)
    {
        $sid = $request['sid'];
        $cid = Customer::where('phone_number',$request['phone'])->firstOrFail()->id;
        $res = SeminarCustomer::where('seminar_id',$sid)->where('customer_id',$cid)->first();
        if($res){
            return 1;
        }
        return SeminarCustomer::Create(['seminar_id' => $sid, 'customer_id' => $cid, 'status' => 0]);
    }

    public function deleteEnroll(Request $request)
    {
        $sid = $request['sid'];
        $cid = Customer::where('phone_number',$request['phone'])->firstOrFail()->id;
        return SeminarCustomer::where('seminar_id', $sid)->where('customer_id', $cid)->delete();
    }

    public function getTodaySeminar(Request $request)
    {
        return Seminar::where('start_date_at', date('Y-m-d'))->get()->map(function ($customer) {
            return $customer->only(['id', 'name']);
        });
    }

    public function signIn(Request $request)
    {
        $sid = $request['sid'];
        $cid = Customer::where('phone_number',$request['phone'])->firstOrFail()->id;
        $res = SeminarCustomer::where('seminar_id', $sid)->where('customer_id', $cid);
        if(!$res->first()){
            return 2;
        }
        if($res->first()->status == 2){
            return 3;
        }
        return $res->update(['status' => 2]);
    }
}
