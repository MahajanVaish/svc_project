<?php

namespace App\Exports;

use App\Models\HydraInquiry;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class HydraEnquiriesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    protected $inquiries;
    protected $type;
    protected $hasFilters;

    public function __construct($inquiries, $type = 'pending', $hasFilters = false)
    {
        $this->inquiries = $inquiries;
        $this->type = $type;
        $this->hasFilters = $hasFilters;
    }

    public function collection()
    {
        return $this->inquiries;
    }

    public function title(): string
    {
        $title = $this->type === 'joined' ? 'Joined Patients' : 'Pending Patients';
        if ($this->hasFilters) {
            $title .= ' (Filtered)';
        }
        return $title;
    }

    public function headings(): array
    {
        return [
            'Patient ID',
            'Patient Name',
            'Gender',
            'Age',
            'Inquiry Date',
            'Inquiry Time',
            'Phone Number',
            'Address',
            'Reference By',
            'Session',
            'Next Follow Up',
            'Total Payment',
            'Discount Payment',
            'Given Payment',
            'Due Payment',
            'Cash Payment',
            'Google Pay',
            'Payment Mode',
            'FOC',
            'Status',
            'Created Date',
            'Updated Date'
        ];
    }

    public function map($inquiry): array
    {
        // Format payment values
        $formatPayment = function($value) {
            return number_format($value ?? 0, 2);
        };

        return [
            'HYDRA-' . str_pad($inquiry->id, 7, '0', STR_PAD_LEFT),
            $inquiry->patient_name ?? '',
            ucfirst($inquiry->gender) ?? '',
            $inquiry->age ?? '',
            $inquiry->inquiry_date ? Carbon::parse($inquiry->inquiry_date)->format('d-M-Y') : '',
            $inquiry->inquiry_time ?? '',
            $inquiry->phone_number ?? '',
            $inquiry->address ?? '',
            $inquiry->reference_by ?? '',
            $inquiry->session ?? '',
            $inquiry->next_follow_up ? Carbon::parse($inquiry->next_follow_up)->format('d-M-Y') : 'Not Set',
            $formatPayment($inquiry->total_payment),
            $formatPayment($inquiry->discount_payment),
            $formatPayment($inquiry->given_payment),
            $formatPayment($inquiry->due_payment),
            $formatPayment($inquiry->cash_payment),
            $formatPayment($inquiry->google_pay),
            $inquiry->payment_mode ?? '',
            $inquiry->foc ? 'Yes' : 'No',
            ucfirst($inquiry->status_name) ?? '',
            $inquiry->created_at ? Carbon::parse($inquiry->created_at)->format('d-M-Y H:i:s') : '',
            $inquiry->updated_at ? Carbon::parse($inquiry->updated_at)->format('d-M-Y H:i:s') : ''
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,  // Patient ID
            'B' => 25,  // Patient Name
            'C' => 10,  // Gender
            'D' => 8,   // Age
            'E' => 15,  // Inquiry Date
            'F' => 15,  // Inquiry Time
            'G' => 15,  // Phone Number
            'H' => 30,  // Address
            'I' => 20,  // Reference By
            'J' => 15,  // Session
            'K' => 15,  // Next Follow Up
            'L' => 15,  // Total Payment
            'M' => 15,  // Discount Payment
            'N' => 15,  // Given Payment
            'O' => 15,  // Due Payment
            'P' => 15,  // Cash Payment
            'Q' => 15,  // Google Pay
            'R' => 20,  // Payment Mode
            'S' => 10,  // FOC
            'T' => 12,  // Status
            'U' => 20,  // Created Date
            'V' => 20,  // Updated Date
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style for header row
        $sheet->getStyle('A1:V1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4CAF50'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Style for data rows
        $lastRow = $this->inquiries->count() + 1;
        $sheet->getStyle('A2:V' . $lastRow)->applyFromArray([
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'DDDDDD'],
                ],
            ],
        ]);

        // Auto filter for all columns
        $sheet->setAutoFilter('A1:V' . $lastRow);

        // Freeze first row
        $sheet->freezePane('A2');

        // Wrap text for address column
        $sheet->getStyle('H')->getAlignment()->setWrapText(true);

        // Set number format for payment columns
        $paymentColumns = ['L', 'M', 'N', 'O', 'P', 'Q'];
        foreach ($paymentColumns as $column) {
            $sheet->getStyle($column . '2:' . $column . $lastRow)
                ->getNumberFormat()
                ->setFormatCode('#,##0.00');
        }

        // Auto size columns for better fit
        foreach (range('A', 'V') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }
}
