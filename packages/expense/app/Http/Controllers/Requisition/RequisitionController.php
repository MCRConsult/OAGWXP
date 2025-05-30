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

class RequisitionController extends Controller
{
    public function index()
    {
        $requisitions = RequisitionHeader::search(request()->all())
                                    ->orderBy('req_number', 'desc')
                                    ->paginate(15);
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
            $headerTemp->supplier_id                = $header['supplier'];
            $headerTemp->supplier_name              = $header['supplier_name'];
            $headerTemp->multiple_supplier          = $header['multiple_supplier'];
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
                $lineTemp->supplier_id              = $line['supplier'];
                $lineTemp->supplier_name            = $line['supplier_name'];
                $lineTemp->supplier_site            = $line['supplier_site'];
                $lineTemp->bank_account_number      = $line['supplier_bank'];
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
                $lineTemp->remaining_receipt_flag   = $line['remaining_receipt_flag'] == true? 'Y': 'N';
                $lineTemp->remaining_receipt_id     = $line['remaining_receipt'];
                $lineTemp->remaining_receipt_number = $this->getRemainingRceipt($line['remaining_receipt']);
                $lineTemp->save();
                
                // IF INTERFACE ERROR UPDATE HEADER STATUS TO PENDING
                // 1. CHECK BUDGET
                // $checkBudget = (new RequisitionHeader)->checkBudget($user, $headerTemp, $line);
                // if ($checkBudget->avaliable_budget == null) {
                //     $headerTemp->status = 'PENDING';
                //     $headerTemp->save();
                // }else{
                //     // 2. RESERVE BUDGET
                //     // 2.1 INSERT TEMP
                //     $temp = (new GLBudgetReservations)->insertGlReserve();
                //     // 2.2 CALL PACKAGE
                //     $result = (new RequisitionHeader)->reserveBudget($batch);
                // }
            }


            \DB::commit();
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

    public function show($id)
    {
        $requisition = RequisitionHeader::findOrFail($id);
        return view('expense::requisition.show', compact('requisition'));
    }

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
            $headerTemp->remaining_receipt_number   = $line['remaining_receipt'];
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
                                ->where('remaining_receipt_number', $line['remaining_receipt'])
                                ->where('seq_number', $request->index+1)
                                ->update([
                                    'remaining_receipt_number'  => $line['remaining_receipt']
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
                                            ->where('remaining_receipt_number', $line['remaining_receipt'])
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


}
