<?php
abstract class ReportContent 
{
    protected $content;
    protected $style = array();    
    
    public function set($content)
    {
        $this->content;
    }
    
    public function get()
    {
        return $this->content;
    }
    
    public function setStyle($style, $value = false)
    {
        if(is_array($style))
        {
            $this->style = $style;
        }
        else
        {
            $this->style[$style] = $value;
        }
    }
    
    public function getStyle($style = '')
    {
        if($style != '')
        {
            return $this->style[$style];
        }
        else
        {
            return $this->style;
        }
    }    
    
    public abstract function getType();
}
