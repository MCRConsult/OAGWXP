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
use Packages\expense\app\Models\InvoiceInterfaceHeader;
use Packages\expense\app\Models\InvoiceInterfaceLine;
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

use Packages\expense\app\Repositories\BudgetInterfaceRepo;
use Packages\expense\app\Repositories\InvoiceInterfaceRepo;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = InvoiceHeader::search(request()->all())
                                    ->with(['user.hrEmployee', 'supplier'])
                                    ->byRelatedUser()
                                    ->orderByRaw('invoice_date desc, voucher_number desc')
                                    ->paginate(25);
        $invoiceTypes = InvoiceType::whereIn('lookup_code', ['STANDARD', 'PREPAYMENT'])->get();
        $statuses = ['NEW'          => 'ขอเบิก'
                    , 'INTERFACED'  => 'เบิกจ่ายแล้ว'
                    , 'ERROR'       => 'เบิกจ่ายไม่สำเร็จ'
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
            $requisition = RequisitionHeader::findOrFail($reqId);
            switch ($activity) {
                case "HOLD_REQUISITION":
                    $requisition->status            = 'HOLD';
                    $requisition->hold_reason       = $reason;
                    $requisition->updated_by        = $user->id;
                    $requisition->updation_by       = $user->person_id;
                    $requisition->save();
                break;
                case "CANCEL_REQUISITION":
                    // UNRESERV BUDGETS
                    $result = (new BudgetInterfaceRepo)->unreserveBudget($requisition, $user);
                    if ($result['status'] == 'S') {
                        $requisition->status         = 'CANCELLED';
                        $requisition->cancel_reason  = $reason;
                        $requisition->updated_by     = $user->id;
                        $requisition->updation_by    = $user->person_id;
                        $requisition->save();
                    }else{
                        $requisition->status         = 'ERROR';
                        $requisition->error_message  = $result['message'];
                        $requisition->updated_by     = $user->id;
                        $requisition->updation_by    = $user->person_id;
                        $requisition->save();
                    }
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
            $sourceDefault = ['500', '510', '520', '530', '540', '550'];
            $header = collect($mergeReqs)->first();
            $prefixInvRef = (new InvoiceHeader)->getInvRef($header->invoice_type);
            $docCate = '';
            $invoiceNum = '';
            $headerDesc = '';
            if ($header->source_type == 'RECEIPT') {
                if ($header->revenue_delivery_code == '02') {
                    $lookup = LookupV::selectRaw('lookup_type, lookup_code, meaning, description')
                                    ->where('lookup_type', 'OAG_AP_REVENUE_CATEGORY_02')
                                    ->where('meaning', $header->document_category)
                                    ->first();
                    $docCate = optional($lookup)->description;
                }else{
                    $lookup = LookupV::selectRaw('lookup_type, lookup_code, meaning, description')
                                    ->where('lookup_type', 'OAG_AP_REVENUE_CATEGORY')
                                    ->where('meaning', $header->document_category)
                                    ->first();
                    $docCate = optional($lookup)->description;
                }
                $invoiceNum = $header->req_number;
            }else{
                $invoiceNum = (new InvoiceHeader)->genDocumentNo($user->org_id, $prefixInvRef);
            }
            // HEADER DESCRIPTION
            if ($header->source_type == 'RECEIPT') {
                $headerDesc = $header->description;
            }elseif(in_array($header->budget_source, $sourceDefault)){
                $headerDesc = $header->description;
            }
            // CREATE NEW INVOICE
            $headerTemp                                     = new InvoiceHeader;
            $headerTemp->invoice_number                     = $invoiceNum;
            $headerTemp->org_id                             = $user->org_id;
            $headerTemp->reference_number                   = $header->reference_number;
            $headerTemp->budget_source                      = $header->budget_source;
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
            $headerTemp->final_judgment_number              = $header->source_type == 'RECEIPT'? $header->ap_invoice_no: '';
            $headerTemp->receipt_number                     = $header->source_type == 'RECEIPT'? $header->receipt_number: '';
            $headerTemp->gfmis_document_number              = '';
            $headerTemp->revenue_delivery_code              = $header->revenue_delivery_code;
            $headerTemp->total_amount                       = $header->source_type == 'REQUISITION'
                                                                ? collect($mergeReqs)->sum('total_amount')
                                                                : collect($mergeReqs)->sum('amount'); // SUM LINE
            $headerTemp->clear_date                         = '';
            $headerTemp->description                        = $headerDesc;
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
                                            ->where('id', $line->id)
                                            ->update([
                                                'invl_reference_id' => $lineTemp->id
                                            ]);
                        \DB::commit();
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

        return view('expense::invoice.show', compact('invoice'));
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

            foreach ($invioceLines as $key => $line) {
                $lineTemp = InvoiceLine::where('invoice_header_id', $invoiceId)
                        ->where('id', $line['id'])
                        ->update([
                            'invoice_header_id'          => $invoiceId
                            , 'seq_number'               => $key+1
                            , 'supplier_id'              => $line['supplier_id']
                            , 'supplier_name'            => $line['supplier_name']
                            , 'supplier_site'            => $line['supplier_site']
                            , 'bank_account_number'      => $line['bank_account_number']
                            , 'budget_plan'              => $line['budget_plan']
                            , 'budget_type'              => $line['budget_type']
                            , 'expense_type'             => $line['expense_type']
                            , 'expense_description'      => $line['expense_description']
                            , 'expense_account'          => $line['expense_account']
                            , 'amount'                   => $line['amount']
                            , 'description'              => $line['description']
                            , 'vehicle_number'           => $line['vehicle_number']
                            , 'policy_number'            => $line['policy_number']
                            , 'vehicle_oil_type'         => $line['vehicle_oil_type']
                            , 'utility_type'             => $line['utility_type']
                            , 'utility_detail'           => $line['utility_detail']
                            , 'unit_quantity'            => $line['unit_quantity']
                            , 'req_invoice_number'       => $line['req_invoice_number']
                            , 'req_invoice_date'         => $line['req_invoice_date']? date('Y-m-d', strtotime($line['req_invoice_date'])): ''
                            , 'req_receipt_number'       => $line['req_receipt_number']
                            , 'req_receipt_date'         => $line['req_receipt_date']? date('Y-m-d', strtotime($line['req_receipt_date'])): ''
                            , 'remaining_receipt_flag'   => $line['remaining_receipt_flag'] == true? 'Y': 'N'
                            , 'remaining_receipt_number' => $line['remaining_receipt_number']
                            , 'ar_receipt_id'            => $line['ar_receipt_id']
                            , 'ar_receipt_number'        => $line['ar_receipt_number']
                            , 'tax_code'                 => $line['tax_code']
                            , 'tax_amount'               => $line['tax_amount']
                            , 'wht_code'                 => $line['wht_code']
                            , 'wht_amount'               => $line['wht_amount']
                        ]);
                \DB::commit();
            }
            \DB::commit();

            if($request->activity == 'INTERFACE'){
                // CHECK HAVE TO CHANGE ACCOUNT
                $invoice = InvoiceHeader::findOrFail($invoiceId);
                $invLines = InvoiceLine::where('invoice_header_id', $invoiceId)->get();
                $valid = true;
                if ($invoice->encumbrance_flag != 'Y' && $invoice->source_type == 'REQUISITION') {
                    foreach ($invLines as $key => $line) {
                        $reqLine = RequisitionLine::where('invl_reference_id', $line->id)->first();
                        if ($line->expense_account != $reqLine->expense_account) {
                            $valid = false;
                        }
                    }
                }
                if (!$valid) {
                    // UNRESERV BUDGET TO REQUISITION
                    $resultReq = (new BudgetInterfaceRepo)->unreserveBudgetREQ($invoice, $user);
                    if ($resultReq['status'] == 'S') {
                        // RESERV BUDGET TO INVOICE
                        $resultInv = (new BudgetInterfaceRepo)->reserveBudgetINV($invoice, $user);
                        if ($resultInv['status'] == 'S') {
                            $invoice->encumbrance_flag = 'Y';
                            $invoice->save();
                        }else{
                            return;
                        }
                    }
                }
                // INTERFACE TO AP INVOICE
                if ($invoice->invoice_type == 'STANDARD' && $invoice->source_type == 'REQUISITION') {
                    $resultInf = $this->interface($invoiceId);
                    if ($resultInf['status'] == 'S') {
                        // UNRESERV BUDGET INVOICE => TYPE IS STANDARD
                        if ($invoice->encumbrance_flag == 'Y') {
                            $result = (new BudgetInterfaceRepo)->unreserveBudgetINV($invoice, $user);
                        }else{
                            $result = (new BudgetInterfaceRepo)->unreserveBudgetREQ($invoice, $user);
                        }
                        if ($result['status'] == 'S') {
                            $invoice->status    = 'INTERFACED';
                            $invoice->save();
                        }else{
                            $invoice->status        = 'ERROR';
                            $invoice->error_message = $result['message'];
                            $invoice->save();
                        }
                        $data = [
                            'status' => $result['status'],
                            'message' => $result['message']
                        ];
                    }else{
                        // DELETE TEMP INTERFACE BY INVOICE NUM
                        InvoiceInterfaceHeader::where('invoice_num', $invoice->invoice_num)
                                                        ->whereNull('x_invoice_id')
                                                        ->delete();
                        InvoiceInterfaceLine::where('invoice_num', $invoice->invoice_num)->delete();
                        $data = [
                            'status' => 'ERROR',
                            'message' => $resultInf['message'],
                        ];
                        return response()->json($data);
                    }
                }else{
                    $resultInf = $this->interface($invoiceId);
                    if ($resultInf['status'] == 'S') {
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
            $requisition = RequisitionHeader::where('invoice_reference_id', $invoiceId)->get()->pluck('id')->toArray();
            RequisitionHeader::where('invoice_reference_id', $invoiceId)
                            ->update([
                                'invoice_reference_id'  => null
                                , 'invioce_number_ref'  => null
                                , 'updated_by'          => $user->id
                                , 'updation_by'         => $user->person_id
                            ]);
            // UPDATE REQUISITION LINES                      
            RequisitionLine::whereIn('req_header_id', $requisition)
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
        $result = (new InvoiceInterfaceRepo)->insertInterface($invoice);
        
        return $result;
    }

    public function reSubmit($invoiceId)
    {
        $invoice = InvoiceHeader::findOrFail($invoiceId);
        try{
            // CALL PACKAGE
            $infIvoice =  InvoiceInterfaceHeader::where('invoice_num', $invoice->invoice_number)
                                            ->whereNull('voucher_num')
                                            ->first();
            $resultInf = (new InvoiceHeader)->interfaceAP($infIvoice->web_batch_no);
            if ($resultInf['status'] == 'S') {
                $invoice->status  = 'INTERFACED';
                $invoice->save();
                $data = [
                    'status' => 'SUCCESS',
                    'message' => '',
                    'redirect_show_page' => route('expense.invoice.show', $invoiceId)
                ];
            }else{
                $invoice->status        = 'ERROR';
                $invoice->error_message = $resultInf['error_msg'];
                $invoice->save();
                $data = [
                    'status' => 'ERROR',
                    'message' => $resultInf['error_msg']
                ];
            }
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
