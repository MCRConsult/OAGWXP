<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $sql = "CREATE OR REPLACE VIEW oagwxp_invoice_reports_view AS
                select
                    req.id                          requisition_id
                    , req.org_id
                    , req.req_number
                    , req.req_date
                    , req.multiple_supplier
                    , req.invoice_reference_id

                    , reql.id                       requisition_line_id
                    , reql.req_header_id
                    , reql.invl_reference_id

                    , invh.id                       invoice_id
                    , invh.source_type
                    , invh.voucher_number
                    , invh.invoice_number
                    , invh.invoice_date
                    , invh.status                   invoice_status
                    , invh.created_by
                    , invh.gfmis_document_number
                    , invh.note
                    
                    , invl.id                       invoice_line_id
                    , invl.invoice_header_id      
                    , invl.seq_number
                    , invl.supplier_id
                    , invl.supplier_name
                    , invl.expense_account
                    , invl.amount
                    , invl.description

                    from oagwxp_requisition_headers     req
                        , oagwxp_requisition_lines      reql
                        , oagwxp_invoice_headers        invh
                        , oagwxp_invoice_lines          invl
                    where 1=1
                    and invh.id     = invl.invoice_header_id
                    and invh.id     = req.invoice_reference_id(+)
                    and invl.id     = reql.invl_reference_id
                    and req.id      = reql.req_header_id(+)"
                ;

            DB::connection('oracle_oagwxp')->statement($sql);
    }

    public function down(): void
    {
        $sql = "DROP VIEW oagwxp_invoice_reports_view";
        DB::connection('oracle_oagwxp')->statement($sql);
    }
};
