<?php

namespace Packages\expense\app\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use Packages\expense\app\Models\RequisitionHeader;
use Packages\expense\app\Models\InvoiceHeader;
use Packages\expense\app\Models\MappingAutoInvoiceV;
use Illuminate\Pagination\LengthAwarePaginator;

class InvoiceController extends Controller
{
    public function getRequisition(Request $request)
    {
        // REQUISITION + INVOICE MAPPING
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        if ($request->keyword == null) {
            $requistion = RequisitionHeader::selectRaw('distinct req_number trans_number')
                        ->whereIn('status', ['PENDING', 'COMPLETED'])
                        ->whereNull('invoice_reference_id')
                        ->when($keyword, function ($query, $keyword) {
                            return $query->where(function($r) use ($keyword) {
                                $r->whereRaw('UPPER(req_number) like ?', ['%'.strtoupper($keyword).'%']);
                            });
                        })
                        ->orderBy('req_number')
                        ->limit(25)
                        ->get();
        }else{
            $requistion = RequisitionHeader::selectRaw('distinct req_number trans_number')
                            ->whereIn('status', ['PENDING', 'COMPLETED'])
                            ->whereNull('invoice_reference_id')
                            ->when($keyword, function ($query, $keyword) {
                                return $query->where(function($r) use ($keyword) {
                                    $r->whereRaw('UPPER(req_number) like ?', ['%'.strtoupper($keyword).'%']);
                                });
                            })
                            ->orderBy('req_number')
                            ->limit(25)
                            ->get();
            // if (count($requistion) <= 0) {
            //     $requistion = MappingAutoInvoiceV::selectRaw('distinct invoice_num trans_number')
            //                 ->when($keyword, function ($query, $keyword) {
            //                     return $query->where(function($r) use ($keyword) {
            //                         $r->whereRaw('UPPER(invoice_num) like ?', ['%'.strtoupper($keyword).'%']);
            //                     });
            //                 })
            //                 ->orderBy('invoice_num')
            //                 ->limit(25)
            //                 ->get();
            // }
        }

        return response()->json(['data' => $requistion]);
    }

    public function getVoucher(Request $request)
    {
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        $vouchers = InvoiceHeader::selectRaw('distinct voucher_number')
                    ->when($keyword, function ($query, $keyword) {
                        return $query->where(function($r) use ($keyword) {
                            $r->whereRaw('UPPER(voucher_number) like ?', ['%'.strtoupper($keyword).'%']);
                        });
                    })
                    ->orderBy('voucher_number')
                    ->limit(25)
                    ->get();

        return response()->json(['data' => $vouchers]);
    }

    public function fetchRequisition(Request $request)
    {
        $invMapping = [];
        $requistion = RequisitionHeader::search($request->search)
                            ->with(['user', 'invoiceType', 'supplier'])
                            ->whereIn('status', ['PENDING', 'COMPLETED'])
                            ->whereNull('invoice_reference_id')
                            ->orderBy('req_number')
                            ->get();
        // $invMapping = MappingAutoInvoiceV::selectRaw('distinct invoice_type_lookup_code, invoice_num, sum(remaining_amount) remaining_amount')
        //                     ->with(['invoiceType'])
        //                     ->orderBy('invoice_num')
        //                     ->groupBy('invoice_type_lookup_code', 'invoice_num')
        //                     ->get();

        $mergeReqs = collect($requistion)->merge($invMapping)->all();
        $perPage = 15;
        // Create a LengthAwarePaginator instance
        $header = new LengthAwarePaginator(
            array_slice($mergeReqs, (1 - 1) * $perPage, $perPage), // Items for the current page
            count($mergeReqs), // Total items
            $perPage, // Items per page
            1, // Current page
            ['path' => request()->url(), 'query' => request()->query()] // Path and query string
        );
        // dd($header);
        $data = [
            'headers' => $header
        ];
        return response()->json($data);
    }

    public function fetchRequisitionRenderPage(Request $request)
    {
        $invMapping =[];
        $requistion = RequisitionHeader::search($request->search)
                            ->with(['user', 'invoiceType'])
                            ->whereIn('status', ['PENDING', 'COMPLETED'])
                            ->whereNull('invoice_reference_id')
                            ->orderBy('req_number')
                            ->get();
        // $invMapping = MappingAutoInvoiceV::selectRaw('distinct invoice_type_lookup_code, invoice_num, sum(remaining_amount) remaining_amount')
        //                     ->with(['invoiceType'])
        //                     ->orderBy('invoice_num')
        //                     ->groupBy('invoice_type_lookup_code', 'invoice_num')
        //                     ->get();

        $mergeReqs = collect($requistion)->merge($invMapping)->all();
        $perPage = 15;
        $currPage = $request->page;
        // Create a LengthAwarePaginator instance
        $header = new LengthAwarePaginator(
            array_slice($mergeReqs, ($currPage - 1) * $perPage, $perPage),
            count($mergeReqs), 
            $perPage,
            $currPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $data = [
            'headers' => $header
        ];
        return response()->json($data);
    }
}
