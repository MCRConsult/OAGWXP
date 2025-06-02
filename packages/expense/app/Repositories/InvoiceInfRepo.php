<?php

namespace Packages\expense\app\Repositories;

use App\Repositories\RequestRepo;
use Illuminate\Support\Str;

use Packages\expense\app\Models\RequisitionHeader;
use Packages\expense\app\Models\RequisitionLine;
use Packages\expense\app\Models\InvoiceHeader;
use Packages\expense\app\Models\InvoiceLine;
use Packages\expense\app\Models\InvoiceInterfaceHeader;
use Packages\expense\app\Models\InvoiceInterfaceLine;

use Carbon\Carbon;
use DB;
use PDO;
use Excel;

class InvoiceInfRepo {

	public function insertInterface($invoice)
	{
        $user = auth()->user();
        $batchNo = 'INV_'.date('YmdHis').Str::random(3);
		\DB::beginTransaction();
		try {
            // INTERFACE HEADER
            $headerInf                              = new InvoiceInterfaceHeader;
            $headerInf->invoice_num                 = $invoice->invoice_number;
            $headerInf->source                      = 'Manual Invoice Entry';
            $headerInf->invoice_type_lookup_code    = $invoice->invoice_type;
            $headerInf->doc_category_code           = $invoice->document_category;
            $headerInf->voucher_num                 = '';
            $headerInf->org_name                    = $invoice->organizationV->name;
            $headerInf->supplier_num                = $invoice->supplierSite->segment1;
            $headerInf->supplier_name               = $invoice->supplier_name;
            $headerInf->supplier_site               = $invoice->supplierSite->vendor_site_code;
            $headerInf->invoice_date                = date('Y-m-d', strtotime($invoice->invoice_date));
            $headerInf->gl_date                     = date('Y-m-d', strtotime($invoice->invoice_date));
            $headerInf->invoice_currency_code       = $invoice->currency;
            $headerInf->invoice_amount              = $invoice->total_amount;
            $headerInf->exchange_rate_type          = '';
            $headerInf->exchange_rate               = '';
            $headerInf->description                 = $invoice->description;
            $headerInf->payment_method_code         = $invoice->payment_method;
            $headerInf->terms_name                  = $invoice->paymentTerm->description;
            $headerInf->liability_account           = $invoice->supplierSite->liability_account; //---SUPPLIER
            $headerInf->attribute4                  = ''; //---REQ_NUMBER 
            $headerInf->attribute5                  = date('Y-m-d', strtotime($invoice->invoice_date));
            $headerInf->attribute15                 = '';
            $headerInf->web_batch_no                = $batchNo;
            $headerInf->creation_date               = Carbon::now();
            $headerInf->last_update_date            = Carbon::now();
            $headerInf->created_by                  = $user->person_id;
            $headerInf->last_updated_by             = $user->person_id;
            $headerInf->save();

            // INTERFACE LINES
            foreach ($invoice->lines as $key => $line) {
                $lineInf                            = new InvoiceInterfaceLine;
                $lineInf->invoice_num               = $invoice->invoice_number;
                $lineInf->line_number               = $line->seq_number;
                $lineInf->line_type_lookup_code     = 'ITEM';
                $lineInf->accounting_date           = date('Y-m-d', strtotime($invoice->invoice_date));
                $lineInf->amount                    = $line->amount;
                $lineInf->wht_code                  = $line->wht_code;
                $lineInf->vat_code                  = $line->tax_code;
                $lineInf->description               = $line->description;
                $lineInf->distributrion_account     = $line->expense_account;
                $lineInf->attribute4                = optional(optional($line->requisitionLine)->header)->req_number; //---REQ_NUMBER 
                $lineInf->web_batch_no              = $batchNo;
                $lineInf->creation_date             = Carbon::now();
                $lineInf->last_update_date          = Carbon::now();
                $lineInf->created_by                = $user->person_id;
                $lineInf->last_updated_by           = $user->person_id;
                $lineInf->save();
            }
			\DB::commit();
            // CALL PACKAGE
            // $result = (new InvoiceHeader)->interfaceAP($batchNo);
            // $data = [
            //     'status' => $result['status'],
            //     'message' => '',
            // ];
		} catch (\Exception $e) {
            \DB::rollback();
            throw new \Exception($e->getMessage(), 1);
            $data = [
                'status' => 'E',
                'message' => $e->getMessage(),
            ];
        }
        // return $data;
	}
}
