<?php

namespace Packages\expense\app\Http\Controllers\Report;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

use Packages\expense\app\Exports\InvoiceExport;
use Packages\expense\app\Models\RequisitionHeader;
use Packages\expense\app\Models\RequisitionLine;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->type;
        if ($type == 'REQUISITION') {
            $reportName = 'รายงานหลักฐานเอกสารส่งเบิก';
        }else{
            $reportName = 'รายงานทะเบียนคุมหลักฐานขอเบิก';
        }
        
        return view('expense::report.index', compact('type', 'reportName'));
    }

    public function requisionExport() 
    {
        $requisitions = RequisitionHeader::searchReport(request())
                            ->orderByRaw('req_date asc, req_number asc')
                            ->get();
        $contentHtml = view('expense::report.requisition.pdf', compact('requisitions'))->render();

        return PDF::loadHTML($contentHtml)
            ->setPaper('A3', 'landscape')
            ->stream('OAG - รายงานหลักฐานเอกสารส่งเบิก.pdf');
    }

    public function invoiceExport() 
    {
        return Excel::download(new InvoiceExport, 'OAG - รายงานทะเบียนคุมหลักฐานขอเบิก (ทบ.อส.1).xlsx');
    }
}
