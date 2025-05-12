<?php

    // function genRoute($requestType, $request)
    // {
    //     if($requestType == 'CASH-ADVANCE'){
    //         return route('cash-advances.show', ['cashAdvanceId' => $request->id]);
    //     }elseif($requestType == 'CLEARING'){
    //         return route('cash-advances.clear', ['cashAdvanceId' => $request->id]);
    //     }elseif($requestType == 'EMP-EXPENSE'){
    //         return route('emp-expenses.show', ['reimId' => $request->id]);
    //     }elseif($requestType == 'INVOICE'){
    //         return route('invoices.show', ['invoiceId' => $request->id]);
    //     }elseif($requestType == 'PETTY-CASH'){
    //         return route('petty-cashs.show', ['type' => $request->type, 'pettyCashId' => $request->id]);
    //     }elseif($requestType == 'CASH-TRANSFER'){
    //         return route('cash-transfers.show', ['cashTransferId' => $request->id]);
    //     }

    //     return '';
    // }

    function genInvApprovalRoute($requestType, $request, $userId, $activity)
    {
        $parentId = $request->id;
        $param = [
            'request_type' => $requestType,
            'id' => $parentId,
            'user_id' => $userId,
            'activity' => $activity,
            'reason' => '',
        ];

        if ($requestType == 'PREPARE-PLAN') {
            $param['id'] = $request->plan_line_id;
            $param['type'] = $requestType;
            $parent = \Packages\inventory\app\Models\Plan\PtwinvPlanLine::find($param['id']);
        }


        // if($requestType == 'CASH-ADVANCE'){
        //     $parent = \App\CashAdvance::find($parentId);
        //     $param['type'] = 'CASH-ADVANCE';
        // }elseif($requestType == 'CLEARING'){
        //     $parent = \App\CashAdvance::find($parentId);
        //     $param['type'] = 'CLEARING';
        // }elseif($requestType == 'EMP-EXPENSE'){
        //     $parent = \App\EmpExpense::find($parentId);
        // }elseif($requestType == 'INVOICE'){
        //     $parent = \App\Invoice::find($parentId);
        // }elseif($requestType == 'PETTY-CASH'){
        //     $parent = \App\PettyCash::find($parentId);
        // }elseif($requestType == 'CASH-TRANSFER'){
        //     $parent = \App\CashTransfer::find($parentId);
        // }

        $encrypted = \Crypt::encryptString(json_encode($param));
        $route = route('inv.api.approval',['key' => $encrypted]);
        return $route;
    }
