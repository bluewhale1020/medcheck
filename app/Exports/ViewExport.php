<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;


use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

use \PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use \PhpOffice\PhpSpreadsheet\Style\Border;
use \PhpOffice\PhpSpreadsheet\Style\Alignment;
use \PhpOffice\PhpSpreadsheet\Style\Fill;
use \PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ViewExport extends DefaultValueBinder implements WithCustomValueBinder, FromView, WithEvents
{

    public function bindValue(Cell $cell, $value)
    {
        if (is_numeric($value) and strpos( $value, "." ) !== false) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                // $event->sheet->getDelegate() : ->getActiveSheet()と同じ

                $worksheet = $event->sheet->getDelegate();
                // Get the highest row and column numbers referenced in the worksheet
                $highestRow = $worksheet->getHighestRow(); // e.g. 10
                $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'

                $table_range = "A1:".$highestColumn.$highestRow;
                $head_range = "A1:".$highestColumn."1";


                $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn); // e.g. 5
                
                if($this->fileName =='progresslist'){
                    $result_range = "F1:".$highestColumn.$highestRow;
                    $result_start_col = 6;                    

                    for ($row = 2; $row <= $highestRow; ++$row) {
                        for ($col = 6; $col <= $highestColumnIndex; ++$col) {
                            $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                            switch ($value) {
                                case '●':
                                $worksheet->getStyleByColumnAndRow($col, $row)
                                ->getFont()->getColor()->setARGB('e3342f');
                                    break;
                                case '✔':
                                $worksheet->getStyleByColumnAndRow($col, $row)
                                ->getFont()->getColor()->setARGB('38c172');
                                    break;
                                case '△':
                                $worksheet->getStyleByColumnAndRow($col, $row)
                                ->getFont()->getColor()->setARGB('f6993f');
                                    break;
                                
                                default:
                                    # code...
                                    break;
                            }
                        }
    
                    }

                    //column widths C,D
                    $worksheet->getColumnDimension('C')->setWidth(18);                    
                    $worksheet->getColumnDimension('D')->setWidth(12);                    

                }else if($this->fileName =='result'){
                    $result_range = "F1:".$highestColumn.$highestRow;
                    $result_start_col = 6;

                    //column widths C,D
                    $worksheet->getColumnDimension('C')->setWidth(12);                    
                    $worksheet->getColumnDimension('D')->setWidth(18);                           
                    $worksheet->getColumnDimension('E')->setWidth(12);
                                    

                }else if($this->fileName =='reception_list'){
                    $result_range = "H1:".$highestColumn.$highestRow;
                    $result_start_col = 8;                   
                    //column widths C,D
                    $worksheet->getColumnDimension('D')->setWidth(25);                    
                    $worksheet->getColumnDimension('F')->setWidth(18);                           
                    $worksheet->getColumnDimension('G')->setWidth(18);                           
                }

                //column widths start col ~ heighest
                for ($col = $result_start_col; $col <= $highestColumnIndex; ++$col) {
                    $worksheet->getColumnDimension(Coordinate::stringFromColumnIndex($col))
                    ->setWidth(16);                        
                }

                //header style
                $headerStyle = [
                    'font'=>[
                        'color'=>[
                            'rgb'=>'FFFFFF'
                        ],
                        'bold'=>true,
                        // 'size'=>11
                    ],
                    'fill'=>[
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => '538ED5'
                        ]
                    ],
                ];                

                //table borders
                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            // 'color' => ['argb' => 'FFFF0000'],
                        ],
                    ],
                ];

                $worksheet->getStyle($head_range)->applyFromArray($headerStyle);                                
                $worksheet->getStyle($table_range)->applyFromArray($styleArray);                                

                //result range text center
                $worksheet->getStyle($result_range)->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);

         
            },
        ];
    }

    private $view;

    public function __construct(View $view,String $filename)
    {
        $this->view = $view;
        $this->fileName =$filename;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        return $this->view;
    }
}
