<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $sql = "CREATE OR REPLACE VIEW oagwxp_ar_budget_receipts_view AS
                select * from apps.OAG_AR_BUDGET_RECEIPT_NUMBER_ID rec
                    WHERE NOT EXISTS
                   (
                        SELECT wrec.remaining_receipt_number, sum(wrec.amount) amount
                        FROM oagwxp_requisition_receipt_temps    wrec
                        WHERE 1=1
                        and wrec.remaining_receipt_number = rec.receipt_number
                        GROUP BY wrec.remaining_receipt_number
                        having sum(wrec.amount) >= rec.remaining_amount
                   )"
                ;

            DB::connection('oracle_oagwxp')->statement($sql);
    }

    public function down(): void
    {
        $sql = "DROP VIEW oagwxp_ar_budget_receipts_view";
        DB::connection('oracle_oagwxp')->statement($sql);
    }
};
