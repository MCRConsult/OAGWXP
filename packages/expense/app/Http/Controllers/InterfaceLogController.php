<?php

namespace Packages\expense\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use Packages\expense\app\Models\InvoiceInterfaceHeader;
use Packages\expense\app\Models\GLJournalInterface;
use Packages\expense\app\Models\GLBudgetReservations;

class InterfaceLogController extends Controller
{
    public function index()
    {
        $interfaceInvoices = InvoiceInterfaceHeader::search(request())
                        ->orderByRaw('creation_date desc, invoice_num desc')
                        ->paginate(25);
        $interfaceJournals = GLJournalInterface::search(request())
                        ->selectRaw('distinct reference2, web_batch_no, creation_date, interface_status, interface_msg, default_effective_date')
                        ->orderByRaw('default_effective_date desc, reference2 desc')
                        ->paginate(25);
        $interfaceEncumbrances = GLBudgetReservations::search(request())
                        ->selectRaw('distinct reserve_date, reserve_type, description, batch_no, reserve_status, error_msg')
                        ->orderByRaw('reserve_date desc')
                        ->paginate(25);
        $statuses = ['All'   => 'ทั้งหมด'
                    , 'S'    => 'ตั้งเบิก'
                    , 'E'    => 'ตั้งเบิกไม่สำเร็จ'];
        $statusEncs = ['All' => 'ทั้งหมด'
                    , 'S'    => 'สำเร็จ'
                    , 'E'    => 'ไม่สำเร็จ'];

        return view('expense::interface-log.index', compact('interfaceInvoices', 'interfaceJournals', 'interfaceEncumbrances', 'statuses', 'statusEncs'));
    }

    public function handleReserve($batch)
    {
        GLBudgetReservations::where('batch_no', $batch)
                            ->update([
                                'reserve_status'    => 'P'
                                , 'error_msg'       => ''
                            ]);
        $result = (new GLBudgetReservations)->callReserveBudget($batch);
        $data = [
            'status' => $result['status'],
            'message' => $result['error_msg'],
        ];
        return response()->json($data);
    }
}
