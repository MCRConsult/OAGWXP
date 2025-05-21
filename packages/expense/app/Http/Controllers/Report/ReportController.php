<?php

namespace Packages\expense\app\Http\Controllers\Report;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;

use Packages\expense\app\Models\RequisitionHeader;
use Packages\expense\app\Models\RequisitionLine;
use Packages\expense\app\Models\InvoiceHeader;
use Packages\expense\app\Models\InvoiceLine;
use Packages\expense\app\Models\InvoiceType;
use Packages\expense\app\Models\DocumentCategory;
use Packages\expense\app\Models\Supplier;
use Packages\expense\app\Models\SupplierBank;
use Packages\expense\app\Models\PaymentMethod;
use Packages\expense\app\Models\Currency;
use Packages\expense\app\Models\FlaxValueV;
use Packages\expense\app\Models\COAListV;

class ReportController extends Controller
{
    public function index()
    {
        dd('dddd');
        $invoices = InvoiceHeader::search(request()->all())
                                    ->orderBy('voucher_number', 'desc')
                                    ->paginate(15);
        $invoiceTypes = InvoiceType::whereIn('lookup_code', ['STANDARD', 'PREPAYMENT'])->get();
        $statuses = ['NEW'          => 'รอเบิกจ่าย'
                    , 'INTERFACED'  => 'เบิกจ่ายแล้ว'
                    , 'CANCELLED'   => 'ยกเลิก'];

        return view('expense::invoice.index', compact('invoices', 'invoiceTypes', 'statuses'));
    }
}
