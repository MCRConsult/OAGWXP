<?php

namespace Packages\expense\App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use Packages\expense\app\Models\InvoiceReportV;

class InvoiceExport implements FromView, ShouldAutoSize, WithColumnFormatting, WithStyles
{
    public function view(): View
    {
        $invDateFrom = request()->invoice_date_from ?? date('Y-m-d');
        $invDateTo = request()->invoice_date_to ?? date('Y-m-d');
        $invoices = InvoiceReportV::search(request())
                                // ->where('invoice_status', 'INTERFACED')
                                ->orderByRaw('req_number, invoice_number')
                                ->get();

        return view('expense::report._invoice_excel', compact('invoices', 'invDateTo'));
    }

    public function columnFormats(): array
    {
        return [
            'F' => '#,##0.00',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:Z1000')->getFont()->setName('TH SarabunPSK')->setSize(16)->setBold(true);
    }
}
