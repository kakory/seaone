<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\SeminarResource;
use App\Models\Course;
use App\Models\Customer;
use App\Models\Seminar;
use App\Models\SeminarCustomer;
use App\Models\Privilege;
use App\Models\PrivilegeCustomer;
use App\Models\Banner;

class EnrollController extends Controller
{
    public function getSeminar()
    {
        //已上线&未截止（服务端） 有名额（客户端）
        return SeminarResource::collection(Seminar::where('is_online', 1)->
            where('closing_date_at', '>', date('Y-m-d'))->orderBy('start_date_at', 'asc')->get());
    }

    public function getBanner()
    {
        return Banner::where('is_show', 1)->get();
    }

    public function getCustomer(Request $request)
    {
        $customer = Customer::where('unique_id',$request['uid'])->firstOrFail();
        $privilege = PrivilegeCustomer::where('customer_id',$customer->id)->where('privilege_id',1)->first();
        return [
            'name'=>$customer->name,
            'company_name'=>$customer->company_name,
            'photo'=>$customer->photo,
            'limit'=>$privilege ? $privilege->limit : '',
        ];
    }

    public function getEnrollHistory(Request $request)
    {
        return Customer::where('unique_id',$request['uid'])->firstOrFail()->enroll;
    }

    public function createEnroll(Request $request)
    {
        $seminarId = $request['sid'];
        $privilegeId = Course::where('id', $request['cid'])->first()->privilege_id;
        $customerId = Customer::where('unique_id',$request['uid'])->firstOrFail()->id;
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
        $privilegeType = Privilege::where('id', $privilegeId)->first()->type;
        if(!$contract || ($contract->limit < date('Y-m-d') && $privilegeType == 0)){
            abort(403, '您的会员已过期，请联系客服');
        }
        
        return SeminarCustomer::Create(['seminar_id' => $seminarId, 'customer_id' => $customerId, 'status' => 1]);
    }

    public function deleteEnroll(Request $request)
    {
        $sid = $request['sid'];
        $cid = Customer::where('unique_id',$request['uid'])->firstOrFail()->id;
        return SeminarCustomer::where('seminar_id', $sid)->where('customer_id', $cid)->delete();
    }

    public function getDailySeminar(Request $request)
    {
        return Seminar::where('start_date_at', '<=', date('Y-m-d'))->where('end_date_at', '>=', date('Y-m-d'))->get()->map(function ($seminar) {
            return $seminar->only(['id', 'name', 'course_id', 'start_date_at']);
        });
    }

    public function signIn(Request $request)
    {
        $sid = $request['sid'];
        $cid = Customer::where('unique_id',$request['uid'])->firstOrFail()->id;
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
        $cid = Customer::where('unique_id',$request['uid'])->firstOrFail()->id;
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

    // public function start(Request $request)
    // {
    //     $salt = 'grrr4u5h1r0';
    //     $customers = Customer::all();
    //     foreach ($customers as $customer) {
    //         $customer->update(['unique_id' => sha1($customer->phone_number . $salt)]);
    //     }
    //     return 'done';
    // }
}
