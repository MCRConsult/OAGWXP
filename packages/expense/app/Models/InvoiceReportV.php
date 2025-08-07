<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class InvoiceReportV extends Model
{
    protected $table = 'oagwxp_invoice_reports_view';
    protected $connection = 'oracle_oagwxp';

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function scopeSearch($q, $search)
    {
        $invDateFrom = $search->invoice_date_from ?? null;
        $invDateTo = $search->invoice_date_to ?? null;
        // INVOICE NUMBER
        if ($search->invoice_number_from && $search->invoice_number_to) {
            $q->whereBetween('invoice_number', [$search->invoice_number_from, $search->invoice_number_to]);
        }elseif ($search->invoice_number_from && !$search->invoice_number_to) {
            $q->where('invoice_number', $search->invoice_number_from);
        }
        // VOUCHER NUMBER
        if ($search->voucher_number_from && $search->voucher_number_to) {
            $q->whereBetween('voucher_number', [$search->voucher_number_from, $search->voucher_number_to]);
        }elseif ($search->voucher_number_from && !$search->voucher_number_to) {
            $q->where('voucher_number', $search->voucher_number_from);
        }
        // SUPPLIER
        if ($search->supplier_from && $search->supplier_to) {
            $q->whereBetween('supplier_id', [$search->supplier_from, $search->supplier_to]);
        }elseif ($search->supplier_from && !$search->supplier_to) {
            $q->where('supplier_id', $search->supplier_from);
        }
        // INVOICE DATE
        if ($search->invoice_date_from && $search->invoice_date_to) {
            $q->whereRaw("trunc(invoice_date) >= TO_DATE('{$invDateFrom}','YYYY-mm-dd')")
                ->whereRaw("trunc(invoice_date) <= TO_DATE('{$invDateTo}','YYYY-mm-dd')");
        }elseif ($search->invoice_date_from && !$search->invoice_date_to) {
            $q->whereRaw("trunc(invoice_date) >= TO_DATE('{$invDateFrom}','YYYY-mm-dd')");
        }
               
        return $q;
    }
}
