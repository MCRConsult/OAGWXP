<?php

namespace Packages\expense\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;

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

        $interfaceJournals = GLJournalInterface::selectRaw('distinct reference2, web_batch_no, default_effective_date, interface_status, interface_msg')
                        ->search(request())
                        ->where('user_je_category_name', 'เงินเดือน')
                        ->orderByRaw('default_effective_date desc, reference2 desc')
                        ->get();
        $perPage = 25;
        $currPage = (int)request()->page;
        $interfaceJournals = collect($interfaceJournals)->all();
        $interfaceJournals = new LengthAwarePaginator(
            array_slice($interfaceJournals, ($currPage - 1) * $perPage, $perPage),
            count($interfaceJournals), 
            $perPage,
            $currPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $interfaceEncumbrances = GLBudgetReservations::search(request())
                        ->selectRaw('distinct trunc(reserve_date) reserve_date, reserve_type, batch_no, reserve_status, error_msg')
                        ->orderByRaw('reserve_date desc, reserve_type desc')
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
