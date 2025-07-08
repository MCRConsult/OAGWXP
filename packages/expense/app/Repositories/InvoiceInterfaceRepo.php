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
use Packages\expense\app\Models\SupplierSite;

use Carbon\Carbon;
use DB;
use PDO;
use Excel;

class InvoiceInterfaceRepo {

	public function insertInterfaceAPInvoice($invoice)
	{
        $user = auth()->user();
        $batchNo = 'INV-'.date('Ymd').'-'.$invoice->invoice_number;
        $reqDate = $invoice->source_type == 'RECEIPT'? $invoice->invoice_date: $invoice->requisitions->first()->req_date;
        $attr14 = '';
        if ($invoice->source_type == 'RECEIPT') {
            if ($invoice->final_judgment == '' || $invoice->final_judgment == null || $invoice->final_judgment == 'No') {
               $attr14 = 'Remittance';
            }else{
                $attr14 = $invoice->invoice_number;
            }
        }

		\DB::beginTransaction();
		try {
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
            $headerInf->attribute1                  = $invoice->contact_date;
            $headerInf->attribute2                  = $invoice->gfmis_document_number;
            $headerInf->attribute3                  = $invoice->final_judgment;
            $headerInf->attribute4                  = $invoice->source_type == 'RECEIPT'
                                                        ? $invoice->receipt_number
                                                        : implode(',', $invoice->requisitions->pluck('req_number')->toArray());
            $headerInf->attribute5                  = date('Y-m-d', strtotime($reqDate));
            $headerInf->attribute14                 = $attr14;
            $headerInf->attribute15                 = $invoice->note;
            $headerInf->remittance_message1         = $invoice->source_type == 'RECEIPT'? $invoice->receipt_number: '';
            $headerInf->revenue_delivery_code       = $invoice->revenue_delivery_code;
            $headerInf->final_judgment_number       = $invoice->final_judgment == 'Yes' && $invoice->source_type == 'RECEIPT'
                                                        ? $invoice->final_judgment_number: ''; // AP_INVOICE_NO
            $headerInf->web_batch_no                = $batchNo;
            $headerInf->creation_date               = Carbon::now();
            $headerInf->last_update_date            = Carbon::now();
            $headerInf->created_by                  = $user->person_id;
            $headerInf->last_updated_by             = $user->person_id;
            $headerInf->save();

            // INTERFACE LINES
            foreach ($invoice->lines as $key => $line) {
                // GET SUPPLER ACCOUNT W/ SUPPLIER_ID LINE LEVEL
                $expAccount = $line->expense_account;
                if ($invoice->invoice_type == 'PREPAYMENT') {
                    $supplier = SupplierSite::where('vendor_id', $line->supplier_id)->first();
                    $expAccount = $supplier->prepayment_account;
                }

                $lineInf                            = new InvoiceInterfaceLine;
                $lineInf->invoice_num               = $invoice->invoice_number;
                $lineInf->line_number               = $line->seq_number;
                $lineInf->line_type_lookup_code     = 'ITEM';
                $lineInf->accounting_date           = date('Y-m-d', strtotime($invoice->invoice_date));
                $lineInf->amount                    = $line->amount;
                $lineInf->wht_code                  = $line->wht_code;
                $lineInf->vat_code                  = $line->tax_code;
                $lineInf->description               = $line->description;
                $lineInf->distributrion_account     = $expAccount;  // $line->expense_account;
                $lineInf->attribute1                = $line->supplier_name;
                $lineInf->attribute2                = $line->bank_account_number;
                $lineInf->attribute3                = $line->remaining_receipt_number;
                $lineInf->attribute4                = $line->ar_receipt_number;
                $lineInf->attribute7                = $line->utility_type;
                $lineInf->attribute8                = $line->utility_detail;
                $lineInf->attribute9                = $line->req_invoice_number;
                $lineInf->attribute11               = $line->req_invoice_date? date('Y-m-d', strtotime($line->req_invoice_date)): '';
                $lineInf->attribute12               = $line->unit_quantity;
                $lineInf->attribute13               = $line->req_receipt_date? date('Y-m-d', strtotime($line->req_receipt_date)): '';
                $lineInf->attribute14               = $line->req_receipt_number;
                $lineInf->attribute15               = $line->contract_number;
                $lineInf->global_attribute1         = $line->vehicle_number;
                $lineInf->global_attribute2         = $line->policy_number;
                $lineInf->global_attribute3         = $line->vehicle_oil_type;
                $lineInf->perpay_invoice_number     = optional(optional($invoice->requisition)->clear)->invioce_number_ref;
                $lineInf->web_batch_no              = $batchNo;
                $lineInf->creation_date             = Carbon::now();
                $lineInf->last_update_date          = Carbon::now();
                $lineInf->created_by                = $user->person_id;
                $lineInf->last_updated_by           = $user->person_id;
                $lineInf->save();
            }
			\DB::commit();

            // ======== CALL PACKAGE
            $result = (new InvoiceHeader)->callInterfaceAPInvoice($batchNo);
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
