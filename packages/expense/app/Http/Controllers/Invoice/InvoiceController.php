<?php

namespace Packages\expense\app\Http\Controllers\Invoice;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;

use Packages\expense\app\Models\RequisitionHeader;
use Packages\expense\app\Models\RequisitionLine;
use Packages\expense\app\Models\InvoiceType;
use Packages\expense\app\Models\DocumentCategory;
use Packages\expense\app\Models\Supplier;
use Packages\expense\app\Models\SupplierBank;
use Packages\expense\app\Models\PaymentMethod;
use Packages\expense\app\Models\Currency;
use Packages\expense\app\Models\FlaxValueV;

class InvoiceController extends Controller
{
    public function index()
    {
        dd('invoice');
        // return view('e-expenses.invoice.index', [
        //     'invoices'      => $invoices,
        // ]);
    }

    public function create()
    {
        $invoiceTypes = InvoiceType::whereIn('lookup_code', ['STANDARD', 'PREPAYMENT'])->get();
        return view('expense::invoice.create', compact('invoiceTypes'));
    }

    public function groupInvoice(Request $request)
    {
        $requisitions = $request->requisitions;
        $mergeReqs = [];
        $invMapping = [];
        foreach ($requisitions as $key => $req) {
            $requistion = RequisitionHeader::with(['user', 'invoiceType', 'lines'])
                            ->where('req_number', $req)
                            ->get();
            if (count($requistion) <= 0) {
                $invMapping = MappingAutoInvoiceV::with(['invoiceType'])
                                ->where('invoice_num', $req)
                                ->get();
            }
            $mergeReqs = collect($requistion)->merge($invMapping)->all();
        }
        dd($mergeReqs);
        // CREATE NEW INVOICE
        







    }

}
