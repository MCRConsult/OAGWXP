<?php

namespace Packages\expense\App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

use Packages\expense\app\Models\InvoiceReportV;

class InvoiceExport implements FromView, ShouldAutoSize, WithColumnFormatting, WithStyles, WithColumnWidths
{
    public function view(): View
    {
        $invDateFrom = request()->invoice_date_from ?? null;
        $invDateTo = request()->invoice_date_to ?? null;
        $invoices = InvoiceReportV::search(request())
                                ->where('invoice_status', 'INTERFACED')
                                ->orderByRaw('invoice_date asc, req_number asc, invoice_number asc')
                                ->get();

        return view('expense::report.invoice.excel', compact('invoices', 'invDateTo'));
    }

    public function columnFormats(): array
    {
        return [
            'F' => '#,##0.00',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 25,
            'C' => 45,
            'D' => 65,
            'E' => 20,
            'F' => 30,
            'G' => 25,
            'H' => 25,
            'I' => 65,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:Z1000')->getFont()->setName('TH SarabunPSK')->setSize(16)->setBold(true);
    }
}
