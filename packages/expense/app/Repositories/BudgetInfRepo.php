<?php

namespace Packages\expense\app\Repositories;

use App\Repositories\RequestRepo;
use Carbon\Carbon;

use Packages\expense\app\Models\RequisitionHeader;
use Packages\expense\app\Models\GLBudgetReservations;

class BudgetInfRepo {

	public function reserveBudget($requisition, $user)
    {
        $batchNo = 'RESV-'.date('Ymd').'-'.$requisition->req_number;
        \DB::beginTransaction();
        try {
            // INTERFACE HEADER
            foreach ($requisition->lines as $key => $line) {
                $reserve                     = new GLBudgetReservations;
                $reserve->reserve_date       = date('Y-m-d');
                $reserve->reserve_type       = 'RESERVE';
                $reserve->amount             = $line->amount;
                $reserve->description        = $requisition->req_number.' '.$line->description;
                $reserve->source_table_name  = $line->getTable();
                $reserve->source_table_id    = $line->id;
                $reserve->period_name        = date('M-y');
                $reserve->org_id             = $user->org_id;
                $reserve->account_code       = $line->expense_account;
                $reserve->batch_no           = $batchNo;
                $reserve->save();
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
