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
        $invDateFrom = request()->invoice_date_from ?? date('Y-m-d');
        $invDateTo = request()->invoice_date_to ?? date('Y-m-d');
        // REQ NUMBER
        if (request()->req_number_from && request()->req_number_to) {
            $q->whereBetween('req_number', [request()->req_number_from, request()->req_number_to]);
        }elseif (request()->req_number_from && !request()->req_number_to) {
            $q->where('req_number', request()->req_number_from);
        }
        // SUPPLIER
        if (request()->supplier_from && request()->supplier_to) {
            $q->whereBetween('supplier_id', [request()->supplier_from, request()->supplier_to]);
        }elseif (request()->supplier_from && !request()->supplier_to) {
            $q->where('supplier_id', request()->supplier_from);
        }
        // INVOICE DATE
        if (request()->invoice_date_from && request()->invoice_date_to) {
            $q->whereRaw("trunc(invoice_date) >= TO_DATE('{$invDateFrom}','YYYY-mm-dd')")
                ->whereRaw("trunc(invoice_date) <= TO_DATE('{$invDateTo}','YYYY-mm-dd')");
        }elseif (request()->invoice_date_from && !request()->invoice_date_to) {
            $q->whereRaw("trunc(invoice_date) >= TO_DATE('{$invDateFrom}','YYYY-mm-dd')");
        }
               
        return $q;
    }
}
