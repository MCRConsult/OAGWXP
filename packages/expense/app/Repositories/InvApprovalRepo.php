<?php

namespace Packages\expense\app\Repositories;

use App\Repositories\RequestRepo;


use Packages\inventory\app\Models\PtwinvApproval as Approval;
use Packages\inventory\app\Models\User;
use App\Employee;
use App\PositionInfo;
use App\POHierarchy;
use App\Job;
use App\Preference;


use Packages\inventory\app\Models\PtinvGenAmeTran;
use Packages\inventory\app\Models\PtinvAmeApproversList;
use DB;
use PDO;

class InvApprovalRepo
{
    public function approve($parent, $processType, $approverType, $userId = null)
    {
        if(!$userId) {
            $user = \Auth::user();
            $userId = $user->id;
        }else {
            $user = User::find($userId);
        }

        $approval = new Approval();
        $approval->user_id  = $userId;
        $approval->user_name  = $user->name;
        $approval->process_type = $processType;
        $approval->approver_type = $approverType;
        $lastApprover = self::getLastApprover($parent, $processType, $approverType);
        if($lastApprover){
            $approval->hierarchy_level = (int)$lastApprover->hierarchy_level + 1;
        }else{
            $approval->hierarchy_level = 1;
        }
        $parent->approvals()->save($approval);
    }

    public function reset($parent, $processType, $approverType = null)
    {
        $query = Approval::where('approvalable_id',$parent->id)
            ->where('process_type',$processType);
        if($approverType){
            $query->where('approver_type',$approverType);
        }
        $approvals = $query->get();
        foreach($approvals as $approval){
            $approval->delete();
        }
    }

    public static function getNextApprover($parent, $processType, $refreshPkgFlag = false)
    {
        $result = [];
        $userId = [];
        $transId = $parent->trans->id;

        $oldApprover = self::getApproverAll($transId);
        if ($refreshPkgFlag) {
            $resultGenApprovers = self::genApprovers($transId);
            if ($resultGenApprovers['message']) {
                throw new \Exception($resultGenApprovers['message'], 1);
            }
        }
        $findApprover = self::getApproverAll($transId);

        // $approver = $parent->approver;
        if ($parent->prepare_next_approver_id) {
            $userPersonId = auth()->user()->oracle_person_id;
            $findCurrentAppr = $findApprover->where('orig_system_id', $userPersonId)->first();
            if(!$findCurrentAppr){
                $findCurrentAppr = $oldApprover->where('orig_system_id', $userPersonId)->first();
                $findCurrentAppr = $findApprover->where('approver_order_number', $findCurrentAppr->approver_order_number)->first();
            }else {
                $findCurrentAppr = $findApprover->where('approver_order_number', '>', $findCurrentAppr->approver_order_number)->first();
            }
            $nextApproverOrderNumber = optional($findCurrentAppr)->approver_order_number ?? null;
        } else {
            if (count($findApprover) == 0) {
                throw new \Exception("Error : Not found next approver user data, please contact administrator to solve this issue.", 1);
            }
            $nextApproverOrderNumber = $findApprover->first()->approver_order_number;
        }
        if (!$nextApproverOrderNumber) {
            return $result;
        }

        foreach ($findApprover->where('approver_order_number', $nextApproverOrderNumber) as $key => $emp) {
            $nextApprover = User::findByOraclePersonId($emp->orig_system_id);
            $userId[] = $nextApprover->id;
        }

        $result = ['user_id' => $userId];
        return $result;
    }

    public static function getRelatedApprovers($parent,$processType,$approverType)
    {
        $query = Approval::where('approvalable_id',$parent->id)
                        ->where('process_type',$processType);
        if($approverType){
            $query->where('approver_type',$approverType);
        }
        $approvalUserIds = $query->pluck('user_id');
        if(count($approvalUserIds)>0){
            return User::whereIn('id',$approvalUserIds)->get();
        }else{
            return ;
        }
    }

    public static function validateTopHierarchyLimitAmount($amount,$versionNumber,$subordinatePositionId,$orgId)
    {
        // IF NOT FOUND SUBORDINATE AS PARENT POSITION HIERARCHY => IS BOTTOM CHILD POSITION HIERARCHY
        $topHierarchy = POHierarchy::getTopApprovalLimitAmount(POHierarchy::getApprovalHierarchyName(),POHierarchy::getApprovalControlFunctionName(),$versionNumber,$subordinatePositionId,$orgId);
        if($topHierarchy['status'] == 'E'){
            throw new \Exception("Error : ".$topHierarchy['err_msg'].", please contact administrator to solve this issue.", 1);
        }
        $topPositionId = $topHierarchy['max_position_id'];

        $topHierarchyPositionInfo = PositionInfo::expensePosition($versionNumber)->where('parent_pos_id',$topPositionId)->first();

        // IF AMOUNT REQUEST MORE THAN TOP HIERARCHY LIMIT AMOUNT => NOT ALLOW TO REQUEST
        if((float)$amount > (float)$topHierarchyPositionInfo->parent_amount_limit){
            throw new \Exception("Error : Amount request (".number_format($amount,2).") is over top hierarchy position limit amount (".number_format($topHierarchyPositionInfo->parent_amount_limit,2)."), please contact administrator to solve this issue.", 1);
        }
    }

    public static function getLastApprover($parent, $processType, $approverType = null)
    {
        $query = Approval::where('approvalable_id',$parent->id)
                        ->where('process_type',$processType);
        if($approverType){
            $query->where('approver_type',$approverType);
        }
        $approvals = $query->get();
        if(count($approvals) > 0){
            $lastApprover = $approvals->sortByDesc('hierarchy_level')->values()->first();
            return $lastApprover;
        }else{
            return ;
        }
    }

    public static function genApprovers($transId, $transactionTypeName)
    {
        $id = date('YmdHis') . rand(100,999);
        $getAmeTable = (new PtinvGenAmeTran)->getTable();
        $sql = "
            INSERT into $getAmeTable(id, transaction_type_id, transaction_type_name, record_status, record_msg, created_at)
            values($id, $transId, '$transactionTypeName', 'X', null, sysdate)"
        ;
        DB::connection('oracle_inv')->statement($sql);

        $getAme = PtinvGenAmeTran::where('id', $id)->first();
        if (!$getAme) {
            $result['status'] = 'E';
            $result['message'] = 'not found';
        } else {
            $result['status'] = $getAme->record_status;
            $result['message'] = $getAme->record_msg;
        }
        return $result;
    }

    public static function getApproverAll($transId)
    {
        $data = PtinvAmeApproversList::selectRaw("distinct name, orig_system_id, approver_order_number")
                ->with(['employee', 'user'])
                ->whereNull('approval_status')
                ->where('transaction_id', $transId)
                ->orderBy('approver_order_number')
                ->get();
        foreach ($data ?? [] as $key => $approver) {
            if (!$approver->user) {
                (new \Packages\inventory\app\Models\User)->syncUser($approver->name);
            }
        }
        return $data;
    }

    public function getApprovers($parent)
    {
        if (count($parent->next_approver_id ?? [])) {
            return \App\User::whereIn('id', $parent->next_approver_id)->get();
        }
        return [];
    }

    public function getApproversName($parent)
    {
        $name = '';
        foreach ($this->getApprovers($parent) as $key => $user) {
            if ($key == 0 ) {
                $name .= $user->name;
            } else {
                $name .= ", $user->name";
            }
        }
        return $name ?? '-';
    }

    public function checkNextApprover($parent)
    {
        $userId = \Auth::user()->id;
        return in_array($userId, $parent->next_approver_id ?? []);
    }

    public function getClearApprovers($parent)
    {
        if (count($parent->next_clearing_approver_id ?? [])) {
            return \App\User::whereIn('id', $parent->next_clearing_approver_id)->get();
        }
        return [];
    }

    public function getClearApproversName($parent)
    {
        $name = '';
        foreach ($this->getClearApprovers($parent) as $key => $user) {
            if ($key == 0 ) {
                $name .= $user->name;
            } else {
                $name .= ", $user->name";
            }
        }
        return $name ?? '-';
    }

    public function checkNextClearApprover($parent)
    {
        $userId = \Auth::user()->id;
        return in_array($userId, $parent->next_clearing_approver_id ?? []);
    }

}
