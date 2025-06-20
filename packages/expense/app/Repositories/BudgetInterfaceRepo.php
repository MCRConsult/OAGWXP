<?php

namespace Packages\expense\app\Repositories;

use App\Repositories\RequestRepo;
use Carbon\Carbon;

use Packages\expense\app\Models\GLAccountHierarchyV;
use Packages\expense\app\Models\RequisitionHeader;
use Packages\expense\app\Models\GLBudgetReservations;

class BudgetInterfaceRepo {

	public function reserveBudget($requisition, $user)
    {
        $batchNo = 'RESV-'.date('Ymd').'-'.$requisition->req_number;
        \DB::beginTransaction();
        try {
            // INTERFACE HEADER
            foreach ($requisition->lines as $key => $line) {
                $budgetAvaliable = (new GLAccountHierarchyV)->findFund($user->org_id, $line->expense_account);
                if ($budgetAvaliable != null) {
                    $reserve                     = new GLBudgetReservations;
                    $reserve->reserve_date       = Carbon::now();
                    $reserve->reserve_type       = 'RESERVE';
                    $reserve->amount             = $line->amount;
                    $reserve->description        = $requisition->req_number.' '.$line->description;
                    $reserve->source_table_name  = $line->getTable();
                    $reserve->source_table_id    = $line->id;
                    $reserve->period_name        = strtoupper(date('M-y'));
                    $reserve->org_id             = $user->org_id;
                    $reserve->account_code       = $line->expense_account;
                    $reserve->batch_no           = $batchNo;
                    $reserve->user_je_source_name = 'Web Encumbrance';
                    $reserve->save();
                }
            }
            \DB::commit();
            // CALL PACKAGE
            $result = (new RequisitionHeader)->callReserveBudget($batchNo);
            $data = [
                'status' => $result['status'],
                'message' => $result['error_msg'],
            ];
        } catch (\Exception $e) {
            \DB::rollback();
            throw new \Exception($e->getMessage(), 1);
            $data = [
                'status' => 'E',
                'message' => $e->getMessage(),
            ];
        }
        return $data;
    }

    // UNRESERVE FOR REQUISITION
    public function unreserveBudgetREQ($requisition, $user)
    {
        $batchNo = 'UNRESV-'.date('Ymd').'-'.$requisition->req_number;
        \DB::beginTransaction();
        try {
            // INTERFACE HEADER
            foreach ($requisition->lines as $key => $line) {
                $budgetAvaliable = (new GLAccountHierarchyV)->findFund($user->org_id, $line->expense_account);
                if ($budgetAvaliable != null) {
                    $unreserve                     = new GLBudgetReservations;
                    $unreserve->reserve_date       = Carbon::now();
                    $unreserve->reserve_type       = 'UNRESERVE';
                    $unreserve->amount             = $line->amount;
                    $unreserve->description        = $requisition->req_number.' '.$line->description;
                    $unreserve->source_table_name  = $line->getTable();
                    $unreserve->source_table_id    = $line->id;
                    $unreserve->period_name        = strtoupper(date('M-y'));
                    $unreserve->org_id             = $user->org_id;
                    $unreserve->account_code       = $line->expense_account;
                    $unreserve->batch_no           = $batchNo;
                    $unreserve->user_je_source_name = 'Web Encumbrance';
                    $unreserve->save();
                }
            }
            \DB::commit();
            // CALL PACKAGE
            $result = (new RequisitionHeader)->callReserveBudget($batchNo);
            $data = [
                'status' => $result['status'],
                'message' => $result['error_msg'],
            ];
        } catch (\Exception $e) {
            \DB::rollback();
            throw new \Exception($e->getMessage(), 1);
            $data = [
                'status' => 'E',
                'message' => $e->getMessage(),
            ];
        }
        return $data;
    }

    // UNRESERVE FOR INVOICE
    public function unreserveBudgetINV($invoice, $user)
    {
        $batchNo = 'UNRESV-'.date('Ymd').'-'.$invoice->invoice_number;
        \DB::beginTransaction();
        try {
            // INTERFACE HEADER
            foreach ($invoice->requisitions as $key => $requisition) {
                foreach ($requisition->lines as $key => $line) {
                    $budgetAvaliable = (new GLAccountHierarchyV)->findFund($user->org_id, $line->expense_account);
                    if ($budgetAvaliable != null) {
                        $unreserve                     = new GLBudgetReservations;
                        $unreserve->reserve_date       = Carbon::now();
                        $unreserve->reserve_type       = 'UNRESERVE';
                        $unreserve->amount             = $line->amount;
                        $unreserve->description        = $requisition->req_number.' '.$line->description;
                        $unreserve->source_table_name  = $line->getTable();
                        $unreserve->source_table_id    = $line->id;
                        $unreserve->period_name        = strtoupper(date('M-y'));
                        $unreserve->org_id             = $user->org_id;
                        $unreserve->account_code       = $line->expense_account;
                        $unreserve->batch_no           = $batchNo;
                        $unreserve->user_je_source_name = 'Web Encumbrance';
                        $unreserve->save();
                    }
                }
            }
            \DB::commit();
            // CALL PACKAGE
            $result = (new RequisitionHeader)->callReserveBudget($batchNo);
            $data = [
                'status' => $result['status'],
                'message' => $result['error_msg'],
            ];
        } catch (\Exception $e) {
            \DB::rollback();
            throw new \Exception($e->getMessage(), 1);
            $data = [
                'status' => 'E',
                'message' => $e->getMessage(),
            ];
        }
        return $data;
    }
}
