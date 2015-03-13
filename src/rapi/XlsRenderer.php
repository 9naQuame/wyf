<?php

class XlsRenderer extends ReportRenderer
{
    private $spreadsheet;
    private $worksheet;
    private $row = 1;
    
    public function __construct()
    {
        $this->spreadsheet = new PHPExcel($file);
        $this->spreadsheet->getProperties()
            ->setCreator('WYF PHP Framework')
            ->setTitle('Report');
        
        $this->worksheet = $this->spreadsheet->getActiveSheet();
        $this->worksheet->getHeaderFooter()->setEvenFooter("Generated on ".date("jS F, Y @ g:i:s A")." by ".$_SESSION["user_lastname"]." ".$_SESSION["user_firstname"]);
        $this->worksheet->getHeaderFooter()->setOddFooter("Generated on ".date("jS F, Y @ g:i:s A")." by ".$_SESSION["user_lastname"]." ".$_SESSION["user_firstname"]);        
    }
    
    public function output() 
    {
        $writer = new PHPExcel_Writer_Excel2007($this->spreadsheet);        
        $file = "app/temp/" . uniqid() . "_report.xlsx";
        $writer->save($file);
        Application::redirect("/$file");        
    }

    public function renderLogo(LogoContent $content) 
    {
        
    }

    public function renderTable(TableContent $content) 
    {
        $style = array(
            'header:border' => $this->convertColor(array(200,200,200)),
            'header:background' => $this->convertColor(array(200,200,200)),
            'header:text' => $this->convertColor(array(255,255,255)),
            'body:background' => $this->convertColor(array(255,255,255)),
            'body:stripe' => $this->convertColor(array(250, 250, 250)),
            'body:border' => $this->convertColor(array(200, 200, 200)),
            'body:text' => $this->convertColor(array(0,0,0))
        );  
        
        if($content->getAsTotalsBox())
        {
            $totals = $content->getData();
            for($i = 0; $i<$this->numColumns; $i++)
            {
                $this->worksheet->setCellValueByColumnAndRow($i,$this->row,$totals[$i]);
                $this->worksheet->getStyleByColumnAndRow($i, $this->row)
                    ->getFont()
                    ->setBold(true);
            }
        }
        else
        {
            $headers = $content->getHeaders();
            $this->numColumns = count($headers);

            foreach($headers as $col=>$header)
            {
                $this->worksheet->setCellValueByColumnAndRow($col,$this->row,str_replace("\\n","\n",$header));
                $this->worksheet->getStyleByColumnAndRow($col, $this->row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                $this->worksheet->getStyleByColumnAndRow($col, $this->row)->getFill()->getStartColor()->setRGB($style['header:background']);
                $this->worksheet->getStyleByColumnAndRow($col, $this->row)->getFont()->setBold(true)->getColor()->setRGB($style['header:text']);
            }

            $fill = false;
            $types = $content->getDataTypes();
            $widths = $content->getTableWidths();

            foreach($content->getData() as $this->rowData)
            {
                $this->row++;
                $col = 0;
                foreach($this->rowData as $field)
                {
                    switch($content->data_params["type"][$col])
                    {
                         case "number":
                             $field = str_replace(",", "", $field);
                             $field = $field === null || $field == "" ? "0" : round($field, 0);
                             break;
                         case "double":
                             $field = str_replace(",", "", $field);
                             $field = $field === null || $field == "" ? "0.00" : round($field, 2);
                             break;
                         case "right_align":
                             break;
                     }
                    $this->worksheet->setCellValueByColumnAndRow($col, $this->row, trim($field));
                    $this->worksheet->getColumnDimensionByColumn($col)->setAutoSize(true);
                    if($fill)
                    {
                        $this->worksheet->getStyleByColumnAndRow($col, $this->row)
                            ->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                        $this->worksheet->getStyleByColumnAndRow($col, $this->row)
                            ->getFill()->getStartColor()->setARGB($style['body:stripe']);
                    }
                    $col++;
                }
                $fill = !$fill;
            }
        }
        $this->row++;
    }

    public function renderText(TextContent $content) 
    {
        $this->worksheet->setCellValueByColumnAndRow(0, $this->row, $content->getText());
        switch($content->getStyle())
        {
            case 'title':
                $this->worksheet->getStyleByColumnAndRow(0, $this->row)
                    ->getFont()
                        ->setBold(true)
                        ->setSize(16)
                        ->setName('Helvetica');
                $this->worksheet->getRowDimension($this->row)
                    ->setRowHeight(36);     
                break;
        }
        $this->row++;
    }
    
    private function convertColor($color)
    {
        return dechex($color[0]) . dechex($color[1]) . dechex($color[2]);
    }    
}

