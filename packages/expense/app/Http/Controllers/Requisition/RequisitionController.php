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
use Packages\expense\app\Models\POExpenseAccountRuleV;
use Packages\expense\app\Models\ARBudgetReceiptV;
use Packages\expense\app\Models\GLPeriod;
use Packages\expense\app\Models\COAListV;
use Packages\expense\app\Models\GLBudgetReservations;
use Packages\expense\app\Models\GLAccountHierarchyV;

use Packages\expense\app\Repositories\BudgetInfRepo;
use Packages\expense\app\Repositories\GLJournalInfRepo;

class RequisitionController extends Controller
{
    public function index()
    {
        $search = request()->all();
        $requisitions = RequisitionHeader::search(request()->all())
                                    ->with(['user.hrEmployee', 'invoiceType', 'invoice', 'clear'])
                                    ->whereNotNull('req_number')
                                    ->orderBy('req_number', 'desc')
                                    ->paginate(25);
        $invoiceTypes = InvoiceType::whereIn('lookup_code', ['STANDARD', 'PREPAYMENT'])->get();
        $statuses = ['COMPLETED'    => 'รอเบิกจ่าย'
                    , 'PENDING'     => 'รอจัดสรร'
                    , 'HOLD'        => 'รอตรวจสอบ'
                    , 'CANCELLED'   => 'ยกเลิก'];

        return view('expense::requisition.index', compact('requisitions', 'invoiceTypes', 'statuses'));
    }

    public function create()
    {
        $user = auth()->user()->hrEmployee;
        $referenceNo = date('YmdHis').'-'.Str::random(5);
        $invoiceTypes = InvoiceType::whereIn('lookup_code', ['STANDARD', 'PREPAYMENT'])->get();
        $defaultSetName = (new COAListV)->getDefaultSetName();
        return view('expense::requisition.create', compact('user', 'invoiceTypes', 'referenceNo', 'defaultSetName'));
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
            $headerTemp->status                     = 'COMPLETED';
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
                $lineTemp->save();
            }
            \DB::commit();

            $requistion = RequisitionHeader::findOrFail($headerTemp->id);
            // IF PAYMENT_TYPE == NON-PAYMENT HAVE TO CALL GL INTERFACE
            if ($headerTemp->payment_type == 'NON-PAYMENT') {
                $result = (new GLJournalInfRepo)->insertInterface($requistion);
                if ($result['status'] == 'S') {
                    $header->status        = 'INTERFACED';
                    $header->save();
                }else{
                    $header->status        = 'ERROR';
                    $header->error_message = $result['message'];
                    $header->save();
                }
            }else{
                // 1 FIND FUND CHECK BUDGET
                $findFunds = [];
                foreach ($requistion->lines as $key => $line) {
                    // FIND FUND AVALIABLE
                    $budgetAvaliable = (new GLAccountHierarchyV)->findFund($user->org_id, $line->expense_account);
                    // GET SUMMARY ACCOUNT
                    $account = GLAccountHierarchyV::where('account_code', $line->expense_account)->first();
                    $budgetAccount = optional($account)->summary_code_combination_id;
                    if ($budgetAvaliable != null) {
                        if (isset($findFunds[$budgetAccount])) {
                            if ($findFunds[$budgetAccount] <= 0) {
                                $requistion->status = 'PENDING';
                            }elseif($findFunds[$budgetAccount] - $line->amount <= 0){
                                $requistion->status = 'PENDING';
                            }else{
                                $findFunds[$budgetAccount] = $findFunds[$budgetAccount] - $line->amount;
                            }
                        }else{
                            if ($budgetAvaliable <= 0) {
                                $requistion->status = 'PENDING';
                            }elseif($budgetAvaliable - $line->amount <= 0){
                                $requistion->status = 'PENDING';
                            }else{
                                $findFunds[$budgetAccount] = $budgetAvaliable - $line->amount;
                            }
                        }
                    }
                }
                $result = (new BudgetInfRepo)->reserveBudget($requistion, $user);
                if ($result['status'] == 'S') {
                    $header->status        = 'INTERFACED';
                    $header->save();
                }else{
                    $header->status        = 'ERROR';
                    $header->error_message = $result['message'];
                    $header->save();
                }
            }
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
        if ($requisition->clear_flag == 'Y') {
            return view('expense::requisition.clear.show', compact('requisition'));
        }
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
            $clearReq->status               = 'CLEAR';
            $clearReq->clear_flag           = 'Y';
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
            $requisition->total_amount              = $request->totalApply;
            $requisition->status                    = 'COMPLETED';
            $requisition->description               = $header['description'];
            $requisition->updated_by                = $user->id;
            $requisition->updation_by               = $user->person_id;
            if ($header['clear_flag'] == 'Y') {
                $requisition->req_number            = (new RequisitionHeader)->genDocumentNo($user->org_id, $prefixReq[0]);
                // UPDATE REF CLEAR REQUISITION
                $refRequisition = RequisitionHeader::findOrFail($request->refRequisition);
                $refRequisition->clear_reference_id    = $requisition->id;
                $refRequisition->clear_reference_date  = date('Y-m-d');
                $refRequisition->save();
            }
            $requisition->save();


            RequisitionLine::where('req_header_id', $reqId)->delete();
            foreach ($lines as $key => $line) {
                $lineTemp                           = new RequisitionLine;
                $lineTemp->req_header_id            = $reqId;
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
                $lineTemp->save();
            }

            \DB::commit();
            $data = [
                'status' => 'SUCCESS',
                'message' => '',
                'redirect_show_page' => route('expense.requisition.show', $reqId)
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
            $headerTemp->remaining_receipt_number   = $this->getRemainingRceipt($line['remaining_receipt_id']);
            $headerTemp->amount                     = $line['amount'];
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

    private function getRemainingRceipt($receiptId)
    {
        $receipt = ARBudgetReceiptV::where('cash_receipt_id', $receiptId)->first();
        return optional($receipt)->receipt_number;
    }

    public function reSubmit($reqId)
    {
        $requisition = RequisitionHeader::findOrFail($reqId);
        // CALL PACKAGE
        $infGLJournal =  GLJournalInterface::where('reference2', $requisition->req_number)
                                        ->where('interface_status', 'E')
                                        ->first();
        $resultInf = (new RequisitionHeader)->interfaceGL($infGLJournal->reference1);
        
        if ($resultInf['status'] == 'S') {
            $invoice->status  = 'INTERFACED';
            $invoice->save();

            return redirect()->back()->with('message', 'ส่งข้อมูลเข้าระบบเรียบร้อยแล้ว');
        }else{
            $invoice->status        = 'ERROR';
            $invoice->error_message = $resultInf['error_msg'];
            $invoice->save();

            return redirect()->back()->withErrors($resultInf['error_msg']);
        }
    }
}
