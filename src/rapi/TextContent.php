<?php
/**
 * 
 */
class TextContent extends ReportContent
{
    public $text;
    
    public function __construct($text=null, $style=null)
    {
        $this->setStyle($style);
        $this->text = $text;
    }
    
    public function getType()
    {
        return "text";
    }
}
