<?php

namespace Packages\expense\app\Http\Controllers\Invoice;

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

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = InvoiceHeader::search(request()->all())
                                    ->orderBy('invoice_date', 'desc')
                                    ->orderBy('voucher_number', 'desc')
                                    ->paginate(15);
        $invoiceTypes = InvoiceType::whereIn('lookup_code', ['STANDARD', 'PREPAYMENT'])->get();
        $statuses = ['NEW'          => 'รอเบิกจ่าย'
                    , 'INTERFACED'  => 'เบิกจ่ายแล้ว'
                    , 'CANCELLED'   => 'ยกเลิก'];

        return view('expense::invoice.index', compact('invoices', 'invoiceTypes', 'statuses'));
    }

    public function create()
    {
        $invoiceTypes = InvoiceType::whereIn('lookup_code', ['STANDARD', 'PREPAYMENT'])->get();
        return view('expense::invoice.create', compact('invoiceTypes'));
    }

    public function groupInvoice(Request $request)
    {
        $user = auth()->user();
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
            if ($key == 0) {
                $mergeReqs = collect($requistion)->merge($invMapping)->all();
            }else{
                $mergeReqs = collect($mergeReqs)->merge($requistion)->merge($invMapping)->all();
            }
        }
        // VALIDATE : เช็คเงื่อนไขให้เลือกรายการได้เฉพาะรายการที่มีประเภทและชื่อสั่งจ่ายเดียวกันเท่านั้น
        $suppliers = collect($mergeReqs)->pluck('supplier_id')->toArray();
        $invoiceTypes = collect($mergeReqs)->pluck('invoice_type')->toArray();
        if (count(array_unique($suppliers)) > 1) {
            $data = [
                'status' => 'ERROR',
                'message' => 'รายการที่เลือกมีชื่อสั่งจ่ายมากกว่า 1 ชื่อสั่งจ่าย กรุณาตรวจสอบ',
            ];
            return response()->json($data);
        }
        if (count(array_unique($invoiceTypes)) > 1) {
            $data = [
                'status' => 'ERROR',
                'message' => 'รายการที่เลือกมีประเภทเอกสารมากกว่า 1 ประเภท กรุณาตรวจสอบ',
            ];
            return response()->json($data);
        }

        \DB::beginTransaction();
        try{
            $header = collect($mergeReqs)->first();
            $prefixInvRef = (new InvoiceHeader)->getInvRef($header->invoice_type);
            // CREATE NEW INVOICE
            $headerTemp                             = new InvoiceHeader;
            $headerTemp->invoice_number             = (new InvoiceHeader)->genDocumentNo('101', $prefixInvRef);
            $headerTemp->org_id                     = 101;
            $headerTemp->invoice_date               = date('Y-m-d');
            $headerTemp->invoice_type               = $header->invoice_type;
            $headerTemp->document_category          = $header->document_category;
            $headerTemp->supplier_id                = $header->supplier_id;
            $headerTemp->supplier_name              = $header->supplier_name;
            // GET FROM SUPPLIER
            $headerTemp->payment_method             = $header->supplier->payment_method_lookup_code;
            $headerTemp->payment_term               = $header->supplier->terms_id;
            $headerTemp->currency                   = $header->supplier->payment_currency_code;
            $headerTemp->contact_date               = '';
            $headerTemp->final_judgment             = '';
            $headerTemp->gfmis_document_number      = '';
            $headerTemp->total_amount               = collect($mergeReqs)->sum('total_amount'); // SUM LINE
            $headerTemp->clear_date                 = '';
            $headerTemp->description                = '';
            $headerTemp->note                       = '';        
            $headerTemp->status                     = 'NEW';
            $headerTemp->requester                  = $user->id;
            $headerTemp->created_by                 = $user->id;
            $headerTemp->updated_by                 = $user->id;
            $headerTemp->creation_by                = $user->person_id;
            $headerTemp->updation_by                = $user->person_id;
            $headerTemp->save();

            foreach ($mergeReqs as $key => $requisition) {
                foreach ($requisition->lines as $key => $line) {
                    $lineTemp                           = new InvoiceLine;
                    $lineTemp->invoice_header_id        = $headerTemp->id;
                    $lineTemp->seq_number               = $key+1;
                    $lineTemp->supplier_id              = $line->supplier_id;
                    $lineTemp->supplier_name            = $line->supplier_name;
                    $lineTemp->bank_account_number      = $line->bank_account_number;
                    $lineTemp->budget_plan              = $line->budget_plan;
                    $lineTemp->budget_type              = $line->budget_type;
                    $lineTemp->expense_type             = $line->expense_type;
                    $lineTemp->expense_description      = $line->expense_description;
                    $lineTemp->expense_account          = $line->expense_account;
                    $lineTemp->amount                   = $line->amount;
                    $lineTemp->description              = $line->description;
                    $lineTemp->vehicle_number           = $line->vehicle_number;
                    $lineTemp->policy_number            = $line->policy_number;
                    $lineTemp->vehicle_oil_type         = $line->vehicle_oil_type;
                    $lineTemp->utility_type             = $line->utility_type;
                    $lineTemp->utility_detail           = $line->utility_detail;
                    $lineTemp->unit_quantity            = $line->unit_quantity;
                    $lineTemp->req_invoice_number       = $line->invoice_number;
                    $lineTemp->req_invoice_date         = $line->invoice_date? date('Y-m-d', strtotime($line->invoice_date)): '';
                    $lineTemp->req_receipt_number       = $line->receipt_number;
                    $lineTemp->req_receipt_date         = $line->receipt_date? date('Y-m-d', strtotime($line->receipt_date)): '';
                    $lineTemp->remaining_receipt_flag   = $line->remaining_receipt_flag;
                    $lineTemp->remaining_receipt_number = $line->remaining_receipt_number;
                    $lineTemp->save();
                }
                // UPDATE REQUISITION
                $requistion = RequisitionHeader::where('req_number', $requisition->req_number)
                                        ->update([
                                            'invoice_reference_id'  => $headerTemp->id
                                            , 'invioce_number_ref'  => $headerTemp->invoice_number
                                            , 'updated_by'          => $user->id
                                            , 'updation_by'         => $user->person_id
                                        ]);
            }
            \DB::commit();
            $data = [
                'status' => 'SUCCESS',
                'message' => '',
                'redirect_page' => route('expense.invoice.edit', $headerTemp->id)
            ];
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error($e);
            $data = [
                'status' => 'ERROR',
                'message' => $e->getMessage(),
            ];
        }
        return response()->json($data);
    }

    public function show($invoiceId)
    {
        $invoice = InvoiceHeader::findOrFail($invoiceId);

        return view('expense::invoice.show', compact('invoice',));
    }

    public function edit($invoiceId)
    {
        $invoice = InvoiceHeader::where('id', $invoiceId)
                            ->with(['lines', 'lines.expense', 'user', 'user.hrEmployee'])
                            ->first();
        $invoiceTypes = InvoiceType::whereIn('lookup_code', ['STANDARD', 'PREPAYMENT'])->get();
        $defaultSetName = (new COAListV)->getDefaultSetName();

        return view('expense::invoice.edit', compact('invoice', 'invoiceTypes', 'defaultSetName'));
    }

    public function update(Request $request, $invoiceId)
    {
        $user = auth()->user();
        $invioce = $request->header;
        $invioceLines = $request->lines;

        \DB::beginTransaction();
        try {
            InvoiceHeader::where('id', $invoiceId)
                    ->update([
                        'invoice_date'            => $invioce['invoice_date']? date('Y-m-d', strtotime($invioce['invoice_date'])): ''
                        , 'invoice_type'          => $invioce['invoice_type']
                        , 'document_category'     => $invioce['document_category']
                        , 'supplier_id'           => $invioce['supplier_id']
                        , 'supplier_name'         => $invioce['supplier_name']
                        , 'payment_method'        => $invioce['payment_method']
                        , 'payment_term'          => $invioce['payment_term']
                        , 'clear_date'            => $invioce['clear_date']? date('Y-m-d', strtotime($invioce['clear_date'])): ''
                        , 'currency'              => $invioce['currency']
                        , 'contact_date'          => $invioce['contact_date']? date('Y-m-d', strtotime($invioce['contact_date'])): ''
                        , 'final_judgment'        => $invioce['final_judgment']
                        , 'gfmis_document_number' => $invioce['gfmis_document_number']
                        , 'total_amount'          => $request->totalApply
                        , 'description'           => $invioce['description']
                        , 'note'                  => $invioce['note']
                        , 'updated_by'            => $user->id
                        , 'updation_by'           => $user->person_id
                    ]);

            InvoiceLine::where('invoice_header_id', $invoiceId)->delete();
            foreach ($invioceLines as $key => $line) {
                $lineTemp                           = new InvoiceLine;
                $lineTemp->invoice_header_id        = $invoiceId;
                $lineTemp->seq_number               = $key+1;
                $lineTemp->supplier_id              = $line['supplier_id'];
                $lineTemp->supplier_name            = $line['supplier_name'];
                $lineTemp->bank_account_number      = $line['bank_account_number'];
                $lineTemp->budget_plan              = $line['budget_plan'];
                $lineTemp->budget_type              = $line['budget_type'];
                $lineTemp->expense_type             = $line['expense_type'];
                $lineTemp->expense_description      = $line['expense_description'];
                $lineTemp->expense_account          = $line['expense_account'];
                $lineTemp->amount                   = $line['amount'];
                $lineTemp->description              = $line['description'];
                $lineTemp->vehicle_number           = $line['vehicle_number'];
                $lineTemp->policy_number            = $line['policy_number'];
                $lineTemp->vehicle_oil_type         = $line['vehicle_oil_type'];
                $lineTemp->utility_type             = $line['utility_type'];
                $lineTemp->utility_detail           = $line['utility_detail'];
                $lineTemp->unit_quantity            = $line['unit_quantity'];
                $lineTemp->req_invoice_number       = $line['req_invoice_number'];
                $lineTemp->req_invoice_date         = $line['req_invoice_date']? date('Y-m-d', strtotime($line['req_invoice_date'])): '';
                $lineTemp->req_receipt_number       = $line['req_receipt_number'];
                $lineTemp->req_receipt_date         = $line['req_receipt_date']? date('Y-m-d', strtotime($line['req_receipt_date'])): '';
                $lineTemp->remaining_receipt_flag   = $line['remaining_receipt_flag'] == true? 'Y': 'N';
                $lineTemp->remaining_receipt_number = $line['remaining_receipt_number'];
                $lineTemp->ar_receipt_id            = $line['ar_receipt_id'];
                $lineTemp->ar_receipt_number        = $line['ar_receipt_number'];
                $lineTemp->tax_code                 = $line['tax_code'];
                $lineTemp->tax_amount               = $line['tax_amount'];
                $lineTemp->wht_code                 = $line['wht_code'];
                $lineTemp->wht_amount               = $line['wht_amount'];
                $lineTemp->save();
            }

            \DB::commit();
            $data = [
                'status' => 'SUCCESS',
                'message' => ''
            ];
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error($e);
            $data = [
                'status' => 'ERROR',
                'message' => $e->getMessage(),
            ];
        }
        return response()->json($data);
    }

    public function cancel(Request $request, $invoiceId)
    {
        $user = auth()->user();
        $invioce = $request->header;
        $invioceLines = $request->lines;
        \DB::beginTransaction();
        try {
            $invoice = InvoiceHeader::where('id', $invoiceId)
                        ->update([
                            'status'        => 'CANCELLED'
                            , 'updated_by'  => $user->id
                            , 'updation_by' => $user->person_id
                        ]);

            // UPDATE REQUISITION
            $requistion = RequisitionHeader::where('invoice_reference_id', $invoiceId)
                                    ->update([
                                        'invoice_reference_id'  => null
                                        , 'invioce_number_ref'  => null
                                        , 'updated_by'          => $user->id
                                        , 'updation_by'         => $user->person_id
                                    ]);
            \DB::commit();
            $data = [
                'status' => 'SUCCESS',
                'message' => '',
                'redirect_page' => route('expense.invoice.index')
            ];
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error($e);
            $data = [
                'status' => 'ERROR',
                'message' => $e->getMessage(),
            ];
        }
        return response()->json($data);
    }
}
