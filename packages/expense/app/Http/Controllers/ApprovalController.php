<?php

namespace Packages\expense\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Packages\inventory\app\Models\User;

use Packages\Util;

class ApprovalController extends Controller
{

    // public function index(Request $request)
    // {
    //     $decrypt = \Crypt::decryptString($request->key);
    //     $decrypt = json_decode($decrypt);
    //     $request->merge((array) $decrypt ?? []);
    //     $errorMessage = false;
    //     try {
    //         $parentId = $request->id;
    //         $requestType = $request->request_type;
    //         $userId = $request->user_id;
    //         $docNo = '';

    //         if($requestType == 'PREPARE-PLAN'){
    //             $parent = \Packages\inventory\app\Models\Plan\PtwinvPlanLine::find($parentId);
    //             $controller = '\Packages\inventory\app\Http\Controllers\PreparePlanController';
    //         }


    //         (new $controller)->setStatus($request, $parentId);

    //     } catch (\Exception $e) {
    //         \Log::error($e->getMessage());
    //         \Log::error($e);

    //         $errorMessage = "403 : Forbidden (". $e->getMessage() . ")";
    //     }

    //     $title = "$requestType #";
    //     if ($errorMessage) {
    //         $description = $errorMessage;
    //     } else {
    //         $user = User::find($userId);
    //         $activity = $request->activity;
    //         // $lastLog = $parent->activityLogs()->where('user_id', $userId)->orderBy('id')->first();
    //         // $lastLog = $parent->activityLogs()->orderBy('id')->first();

    //         // $activityTitle = optional($lastLog)->title;
    //         $description = "<strong>$user->name</strong> : <span> $activity </span>";
    //     }
    //     return view('inventory::api.messages', compact('title', 'description', 'errorMessage'));
    // }
}
