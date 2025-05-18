<?php

namespace Packages\expense\app\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use Packages\expense\app\Models\RequisitionHeader;
use Packages\expense\app\Models\LookupV;

class RequisitionController extends Controller
{
    public function getRequisition(Request $request)
    {
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        $reqNumber = RequisitionHeader::selectRaw('distinct req_number')
                        ->when($keyword, function ($query, $keyword) {
                            return $query->where(function($r) use ($keyword) {
                                $r->whereRaw('UPPER(req_number) like ?', ['%'.strtoupper($keyword).'%']);
                            });
                        })
                        ->orderBy('req_number')
                        ->get();

        return response()->json(['data' => $reqNumber]);
    }

    public function getDocumentCategory(Request $request)
    {
        $budgetSource = $request->budget_source;
        $default = [];
        // if (auth()->user()->org_id == 82) {
            $default = LookupV::selectRaw('distinct lookup_code, tag')
                            ->where('lookup_type', 'OAG_AP_SOURCE_CATEGORY')
                            ->where('lookup_code', $budgetSource)
                            ->first();
        // }

        return response()->json(['data' => $default]);
    }
}
