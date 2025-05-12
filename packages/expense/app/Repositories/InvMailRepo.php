<?php

namespace Packages\expense\app\Repositories;


use Mail;
use Validator;
use Packages\inventory\app\Models\User;

use Packages\inventory\app\Mail\InvApprovalMail;

class InvMailRepo
{
    // ####################
    // ### Request Type ###
    // CASH-ADVANCE
    // CLEARING
    // EMP-EXPENSE
    // INVOICE
    // PETTY-CASH
    // CASH-TRANSFER
    // ####################

    public static function sendRequest($requestType,$request,$receivers,$ccReceivers,$reason = null,$toFinanceDept = null)
    {
        if(count($receivers) > 0){
            $data = self::templateSendRequest($requestType,$request,$receivers,$reason,$toFinanceDept);
            // $data['userId'] = 1;
            // return view($data['view'])->with($data);
            foreach($receivers as $receiver){
                if($receiver['email']){
                    Mail::to($receiver['email'])->cc($ccReceivers)->queue(new InvApprovalMail($data, $receiver['user_id']));
                }
            }
        }
    }

    public static function approverProcess($requestType,$request,$actionType,$receivers,$ccReceivers,$reason = null,$toFinanceDept = null, $lastApproveOrReject = false)
    {
        if(count($receivers) > 0){
            $data = self::templateApproverProcess($requestType,$request,$actionType,$receivers,$reason,$toFinanceDept,$lastApproveOrReject);
            foreach($receivers as $receiver){
                if($receiver['email']){
                    Mail::to($receiver['email'])->cc($ccReceivers)->queue(new ApprovalMail($data, $receiver['user_id']));
                }
            }
        }
    }

    public static function financeProcess($requestType,$request,$actionType,$receivers,$ccReceivers,$reason = null, $lastApproveOrReject = false)
    {
        if(count($receivers) > 0){
            $data = self::templateFinanceProcess($requestType,$request,$actionType,$receivers,$reason,$lastApproveOrReject);
            foreach($receivers as $receiver){
                if($receiver['email']){
                    Mail::to($receiver['email'])->cc($ccReceivers)->queue(new ApprovalMail($data, $receiver['user_id']));
                }
            }
        }
    }

    public static function unblock($requestType,$request,$receivers,$ccReceivers,$reason)
    {
        if(count($receivers) > 0){
            $data = self::templateUnblock($requestType,$request,$receivers,$reason);
            foreach($receivers as $receiver){
                if($receiver['email']){
                    Mail::to($receiver['email'])->cc($ccReceivers)->queue(new ApprovalMail($data, $receiver['user_id']));
                }
            }
        }
    }

    private static function templateSendRequest($requestType,$request,$receivers,$reason,$toFinanceDept)
    {
        $documentNo = $requestType == 'CLEARING' ? $request->clearing_document_no : $request->document_no;
        $amount     = $requestType == 'CASH-ADVANCE' ? $request->amount : $request->total_receipt_amount_inc_wht;
        $amountFormatted = $amount ? number_format($amount,2) : '0.00';
        // $prefixMail = getPrefixMail($request);
        $prefixMail = 'DEV';
        // $mailSubject = $prefixMail.'-'.$documentNo.'-'.optional($request->user)->name.'-'.$amountFormatted.'-'.$request->purpose;
        $mailSubject = $prefixMail."-[$requestType][$request->clearing_document_no] Request for approval";
        $preparerName = optional($request->user)->name;

        $additionDesc = self::getAdditionDesc($request);
        $receiverNames = '';
        if($toFinanceDept){
            $receiverNames = 'Finance Dept.';
        }else{
            $receiverNames = $receivers ? implode(', '
                , array_map(function($receiver){
                return $receiver['name'];
            }, $receivers)) : '';
        }
        $emailDescription = '';
        $emailDescription = "คุณ $preparerName ได้ส่ง $prefixMail ให้คุณเพื่ออนุมัติ";
        if ($additionDesc) {
            $emailDescription = "คุณ $preparerName ได้ส่ง $prefixMail ($additionDesc) ให้คุณเพื่ออนุมัติ";
        }

        if ($requestType == 'PREPARE-PLAN') {
            $data = [
                'view'          =>  'inventory::emails.template_with_reason',
                // 'subject'       =>  '[PETTY-CASH]['.$request->document_no.'] Send Request',
                'subject'       =>  $mailSubject,
                'title'         =>  'PREPARE-PLAN #'.$request->document_no.' REQUEST WAS SENT.',
                'description'   =>  $emailDescription,
                'reason'        =>  $reason,
                'requestType'   =>  $requestType,
                'request'       =>  $request,
                'receiverNames' =>  $receiverNames
            ];
        }
        return $data;
        dd('CCCCC');

        // CASH-ADVANCE SEND REQUEST
        if($requestType == 'CASH-ADVANCE'){

            // if($toFinanceDept){
            //     $emailDescription = $request->user->name.' send cash advance request'. ($additionDesc ? ' but'.$additionDesc : '') .'.';
            // }else{
            //     $emailDescription = $request->user->name.' send cash advance request to you for approval'. ($additionDesc ? ' but'.$additionDesc : '') .'.';
            // }

            $data = [
                'view'          =>  'emails.template_with_reason',
                // 'subject'       =>  '[CASH ADVANCE]['.$request->document_no.'] Send Request',
                'subject'       =>  $mailSubject,
                'title'         =>  'CASH-ADVANCE #'.$request->document_no.' REQUEST WAS SENT.',
                'description'   =>  $emailDescription,
                'reason'        =>  $reason,
                'requestType'   =>  $requestType,
                'request'       =>  $request,
                'receiverNames' =>  $receiverNames
            ];
        }

        // CLEARING SEND REQUEST
        if($requestType == 'CLEARING'){

            // if($toFinanceDept){
            //     $emailDescription = $request->user->name.' send clearing cash advance request'. ($additionDesc ? ' but'.$additionDesc : '') .'.';
            // }else{
            //     $emailDescription = $request->user->name.' send clearing cash advance request to you for approval'. ($additionDesc ? ' but'.$additionDesc : '') .'.';
            // }

            $data = [
                'view'          =>  'emails.template_with_reason',
                // 'subject'       =>  '[CLEARING]['.$request->clearing_document_no.'] Send Request',
                'subject'       =>  $mailSubject,
                'title'         =>  'CLEARING #'.$request->clearing_document_no.' REQUEST WAS SENT.',
                'description'   =>  $emailDescription,
                'reason'        =>  $reason,
                'requestType'   =>  $requestType,
                'request'       =>  $request,
                'receiverNames' =>  $receiverNames
            ];
        }

        // EMP-EXPENSE SEND REQUEST
        if($requestType == 'EMP-EXPENSE'){

            // if($toFinanceDept){
            //     $emailDescription = $request->user->name.' send emp expense request'. ($additionDesc ? ' but'.$additionDesc : '') .'.';
            // }else{
            //     $emailDescription = $request->user->name.' send emp expense request to you for approval'. ($additionDesc ? ' but'.$additionDesc : '') .'.';
            // }

            $data = [
                'view'          =>  'emails.template_with_reason',
                // 'subject'       =>  '[EMP EXPENSE]['.$request->document_no.'] Send Request',
                'subject'       =>  $mailSubject,
                'title'         =>  'EMP EXPENSE #'.$request->document_no.' REQUEST WAS SENT.',
                'description'   =>  $emailDescription,
                'reason'        =>  $reason,
                'requestType'   =>  $requestType,
                'request'       =>  $request,
                'receiverNames' =>  $receiverNames
            ];
        }

        // INVOICE SEND REQUEST
        if($requestType == 'INVOICE'){

            // if($toFinanceDept){
            //     $emailDescription = $request->user->name.' send invoice request'. ($additionDesc ? ' but'.$additionDesc : '') .'.';
            // }else{
            //     $emailDescription = $request->user->name.' send invoice request to you for approval'. ($additionDesc ? ' but'.$additionDesc : '') .'.';
            // }

            $data = [
                'view'          =>  'emails.template_with_reason',
                // 'subject'       =>  '[INVOICE]['.$request->document_no.'] Send Request',
                'subject'       =>  $mailSubject,
                'title'         =>  'Invoice Non PO #'.$request->document_no.' REQUEST WAS SENT.',
                'description'   =>  $emailDescription,
                'reason'        =>  $reason,
                'requestType'   =>  $requestType,
                'request'       =>  $request,
                'receiverNames' =>  $receiverNames
            ];
        }

        // PETTY-CASH SEND REQUEST
        if($requestType == 'PETTY-CASH'){

            // if($toFinanceDept){
            //     $emailDescription = $request->user->name.' send petty cash request'. ($additionDesc ? ' but'.$additionDesc : '') .'.';
            // }else{
            //     $emailDescription = $request->user->name.' send petty cash request to you for approval'. ($additionDesc ? ' but'.$additionDesc : '') .'.';
            // }

            $data = [
                'view'          =>  'emails.template_with_reason',
                // 'subject'       =>  '[PETTY-CASH]['.$request->document_no.'] Send Request',
                'subject'       =>  $mailSubject,
                'title'         =>  'PETTY-CASH #'.$request->document_no.' REQUEST WAS SENT.',
                'description'   =>  $emailDescription,
                'reason'        =>  $reason,
                'requestType'   =>  $requestType,
                'request'       =>  $request,
                'receiverNames' =>  $receiverNames
            ];
        }

        // CASH-TRANSFER SEND REQUEST
        if($requestType == 'CASH-TRANSFER'){

            // if($toFinanceDept){
            //     $emailDescription = $request->user->name.' send cash transfer request'. ($additionDesc ? ' but'.$additionDesc : '') .'.';
            // }else{
            //     $emailDescription = $request->user->name.' send cash transfer request to you for approval'. ($additionDesc ? ' but'.$additionDesc : '') .'.';
            // }
            // logger($emailDescription);

            $data = [
                'view'          =>  'emails.template_with_reason',
                // 'subject'       =>  '[CASH-TRANSFER]['.$request->document_no.'] Send Request',
                'subject'       =>  $mailSubject,
                'title'         =>  'CASH-TRANSFER #'.$request->document_no.' REQUEST WAS SENT.',
                'description'   =>  $emailDescription,
                'reason'        =>  $reason,
                'requestType'   =>  $requestType,
                'request'       =>  $request,
                'receiverNames' =>  $receiverNames
            ];
        }

        return $data;
    }

    private static function templateApproverProcess($requestType,$request,$actionType,$receivers,$reason,$toFinanceDept,$lastApproveOrReject = false)
    {
        $documentNo = $requestType == 'CLEARING' ? $request->clearing_document_no : $request->document_no;
        $amount     = $requestType == 'CASH-ADVANCE' ? $request->amount : $request->total_receipt_amount_inc_wht;
        $amountFormatted = $amount ? number_format($amount,2) : '0.00';
        $prefixMail = getPrefixMail($request);
        $preparerName = optional($request->user)->name;

        if ($actionType == 'REJECT') {
            $mailSubject = $prefixMail.'-'.$documentNo.'-Rejected-'.optional($request->user)->name.'-'.$amountFormatted.'-'.$request->purpose;
        } else {
            $mailSubject = $prefixMail.'-'.$documentNo.'-'.optional($request->user)->name.'-'.$amountFormatted.'-'.$request->purpose;
        }

        $receiverNames = '';
        if($toFinanceDept){
            $receiverNames = 'Finance Dept.';
        }else{
            $receiverNames = $receivers ? implode(', ', array_map(function($receiver){
                                                  return $receiver['name'];
                                                }, $receivers)) : '';
        }

        $emailDescription = "คุณ $preparerName ได้ส่ง $prefixMail ให้คุณเพื่ออนุมัติ";

        // CASH-ADVANCE APPROVER PROCESS
        if($requestType == 'CASH-ADVANCE'){
            // APPROVER APPROVE
            if($actionType =='APPROVE'){
                $data = [
                    'view'          =>  'emails.template_with_reason',
                    // 'subject'       =>  '[CASH ADVANCE]['.$request->document_no.'] Approved',
                    'subject'       =>  $mailSubject,
                    'title'         =>  'CASH-ADVANCE #'.$request->document_no.' REQUEST WAS APPROVED.',
                    'description'   =>  $emailDescription,
                    'reason'        =>  $reason,
                    'requestType'   =>  $requestType,
                    'request'       =>  $request,
                    'receiverNames' =>  $receiverNames
                ];
            }
            // APPROVER SENDBACK
            if($actionType =='SENDBACK'){
                $data = [
                    'view'          =>  'emails.template_with_reason',
                    // 'subject'       =>  '[CASH ADVANCE]['.$request->document_no.'] Sent back',
                    'subject'       =>  $mailSubject,
                    'title'         =>  'CASH-ADVANCE #'.$request->document_no.' REQUEST WAS SENT BACK.',
                    'description'   =>  '',
                    'reason'        =>  $reason,
                    'requestType'   =>  $requestType,
                    'request'       =>  $request,
                    'receiverNames' =>  $receiverNames
                ];
            }
            // APPROVER REJECT
            if($actionType =='REJECT'){
                $data = [
                    'view'          =>  'emails.template_with_reason',
                    // 'subject'       =>  '[CASH ADVANCE]['.$request->document_no.'] Rejected',
                    'subject'       =>  $mailSubject,
                    'title'         =>  'CASH-ADVANCE #'.$request->document_no.' REQUEST WAS REJECTED.',
                    'description'   =>  '',
                    'reason'        =>  $reason,
                    'requestType'   =>  $requestType,
                    'request'       =>  $request,
                    'receiverNames' =>  $receiverNames
                ];
            }
        }

        // CLEARING APPROVER PROCESS
        if($requestType == 'CLEARING'){

            $additionDesc = self::getAdditionDesc($request);
            $emailDescription = "คุณ $preparerName ได้ส่ง $prefixMail ให้คุณเพื่ออนุมัติ";
            if ($additionDesc) {
                $emailDescription = "คุณ $preparerName ได้ส่ง $prefixMail ($additionDesc) ให้คุณเพื่ออนุมัติ";
            }
            // APPROVER APPROVE
            if($actionType =='APPROVE'){
                $data = [
                    'view'          =>  'emails.template_with_reason',
                    // 'subject'       =>  '[CLEARING]['.$request->clearing_document_no.'] Approved',
                    'subject'       =>  $mailSubject,
                    'title'         =>  'CLEARING #'.$request->clearing_document_no.' REQUEST WAS APPROVED.',
                    'description'   =>  $emailDescription,
                    'reason'        =>  $reason,
                    'requestType'   =>  $requestType,
                    'request'       =>  $request,
                    'receiverNames' =>  $receiverNames
                ];
            }
            // APPROVER SENDBACK
            if($actionType =='SENDBACK'){
                $data = [
                    'view'          =>  'emails.template_with_reason',
                    // 'subject'       =>  '[CLEARING]['.$request->clearing_document_no.'] Sent back',
                    'subject'       =>  $mailSubject,
                    'title'         =>  'CLEARING #'.$request->clearing_document_no.' REQUEST WAS SENT BACK.',
                    'description'   =>  '',
                    'reason'        =>  $reason,
                    'requestType'   =>  $requestType,
                    'request'       =>  $request,
                    'receiverNames' =>  $receiverNames
                ];
            }
        }

        // EMP-EXPENSE APPROVER PROCESS
        if($requestType == 'EMP-EXPENSE'){

            $additionDesc = self::getAdditionDesc($request);
            $emailDescription = "คุณ $preparerName ได้ส่ง $prefixMail ให้คุณเพื่ออนุมัติ";
            if ($additionDesc) {
                $emailDescription = "คุณ $preparerName ได้ส่ง $prefixMail ($additionDesc) ให้คุณเพื่ออนุมัติ";
            }
            // APPROVER APPROVE
            if($actionType =='APPROVE'){
                $data = [
                    'view'          =>  'emails.template_with_reason',
                    // 'subject'       =>  '[EMP EXPENSE]['.$request->document_no.'] Approved',
                    'subject'       =>  $mailSubject,
                    'title'         =>  'EMP EXPENSE #'.$request->document_no.' REQUEST WAS APPROVED.',
                    'description'   =>  $emailDescription,
                    'reason'        =>  $reason,
                    'requestType'   =>  $requestType,
                    'request'       =>  $request,
                    'receiverNames' =>  $receiverNames
                ];
            }
            // APPROVER SENDBACK
            if($actionType =='SENDBACK'){
                $data = [
                    'view'          =>  'emails.template_with_reason',
                    // 'subject'       =>  '[EMP EXPENSE]['.$request->document_no.'] Sent back',
                    'subject'       =>  $mailSubject,
                    'title'         =>  'EMP EXPENSE #'.$request->document_no.' REQUEST WAS SENT BACK.',
                    'description'   =>  '',
                    'reason'        =>  $reason,
                    'requestType'   =>  $requestType,
                    'request'       =>  $request,
                    'receiverNames' =>  $receiverNames
                ];
            }
            // APPROVER REJECT
            if($actionType =='REJECT'){
                $data = [
                    'view'          =>  'emails.template_with_reason',
                    // 'subject'       =>  '[EMP EXPENSE]['.$request->document_no.'] Rejected',
                    'subject'       =>  $mailSubject,
                    'title'         =>  'EMP EXPENSE #'.$request->document_no.' REQUEST WAS REJECTED.',
                    'description'   =>  '',
                    'reason'        =>  $reason,
                    'requestType'   =>  $requestType,
                    'request'       =>  $request,
                    'receiverNames' =>  $receiverNames
                ];
            }
        }

        // INVOICE APPROVER PROCESS
        if($requestType == 'INVOICE'){

            $additionDesc = self::getAdditionDesc($request);
            $emailDescription = "คุณ $preparerName ได้ส่ง $prefixMail ให้คุณเพื่ออนุมัติ";
            if ($additionDesc) {
                $emailDescription = "คุณ $preparerName ได้ส่ง $prefixMail ($additionDesc) ให้คุณเพื่ออนุมัติ";
            }

            // APPROVER APPROVE
            if($actionType =='APPROVE'){
                $data = [
                    'view'          =>  'emails.template_with_reason',
                    // 'subject'       =>  '[INVOICE]['.$request->document_no.'] Approved',
                    'subject'       =>  $mailSubject,
                    'title'         =>  'Invoice Non PO #'.$request->document_no.' REQUEST WAS APPROVED.',
                    'description'   =>  $emailDescription,
                    'reason'        =>  $reason,
                    'requestType'   =>  $requestType,
                    'request'       =>  $request,
                    'receiverNames' =>  $receiverNames
                ];
            }
            // APPROVER SENDBACK
            if($actionType =='SENDBACK'){
                $data = [
                    'view'          =>  'emails.template_with_reason',
                    // 'subject'       =>  '[INVOICE]['.$request->document_no.'] Sent back',
                    'subject'       =>  $mailSubject,
                    'title'         =>  'Invoice Non PO #'.$request->document_no.' REQUEST WAS SENT BACK.',
                    'description'   =>  '',
                    'reason'        =>  $reason,
                    'requestType'   =>  $requestType,
                    'request'       =>  $request,
                    'receiverNames' =>  $receiverNames
                ];
            }
            // APPROVER REJECT
            if($actionType =='REJECT'){
                $data = [
                    'view'          =>  'emails.template_with_reason',
                    // 'subject'       =>  '[INVOICE]['.$request->document_no.'] Rejected',
                    'subject'       =>  $mailSubject,
                    'title'         =>  'Invoice Non PO #'.$request->document_no.' REQUEST WAS REJECTED.',
                    'description'   =>  '',
                    'reason'        =>  $reason,
                    'requestType'   =>  $requestType,
                    'request'       =>  $request,
                    'receiverNames' =>  $receiverNames
                ];
            }
        }

        // PETTY-CASH APPROVER PROCESS
        if($requestType == 'PETTY-CASH'){

            $additionDesc = self::getAdditionDesc($request);
            $emailDescription = "คุณ $preparerName ได้ส่ง $prefixMail ให้คุณเพื่ออนุมัติ";
            if ($additionDesc) {
                $emailDescription = "คุณ $preparerName ได้ส่ง $prefixMail ($additionDesc) ให้คุณเพื่ออนุมัติ";
            }

            // APPROVER APPROVE
            if($actionType =='APPROVE'){
                $data = [
                    'view'          =>  'emails.template_with_reason',
                    // 'subject'       =>  '[PETTY-CASH]['.$request->document_no.'] Approved',
                    'subject'       =>  $mailSubject,
                    'title'         =>  'PETTY-CASH #'.$request->document_no.' REQUEST WAS APPROVED.',
                    'description'   =>  $emailDescription,
                    'reason'        =>  $reason,
                    'requestType'   =>  $requestType,
                    'request'       =>  $request,
                    'receiverNames' =>  $receiverNames
                ];
            }
            // APPROVER SENDBACK
            if($actionType =='SENDBACK'){
                $data = [
                    'view'          =>  'emails.template_with_reason',
                    // 'subject'       =>  '[PETTY-CASH]['.$request->document_no.'] Sent back',
                    'subject'       =>  $mailSubject,
                    'title'         =>  'PETTY-CASH #'.$request->document_no.' REQUEST WAS SENT BACK.',
                    'description'   =>  '',
                    'reason'        =>  $reason,
                    'requestType'   =>  $requestType,
                    'request'       =>  $request,
                    'receiverNames' =>  $receiverNames
                ];
            }
            // APPROVER REJECT
            if($actionType =='REJECT'){
                $data = [
                    'view'          =>  'emails.template_with_reason',
                    // 'subject'       =>  '[PETTY-CASH]['.$request->document_no.'] Rejected',
                    'subject'       =>  $mailSubject,
                    'title'         =>  'PETTY-CASH #'.$request->document_no.' REQUEST WAS REJECTED.',
                    'description'   =>  '',
                    'reason'        =>  $reason,
                    'requestType'   =>  $requestType,
                    'request'       =>  $request,
                    'receiverNames' =>  $receiverNames
                ];
            }
        }

        // CASH-TRANSFER APPROVER PROCESS
        if($requestType == 'CASH-TRANSFER'){

            $additionDesc = self::getAdditionDesc($request);
            $emailDescription = "คุณ $preparerName ได้ส่ง $prefixMail ให้คุณเพื่ออนุมัติ";
            if ($additionDesc) {
                $emailDescription = "คุณ $preparerName ได้ส่ง $prefixMail ($additionDesc) ให้คุณเพื่ออนุมัติ";
            }

            // APPROVER APPROVE
            if($actionType =='APPROVE'){
                $data = [
                    'view'          =>  'emails.template_with_reason',
                    // 'subject'       =>  '[CASH-TRANSFER]['.$request->document_no.'] Approved',
                    'subject'       =>  $mailSubject,
                    'title'         =>  'CASH-TRANSFER #'.$request->document_no.' REQUEST WAS APPROVED.',
                    'description'   =>  $emailDescription,
                    'reason'        =>  $reason,
                    'requestType'   =>  $requestType,
                    'request'       =>  $request,
                    'receiverNames' =>  $receiverNames
                ];
            }
            // APPROVER SENDBACK
            if($actionType =='SENDBACK'){
                $data = [
                    'view'          =>  'emails.template_with_reason',
                    // 'subject'       =>  '[CASH-TRANSFER]['.$request->document_no.'] Sent back',
                    'subject'       =>  $mailSubject,
                    'title'         =>  'CASH-TRANSFER #'.$request->document_no.' REQUEST WAS SENT BACK.',
                    'description'   =>  '',
                    'reason'        =>  $reason,
                    'requestType'   =>  $requestType,
                    'request'       =>  $request,
                    'receiverNames' =>  $receiverNames
                ];
            }
            // APPROVER REJECT
            if($actionType =='REJECT'){
                $data = [
                    'view'          =>  'emails.template_with_reason',
                    // 'subject'       =>  '[CASH-TRANSFER]['.$request->document_no.'] Rejected',
                    'subject'       =>  $mailSubject,
                    'title'         =>  'CASH-TRANSFER #'.$request->document_no.' REQUEST WAS REJECTED.',
                    'description'   =>  '',
                    'reason'        =>  $reason,
                    'requestType'   =>  $requestType,
                    'request'       =>  $request,
                    'receiverNames' =>  $receiverNames
                ];
            }
        }

        if ($lastApproveOrReject) {
            $data['description'] = self::getDescriptionMsg($actionType);
        }
        return $data;

    }

    private static function templateFinanceProcess($requestType,$request,$actionType,$receivers,$reason,$lastApproveOrReject = false)
    {
        $documentNo = $requestType == 'CLEARING' ? $request->clearing_document_no : $request->document_no;
        $amount     = $requestType == 'CASH-ADVANCE' ? $request->amount : $request->total_receipt_amount_inc_wht;
        $amountFormatted = $amount ? number_format($amount,2) : '0.00';
        $prefixMail = getPrefixMail($request);
        if ($actionType == 'REJECT') {
            $mailSubject = $prefixMail.'-'.$documentNo.'-Rejected-'.optional($request->user)->name.'-'.$amountFormatted.'-'.$request->purpose;
        } else {
            $mailSubject = $prefixMail.'-'.$documentNo.'-'.optional($request->user)->name.'-'.$amountFormatted.'-'.$request->purpose;
        }
        
        // CASH-ADVANCE FINANCE PROCESS
        if($requestType == 'CASH-ADVANCE'){
            // FINANCE APPROVE
            if($actionType =='APPROVE'){
                $data = [
                    'view'          =>  'emails.template_with_reason',
                    // 'subject'       =>  '[CASH ADVANCE]['.$request->document_no.'] Approved',
                    'subject'       =>  $mailSubject,
                    'title'         =>  'CASH-ADVANCE #'.$request->document_no.' REQUEST WAS APPROVED.',
                    // 'description'   =>  'Finance Dept. approved cash advance request.',
                    'description'   =>  '',
                    'reason'        =>  $reason,
                    'requestType'   =>  $requestType,
                    'request'       =>  $request,
                    'receiverNames' =>  $receivers ? implode(', ', 
                        array_map(function($receiver){
                            return $receiver['name'];
                        }, $receivers)) : ''
                ];
            }
            // FINANCE SENDBACK
            if($actionType =='SENDBACK'){
                $data = [
                    'view'          =>  'emails.template_with_reason',
                    // 'subject'       =>  '[CASH ADVANCE]['.$request->document_no.'] Sent back',
                    'subject'       =>  $mailSubject,
                    'title'         =>  'CASH-ADVANCE #'.$request->document_no.' REQUEST WAS SENT BACK.',
                    // 'description'   =>  'Finance Dept. sent back cash advance request to requester for edit.',
                    'description'   =>  '',
                    'reason'        =>  $reason,
                    'requestType'   =>  $requestType,
                    'request'       =>  $request,
                    'receiverNames' =>  $receivers ? implode(', ',
                        array_map(function($receiver){
                            return $receiver['name'];
                        }, $receivers)) : ''
                ];
            }
            // FINANCE REJECT
            if($actionType =='REJECT'){
                $data = [
                    'view'          =>  'emails.template_with_reason',
                    // 'subject'       =>  '[CASH ADVANCE]['.$request->document_no.'] Rejected',
                    'subject'       =>  $mailSubject,
                    'title'         =>  'CASH-ADVANCE #'.$request->document_no.' REQUEST WAS REJECTED.',
                    // 'description'   =>  'Finance Dept. rejected cash advance request.',
                    'description'   =>  '',
                    'reason'        =>  $reason,
                    'requestType'   =>  $requestType,
                    'request'       =>  $request,
                    'receiverNames' =>  $receivers ? implode(', ', 
                        array_map(function($receiver){
                            return $receiver['name'];
                        }, $receivers)) : ''
                ];
            }
        }

        // CLEARING FINANCE PROCESS
        if($requestType == 'CLEARING'){

            $additionDesc = self::getAdditionDesc($request);

            // FINANCE APPROVE
            if($actionType =='APPROVE'){
                $data = [
                    'view'          =>  'emails.template_with_reason',
                    // 'subject'       =>  '[CLEARING]['.$request->clearing_document_no.'] Approved',
                    'subject'       =>  $mailSubject,
                    'title'         =>  'CLEARING #'.$request->clearing_document_no.' REQUEST WAS APPROVED.',
                    // 'description'   =>  'Finance Dept. approved clearing cash advance'.$additionDesc.' request.',
                    'description'   =>  '',
                    'reason'        =>  $reason,
                    'requestType'   =>  $requestType,
                    'request'       =>  $request,
                    'receiverNames' =>  $receivers ? implode(', ', 
                        array_map(function($receiver){
                            return $receiver['name'];
                        }, $receivers)) : ''
                ];
            }
            // FINANCE SENDBACK
            if($actionType =='SENDBACK'){
                $data = [
                    'view'          =>  'emails.template_with_reason',
                    // 'subject'       =>  '[CLEARING]['.$request->clearing_document_no.'] Sent back',
                    'subject'       =>  $mailSubject,
                    'title'         =>  'CLEARING #'.$request->clearing_document_no.' REQUEST WAS SENT BACK.',
                    // 'description'   =>  'Finance Dept. sent back cash clearing advance'.$additionDesc.' request to requester for edit.',
                    'description'   =>  '',
                    'reason'        =>  $reason,
                    'requestType'   =>  $requestType,
                    'request'       =>  $request,
                    'receiverNames' =>  $receivers ? implode(', ', 
                        array_map(function($receiver){
                            return $receiver['name'];
                        }, $receivers)) : ''
                ];
            }
            // FINANCE REJECT
            if($actionType =='REJECT'){
                $data = [
                    'view'          =>  'emails.template_with_reason',
                    // 'subject'       =>  '[CASH ADVANCE]['.$request->document_no.'] Rejected',
                    'subject'       =>  $mailSubject,
                    'title'         =>  'CLEARING #'.$request->document_no.' REQUEST WAS REJECTED.',
                    // 'description'   =>  'Finance Dept. rejected cash clearing advance'.$additionDesc.' request to requester for edit.',
                    'description'   =>  '',
                    'reason'        =>  $reason,
                    'requestType'   =>  $requestType,
                    'request'       =>  $request,
                    'receiverNames' =>  $receivers ? implode(', ', 
                        array_map(function($receiver){
                            return $receiver['name'];
                        }, $receivers)) : ''
                ];
            }
        }

        if ($lastApproveOrReject) {
            $data['description'] = self::getDescriptionMsg($actionType);
        }

        return $data;
    }

    private static function templateUnblock($requestType,$request,$receivers,$reason)
    {
        // CASH-ADVANCE UNBLOCK
        if($requestType == 'CASH-ADVANCE'){
            $data = [
                'view'          =>  'emails.template_with_reason',
                'subject'       =>  '[CASH ADVANCE]['.$request->document_no.'] Unblocked',
                'title'         =>  'CASH-ADVANCE #'.$request->document_no.' REQUEST WAS UNBLOCKED.',
                'description'   =>  'Finance Dept. unblocked your cash advance request, now you can send this request to approver.',
                'reason'        =>  $reason,
                'requestType'   =>  $requestType,
                'request'       =>  $request,
                'receiverNames' =>  $receivers ? implode(', ', 
                    array_map(function($receiver){
                        return $receiver['name'];
                    }, $receivers)) : ''
            ];
        }

        // EMP-EXPENSE UNBLOCK
        if($requestType == 'EMP-EXPENSE'){
            $data = [
                'view'          =>  'emails.template_with_reason',
                'subject'       =>  '[EMP EXPENSE]['.$request->document_no.'] Unblocked',
                'title'         =>  'EMP EXPENSE #'.$request->document_no.' REQUEST WAS UNBLOCKED.',
                'description'   =>  'Finance Dept. unblocked your emp expense request, now you can send this request to approver.',
                'reason'        =>  $reason,
                'requestType'   =>  $requestType,
                'request'       =>  $request,
                'receiverNames' =>  $receivers ? implode(', ', 
                    array_map(function($receiver){
                        return $receiver['name'];
                    }, $receivers)) : ''
            ];
        }

        // INVOICE UNBLOCK
        if($requestType == 'INVOICE'){
            $data = [
                'view'          =>  'emails.template_with_reason',
                'subject'       =>  '[Invoice Non PO]['.$request->document_no.'] Unblocked',
                'title'         =>  'Invoice Non PO #'.$request->document_no.' REQUEST WAS UNBLOCKED.',
                'description'   =>  'Finance Dept. unblocked your invoice request, now you can send this request to approver.',
                'reason'        =>  $reason,
                'requestType'   =>  $requestType,
                'request'       =>  $request,
                'receiverNames' =>  $receivers ? implode(', ', 
                    array_map(function($receiver){
                        return $receiver['name'];
                    }, $receivers)) : ''
            ];
        }

        // PETTY-CASH UNBLOCK
        if($requestType == 'PETTY-CASH'){
            $data = [
                'view'          =>  'emails.template_with_reason',
                'subject'       =>  '[PETTY-CASH]['.$request->document_no.'] Unblocked',
                'title'         =>  'PETTY-CASH #'.$request->document_no.' REQUEST WAS UNBLOCKED.',
                'description'   =>  'Finance Dept. unblocked your petty cash request, now you can send this request to approver.',
                'reason'        =>  $reason,
                'requestType'   =>  $requestType,
                'request'       =>  $request,
                'receiverNames' =>  $receivers ? implode(', ', 
                    array_map(function($receiver){
                        return $receiver['name'];
                    }, $receivers)) : ''
            ];
        }

        // CASH-TRANSFER UNBLOCK
        if($requestType == 'CASH-TRANSFER'){
            $data = [
                'view'          =>  'emails.template_with_reason',
                'subject'       =>  '[CASH-TRANSFER]['.$request->document_no.'] Unblocked',
                'title'         =>  'CASH-TRANSFER #'.$request->document_no.' REQUEST WAS UNBLOCKED.',
                'description'   =>  'Finance Dept. unblocked your cash transfer request, now you can send this request to approver.',
                'reason'        =>  $reason,
                'requestType'   =>  $requestType,
                'request'       =>  $request,
                'receiverNames' =>  $receivers ? implode(', ', 
                    array_map(function($receiver){
                        return $receiver['name'];
                    }, $receivers)) : ''
            ];
        }

        return $data;
    }

    // $userData can be => Collection of App\User || App\User || userId
    public static function composeReceivers($userData)
    {
        $receivers = [];
        if(!$userData){ return $receivers; }

        // Collection of App\User
        if(isEloquentCollection($userData)) {
            if(count($userData)>0){
                foreach ($userData as $key => $user) {
                    if($user->employee){
                        if($user->email){
                            array_push($receivers, [
                                'email' =>  $user->email ? 'nuttasunton@mcrconsult.com' : '',
                                // 'email' =>  'nuttasunton@mcrconsult.com',
                            // 'email' =>  'ratshanun@mcrconsult.com',
                                'name'  =>  $user->employee->first_name,
                                'user_id' =>  $user->id
                            ]);
                        }
                    }
                }
            }
        }else{
            // userId
            if(is_numeric($userData)){
                $user = User::find($userData);
                if($user){
                    if($user->employee){
                        if($user->email){
                            array_push($receivers, [
                                'email' =>  $user->email ? 'nuttasunton@mcrconsult.com' : '',
                                // 'email' =>  'nuttasunton@mcrconsult.com',
                            // 'email' =>  'ratshanun@mcrconsult.com',
                                'name'  =>  $user->employee->first_name,
                                'user_id' =>  $user->id
                            ]);
                        }
                    }
                }
            } else if (is_array($userData)) {
                $users = User::with('employee')->whereIn('id', $userData)->get();
                foreach ($users as $key => $user) {
                    array_push($receivers, [
                        'email' =>  $user->email ? 'nuttasunton@mcrconsult.com' : '',
                        // 'email' =>  'nuttasunton@mcrconsult.com',
                            // 'email' =>  'ratshanun@mcrconsult.com',
                        // 'name'  =>  $user->employee->first_name,
                        'name'  =>  $user->name,
                        'user_id' =>  $user->id
                    ]);
                }
            // App\User
            }else{
                if($userData->employee){
                    if($userData->email){
                        array_push($receivers, [
                            'email' =>  $userData->email ? 'nuttasunton@mcrconsult.com' : '',
                            // 'email' =>  'nuttasunton@mcrconsult.com',
                            // 'email' =>  'ratshanun@mcrconsult.com',
                            // 'name'  =>  $userData->employee->first_name,
                            'name'  =>  $userData->name,
                            'user_id' =>  $userData->id
                        ]);
                    }
                }
            }
        }
        // logger($receivers);

        return $receivers;
    }

    private static function getAdditionDesc($request)
    {
        $additionDesc = '';
        if($request->over_budget && $request->exceed_policy){
            $additionDesc = 'over budget and exceed policy';
        }elseif($request->over_budget){
            $additionDesc = 'over budget';
        }elseif($request->exceed_policy){
            $additionDesc = 'exceed policy';
        }
        return $additionDesc;
    }

    private static function getDescriptionMsg($actionType)
    {
        $msg = 'เอกสารของท่านได้ถูกอนุมัติแล้ว';
        if ($actionType == 'REJECT' || $actionType == 'SENDBACK') {
            $msg = 'เอกสารของท่านได้ถูกยกเลิกแล้ว';
        }

        return $msg;
    }

}
