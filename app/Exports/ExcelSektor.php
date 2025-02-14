<?php

// app/Exports/ExportExcelLokasi.php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ExcelSektor implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $data;
    protected $selectedValue;
    protected $tahun;
    protected $header;
    protected $subTotalJumProyek;
    protected $subTotalJumInvestasi;
    protected $subTotalJumTki;
    protected $subTotalJumTka;

    public function __construct($data, $selectedValue, $tahun, $header, $subTotalJumProyek, $subTotalJumInvestasi, $subTotalJumTki, $subTotalJumTka)
    {
        $this->data = $data;
        $this->selectedValue = $selectedValue;
        $this->tahun = $tahun;
        $this->header = $header;
        $this->subTotalJumProyek = $subTotalJumProyek;
        $this->subTotalJumInvestasi = $subTotalJumInvestasi;
        $this->subTotalJumTki = $subTotalJumTki;
        $this->subTotalJumTka = $subTotalJumTka;
        // dd($this->data);
    }

    // public function collection()
    // {
    //     return $this->data;
    // }

    public function headings(): array
    {
        // Modify this according to your data structure
        return [
            ['Ralisasi '.$this->header.' '.$this->tahun.' Berdasarkan SEKTOR'],
            ['No', 'Bidang Usaha', 'Proyek', $this->selectedValue],
            ['', '', '', 'Investasi (Dlm Rp)', 'TKI', 'TKA'],
            [],
        ];
    }

    public function map($row): array
    {
       
        if (is_array($row) && count($row) === 6 && $row[0] === 'Jumlah') {
            // Handle Subtotal Row
            return [
                $row[0],  // 'Subtotal'
                $row[1],  // Empty for Kabupaten/kota column
                $row[2],  // Subtotal for jumlah_proyek
                $row[3],  // Subtotal for tambahan_investasi
                $row[4],  // Subtotal for jumlah_tki
                $row[5],  // Subtotal for jumlah_tka
            ];
        } else {
            // Handle Regular Data Row
            static $counter = 0;
            $counter++;
    
            return [
                $counter,
                $row['namasektor'] ?? '',
                ($row['jumlah_proyek'] != 0) ? $row['jumlah_proyek'] : '0',
                ($row['tambahan_investasi'] != 0) ? $row['tambahan_investasi'] : '0',
                ($row['jumlah_tki'] != 0) ? $row['jumlah_tki'] : '0',
                ($row['jumlah_tka'] != 0) ? $row['jumlah_tka'] : '0',
            ];
        }
    }

    public function collection()
    {
        // Include subtotal calculations here
        $data = $this->data->toArray();
        // dd($data);
        $subtotalRow = [
            'Jumlah',
            '',
            $this->subTotalJumProyek,
            $this->subTotalJumInvestasi,
            $this->subTotalJumTki,
            $this->subTotalJumTka,
        ];
        // dd(collect(array_merge($data, [$subtotalRow])));
        return collect(array_merge($data, [$subtotalRow]));
    }

    public function styles($sheet)
    {
        // Merge cells for the title row
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:A3');
        $sheet->mergeCells('B2:B3');
        $sheet->mergeCells('C2:C3');
        $sheet->mergeCells('D2:F2');

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);

        $headerStyle = [
            'font' => [
                'size' => 16,
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];
        $sheet->getStyle('A1')->applyFromArray($headerStyle);

        // Apply background color to the subheader row
        $subheaderStyle = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '04A08B'],
            ],
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];
        $sheet->getStyle('A2:F3')->applyFromArray($subheaderStyle);

        // Apply number format to the desired columns
        $sheet->getStyle('C')->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('D')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $sheet->getStyle('E')->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('F')->getNumberFormat()->setFormatCode('#,##0');

        // Apply border styles to all cells
        $lastRow = $sheet->getHighestDataRow();
        $lastColumn = 'F'; // Adjust this if the last column is different

        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $sheet->mergeCells('A'.$lastRow.':B'.$lastRow);
        $sheet->getStyle('A')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $range = 'A2:' . $lastColumn . $lastRow;
        $sheet->getStyle($range)->applyFromArray($borderStyle);

       
    }
}

