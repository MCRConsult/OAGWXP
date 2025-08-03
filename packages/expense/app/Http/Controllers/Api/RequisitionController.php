<?php

namespace Packages\expense\app\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;

use Packages\expense\app\Models\RequisitionHeader;
use Packages\expense\app\Models\InvoiceHeader;
use Packages\expense\app\Models\LookupV;
use Packages\expense\app\Models\POExpenseAccountRuleV;
use Packages\expense\app\Models\DocumentCategory;
use Packages\expense\app\Models\GLPeriod;

class RequisitionController extends Controller
{
    public function getRequisition(Request $request)
    {
        $user = auth()->user();
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        $reqNumber = RequisitionHeader::selectRaw('distinct req_number')
                        ->where('requester', $user->id)
                        ->when($keyword, function ($query, $keyword) {
                            return $query->where(function($r) use ($keyword) {
                                $r->whereRaw('UPPER(req_number) like ?', ['%'.strtoupper($keyword).'%']);
                            });
                        })
                        ->orderBy('req_number')
                        ->limit(50)
                        ->get();

        return response()->json(['data' => $reqNumber]);
    }

    public function getInvoice(Request $request)
    {
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        $invNumber = InvoiceHeader::selectRaw('distinct invoice_number')
                        ->whereHas('requisition')
                        ->when($keyword, function ($query, $keyword) {
                            return $query->where(function($r) use ($keyword) {
                                $r->whereRaw('UPPER(invoice_number) like ?', ['%'.strtoupper($keyword).'%']);
                            });
                        })
                        ->orderBy('invoice_number')
                        ->limit(50)
                        ->get();

        return response()->json(['data' => $invNumber]);
    }

    public function getDocumentCategory(Request $request)
    {
        $user = auth()->user();
        $orgName = explode('_', $user->organizationV->name);
        $budgetSource = $request->budget_source;
        $default = [];
        if ($orgName[0] == '000') {
            $default = LookupV::selectRaw('distinct lookup_code, tag')
                            ->where('lookup_type', 'OAG_AP_SOURCE_CATEGORY')
                            ->where('lookup_code', $budgetSource)
                            ->where('tag', 'like', '%'.$orgName[0].'%ขบ.%')
                            ->first();
        }else{
            $default = DocumentCategory::selectRaw('distinct doc_category_code tag')
                            ->whereNotNull('attribute1')
                            ->where('doc_category_code', 'like', '%'.$orgName[0].'%ขบ.%')
                            ->first();
        }

        return response()->json(['data' => $default]);
    }

    public function fetchRequisitionRenderPage()
    {
        $requisitions = RequisitionHeader::search(request()->all())
                                    ->with(['user.hrEmployee', 'invoiceType', 'invoice', 'clear', 'invoiceStatus'])
                                    ->byRelatedUser()
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
