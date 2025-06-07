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
use Packages\expense\app\Models\MappingAutoInvoiceV;
use Packages\expense\app\Models\MTLCategoriesV;
use Packages\expense\app\Models\InvoiceType;
use Packages\expense\app\Models\DocumentCategory;
use Packages\expense\app\Models\Supplier;
use Packages\expense\app\Models\SupplierBank;
use Packages\expense\app\Models\PaymentMethod;
use Packages\expense\app\Models\Currency;
use Packages\expense\app\Models\FlaxValueV;
use Packages\expense\app\Models\COAListV;
use Packages\expense\app\Models\LookupV;

use Packages\expense\app\Repositories\InvoiceInfRepo;

class InvoiceController extends Controller
{
    public function index()
    {
        dd($this->interface(341));
        $invoices = InvoiceHeader::search(request()->all())
                                    ->with(['user.hrEmployee', 'supplier'])
                                    ->orderByRaw('invoice_date desc, voucher_number desc')
                                    ->paginate(25);
        $invoiceTypes = InvoiceType::whereIn('lookup_code', ['STANDARD', 'PREPAYMENT'])->get();
        $statuses = ['NEW'          => 'ขอเบิก'
                    , 'INTERFACED'  => 'เบิกจ่ายแล้ว'
                    , 'CANCELLED'   => 'ยกเลิก'];

        return view('expense::invoice.index', compact('invoices', 'invoiceTypes', 'statuses'));
    }

    public function create()
    {
        $invoiceTypes = InvoiceType::whereIn('lookup_code', ['STANDARD', 'PREPAYMENT'])->get();
        return view('expense::invoice.create', compact('invoiceTypes'));
    }

    // UPDATE STATUS REQUISITION
    public function setStatus(Request $request, $reqId)
    {
        try {
            $user = auth()->user();
            $activity = $request->activity;
            $reason = $request->reason;
            $header = RequisitionHeader::findOrFail($reqId);
            switch ($activity) {
                case "HOLD_REQUISITION":
                    $header->status         = 'HOLD';
                    $header->hold_reason    = $reason;
                    $header->updated_by     = $user->id;
                    $header->updation_by    = $user->person_id;
                    $header->save();
                break;
                case "CANCEL_REQUISITION":
                    $header->status         = 'CANCELLED';
                    $header->cancel_reason  = $reason;
                    $header->updated_by     = $user->id;
                    $header->updation_by    = $user->person_id;
                    $header->save();
                break; 
            }
            \DB::commit();
            $data = [
                'status' => 'SUCCESS',
                'message' => '',
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
                                ->where('req_number', $req)
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
            $docCate = '';
            if ($header->source_type == 'RECEIPT') {
                $lookup = LookupV::where('lookup_type', 'OAG_AP_REVENUE_CATEGORY')
                        ->where('description', $header->document_category)
                        ->first();
                $docCate = optional($lookup)->tag;
            }
            // CREATE NEW INVOICE
            $headerTemp                                     = new InvoiceHeader;
            $headerTemp->invoice_number                     = (new InvoiceHeader)->genDocumentNo($user->org_id, $prefixInvRef);
            $headerTemp->org_id                             = $user->org_id;
            $headerTemp->source_type                        = $header->source_type;
            $headerTemp->invoice_date                       = date('Y-m-d');
            $headerTemp->invoice_type                       = $header->invoice_type;
            $headerTemp->document_category                  = $header->source_type == 'REQUISITION'? $header->document_category: $docCate;
            $headerTemp->supplier_id                        = $header->supplier_id;
            $headerTemp->supplier_name                      = $header->supplier_name;
            // GET FROM SUPPLIER
            $headerTemp->payment_method                     = $header->supplier->payment_method_code;
            $headerTemp->payment_term                       = $header->supplier->terms_id;
            $headerTemp->currency                           = $header->supplier->invoice_currency_code;
            $headerTemp->contact_date                       = '';
            $headerTemp->final_judgment                     = '';
            $headerTemp->gfmis_document_number              = '';
            $headerTemp->total_amount                       = $header->source_type == 'REQUISITION'
                                                                ? collect($mergeReqs)->sum('total_amount')
                                                                : collect($mergeReqs)->sum('amount'); // SUM LINE
            $headerTemp->clear_date                         = '';
            $headerTemp->description                        = '';
            $headerTemp->note                               = '';        
            $headerTemp->status                             = 'NEW';
            $headerTemp->requester                          = $user->id;
            $headerTemp->created_by                         = $user->id;
            $headerTemp->updated_by                         = $user->id;
            $headerTemp->creation_by                        = $user->person_id;
            $headerTemp->updation_by                        = $user->person_id;
            $headerTemp->save();

            foreach ($mergeReqs as $key => $requisition) {
                if ($requisition->source_type == 'RECEIPT') {
                    $expeseType = MTLCategoriesV::where('segment1', 'EXP')
                            ->where('category_concat_segs', $requisition->expense_category)
                            ->first();
                    $budgetType = MTLCategoriesV::where('segment1', 'EXP')
                            ->where('category_concat_segs', $expeseType->attribute4)
                            ->first();
                    $budgetPlan = MTLCategoriesV::where('segment1', 'EXP')
                            ->where('category_concat_segs', $budgetType->attribute3)
                            ->first();
                    // SET EXPENSE ACCOUNT
                    $expAccount = (new MappingAutoInvoiceV)->mappingExpenseAccount($header, $requisition->expense_category);        
                    $lineTemp                               = new InvoiceLine;
                    $lineTemp->invoice_header_id            = $headerTemp->id;
                    $lineTemp->seq_number                   = $key+1;
                    $lineTemp->supplier_id                  = $requisition->supplier_id;
                    $lineTemp->supplier_name                = $requisition->supplier_name;
                    $lineTemp->supplier_site                = $requisition->supplier_site;
                    $lineTemp->bank_account_number          = $requisition->bank_account_num;
                    $lineTemp->budget_plan                  = $budgetPlan->category_concat_segs;
                    $lineTemp->budget_type                  = $budgetType->category_concat_segs;
                    $lineTemp->expense_type                 = $requisition->expense_category;
                    $lineTemp->expense_description          = $expeseType->description;
                    $lineTemp->expense_account              = $expAccount;
                    $lineTemp->amount                       = $requisition->amount;
                    $lineTemp->description                  = $requisition->description;
                    $lineTemp->reference_req_number         = $requisition->req_number;
                    $lineTemp->save();
                }else{
                    foreach ($requisition->lines as $key => $line) {
                        $lineTemp                           = new InvoiceLine;
                        $lineTemp->invoice_header_id        = $headerTemp->id;
                        $lineTemp->seq_number               = $key+1;
                        $lineTemp->supplier_id              = $line->supplier_id;
                        $lineTemp->supplier_name            = $line->supplier_name;
                        $lineTemp->supplier_site            = $line->supplier_site;
                        $lineTemp->bank_account_number      = $line->bank_account_number;
                        $lineTemp->budget_plan              = $line->budget_plan;
                        $lineTemp->budget_type              = $line->budget_type;
                        $lineTemp->expense_type             = $line->expense_type;
                        $lineTemp->expense_description      = $line->expense_description;
                        $lineTemp->expense_account          = $line->expense_account;
                        $lineTemp->amount                   = $header->clear_flag == 'Y'? $line->actual_amount: $line->amount;
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
                        $lineTemp->remaining_receipt_id     = $line->remaining_receipt_id;
                        $lineTemp->remaining_receipt_number = $line->remaining_receipt_number;
                        $lineTemp->reference_req_number     = $requisition->req_number;
                        $lineTemp->save();

                        $requistionLine = RequisitionLine::where('req_header_id', $requisition->id)
                                            ->update([
                                                'invl_reference_id' => $lineTemp->id
                                            ]);
                    }
                }

                if ($requisition->source_type == 'REQUISITION') {
                    // UPDATE REQUISITION
                    $requistion = RequisitionHeader::where('req_number', $requisition->req_number)
                                            ->update([
                                                'invoice_reference_id'  => $headerTemp->id
                                                , 'invioce_number_ref'  => $headerTemp->invoice_number
                                                , 'updated_by'          => $user->id
                                                , 'updation_by'         => $user->person_id
                                            ]);
                }
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
            $header = InvoiceHeader::findOrFail($invoiceId);
            $header->invoice_date                   = $invioce['invoice_date']? date('Y-m-d', strtotime($invioce['invoice_date'])): '';
            $header->invoice_type                   = $invioce['invoice_type'];
            $header->document_category              = $invioce['document_category'];
            $header->supplier_id                    = $invioce['supplier_id'];
            $header->supplier_name                  = $invioce['supplier_name'];
            $header->payment_method                 = $invioce['payment_method'];
            $header->payment_term                   = $invioce['payment_term'];
            $header->clear_date                     = $invioce['clear_date']? date('Y-m-d', strtotime($invioce['clear_date'])): '';
            $header->currency                       = $invioce['currency'];
            $header->contact_date                   = $invioce['contact_date']? date('Y-m-d', strtotime($invioce['contact_date'])): '';
            $header->final_judgment                 = $invioce['final_judgment'];
            $header->gfmis_document_number          = $invioce['gfmis_document_number'];
            $header->total_amount                   = $request->totalApply;
            // $header->status                         = 'CONFIRM';
            $header->description                    = $invioce['description'];
            $header->note                           = $invioce['note'];
            $header->updated_by                     = $user->id;
            $header->updation_by                    = $user->person_id;
            $header->save();

            InvoiceLine::where('invoice_header_id', $invoiceId)->delete();
            foreach ($invioceLines as $key => $line) {
                $lineTemp                           = new InvoiceLine;
                $lineTemp->invoice_header_id        = $invoiceId;
                $lineTemp->seq_number               = $key+1;
                $lineTemp->supplier_id              = $line['supplier_id'];
                $lineTemp->supplier_name            = $line['supplier_name'];
                $lineTemp->supplier_site            = $line['supplier_site'];
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
            if($request->activity == 'INTERFACE'){
                $resultInf = $this->interface($invoiceId);
                if ($resultInf['status'] == 'C') {
                    $invoice->status    = 'INTERFACED';
                    $invoice->save();
                }else{
                    $invoice->status        = 'ERROR';
                    $invoice->error_message = $resultInf['message'];
                    $invoice->save();
                }
                $data = [
                    'status' => $resultInf['status'],
                    'message' => $resultInf['message']
                ];
            }
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
            // UPDATE REQUISITION LINES                      
            $requistion = RequisitionHeader::where('invoice_reference_id', $invoiceId)->get()->pluck('id')->toArray();
            $requistionLine = RequisitionLine::whereIn('req_header_id', $requistion)
                                        ->update([
                                            'invl_reference_id' => null
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

    public function interface($invoiceId)
    {
        $invoice = InvoiceHeader::findOrFail($invoiceId);
        $result = (new InvoiceInfRepo)->insertInterface($invoice);
        
        return $result;
    }
}
