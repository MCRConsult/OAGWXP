<?php

namespace Packages\expense\app\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;

use Packages\expense\app\Models\RequisitionHeader;
use Packages\expense\app\Models\LookupV;
use Packages\expense\app\Models\POExpenseAccountRuleV;
use Packages\expense\app\Models\GLPeriod;

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

    public function fetchRequisitionRenderPage()
    {
        $requisitions = RequisitionHeader::search(request()->all())
                                    ->with(['user.hrEmployee', 'invoiceType'])
                                    ->whereNotNull('req_number')
                                    ->orderBy('req_number', 'desc')
                                    ->get();
        $perPage = 25;
        $currPage = (int)request()->page;
        $requisitions = collect($requisitions)->all();
        $respReq = new LengthAwarePaginator(
            array_slice($requisitions, ($currPage - 1) * $perPage, $perPage),
            count($requisitions), 
            $perPage,
            $currPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $data = [
            'requisitions' => $respReq
        ];
        return response()->json($data);
    }
}
