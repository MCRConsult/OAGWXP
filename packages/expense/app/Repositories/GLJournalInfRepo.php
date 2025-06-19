<?php

namespace Packages\expense\app\Repositories;

use App\Repositories\RequestRepo;
use Illuminate\Support\Str;

use Packages\expense\app\Models\RequisitionHeader;
use Packages\expense\app\Models\RequisitionLine;
use Packages\expense\app\Models\GLJournalInterface;
use Packages\expense\app\Models\GLLedger;

use Carbon\Carbon;
use DB;
use PDO;
use Excel;

class GLJournalInfRepo {

	public function insertInterface($requistion)
	{
        $user = auth()->user();
        $batchNo = 'GL-'.date('Ymd').'-'.$requistion->req_number;
        $ledger = GLLedger::first();
		\DB::beginTransaction();
		try {
            // INTERFACE GL JOURNAL
            foreach ($requistion->lines as $key => $line) {
                // EXPLODE SEGMENT
                $coa = explode('.', $line->expense_account);
                $glDrInf                           = new GLJournalInterface;
                $glDrInf->ledger_id                = $ledger->ledger_id;
                $glDrInf->default_effective_date   = date('Y-m-d', strtotime($requistion->req_date));
                $glDrInf->currency_code            = 'THB';
                $glDrInf->actual_flag              = 'A';
                $glDrInf->je_category              = '';
                $glDrInf->user_je_category_name    = 'เงินเดือน';
                $glDrInf->je_source                = '';
                $glDrInf->user_je_source_name      = '';
                $glDrInf->currency_conversion_date = '';
                $glDrInf->currency_conversion_type = '';
                $glDrInf->currency_conversion_rate = '';
                $glDrInf->segment1                 = $coa[0];
                $glDrInf->segment2                 = $coa[1];
                $glDrInf->segment3                 = $coa[2];
                $glDrInf->segment4                 = $coa[3];
                $glDrInf->segment5                 = $coa[4];
                $glDrInf->segment6                 = $coa[5];
                $glDrInf->segment7                 = $coa[6];
                $glDrInf->segment8                 = $coa[7];
                $glDrInf->segment9                 = $coa[8];
                $glDrInf->segment10                = $coa[9];
                $glDrInf->segment11                = $coa[10];
                $glDrInf->segment12                = $coa[11];
                $glDrInf->segment13                = $coa[12];
                $glDrInf->entered_cr               = 0;
                $glDrInf->entered_dr               = $line->amount;
                $glDrInf->accounted_cr             = 0;
                $glDrInf->accounted_dr             = $line->amount;
                $glDrInf->reference1               = $batchNo;
                $glDrInf->reference2               = $requistion->req_number;
                $glDrInf->reference3               = $batchNo;
                $glDrInf->reference4               = $batchNo;
                $glDrInf->reference5               = $requistion->description;
                $glDrInf->reference6               = '';
                $glDrInf->reference7               = '';
                $glDrInf->reference8               = '';
                $glDrInf->reference9               = '';
                $glDrInf->reference10              = $line->description;
                $glDrInf->batch_period_name_qry    = '';
                $glDrInf->chart_of_accounts_id     = '';
                $glDrInf->code_combination_id      = '';
                $glDrInf->save();
            }

            // LIST CR BY BANK ACCOUNT
            $cashAccount = optional($requistion->cashBankAccount)->cash_acc;
            if ($cashAccount) {
                $coa = explode('.', $cashAccount);
                $glCrInf                           = new GLJournalInterface;
                $glCrInf->ledger_id                = $ledger->ledger_id;
                $glCrInf->default_effective_date   = date('Y-m-d', strtotime($requistion->req_date));
                $glCrInf->currency_code            = 'THB';
                $glCrInf->actual_flag              = 'A';
                $glCrInf->je_category              = '';
                $glCrInf->user_je_category_name    = 'เงินเดือน';
                $glCrInf->je_source                = '';
                $glCrInf->user_je_source_name      = '';
                $glCrInf->currency_conversion_date = '';
                $glCrInf->currency_conversion_type = '';
                $glCrInf->currency_conversion_rate = '';
                $glCrInf->segment1                 = $coa[0];
                $glCrInf->segment2                 = $coa[1];
                $glCrInf->segment3                 = $coa[2];
                $glCrInf->segment4                 = $coa[3];
                $glCrInf->segment5                 = $coa[4];
                $glCrInf->segment6                 = $coa[5];
                $glCrInf->segment7                 = $coa[6];
                $glCrInf->segment8                 = $coa[7];
                $glCrInf->segment9                 = $coa[8];
                $glCrInf->segment10                = $coa[9];
                $glCrInf->segment11                = $coa[10];
                $glCrInf->segment12                = $coa[11];
                $glCrInf->segment13                = $coa[12];
                $glCrInf->entered_cr               = $requistion->total_amount;
                $glCrInf->entered_dr               = 0;
                $glCrInf->accounted_cr             = $requistion->total_amount;
                $glCrInf->accounted_dr             = 0;
                $glCrInf->reference1               = $batchNo;
                $glCrInf->reference2               = $requistion->req_number;
                $glCrInf->reference3               = $batchNo;
                $glCrInf->reference4               = $batchNo;
                $glCrInf->reference5               = $requistion->description;
                $glCrInf->reference6               = '';
                $glCrInf->reference7               = '';
                $glCrInf->reference8               = '';
                $glCrInf->reference9               = '';
                $glCrInf->reference10              = $line->description;
                $glCrInf->batch_period_name_qry    = '';
                $glCrInf->chart_of_accounts_id     = '';
                $glCrInf->code_combination_id      = '';
                $glCrInf->save();
            }
			\DB::commit();
            // CALL PACKAGE
            $result = (new RequisitionHeader)->interfaceGL($batchNo);
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
