<?php

namespace Packages\expense\app\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;

use Packages\expense\app\Models\RequisitionHeader;
use Packages\expense\app\Models\InvoiceHeader;
use Packages\expense\app\Models\MappingAutoInvoiceV;
use Packages\expense\app\Models\InvoiceInterfaceHeader;

class InvoiceController extends Controller
{
    public function getRequisition(Request $request)
    {
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        if ($request->sourceType == 'REQUISITION') {
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
            $requistion = MappingAutoInvoiceV::selectRaw('distinct req_number trans_number')
                            ->doesntHave('invoiceLine')
                            ->when($keyword, function ($query, $keyword) {
                                return $query->where(function($r) use ($keyword) {
                                    $r->whereRaw('UPPER(req_number) like ?', ['%'.strtoupper($keyword).'%']);
                                });
                            })
                            ->orderBy('req_number')
                            ->limit(25)
                            ->get();
        }

        return response()->json(['data' => $requistion]);
    }

    public function getInvoice(Request $request)
    {
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        $vouchers = InvoiceHeader::selectRaw('distinct invoice_number')
                    ->when($keyword, function ($query, $keyword) {
                        return $query->where(function($r) use ($keyword) {
                            $r->whereRaw('UPPER(invoice_number) like ?', ['%'.strtoupper($keyword).'%']);
                        });
                    })
                    ->orderBy('invoice_number')
                    ->limit(25)
                    ->get();

        return response()->json(['data' => $vouchers]);
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

    public function fetchInvoiceRenderPage()
    {
       $invoices = InvoiceHeader::search(request()->all())
                            ->with(['user.hrEmployee', 'supplier'])
                            ->orderByRaw('invoice_date desc, voucher_number desc')
                            ->get();
        $perPage = 25;
        $currPage = (int)request()->page;
        $invoices = collect($invoices)->all();
        $respInvoices = new LengthAwarePaginator(
            array_slice($invoices, ($currPage - 1) * $perPage, $perPage),
            count($invoices), 
            $perPage,
            $currPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $data = [
            'invoices' => $respInvoices
        ];
        return response()->json($data);
    }

    public function fetchInterfaceRenderPage()
    {
        $interfaces = InvoiceInterfaceHeader::search(request())
                        ->orderByRaw('creation_date desc, invoice_num desc')
                        ->get();
        $perPage = 25;
        $currPage = (int)request()->page;
        $interfaces = collect($interfaces)->all();
        $respInterfaces = new LengthAwarePaginator(
            array_slice($interfaces, ($currPage - 1) * $perPage, $perPage),
            count($interfaces), 
            $perPage,
            $currPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $data = [
            'interfaces' => $respInterfaces
        ];
        return response()->json($data);
    }

    // GROUP REQUISITION ============================================
    public function fetchRequisition(Request $request)
    {
        $user = auth()->user();
        $requistion = [];
        $invMapping = [];
        if ($request->search['source_data'] == 'REQUISITION') {
            $requistion = RequisitionHeader::search($request->search)
                                ->with(['user', 'user.hrEmployee', 'invoiceType', 'supplier'])
                                ->where('org_id', $user->org_id)
                                ->where('payment_type', 'PAYMENT')
                                ->whereIn('status', ['PENDING', 'COMPLETED'])
                                ->whereNull('invoice_reference_id')
                                ->orderBy('req_number')
                                ->get();
        }else{
            $invMapping = MappingAutoInvoiceV::selectRaw('distinct supplier_id, invoice_type, req_number, sum(amount) total_amount')
                                ->search($request->search)
                                ->where('org_id', $user->org_id)
                                ->doesntHave('invoiceLine')
                                ->with(['invoiceType', 'supplier'])
                                ->groupBy('invoice_type', 'req_number', 'supplier_id')
                                ->orderBy('req_number')
                                ->get();
        }
        $mergeReqs = collect($requistion)->merge($invMapping)->all();
        $perPage = 25;
        $header = new LengthAwarePaginator(
            array_slice($mergeReqs, (1 - 1) * $perPage, $perPage), // Items for the current page
            count($mergeReqs), // Total items
            $perPage, // Items per page
            1, // Current page
            ['path' => request()->url(), 'query' => request()->query()] // Path and query string
        );

        $data = [
            'headers' => $header
        ];
        return response()->json($data);
    }

    public function fetchRequisitionRenderPage(Request $request)
    {
        $requistion = [];
        $invMapping = [];
        if ($request->search['source_data'] == 'REQUISITION') {
            $requistion = RequisitionHeader::search($request->search)
                                ->with(['user', 'user.hrEmployee', 'invoiceType', 'supplier'])
                                ->where('org_id', $user->org_id)
                                ->whereIn('status', ['PENDING', 'COMPLETED'])
                                ->whereNull('invoice_reference_id')
                                ->orderBy('req_number')
                                ->get();
        }else{
           $invMapping = MappingAutoInvoiceV::selectRaw('distinct supplier_id, invoice_type, req_number, sum(amount) total_amount')
                                ->where('org_id', $user->org_id)
                                ->doesntHave('invoiceLine')
                                ->with(['invoiceType', 'supplier'])
                                ->groupBy('invoice_type', 'req_number', 'supplier_id')
                                ->orderBy('req_number')
                                ->get();
        }

        $mergeReqs = collect($requistion)->merge($invMapping)->all();
        $perPage = 25;
        $currPage = $request->page;
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
