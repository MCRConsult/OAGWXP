<?php

namespace Packages\expense\app\Http\Controllers\Report;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

use Packages\expense\app\Exports\InvoiceExport;

class ReportController extends Controller
{
    public function index()
    {
        return view('expense::report.index');
    }

    public function export() 
    {
        return Excel::download(new InvoiceExport, 'OAG - รายงานทะเบียนคุมหลักฐานขอเบิก (ทบ.อส.1).xlsx');
    }
}
