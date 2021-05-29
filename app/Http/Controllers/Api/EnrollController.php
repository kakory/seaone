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
        $seminarId = $request['sid'];
        $privilegeId = Course::where('id', $request['cid'])->first()->privilege_id;
        $customerId = Customer::where('phone_number',$request['phone'])->firstOrFail()->id;
        $group = $request['group'];

        if(SeminarCustomer::where('seminar_id',$seminarId)->where('customer_id',$customerId)->first()){
            abort(403, '您已报该课程');
        }
        
        $seminars = Seminar::where('group', $group)->get();
        foreach ($seminars as $seminar) {
            if(SeminarCustomer::where('seminar_id',$seminar->id)->where('customer_id',$customerId)->first()){
                abort(403, '您已报本月同类型课程');
            }
        }

        $contract = PrivilegeCustomer::where('privilege_id', $privilegeId)->where('customer_id',$customerId)->first();
        if(!$contract || ($contract->limit < date('Y-m-d') && $privilegeId == 1)){
            abort(403, '您的合同已过期，请与客服联系');
        }
        
        return SeminarCustomer::Create(['seminar_id' => $seminarId, 'customer_id' => $customerId, 'status' => 1]);
    }

    public function deleteEnroll(Request $request)
    {
        $sid = $request['sid'];
        $cid = Customer::where('phone_number',$request['phone'])->firstOrFail()->id;
        return SeminarCustomer::where('seminar_id', $sid)->where('customer_id', $cid)->delete();
    }

    public function getDailySeminar(Request $request)
    {
        return Seminar::where('start_date_at', date('Y-m-d'))->get()->map(function ($seminar) {
            return $seminar->only(['id', 'name', 'course_id', 'start_date_at']);
        });
    }

    public function signIn(Request $request)
    {
        $sid = $request['sid'];
        $cid = Customer::where('phone_number',$request['phone'])->firstOrFail()->id;
        $res = SeminarCustomer::where('seminar_id', $sid)->where('customer_id', $cid);
        if(!$res->first()){
            abort(403, '您还未报该课程');
        }
        if($res->first()->status == 2){
            abort(200, '重复签到');
        }
        return $res->update(['status' => 2]);
    }

    public function checkBasicAndAdvance(Request $request)
    {
        $tags = [0,0];
        $cid = Customer::where('phone_number',$request['phone'])->firstOrFail()->id;
        $enrolls = SeminarCustomer::where('customer_id', $cid)->get();
        foreach ($enrolls as $enroll) {
            $id = Seminar::where('id',$enroll->seminar_id)->first()->course_id;
            if($id == 1){
                $tags[0] = $tags[0] + 1;
            }else if($id == 2){
                $tags[1] = $tags[1] + 1;

            }
        }
        return $tags;
    }
}
