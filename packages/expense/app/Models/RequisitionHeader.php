<?php

namespace Packages\expense\app\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\User;

class RequisitionHeader extends Model
{
    protected $table = 'oagwxp_requisition_headers';
    protected $connection = 'oracle_oagwxp';
    protected $dates = ['req_date', 'clear_date'];
    protected $appends = ['status_icon', 'status_text', 'req_date_format'];
    public $timestamps = true;

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function lines()
    {
        return $this->hasMany(RequisitionLine::class, 'req_header_id', 'id');
    }

    public function clear()
    {
        return $this->hasOne(self::class, 'id', 'clear_reference_id');
    }

    public function invoice()
    {
        return $this->hasOne(InvoiceHeader::class, 'id', 'invoice_reference_id');
    }

    public function invoiceType()
    {
        return $this->hasOne(InvoiceType::class, 'lookup_code', 'invoice_type');
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class, 'vendor_id', 'supplier_id');
    }

    public function budgetSource()
    {
        return $this->hasOne(FlexValueV::class, 'flex_value', 'budget_source')->where('flex_value_set_name', 'OAG_GL_BUDGET_SOURCE');
    }

    public function paymentType()
    {
        return $this->hasOne(LookupV::class, 'lookup_code', 'payment_type')->where('lookup_type', 'OAG_AP_PAYMENT_TYPE')->select('description');
    }

    public function cashBankAccount()
    {
        return $this->hasOne(BankAccount::class, 'bank_account_id', 'cash_bank_account_id');
    }

    public function getInvRef($invType)
    {
        if($invType == 'PREPAYMENT') {
            return 'ADV';
        }else {
            return 'INV';
        }
    }

    public function scopeByRelatedUser($query)
    {
        $user = \Auth::user();
        return $query->where('created_by', $user->id);
    }
    

    public static function genDocumentNo($orgId, $prefix)
    {
        $date = now()->addYear(543)->format('y');
        do {
            $runningTranId = \Packages\expense\app\Models\TransactionSeq::getTranID(
                $orgId,
                'Packages\expense\app\Models\RequisitionHeader',
                $date,
                $prefix
            );
            $doc = $date.'-'.$prefix.'-'.str_pad($runningTranId, 10, '0', STR_PAD_LEFT);
        } while(self::checkDupDocumentNo($orgId, $doc));

        $result = $doc;
        return $result;
    }

    private static function checkDupDocumentNo($orgId,$invDocNo)
    {
        return false;
    }

    public function getStatusTextAttribute()
    {
        return $this->getStatusText($this->status);
    }

    public function getStatusIconAttribute()
    {
        return $this->getStatusIcon($this->status);
    }

    function getStatusText()
    {
        $status = $this->status;
        $result = "";
        switch ($status) {
            case "COMPLETED":
                $result = "รอเบิกจ่าย";
                break;
            case "PENDING":
                $result = "รอจัดสรร";
                break;
            case "HOLD":
                $result = "รอตรวจสอบ";
                break;
            case "INTERFACED":
                $result = "เบิกจ่ายแล้ว";
                break;
            case "ERROR":
                $result = "เบิกจ่ายไม่สำเร็จ";
                break;
            case "REVERSED":
                $result = "กลับรายการบัญชีแล้ว";
                break;
            case "UNREVERSED":
                $result = "กลับรายการบัญชีไม่สำเร็จ";
                break;
            case "CANCELLED":
                $result = "ยกเลิก";
                break;
            default:
                $result = "รายการใหม่";
                break;
        }
        return $result;
    }

    function getStatusIcon()
    {
        $status = $this->status;
        $result = "";
        switch ($status) {
            case "COMPLETED":
                $result = "<span class='badge badge-success' style='padding: 5px; color: fff;'> รอเบิกจ่าย </span>";
                break;
            case "PENDING":
                $result = "<span class='badge badge-warning' style='padding: 5px; color: fff;'> รอจัดสรร </span>";
                break;
            case "HOLD":
                $result = "<span class='badge badge-warning' style='padding: 5px; background-color: #fda668; color: fff;'> รอตรวจสอบ </span>";
                break;
            case "INTERFACED":
                $result = "<span class='badge badge-primary' style='padding: 5px;'> เบิกจ่ายแล้ว </span>";
                break;
            case "ERROR":
                $result = "<span class='badge badge-danger' style='padding: 5px; background-color: #e3302f; color: fff;'> เบิกจ่ายไม่สำเร็จ </span>";
                break;
            case "REVERSED":
                $result = "<span class='badge badge-primary' style='padding: 5px; background-color: #129990; color: fff;'> กลับรายการบัญชีแล้ว </span>";
                break;
            case "UNREVERSED":
                $result = "<span class='badge badge-primary' style='padding: 5px; background-color: #bb3e00; color: fff;'> กลับรายการบัญชีไม่สำเร็จ </span>";
                break;
            case "CANCELLED":
                $result = "<span class='badge badge-danger' style='padding: 5px; color: fff;'> ยกเลิก </span>";
                break;
            default:
                $result = "<span class='badge badge-secondary' style='padding: 5px; color: fff;'> รายการใหม่ </span>";
                break;
        }
        return $result;
    }

    public function getReqDateFormatAttribute()
    {
        return date('d-m-Y', strtotime($this->req_date));
    }

    public function scopeSearch($q, $search)
    {
        // dd($search);
        $cols = ['req_number', 'invoice_type', 'status', 'payment_type'];
        foreach ($search as $key => $value) {
            $value = trim($value);
            if ($value) {
                if (in_array($key, $cols)) {
                    $q->where($key, 'like', "%$value%");
                } else if ($key == 'req_date') {
                    $date = date('Y-m-d', strtotime($value));
                    $q->whereDate('req_date', $date);
                }
            }
        }

        return $q;
    }

    public function checkBudget($headerTemp, $lineTemp, $user)
    {
        $date = date('d-m-Y', strtotime($headerTemp->req_date));
        $expenseAccount = $lineTemp->expense_account;
        $budget = \DB::connection('oracle')->table('DUAL')
                    ->selectRaw("oaggl_process.find_budget( p_org_id => {$user->org_id}
                                    , p_concatenated_segments   => '{$expenseAccount}'
                                    , p_date                    => to_date('{$date}','DD-MM-YYYY')
                                ) avaliable_budget")->first();

        return $budget;
    }

    public function interfaceGL($batch)
    {
        $db = \DB::connection('oracle')->getPdo();
        $sql = "
            declare
                l_status    varchar2(10);
                l_msg       varchar2(1000);
            begin
                OAGGL_JOURNAL_INF_PKG.MAIN( P_WEB_BATCH_NO  => '{$batch}'
                                            , X_STATUS      => :l_status
                                            , X_MESSAGE     => :l_msg
                                        );
                dbms_output.put_line('l_status : ' || :l_status); 
                dbms_output.put_line('l_msg : ' || :l_msg); 
            end;
        ";

        logger($sql);
        $stmt = $db->prepare($sql);
        $result = [];
        $stmt->bindParam(':l_status', $result['status'], \PDO::PARAM_STR, 20);
        $stmt->bindParam(':l_msg', $result['error_msg'], \PDO::PARAM_STR, 2000);
        $stmt->execute();

        return $result;
    }
}
