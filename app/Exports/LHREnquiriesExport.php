<?php

namespace App\Exports;

use App\Models\LHRInquiry;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class LHREnquiriesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
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
            'Follow Up Date',
            'Status',
            'Address',
            'Reference By',
            'Area',
            'Session',
            'Area Code',
            'Energy',
            'Frequency',
            'Shot',
            'Staff Name',
            'Hormonal Issues',
            'Medication',
            'Previous Treatment',
            'PCOD/Thyroid',
            'Skin Conditions',
            'Ongoing Treatments',
            'Implants/Tattoos',
            'Procedure',
            'Total Payment',
            'Discount Payment',
            'Given Payment',
            'Due Payment',
            'Cash Payment',
            'Google Pay',
            'Cheque Payment',
            'FOC',
            'Account',
            'Time',
            'Notes',
            'Created Date'
        ];
    }

    public function map($inquiry): array
    {
        // Decode procedure JSON
        $procedure = '';
        if ($inquiry->procedure) {
            $procArray = json_decode($inquiry->procedure, true);
            if (is_array($procArray)) {
                $procedure = implode(', ', $procArray);
            }
        }

        // Format payment values
        $formatPayment = function($value) {
            return number_format($value ?? 0, 2);
        };

        return [
            'LHR-' . str_pad($inquiry->id, 7, '0', STR_PAD_LEFT),
            $inquiry->patient_name ?? '',
            ucfirst($inquiry->gender) ?? '',
            $inquiry->age ?? '',
            $inquiry->inquiry_date ? Carbon::parse($inquiry->inquiry_date)->format('d-M-Y') : '',
            $inquiry->next_follow_up ? Carbon::parse($inquiry->next_follow_up)->format('d-M-Y') : 'Not Set',
            ucfirst($inquiry->status_name) ?? '',
            $inquiry->address ?? '',
            $inquiry->reference_by ?? '',
            $inquiry->area ?? '',
            $inquiry->session ?? '',
            $inquiry->area_code ?? '',
            $inquiry->energy ?? '',
            $inquiry->frequency ?? '',
            $inquiry->shot ?? '',
            $inquiry->staff_name ?? '',
            ucfirst($inquiry->hormonal_issues) ?? 'No',
            ucfirst($inquiry->medication) ?? 'No',
            ucfirst($inquiry->previous_treatment) ?? 'No',
            ucfirst($inquiry->pcod_thyroid) ?? 'No',
            ucfirst($inquiry->skin_conditions) ?? 'No',
            ucfirst($inquiry->ongoing_treatments) ?? 'No',
            ucfirst($inquiry->implants_tattoos) ?? 'No',
            $procedure,
            $formatPayment($inquiry->total_payment),
            $formatPayment($inquiry->discount_payment),
            $formatPayment($inquiry->given_payment),
            $formatPayment($inquiry->due_payment),
            $formatPayment($inquiry->cash_payment),
            $formatPayment($inquiry->google_pay),
            $formatPayment($inquiry->cheque_payment),
            $inquiry->foc ? 'Yes' : 'No',
            $inquiry->account ?? '',
            $inquiry->time ?? '',
            $inquiry->notes ?? '',
            $inquiry->created_at ? Carbon::parse($inquiry->created_at)->format('d-M-Y H:i:s') : ''
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
            'F' => 15,  // Follow Up Date
            'G' => 12,  // Status
            'H' => 15,  // Mobile Number
            'I' => 25,  // Email
            'J' => 30,  // Address
            'K' => 20,  // Reference By
            'L' => 15,  // Area
            'M' => 15,  // Session
            'N' => 12,  // Area Code
            'O' => 12,  // Energy
            'P' => 12,  // Frequency
            'Q' => 12,  // Shot
            'R' => 20,  // Staff Name
            'S' => 18,  // Hormonal Issues
            'T' => 15,  // Medication
            'U' => 20,  // Previous Treatment
            'V' => 15,  // PCOD/Thyroid
            'W' => 18,  // Skin Conditions
            'X' => 20,  // Ongoing Treatments
            'Y' => 20,  // Implants/Tattoos
            'Z' => 20,  // Procedure
            'AA' => 15, // Total Payment
            'AB' => 15, // Discount Payment
            'AC' => 15, // Given Payment
            'AD' => 15, // Due Payment
            'AE' => 15, // Cash Payment
            'AF' => 15, // Google Pay
            'AG' => 15, // Cheque Payment
            'AH' => 10, // FOC
            'AI' => 15, // Account
            'AJ' => 10, // Time
            'AK' => 40, // Notes
            'AL' => 20, // Created Date
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style for header row
        $sheet->getStyle('A1:AL1')->applyFromArray([
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
        $sheet->getStyle('A2:AL' . $lastRow)->applyFromArray([
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
        $sheet->setAutoFilter('A1:AL' . $lastRow);

        // Freeze first row
        $sheet->freezePane('A2');

        // Wrap text for notes column
        $sheet->getStyle('AK')->getAlignment()->setWrapText(true);

        // Set number format for payment columns
        $paymentColumns = ['AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG'];
        foreach ($paymentColumns as $column) {
            $sheet->getStyle($column . '2:' . $column . $lastRow)
                ->getNumberFormat()
                ->setFormatCode('#,##0.00');
        }

        // Auto size columns for better fit
        foreach (range('A', 'L') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }
}