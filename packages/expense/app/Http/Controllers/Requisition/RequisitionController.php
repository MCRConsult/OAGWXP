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

class RequisitionController extends Controller
{
    public function index()
    {
        $requisitions = RequisitionHeader::search(request()->all())
                                    ->orderBy('req_number', 'desc')
                                    ->paginate(15);
        $invoiceTypes = InvoiceType::whereIn('lookup_code', ['STANDARD', 'PREPAYMENT'])->get();
        $statuses = ['DISBURSEMENT' => 'รอเบิกจ่าย'
                    , 'ALLOCATE'    => 'รอจัดสรร'
                    , 'CANCELLED'   => 'ยกเลิก'];

        return view('expense::requisition.index', compact('requisitions', 'invoiceTypes', 'statuses'));
    }

    public function create()
    {
        $referenceNo = date('YmdHis').'-'.Str::random(5);
        $invoiceTypes = InvoiceType::whereIn('lookup_code', ['STANDARD', 'PREPAYMENT'])->get();
        return view('expense::requisition.create', compact('invoiceTypes', 'referenceNo'));
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
            $headerTemp->org_id                     = 101;
            $headerTemp->reference_no               = $header['reference_no'];
            $headerTemp->budget_source              = $header['budget_source'];
            $headerTemp->invoice_type               = $header['invoice_type'];
            $headerTemp->document_category          = $header['document_category'];
            $headerTemp->req_number                 = (new RequisitionHeader)->genDocumentNo('101', $prefixReq[0]);
            $headerTemp->req_date                   = date('Y-m-d', strtotime($header['req_date']));
            $headerTemp->payment_type               = $header['payment_type'];
            $headerTemp->supplier_id                = $header['supplier'];
            $headerTemp->supplier_name              = $header['supplier_name'];
            $headerTemp->multiple_supplier          = $header['multiple_supplier'];
            $headerTemp->total_amount               = $request->totalApply;
            $headerTemp->status                     = 'DISBURSEMENT';
            $headerTemp->description                = $header['description'];
            $headerTemp->requester                  = $user->id;
            $headerTemp->created_by                 = $user->id;
            $headerTemp->updated_by                 = $user->id;
            $headerTemp->creation_by                = $user->person_id;
            $headerTemp->updation_by                = $user->person_id;
            $headerTemp->save();

            foreach ($lines as $key => $line) {
                // GET CONCATE SEGMENT
                $accountConcate = $this->concateAccount($line['expense_type'], $user, $header['budget_source'], $line['budget_plan'], $line['budget_type'], $header['document_category']);
                $lineTemp                           = new RequisitionLine;
                $lineTemp->req_header_id            = $headerTemp->id;
                $lineTemp->seq_no                   = $key+1;
                $lineTemp->supplier_id              = $line['supplier'];
                $lineTemp->supplier_name            = $line['supplier_name'];
                $lineTemp->bank_account_number      = $line['supplier_bank'];
                $lineTemp->budget_plan              = $line['budget_plan'];
                $lineTemp->budget_type              = $line['budget_type'];
                $lineTemp->expense_type             = $line['expense_type'];
                $lineTemp->expense_description      = $line['expense_description'];
                $lineTemp->amount                   = $line['amount'];
                $lineTemp->segment_account          = $accountConcate;
                $lineTemp->description              = $line['description'];
                $lineTemp->vehicle_no               = $line['vehicle_no'];
                $lineTemp->policy_no                = $line['policy_no'];
                $lineTemp->vehicle_oil_type         = $line['vehicle_oil_type'];
                $lineTemp->utility_type             = $line['utility_type'];
                $lineTemp->utility_detail           = $line['utility_detail'];
                $lineTemp->unit_quantity            = $line['unit_quantity'];
                $lineTemp->invoice_no               = $line['invoice_no'];
                $lineTemp->invoice_date             = $line['invoice_date']? date('Y-m-d', strtotime($line['invoice_date'])): '';
                $lineTemp->receipt_no               = $line['receipt_no'];
                $lineTemp->receipt_date             = $line['receipt_date']? date('Y-m-d', strtotime($line['receipt_date'])): '';
                $lineTemp->remaining_receipt_flag   = $line['remaining_receipt_flag'] == true? 'Y': 'N';
                $lineTemp->remaining_receipt_no     = $line['remaining_receipt'];
                $lineTemp->save();
            }
            // IF INTERFACE ERROR UPDATE HEADER STATUS TO ALLOCATE


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

    private function concateAccount($itemCate, $user, $budgetSource, $budgetPlan, $budgetType, $documentCategory)
    {
        $docCate = explode('_', $documentCategory);
        $employee = $user->hrEmployee;
        $accountRules = POExpenseAccountRuleV::where('item_category', $itemCate)
                                            ->orderBy('segment_num')
                                            ->get()
                                            ->pluck('segment_value', 'segment_num');

        $concatenatedSegments = '';
        $segments = [];
        // SEGMENT1
        $segments[1] = $employee->segment1;
        // SEGMENT2
        $segments[2] = $employee->segment2;
        // SEGMENT3
        $segments[3] = date('Y');
        // SEGMENT4
        $segments[4] = $budgetSource;
        // SEGMENT5-8
        $segments[5] = null;
        $segments[6] = null;
        $segments[7] = null;
        $segments[8] = null;
        if ($budgetSource == 510) {
            $segments[5] = $budgetSource;
            $segments[6] = $budgetSource;
            $segments[7] = $budgetSource;
            $segments[8] = $budgetSource;
        }elseif(in_array($budgetSource, [520, 530, 550])){
            $segments[5] = '00000';
            $segments[6] = $accountRules[6];
            $segments[7] = '00000';
            $segments[8] = '';
        }elseif($budgetSource == 540){
            $segments[5] = '00000';
            $segments[6] = '00000';
            $segments[7] = '00000';
            $segments[8] = '';
        }elseif($docCate[1] == 'สบพ'){
            $segments[5] = $budgetSource;
            $segments[6] = $budgetSource;
            $segments[7] = $budgetSource;
            $segments[8] = $accountRules[8];
        }else{
            $segments[5] = $budgetPlan;
            $segments[6] = '00000';
            $segments[7] = '00000';
            $segments[8] = $budgetType;
        }
        // SEGMENT9
        $segments[9] = '00000000000000000000';
        // SEGMENT10
        $segments[10] = $accountRules[10];
        // SEGMENT11
        $segments[11] = $accountRules[11];
        // SEGMENT12
        $segments[12] = '00';
        // SEGMENT13
        $segments[13] = '000';

        $concatenatedSegments = $segments[1].'.'.$segments[2].'.'.$segments[3].'.'.$segments[4].'.'.$segments[5].'.'.$segments[6].'.'.$segments[7].'.'.$segments[8].'.'.$segments[9].'.'.$segments[10].'.'.$segments[11].'.'.$segments[12].'.'.$segments[13];

        return $concatenatedSegments;
    }

    public function useARReceipt(Request $request)
    {
        $header = $request->header;
        $line = $request->line;
        $user = auth()->user();
        \DB::beginTransaction();
        try {
            $headerTemp                             = new RequisitionReceiptTemp;
            $headerTemp->org_id                     = 101;
            $headerTemp->reference_no               = $header['reference_no'];
            $headerTemp->seq_no                     = $request->seq+1;
            $headerTemp->remaining_receipt_flag     = $line['remaining_receipt_flag']? 'Y': 'N';
            $headerTemp->remaining_receipt_no       = $line['remaining_receipt'];
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
            RequisitionReceiptTemp::where('reference_no', $header['reference_no'])
                                ->where('remaining_receipt_no', $line['remaining_receipt'])
                                ->where('seq_no', $request->index+1)
                                ->update([
                                    'remaining_receipt_no' => $line['remaining_receipt']
                                    , 'amount'             => $line['amount']
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
            $receiptTemp = RequisitionReceiptTemp::where('reference_no', $header['reference_no'])
                                            ->where('remaining_receipt_no', $line['remaining_receipt'])
                                            ->where('seq_no', $request->index+1)
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
