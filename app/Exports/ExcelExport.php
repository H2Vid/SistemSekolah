<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExcelExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{

    protected $headings;
    protected $mapping;
    protected $collection;

    public function __construct(array $headings, array $mapping, $collection)
    {
        $this->headings = $headings;
        $this->mapping = $mapping;
        $this->collection = $collection;
    }


    public function headings(): array
    {
        return $this->headings;
    }

    public function map($table): array
    {
        $mappedValues = [];

        foreach ($this->mapping as $expression) {
            $mappedValues[] = eval("return $expression;");
        }
        
        // dd($mappedValues);
        return $mappedValues;
        // return $this->mapping;
    }

    public function collection()
    {
        return $this->collection;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $count = count($this->collection);
                if($count >= 1){
                    $count = $count + 1;
                }

                $count_headings = count($this->headings);
                $head_last = chr(65 + $count_headings - 1);
                $cellRange = 'A1:'.$head_last.'1';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(25);
                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_HAIR,
                            'color' => ['argb' => '#000000'],
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ];
                $event->sheet->getStyle('A2:'.$head_last.$count)->applyFromArray($styleArray);

                $styleHeaderArray = [
                     'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_HAIR,
                            'color' => ['argb' => '#000000'],
                        ],
                    ],
                ];
                $event->sheet->getStyle($cellRange)->applyFromArray($styleHeaderArray);
                
            },
        ];

    }
}