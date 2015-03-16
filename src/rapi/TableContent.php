<?php
class TableContent extends ReportContent
{
    protected $headers;
    protected $data;
    protected $dataParams = null;
    protected $autoTotals;
    private $totals = array();
    protected $totalsBox;
    protected $numColumns;
    
    public function __construct($headers, $data)
    {   
        $this->headers = $headers;
        $this->data = $data;
        $this->numColumns = count($headers);
    }
    
    public function setAsTotalsBox($totalsBox)
    {
        $this->totalsBox = $totalsBox;
    }
    
    public function getAsTotalsBox()
    {
        return $this->totalsBox;
    }
    
    public function setAutoTotals($autoTotals)
    {
        $this->autoTotals = $autoTotals;
    }
    
    public function getAutoTotals()
    {
        return $this->autoTotals;
    }
    
    public function getTableWidths()
    {
        if(isset($this->dataParams['widths']))
        {
            return $this->dataParams['widths'];
        }
        else
        {
            return $this->computeTableWidths();
        }
    }

    protected function computeTableWidths()
    {
        $widths = array();
        foreach($this->headers as $i=>$header)
        {
            $lines = explode("\n",$header);
            foreach($lines as $line)
            {
                $widths[$i] = strlen($line) > $widths[$i] ? strlen($line) : $widths[$i];
            }
        }
        
        foreach($this->data as $row)
        {
            $i = 0;
            foreach($row as $column)
            {
                $widths[$i] = strlen($column) > $widths[$i] ? strlen($column) : $widths[$i];
                $i++;
            }
        }
        
        $totals = $this->getTotals();
        
        if(count($totals) > 0)
        {
            foreach($totals as $i => $column)
            {
                $column = number_format($column, 2, '.', ',');
                $widths[$i] = strlen($column) > $widths[$i] ? strlen($column) : $widths[$i];
            }            
        }
        return $widths;
    }
    
    public function setTotals($totals)
    {
        $this->totals = $totals;
    }
    
    public function getTotals()
    {   
        for($i = 0; $i < $this->numColumns; $i++)
        {
            $totals[$i] = null;
        }    
        
        foreach($this->data as $fields)
        {
            $i = 0;
            foreach($fields as $field)
            {
                if($this->dataParams["total"][$i])
                {
                    $totals[$i] += $this->getFieldValue($field, $this->dataParams['type'][$i]);
                }
                $i++;
            }
        }

        return $totals;
    }
    
    private function getFieldValue($value, $type)
    {

        $field = str_replace(array(",", ' '), "", $value);

        switch($type)
        {
            case 'double':
                $field = round($field, 2);
                break;
            case 'number':
                $field = round($field, 0);
                break;
        }
        
        return $field;
    }
    
    public function getHeaders()
    {
        return $this->headers;
    }
    
    public function setDataTypes($types)
    {
    	$this->dataParams['type'] = $types;
    }
    
    public function getDataTypes()
    {
        return $this->dataParams['type'];
    }
    
    public function setTotalsFields($total)
    {
    	$this->dataParams['total'] = $total;
    	$this->setAutoTotals(true);
    }
    
    public function setIgnoredFields($ignore)
    {
        $this->dataParams['ignore'] = $ignore;
    }
    
    public function setWidths($widths)
    {
        $this->dataParams['widths'] = $widths;
    }
    
    public function getData()
    {
        return $this->data;
    }
    
    public function getType()
    {
        return "table";
    }
    
    public function getNumColumns()
    {
        return $this->numColumns;
    }
    
    public function setDataParams($dataParams)
    {
        $this->dataParams = $dataParams;
    }
    
    public function getDataParams()
    {
        return $this->dataParams;
    }
}
