<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\SeminarResource;
use App\Models\Course;
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
        $seminar = $request['sid'];
        $customer = Customer::where('phone_number',$request['phone'])->firstOrFail()->id;
        $group = 0;
        if(!$group = $request['group']){
            $group = Seminar::where('id',$seminar)->first()->group;
        }

        //重复报课
        if(SeminarCustomer::where('seminar_id',$seminar)->where('customer_id',$customer)->first()){
            return 1;
        }
        
        //本月报了相同group的课
        $months = Seminar::where('group', $group)->get();
        foreach ($months as $month) {
            if(SeminarCustomer::where('seminar_id',$month->id)->where('customer_id',$customer)->first()){
                return 2;
            }
        }

        //合约过期or无合约
        $privilege = Course::where('id', $request['cid'])->first()->privilege_id;
        $contract = PrivilegeCustomer::where('privilege_id', $privilege)->where('customer_id',$customer)->first();
        if(!$contract){
            return 3;
        }
        if($contract->limit < date('Y-m-d')){
            return 3;
        }
        
        return SeminarCustomer::Create(['seminar_id' => $seminar, 'customer_id' => $customer, 'status' => 1]);
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
