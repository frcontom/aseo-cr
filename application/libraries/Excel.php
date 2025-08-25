<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill; // Importar Fill

class Excel
{
    public function __construct()
    {
        require 'vendor/autoload.php';
//        return new Spreadsheet();
    }

    public function createSpreadsheet()
    {
        return new Spreadsheet();
    }

    /**
     * Devuelve una nueva instancia de PhpSpreadsheet\Writer\Xlsx.
     *
     * @param Spreadsheet $spreadsheet La instancia del Spreadsheet.
     * @return Xlsx
     */
    public function createXlsxWriter(Spreadsheet $spreadsheet,$fileName = 'export.xlsx')
    {
        $writer =  new Xlsx($spreadsheet);
        $filePath = FCPATH . '_files/exports/' . $fileName;
       return $writer->save($filePath);
    }

}