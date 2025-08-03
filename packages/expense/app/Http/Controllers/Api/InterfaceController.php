<?php

namespace Packages\expense\app\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;

use Packages\expense\app\Models\InvoiceInterfaceHeader;
use Packages\expense\app\Models\GLJournalInterface;
use Packages\expense\app\Models\GLBudgetReservations;

class InterfaceController extends Controller
{
    public function fetchInvoiceInterface()
    {
        $interfaces = InvoiceInterfaceHeader::search(request())
                        ->orderByRaw('creation_date desc, invoice_num desc')
                        ->get();
        $perPage = 25;
        $currPage = (int)request()->page;
        $interfaces = collect($interfaces)->all();
        $respInterfaces = new LengthAwarePaginator(
            array_slice($interfaces, ($currPage - 1) * $perPage, $perPage),
            count($interfaces), 
            $perPage,
            $currPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $data = [
            'interfaces' => $respInterfaces
        ];
        return response()->json($data);
    }

    public function fetchJournalInterface()
    {
        $interfaces = GLJournalInterface::search(request())
                        ->selectRaw('distinct reference2, web_batch_no, default_effective_date, interface_status, interface_msg')
                        ->where('user_je_category_name', 'เงินเดือน')
                        ->orderByRaw('default_effective_date desc, reference2 desc')
                        ->get();
        $perPage = 25;
        $currPage = (int)request()->page;
        $interfaces = collect($interfaces)->all();
        $respInterfaces = new LengthAwarePaginator(
            array_slice($interfaces, ($currPage - 1) * $perPage, $perPage),
            count($interfaces), 
            $perPage,
            $currPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $data = [
            'interfaces' => $respInterfaces
        ];
        return response()->json($data);
    }

    public function fetchEncumbranceInterface()
    {
        $interfaces = GLBudgetReservations::search(request())
                        ->selectRaw('distinct trunc(reserve_date) as reserve_date, reserve_type, batch_no, reserve_status, error_msg')
                        ->orderByRaw('reserve_date desc, reserve_type desc')
                        ->get();
        $perPage = 25;
        $currPage = (int)request()->page;
        $interfaces = collect($interfaces)->all();
        $respInterfaces = new LengthAwarePaginator(
            array_slice($interfaces, ($currPage - 1) * $perPage, $perPage),
            count($interfaces), 
            $perPage,
            $currPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $data = [
            'interfaces' => $respInterfaces
        ];
        return response()->json($data);
    }
}
