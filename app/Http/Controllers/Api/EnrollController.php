<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\SeminarResource;
use App\Http\Resources\BannerResource;
use App\Models\Customer;
use App\Models\Seminar;
use App\Models\SeminarCustomer;
use App\Models\Banner;

class EnrollController extends Controller
{
    public function getSeminar()
    {
        return SeminarResource::collection(Seminar::all())->where('is_online', '1');
    }

    public function getBanner()
    {
        return BannerResource::collection(Banner::all());
    }

    public function getCustomerInfo(Request $request)
    {
        return Customer::where('phone_number',$request['phone'])->first();
    }

    public function getEnrollHistory(Request $request)
    {
        return Customer::where('phone_number',$request['phone'])->first()->enroll;
    }

    public function createEnroll(Request $request)
    {
        $sid = $request['sid'];
        $cid = Customer::where('phone_number',$request['phone'])->first()->id;
        return SeminarCustomer::create(['seminar_id' => $sid, 'customer_id' => $cid, 'status' => 0]);
    }

    public function deleteEnroll(Request $request)
    {
        $sid = $request['sid'];
        $cid = Customer::where('phone_number',$request['phone'])->first()->id;
        return SeminarCustomer::where('seminar_id', $sid)->where('customer_id', $cid)->delete();
    }

    public function signIn(Request $request)
    {
        $sid = $request['sid'];
        $cid = Customer::where('phone_number',$request['phone'])->first()->id;
        return SeminarCustomer::where('seminar_id', $sid)->where('customer_id', $cid)->update(['status' => 2]);
    }
}
