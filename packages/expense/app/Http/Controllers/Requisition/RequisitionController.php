<?php

namespace Packages\expense\app\Http\Controllers\Requisition;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

use PDF;
use Auth;
use Carbon\Carbon;

use Packages\expense\app\Models\RequisitionHeader;
use Packages\expense\app\Models\RequisitionLine;
use Packages\expense\app\Models\RequisitionReceiptTemp;
use Packages\expense\app\Models\InvoiceType;
use Packages\expense\app\Models\DocumentCategory;
use Packages\expense\app\Models\Supplier;
use Packages\expense\app\Models\SupplierBank;
use Packages\expense\app\Models\PaymentMethod;
use Packages\expense\app\Models\Currency;
use Packages\expense\app\Models\FlaxValueV;
use Packages\expense\app\Models\MTLCategoriesV;
use Packages\expense\app\Models\POExpenseAccountRuleV;
use Packages\expense\app\Models\OAGARBudgetReceiptV;
use Packages\expense\app\Models\GLPeriod;
use Packages\expense\app\Models\COAListV;
use Packages\expense\app\Models\MappingAutoInvoiceV;
use Packages\expense\app\Models\GLBudgetReservations;
use Packages\expense\app\Models\GLAccountHierarchyV;
use Packages\expense\app\Models\GLJournalInterface;

use Packages\expense\app\Repositories\BudgetInterfaceRepo;
use Packages\expense\app\Repositories\JournalInterfaceRepo;

class RequisitionController extends Controller
{
    public function index()
    {
        $search = request()->all();
        $requisitions = RequisitionHeader::search(request()->all())
                                    ->with(['user.hrEmployee', 'invoiceType', 'invoice', 'clear', 'invoiceStatus'])
                                    ->byRelatedUser()
                                    ->whereNotNull('req_number')
                                    ->orderBy('req_number', 'desc')
                                    ->paginate(25);
        $invoiceTypes = InvoiceType::whereIn('lookup_code', ['STANDARD', 'PREPAYMENT'])->get();
        $statuses = ['COMPLETED'        => 'รอเบิกจ่าย'
                    , 'PENDING'         => 'รอจัดสรร'
                    , 'HOLD'            => 'รอตรวจสอบ'
                    , 'WAITING_CLEAR'   => 'รอเคลียร์เงินยืม'
                    , 'INTERFACED'      => 'ตั้งเบิก'
                    , 'ERROR'           => 'ตั้งเบิกไม่สำเร็จ'
                    , 'REVERSED'        => 'กลับรายการบัญชีแล้ว'
                    , 'UNREVERSED'      => 'กลับรายการบัญชีไม่สำเร็จ'
                    , 'CANCELLED'       => 'ยกเลิก'];

        return view('expense::requisition.index', compact('requisitions', 'invoiceTypes', 'statuses'));
    }

    public function create()
    {
        $user = auth()->user()->hrEmployee;
        $referenceNo = date('YmdHis').'-'.Str::random(5);
        $invoiceTypes = InvoiceType::whereIn('lookup_code', ['STANDARD', 'PREPAYMENT'])->get();
        $defaultSetName = (new COAListV)->getDefaultSetName();
        $defaultSupplier = Supplier::where('vendor_name', 'สำนักงานอัยการสูงสุด')->first();
        return view('expense::requisition.create', compact('user', 'invoiceTypes', 'referenceNo', 'defaultSetName', 'defaultSupplier'));
    }

    public function store(Request $request)
    {
        $header = $request->header;
        $lines = $request->lines;
        $user = auth()->user();
        // PREFIX GENERATE SEQ
        $prefixReq = explode('_', $header['document_category']);
        $prefixInvRef = (new RequisitionHeader)->getInvRef($header['invoice_type']);
        \DB::beginTransaction();
        try {
            $headerTemp                             = new RequisitionHeader;
            $headerTemp->org_id                     = $user->org_id;
            $headerTemp->reference_number           = $header['reference_number'];
            $headerTemp->source_type                = 'REQUISITION';
            $headerTemp->budget_source              = $header['budget_source'];
            $headerTemp->invoice_type               = $header['invoice_type'];
            $headerTemp->document_category          = $header['document_category'];
            $headerTemp->req_number                 = (new RequisitionHeader)->genDocumentNo($user->org_id, $prefixReq[0]);
            $headerTemp->req_date                   = date('Y-m-d', strtotime($header['req_date']));
            $headerTemp->payment_type               = $header['payment_type'];
            $headerTemp->supplier_id                = $header['supplier_id'];
            $headerTemp->supplier_name              = $header['supplier_name'];
            $headerTemp->multiple_supplier          = $header['multiple_supplier'];
            $headerTemp->cash_bank_account_id       = $header['cash_bank_account_id'];
            $headerTemp->total_amount               = $request->totalApply;
            // $headerTemp->status                     = 'COMPLETED';
            $headerTemp->description                = $header['description'];
            $headerTemp->requester                  = $user->id;
            $headerTemp->created_by                 = $user->id;
            $headerTemp->updated_by                 = $user->id;
            $headerTemp->creation_by                = $user->person_id;
            $headerTemp->updation_by                = $user->person_id;
            $headerTemp->save();

            foreach ($lines as $key => $line) {
                // GET CONCATE SEGMENT
                $lineTemp                           = new RequisitionLine;
                $lineTemp->req_header_id            = $headerTemp->id;
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
                $lineTemp->invoice_number           = $line['invoice_number'];
                $lineTemp->invoice_date             = !is_null($line['invoice_date'])? date('Y-m-d', strtotime($line['invoice_date'])): '';
                $lineTemp->receipt_number           = $line['receipt_number'];
                $lineTemp->receipt_date             = !is_null($line['receipt_date'])? date('Y-m-d', strtotime($line['receipt_date'])): '';
                $lineTemp->remaining_receipt_flag   = $line['remaining_receipt_flag'];
                $lineTemp->remaining_receipt_id     = $line['remaining_receipt_id'];
                $lineTemp->remaining_receipt_number = $this->getRemainingRceipt($line['remaining_receipt_id']);
                $lineTemp->receipt_account          = $line['receipt_account'];
                $lineTemp->contract_number          = $line['contract_number'];
                $lineTemp->save();

                if ($line['remaining_receipt_flag'] == 'Y') {
                    RequisitionReceiptTemp::where('reference_number', $headerTemp->reference_number)
                                ->where('remaining_receipt_id', $line['remaining_receipt_id'])
                                ->where('seq_number', $key+1)
                                ->update([
                                    'invoice_number' => $headerTemp->req_number
                                ]);
                }
            }
            \DB::commit();
            // CALL GL INTERFACE + RESERVE BUDGET
            $result = $this->handleRequistionReserv($headerTemp, $user);
            $data = [
                'status' => 'SUCCESS',
                'message' => '',
                'redirect_show_page' => route('expense.requisition.show', $headerTemp->id)
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

    public function show($reqId)
    {
        $requisition = RequisitionHeader::findOrFail($reqId);
        return view('expense::requisition.show', compact('requisition'));
    }

    public function hold($reqId)
    {
        $requisition = RequisitionHeader::where('id', $reqId)
                            ->with(['lines', 'lines.expense', 'user', 'user.hrEmployee'])
                            ->first();
        $invoiceTypes = InvoiceType::whereIn('lookup_code', ['STANDARD', 'PREPAYMENT'])->get();
        $defaultSetName = (new COAListV)->getDefaultSetName();

        return view('expense::requisition.hold.index', compact('requisition', 'invoiceTypes', 'defaultSetName'));
    }

    public function clear($reqId)
    {
        $user = auth()->user();
        \DB::beginTransaction();
        try {
            $requisition = RequisitionHeader::findOrFail($reqId);
            // CLAER HEADER
            $clearReq                       = $requisition->replicate();
            $clearReq->invoice_type         = 'STANDARD';
            $clearReq->req_number           = '';
            $clearReq->req_date             = date('Y-m-d');
            $clearReq->status               = 'WAITING_CLEAR';
            $clearReq->clear_flag           = 'Y';
            $clearReq->invoice_reference_id = null;
            $clearReq->invioce_number_ref   = null;
            $clearReq->created_by           = $user->id;
            $clearReq->updated_by           = $user->id;
            $clearReq->creation_by          = $user->person_id;
            $clearReq->updation_by          = $user->person_id;
            $clearReq->save();
            // CLAER LINES
            foreach ($requisition->lines as $line) {
                $clearLines                 = $line->replicate();
                $clearLines->req_header_id  = $clearReq->id;
                $clearLines->actual_amount  = $line->amount;
                $clearLines->save();
            }
            \DB::commit();
            // GET DATA CLEAR REQUISITION
            $requisition = RequisitionHeader::where('id', $reqId)
                                        ->with(['invoice'])
                                        ->first();
            $clearReq = RequisitionHeader::where('id', $clearReq->id)
                                        ->with(['lines'])
                                        ->first();
        } catch (\Exception $e) {
            \DB::rollback();
            throw new \Exception($e->getMessage(), 1);
        }

        return view('expense::requisition.clear.index', compact('requisition', 'clearReq'));
    }

    public function clearEdit($reqId)
    {
        $requisition = RequisitionHeader::where('id', $reqId)
                            ->with(['lines', 'lines.expense', 'user', 'user.hrEmployee'])
                            ->first();
        $invoiceTypes = InvoiceType::whereIn('lookup_code', ['STANDARD', 'PREPAYMENT'])->get();
        $defaultSetName = (new COAListV)->getDefaultSetName();

        return view('expense::requisition.clear.edit', compact('requisition', 'invoiceTypes', 'defaultSetName'));
    }

    // THIS FUNCTION USE HOLD AND CLEAR(SAVE CLEARING)
    public function update(Request $request, $reqId)
    {
        $user = auth()->user();
        $header = $request->header;
        $lines = $request->lines;
        \DB::beginTransaction();
        try {
            $requisition = RequisitionHeader::findOrFail($reqId);
            $prefixReq = explode('_', $requisition->document_category);
            $requisition->budget_source             = $header['budget_source'];
            $requisition->invoice_type              = $header['invoice_type'];
            $requisition->document_category         = $header['document_category'];
            $requisition->req_date                  = date('Y-m-d', strtotime($header['req_date']));
            $requisition->payment_type              = $header['payment_type'];
            $requisition->supplier_id               = $header['supplier_id'];
            $requisition->supplier_name             = $header['supplier_name'];
            $requisition->multiple_supplier         = $header['multiple_supplier'];
            $requisition->cash_bank_account_id      = $header['cash_bank_account_id'];
            $requisition->total_amount              = $header['clear_flag'] == 'Y'? $requisition->total_amount: $request->totalApply;
            $requisition->status                    = $header['clear_flag'] == 'Y'? 'WAITING_CLEAR': 'COMPLETED';
            $requisition->description               = $header['description'];
            $requisition->updated_by                = $user->id;
            $requisition->updation_by               = $user->person_id;
            if ($header['clear_flag'] == 'Y' && is_null($requisition->req_number)) {
                $requisition->req_number            = (new RequisitionHeader)->genDocumentNo($user->org_id, $prefixReq[0]);
                // UPDATE REF CLEAR REQUISITION
                if (isset($request->refRequisition)) {
                    $refRequisition = RequisitionHeader::findOrFail($request->refRequisition);
                    $refRequisition->clear_reference_id    = $requisition->id;
                    $refRequisition->clear_reference_date  = date('Y-m-d');
                    $refRequisition->save();
                }
            }
            $requisition->save();

            foreach ($lines as $key => $line) {
                if (is_null($line['split_flag']) || $requisition->status != 'WAITING_CLEAR') {
                    $lineTemp = RequisitionLine::FindOrfail($line['id']);
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
                    if ($header['clear_flag'] == 'Y') {
                        $lineTemp->actual_amount        = $line['actual_amount'];
                    }
                    $lineTemp->description              = $line['description'];
                    $lineTemp->vehicle_number           = $line['vehicle_number'];
                    $lineTemp->policy_number            = $line['policy_number'];
                    $lineTemp->vehicle_oil_type         = $line['vehicle_oil_type'];
                    $lineTemp->utility_type             = $line['utility_type'];
                    $lineTemp->utility_detail           = $line['utility_detail'];
                    $lineTemp->unit_quantity            = $line['unit_quantity'];
                    $lineTemp->invoice_number           = $line['invoice_number'];
                    $lineTemp->invoice_date             = !is_null($line['invoice_date'])? date('Y-m-d', strtotime($line['invoice_date'])): '';
                    $lineTemp->receipt_number           = $line['receipt_number'];
                    $lineTemp->receipt_date             = !is_null($line['receipt_date'])? date('Y-m-d', strtotime($line['receipt_date'])): '';
                    $lineTemp->remaining_receipt_flag   = $line['remaining_receipt_flag'];
                    $lineTemp->remaining_receipt_id     = $line['remaining_receipt_id'];
                    $lineTemp->remaining_receipt_number = $this->getRemainingRceipt($line['remaining_receipt_id']);
                    $lineTemp->receipt_account          = $line['receipt_account'];
                    $lineTemp->contract_number          = $line['contract_number'];
                    $lineTemp->save();
                }
            }
            // UPDATE CLEAR FIRST TIME ONLY
            if ($header['clear_flag'] == 'Y' && $requisition->status == 'WAITING_CLEAR') {
                $this->handleClearingBalance($reqId, $requisition, $lines);
            }
            \DB::commit();
            $data = [
                'status' => 'SUCCESS',
                'message' => '',
                'redirect_show_page' => $header['clear_flag'] == 'Y'
                                        ? route('expense.requisition.clear-edit', $reqId)
                                        : route('expense.requisition.show', $reqId)
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

    public function handleClearingBalance($reqId, $header, $lines)
    {
        foreach ($lines as $key => $line) {
            $lastSeq = RequisitionLine::where('req_header_id', $reqId)->count();
            if ($line['amount'] != $line['actual_amount'] && is_null($line['split_flag'])) {
                $diff_amount = $line['amount'] - $line['actual_amount'];
                $expense_category = 'EXP.200.299999.0000000001';
                $expeseType = MTLCategoriesV::where('segment1', 'EXP')
                            ->where('category_concat_segs', $expense_category)
                            ->first();
                $budgetType = MTLCategoriesV::where('segment1', 'EXP')
                        ->where('category_concat_segs', $expeseType->attribute4)
                        ->first();
                $budgetPlan = MTLCategoriesV::where('segment1', 'EXP')
                        ->where('category_concat_segs', $budgetType->attribute3)
                        ->first();
                // SET EXPENSE ACCOUNT
                $expAccount = (new MappingAutoInvoiceV)->mappingClearingAccount($header, $expense_category);
                // INSERT NEW LINE : คืนเงิน เมื่อยอดไม่เท่ากับยอดตั้งต้น
                $lineTemp                          = new RequisitionLine;
                $lineTemp->req_header_id            = $reqId;
                $lineTemp->seq_number               = $lastSeq+1;
                $lineTemp->supplier_id              = $line['supplier_id'];
                $lineTemp->supplier_name            = $line['supplier_name'];
                $lineTemp->supplier_site            = $line['supplier_site'];
                $lineTemp->bank_account_number      = $line['bank_account_number'];
                $lineTemp->budget_plan              = $budgetPlan->category_concat_segs;
                $lineTemp->budget_type              = $budgetType->category_concat_segs;
                $lineTemp->expense_type             = $expeseType->category_concat_segs;
                $lineTemp->expense_description      = $expeseType->description;
                $lineTemp->expense_account          = $expAccount;
                $lineTemp->amount                   = $line['amount'];
                $lineTemp->actual_amount            = $diff_amount;
                $lineTemp->description              = $line['description'];
                $lineTemp->vehicle_number           = $line['vehicle_number'];
                $lineTemp->policy_number            = $line['policy_number'];
                $lineTemp->vehicle_oil_type         = $line['vehicle_oil_type'];
                $lineTemp->utility_type             = $line['utility_type'];
                $lineTemp->utility_detail           = $line['utility_detail'];
                $lineTemp->unit_quantity            = $line['unit_quantity'];
                $lineTemp->invoice_number           = $line['invoice_number'];
                $lineTemp->invoice_date             = !is_null($line['invoice_date'])? date('Y-m-d', strtotime($line['invoice_date'])): '';
                $lineTemp->receipt_number           = $line['receipt_number'];
                $lineTemp->receipt_date             = !is_null($line['receipt_date'])? date('Y-m-d', strtotime($line['receipt_date'])): '';
                $lineTemp->remaining_receipt_flag   = 'N';
                $lineTemp->remaining_receipt_id     = '';
                $lineTemp->remaining_receipt_number = '';
                $lineTemp->split_flag               = 'Y';
                $lineTemp->save();
            }
        }
        return;
    }

    public function clearUpdate(Request $request, $reqId)
    {
        $user = auth()->user();
        $header = $request->header;
        $lines = $request->lines;
        \DB::beginTransaction();
        try {
            $requisition = RequisitionHeader::findOrFail($reqId);
            $prefixReq = explode('_', $requisition->document_category);
            $requisition->budget_source             = $header['budget_source'];
            $requisition->invoice_type              = $header['invoice_type'];
            $requisition->document_category         = $header['document_category'];
            $requisition->req_date                  = date('Y-m-d', strtotime($header['req_date']));
            $requisition->payment_type              = $header['payment_type'];
            $requisition->supplier_id               = $header['supplier_id'];
            $requisition->supplier_name             = $header['supplier_name'];
            $requisition->multiple_supplier         = $header['multiple_supplier'];
            $requisition->cash_bank_account_id      = $header['cash_bank_account_id'];
            $requisition->total_amount              = $header['clear_flag'] == 'Y'? $requisition->total_amount: $request->totalApply;
            $requisition->status                    = 'WAITING_CLEAR';
            $requisition->description               = $header['description'];
            $requisition->updated_by                = $user->id;
            $requisition->updation_by               = $user->person_id;
            $requisition->save();

            foreach ($lines as $key => $line) {
                $lineTemp = RequisitionLine::FindOrfail($line['id']);
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
                if ($header['clear_flag'] == 'Y') {
                    $lineTemp->actual_amount        = $line['actual_amount'];
                }
                $lineTemp->description              = $line['description'];
                $lineTemp->vehicle_number           = $line['vehicle_number'];
                $lineTemp->policy_number            = $line['policy_number'];
                $lineTemp->vehicle_oil_type         = $line['vehicle_oil_type'];
                $lineTemp->utility_type             = $line['utility_type'];
                $lineTemp->utility_detail           = $line['utility_detail'];
                $lineTemp->unit_quantity            = $line['unit_quantity'];
                $lineTemp->invoice_number           = $line['invoice_number'];
                $lineTemp->invoice_date             = !is_null($line['invoice_date'])? date('Y-m-d', strtotime($line['invoice_date'])): '';
                $lineTemp->receipt_number           = $line['receipt_number'];
                $lineTemp->receipt_date             = !is_null($line['receipt_date'])? date('Y-m-d', strtotime($line['receipt_date'])): '';
                $lineTemp->remaining_receipt_flag   = $line['remaining_receipt_flag'];
                $lineTemp->remaining_receipt_id     = $line['remaining_receipt_id'];
                $lineTemp->remaining_receipt_number = $this->getRemainingRceipt($line['remaining_receipt_id']);
                $lineTemp->receipt_account          = $line['receipt_account'];
                $lineTemp->contract_number          = $line['contract_number'];
                $lineTemp->save();
            }
            \DB::commit();
            $data = [
                'status' => 'SUCCESS',
                'message' => '',
                'redirect_show_page' => $header['clear_flag'] == 'Y'
                                        ? route('expense.requisition.clear-edit', $reqId)
                                        : route('expense.requisition.show', $reqId)
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

    public function clearRemove(Request $request, $reqId)
    {
        \DB::beginTransaction();
        try {
            $line = $request->line;
            $lineTemp = RequisitionLine::where('id', $line['id'])->delete();
            \DB::commit();
            $data = [
                'status' => 'SUCCESS',
                'message' => '',
            ];
        } catch (Exception $e) {
            \DB::rollback();
            \Log::error($e);
            $data = [
                'status' => 'ERROR',
                'message' => $e->getMessage(),
            ];
        }
        return response()->json($data);
    }

    public function clearSubmit($reqId)
    {
        $user = auth()->user();
        \DB::beginTransaction();
        try {
            $requisition = RequisitionHeader::findOrFail($reqId);
            $refRequisition = RequisitionHeader::findOrFail($requisition->clear->id);
            // CHECK LINE BUDGET FOR UNRESERV/RESERV -- PROCESS CLEAR
            $result = $this->handleClearingReserv($refRequisition, $requisition, $user);
            if ($result['status'] == 'E') {
                $data = [
                    'status' => $result['status'],
                    'message' => $result['message']
                ];
                return response()->json($data);
            }
            // UPDATE STATUS
            $requisition->status = 'COMPLETED';
            $requisition->save();
            \DB::commit();
            $data = [
                'status' => 'SUCCESS',
                'message' => '',
                'redirect_show_page' => route('expense.requisition.show', $requisition->id)
            ];
        } catch (Exception $e) {
            \DB::rollback();
            \Log::error($e);
            $data = [
                'status' => 'ERROR',
                'message' => $e->getMessage(),
            ];
        }
        return response()->json($data);
    }

    public function handleRequistionReserv($headerTemp, $user)
    {
        $requisition = RequisitionHeader::findOrFail($headerTemp->id);
        // IF PAYMENT_TYPE == NON-PAYMENT HAVE TO CALL GL INTERFACE
        if ($requisition->payment_type == 'NON-PAYMENT') {
            $result = (new JournalInterfaceRepo)->insertInterfaceJournal($requisition);
            if ($result['status'] == 'S') {
                $requisition->status        = 'INTERFACED';
                $requisition->save();
            }else{
                $requisition->status        = 'ERROR';
                $requisition->error_message = $result['message'];
                $requisition->save();
            }
        }else{
            // 1 FIND FUND CHECK BUDGET
            $findFunds = [];
            $overBudgets = [];
            foreach ($requisition->lines as $key => $line) {
                // FIND FUND AVALIABLE
                $budgetAvaliable = (new GLAccountHierarchyV)->findFund($user->org_id, $line->expense_account);
                // GET SUMMARY ACCOUNT
                $account = GLAccountHierarchyV::where('account_code', $line->expense_account)->first();
                $budgetAccount = optional($account)->summary_code_combination_id;
                if ($budgetAvaliable != null) {
                    if (isset($findFunds[$budgetAccount])) {
                        if ($findFunds[$budgetAccount] <= 0) {
                            array_push($overBudgets, $line->expense_account);
                        }elseif($findFunds[$budgetAccount] - $line->amount <= 0){
                            array_push($overBudgets, $line->expense_account);
                        }else{
                            $findFunds[$budgetAccount] = $findFunds[$budgetAccount] - $line->amount;
                        }
                    }else{
                        if ($budgetAvaliable <= 0) {
                            array_push($overBudgets, $line->expense_account);
                        }elseif($budgetAvaliable - $line->amount <= 0){
                            array_push($overBudgets, $line->expense_account);
                        }else{
                            $findFunds[$budgetAccount] = $budgetAvaliable - $line->amount;
                        }
                    }
                }
            }
            $result = [];
            if (count($overBudgets) <= 0) {
                $result = (new BudgetInterfaceRepo)->reserveBudget($requisition, $user);
                if ($result['status'] == 'S') {
                    $requisition->status            = 'COMPLETED';
                    $requisition->save();
                }else{
                    $requisition->status        = 'PENDING';
                    $requisition->error_message = $result['message'];
                    $requisition->save();
                }
            }else{
                $requisition->status = 'PENDING';
                $requisition->error_message = implode(',', $overBudgets);
                $requisition->save();
            }
        }

        return $result;
    }

    public function handleClearingReserv($refRequisition, $requisition, $user)
    {
        // CHECK LINE AMOUNT
        $result = [];
        $resUnreserv = (new BudgetInterfaceRepo)->unreserveBudget($refRequisition, $user);
        if ($resUnreserv['status'] == 'S') {
            // 1 FIND FUND CHECK BUDGET
            $findFunds = [];
            $overBudgets = [];
            foreach ($requisition->lines as $key => $line) {
                // FIND FUND AVALIABLE
                $budgetAvaliable = (new GLAccountHierarchyV)->findFund($user->org_id, $line->expense_account);
                // GET SUMMARY ACCOUNT
                $account = GLAccountHierarchyV::where('account_code', $line->expense_account)->first();
                $budgetAccount = optional($account)->summary_code_combination_id;
                if ($budgetAvaliable != null) {
                    if (isset($findFunds[$budgetAccount])) {
                        if ($findFunds[$budgetAccount] <= 0) {
                            array_push($overBudgets, $line->expense_account);
                        }elseif($findFunds[$budgetAccount] - $line->amount <= 0){
                            array_push($overBudgets, $line->expense_account);
                        }else{
                            $findFunds[$budgetAccount] = $findFunds[$budgetAccount] - $line->amount;
                        }
                    }else{
                        if ($budgetAvaliable <= 0) {
                            array_push($overBudgets, $line->expense_account);
                        }elseif($budgetAvaliable - $line->amount <= 0){
                            array_push($overBudgets, $line->expense_account);
                        }else{
                            $findFunds[$budgetAccount] = $budgetAvaliable - $line->amount;
                        }
                    }
                }
            }
            if (count($overBudgets) <= 0) {
                $result = (new BudgetInterfaceRepo)->reserveBudget($requisition, $user);
                if ($result['status'] == 'S') {
                    $requisition->status            = 'COMPLETED';
                    $requisition->save();
                }else{
                    $requisition->status        = 'PENDING';
                    $requisition->error_message = $result['message'];
                    $requisition->save();
                }
            }else{
                $requisition->status = 'PENDING';
                $requisition->error_message = implode(',', $overBudgets);
                $requisition->save();
            }
        }else{
            return $resUnreserv;
        }
        return $result;
    }

    private function getRemainingRceipt($receiptId)
    {
        $receipt = OAGARBudgetReceiptV::where('cash_receipt_id', $receiptId)->first();
        return optional($receipt)->receipt_number;
    }

    // PROCESS RE-INTERFACE FROM INVOICE
    public function reSubmitRequisition($reqId)
    {
        try {
            $user = auth()->user();
            $requisition = RequisitionHeader::findOrFail($reqId);
            // 1 FIND FUND CHECK BUDGET
            $findFunds = [];
            $overBudgets = [];
            foreach ($requisition->lines as $key => $line) {
                // FIND FUND AVALIABLE
                $budgetAvaliable = (new GLAccountHierarchyV)->findFund($user->org_id, $line->expense_account);
                // GET SUMMARY ACCOUNT
                $account = GLAccountHierarchyV::where('account_code', $line->expense_account)->first();
                $budgetAccount = optional($account)->summary_code_combination_id;
                if ($budgetAvaliable != null) {
                    if (isset($findFunds[$budgetAccount])) {
                        if ($findFunds[$budgetAccount] <= 0) {
                            array_push($overBudgets, $line->expense_account);
                        }elseif($findFunds[$budgetAccount] - $line->amount <= 0){
                            array_push($overBudgets, $line->expense_account);
                        }else{
                            $findFunds[$budgetAccount] = $findFunds[$budgetAccount] - $line->amount;
                        }
                    }else{
                        if ($budgetAvaliable <= 0) {
                            array_push($overBudgets, $line->expense_account);
                        }elseif($budgetAvaliable - $line->amount <= 0){
                            array_push($overBudgets, $line->expense_account);
                        }else{
                            $findFunds[$budgetAccount] = $budgetAvaliable - $line->amount;
                        }
                    }
                }
            }
            if (count($overBudgets) <= 0) {
                $result = (new BudgetInterfaceRepo)->reserveBudget($requisition, $user);
                if ($result['status'] == 'S') {
                    $requisition->status            = 'COMPLETED';
                    $requisition->save();
                }else{
                    $requisition->status        = 'PENDING';
                    $requisition->error_message = $result['message'];
                    $requisition->save();
                }
            }else{
                $requisition->status = 'PENDING';
                $requisition->error_message = implode(',', $overBudgets);
                $requisition->save();
            }
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

    // RESUBMIT GL INTERFACE
    public function reSubmitJournal($reqId)
    {
        try {
            $requisition = RequisitionHeader::findOrFail($reqId);
            // CALL PACKAGE
            $infGLJournal =  GLJournalInterface::where('reference2', $requisition->req_number)
                                            ->where('interface_status', 'E')
                                            ->first();
            $resultInf = (new RequisitionHeader)->callInterfaceJournal($infGLJournal->reference1);
            if ($resultInf['status'] == 'S') {
                $requisition->status  = 'INTERFACED';
                $requisition->save();
                $data = [
                    'status' => 'SUCCESS',
                    'message' => '',
                    'redirect_show_page' => route('expense.requisition.show', $reqId)
                ];
            }else{
                $requisition->status        = 'ERROR';
                $requisition->error_message = $resultInf['error_msg'];
                $requisition->save();
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

    // REVERSE GL INTERFACE
    public function reverseJournal($reqId)
    {
        try {
            $requisition = RequisitionHeader::findOrFail($reqId);
            // UPDATE DATA FOR REVERSE
            $infGLJournal =  GLJournalInterface::where('reference2', $requisition->req_number)
                                            ->where('interface_status', 'S')
                                            ->update([
                                                'process_flag'         => 'NEW'
                                               , 'interface_msg'       => ''
                                               , 'interface_status'    => ''
                                               , 'reverse_flag'         => 'Y'
                                            ]);
            \DB::commit();
            // INTERFACE GL REVERSE
            $reverseJournal =  GLJournalInterface::where('reference2', $requisition->req_number)->first();
            $resultInf = (new RequisitionHeader)->callInterfaceJournal($reverseJournal->reference1);
            if ($resultInf['status'] == 'S') {
                $requisition->status        = 'REVERSED';
                $requisition->reverse_flag  = 'Y';
                $requisition->save();
                $data = [
                    'status' => 'SUCCESS',
                    'message' => '',
                    'redirect_show_page' => route('expense.requisition.show', $reqId)
                ];
            }else{
                $requisition->status        = 'UNREVERSED';
                $requisition->error_message = $resultInf['error_msg'];
                $requisition->save();
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

    //======================= AR RECEIPT =======================
    public function useARReceipt(Request $request)
    {
        $header = $request->header;
        $line = $request->line;
        $user = auth()->user();
        \DB::beginTransaction();
        try {
            $headerTemp                             = new RequisitionReceiptTemp;
            $headerTemp->org_id                     = $user->org_id;
            $headerTemp->reference_number           = $header['reference_number'];
            $headerTemp->seq_number                 = $request->seq+1;
            $headerTemp->remaining_receipt_flag     = $line['remaining_receipt_flag']? 'Y': 'N';
            $headerTemp->remaining_receipt_id       = $line['remaining_receipt_id'];
            $headerTemp->remaining_receipt_number   = $this->getRemainingRceipt($line['remaining_receipt_id']);
            $headerTemp->amount                     = $line['amount'];
            $headerTemp->expense_account            = $line['expense_account'];
            $headerTemp->created_by                 = $user->id;
            $headerTemp->updated_by                 = $user->id;
            $headerTemp->creation_by                = $user->person_id;
            $headerTemp->updation_by                = $user->person_id;
            $headerTemp->save();
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

    public function updateARReceipt(Request $request)
    {
        $header = $request->header;
        $line = $request->line;
        \DB::beginTransaction();
        try {
            RequisitionReceiptTemp::where('reference_number', $header['reference_number'])
                                ->where('remaining_receipt_id', $line['remaining_receipt_id'])
                                ->where('remaining_receipt_number', $this->getRemainingRceipt($line['remaining_receipt_id']))
                                ->where('seq_number', $request->index+1)
                                ->update([
                                    'remaining_receipt_number'  => $this->getRemainingRceipt($line['remaining_receipt_id'])
                                    , 'amount'                  => $line['amount']
                                ]);
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

    public function removeARReceipt(Request $request)
    {
        $header = $request->header;
        $line = $request->line;
        \DB::beginTransaction();
        try {
            $receiptTemp = RequisitionReceiptTemp::where('reference_number', $header['reference_number'])
                                            ->where('remaining_receipt_id', $line['remaining_receipt_id'])
                                            ->where('remaining_receipt_number', $this->getRemainingRceipt($line['remaining_receipt_id']))
                                            ->where('seq_number', $request->index+1)
                                            ->delete();
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
}
