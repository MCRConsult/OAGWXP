<?php

namespace Packages\expense\app\Repositories;

use App\Repositories\RequestRepo;
use Carbon\Carbon;

use Packages\expense\app\Models\RequisitionHeader;
use Packages\expense\app\Models\GLBudgetReservations;

class BudgetInfRepo {

	public function insertGlReserve($header, $line, $user)
    {
        $batchNo = 'RESV_'.date('YmdHis').Str::random(3);
        \DB::beginTransaction();
        try {
            // INTERFACE HEADER
            $reserve                     = new GLBudgetReservations;
            $reserve->reserve_date       = date('Y-m-d');
            $reserve->reserve_type       = 'RESERVE';
            $reserve->amount             = $line->amount;
            $reserve->description        = $header->req_number.' '.$line->description;
            $reserve->source_table_name  = $line->getTable();
            $reserve->source_table_id    = $line->id;
            $reserve->period_name        = date('M-y');
            $reserve->org_id             = $user->org_id;
            $reserve->account_code       = $line->expense_account;
            $reserve->batch_no           = $batchNo;
            $reserve->save();

            \DB::commit();
            // CALL PACKAGE
            // $result = (new RequisitionHeader)->reserveBudget($batchNo);
            $data = [
                'status' => $result['status'],
                'message' => '',
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
