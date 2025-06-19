<?php

namespace Packages\expense\app\Http\Controllers\Invoice;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use Packages\expense\app\Models\InvoiceInterfaceHeader;
use Packages\expense\app\Models\InvoiceInterfaceLine;

class InterfaceLogController extends Controller
{
    public function index()
    {
        $interfaces = InvoiceInterfaceHeader::search(request())
                        ->orderByRaw('creation_date desc, invoice_num desc')
                        ->paginate(25);
        $statuses = ['All'  => 'ทั้งหมด'
                    , 'S'   => 'ส่งเบิกจ่ายแล้ว'
                    , 'E'   => 'มีข้อผิดพลาด'];

        return view('expense::interface-log.index', compact('interfaces', 'statuses'));
    }
}
